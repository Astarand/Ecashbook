@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/other-income-list') }}">Other Income</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Other Income</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-add-other-income-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
	
	<div class="col-md-4">
		<div class="page-header-title">
			<h2 class="mb-0">Add New Income</h2>
		</div>
	</div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                {{-- <form action="{{ route('income.store') }}" method="POST"> --}}
                <form id="incomeForm" enctype="multipart/form-data">

                    @csrf
                    <div class="row">

						<div class="mb-3 col-md-3">
							<label class="form-label">Proprietorship Company</label>
							<select name="propId" class="form-control">
								<option value="">{{ parentCompanyName() }}</option>
								@foreach($proprietorships as $company)
									<option value="{{ $company->id }}">
										{{ $company->comp_name }}
									</option>
								@endforeach
							</select>
						</div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="dateInput" id="dateInput" required class="form-control" placeholder="Date">
                        </div>
						
						<!-- MAIN CATEGORY -->
						<div class="col-md-3 mb-3">
							<label class="form-label">Income Type <span class="text-danger">*</span></label>
							<select id="incomeType" name="incomeType" class="form-select" required>
								<option value="">Select</option>
								<option value="Revenue">Other Operating Income</option>
								<option value="Other">Other Non-Operating Income</option>
							</select>
						</div>
						
						<!-- SUB CATEGORY -->
						<div class="col-sm-3 mb-3">
                            <label class="form-label">Income Category<span class="text-danger">*</span></label>
                            <select id="categoryIncome" name="categoryIncome" required class="form-select">
                                <option value="">Select Category</option>
                                <!--<option value="Other Income">Other Income</option>-->
                            </select>
                        </div>
                        <div class="mb-3 col-sm-3" id="otherIncomeCategory" style="display: none;">
                            <label for="otherInput" class="form-label">Other</label>
                            <input type="text" name="other_income" class="form-control" id="otherInput"
                                placeholder="Enter other category">
                        </div>
						
						<div class="col-md-3 mb-3">
							<label class="form-label">Party / Source Name<span class="text-danger"></span></label>
							<input type="text" name="customer_name" id="customer_name" class="form-control">
							<!--<select name="customer_id" id="customer_id" required class="form-control">
								<option value="">Select</option>
								@foreach($customers as $customer)
									<option value="{{ $customer->id }}">
										{{ $customer->cust_name }}
									</option>
								@endforeach
							</select>-->
						</div>
						<div class="col-md-3 mb-3">
							<label class="form-label">Invoice / Reference Number</label>
							<input type="text" name="invoice_no" id="invoice_no" class="form-control">
						</div>
						<div class="col-md-3 mb-3">
                            <label class="form-label">Income Amount<span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" id="amount" required class="form-control" placeholder="Amount">
                        </div>
						<div class="col-md-3 mb-3">
							<label class="form-label">Received Amount<span class="text-danger">*</span></label>
							<select name="pay_status" id="pay_status" required class="form-select">
								<option value="">Select</option>
								<option value="Full">Full</option>
								<option value="Advance">Advance</option>														
								<option value="Due">Due</option>														
							</select>
						</div>
						<div class="col-md-3 mb-3">
							<label class="form-label">Payment Mode<span class="text-danger">*</span></label>
							<select name="pay_mode" id="pay_mode" required class="form-select">
								<option value="">Select</option>
								<option value="Cash">Cash</option>
								<option value="Bank">Bank</option>														
								<option value="UPI">UPI</option>														
							</select>
						</div>
						<div class="col-md-3 mb-3">
							<div class="form-group">
								<label class="form-label">Select Bank</label>
								<select name="bank_id" id="bank_id" class="form-control">
									<option value="">-- Select Bank --</option>
									@foreach($bankDetails as $bank)
										<option value="{{ $bank->id }}">
											{{ $bank->bank_name }}
										</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-3 mb-3">
                            <label class="form-label">Advance Amount</label>
                            <input type="number" step="0.01" name="advance_amt" id="advance_amt" class="form-control" placeholder="Amount">
                        </div>
						<div class="col-md-3 mb-3">
                            <label class="form-label">Balance Receivable Amount</label>
                            <input type="number" step="0.01" name="receivable_amt" id="receivable_amt" class="form-control" placeholder="Amount">
                        </div>
						<div class="col-md-3 mb-3">
                            <label class="form-label">Adjust Now (Amount)</label>
                            <input type="number" step="0.01" name="adjust_amt" id="adjust_amt" class="form-control" placeholder="Amount">
                        </div>
						<!--<div class="col-md-3 mb-3" id="dueDateContainer" style="display:none;">
							<label class="form-label">Due Date<span class="text-danger">*</span></label>
							<input type="date" name="due_date" id="due_date" class="form-control">
						</div>-->
						
						
						<div class="card">
							<div class="card-header">
								<h5>GST & TDS</h5>
							</div>
							<div class="card-body">
								<div class="row currAsset">
									<div class="col-md-12">
										<label class="form-label">TDS Applicable</label>
										<div class="row">
											<div class="col-6">
												<div class="card shadow-sm border-0 p-3 m-2">
													<div class="form-check">
														<input class="form-check-input" type="radio" name="tds_applicable" value="yes" id="tdsYes">
														<label class="form-check-label" for="tdsYes">Yes</label>
													</div>
												</div>
											</div>
											<div class="col-6">
												<div class="card shadow-sm border-0 p-3 m-2">
													<div class="form-check">
														<input class="form-check-input" type="radio" name="tds_applicable" value="no" id="tdsNo" checked>
														<label class="form-check-label" for="tdsNo">No</label>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="tds-container col-md-12" id="tdsContainer">
										<div class="row">
											<div class="col-md-6">
												<div id="tds_dropdown_universal">
													<label for="tds_percent" class="form-label">TDS Percentage</label>
													<select name="tds_percent" id="tds_percent" class="form-control">
														@foreach ($purposes_of_tds as $purpose)
														<option value="{{ $purpose->tds_rate . '-' . $purpose->id }}">
															{{ $purpose->category }} ({{ $purpose->tds_rate }}%)
														</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<label for="tds_amount">TDS Amount</label>
												<input type="text" id="tds_amount"  class="form-control" readonly>
											</div>
										</div>
									</div>

									<div class="gst-container col-md-12">
										<div class="mb-3">
											<label class="form-label">GST Applicable</label>
											<div class="row">
												<div class="col-6">
													<div class="card shadow-sm border-0 p-3 m-2">
														<div class="form-check">
															<input class="form-check-input" type="radio" name="gst_applicable" value="yes" id="gstYes_ca" >
															<label class="form-check-label" for="gstYes_ca">Yes</label>
														</div>
													</div>
												</div>
												<div class="col-6">
													<div class="card shadow-sm border-0 p-3 m-2">
														<div class="form-check">
															<input class="form-check-input" name="gst_applicable" value="no" type="radio" id="gstNo_ca" checked>
															<label class="form-check-label" for="gstNo_ca">No</label>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row mb-3">
											<div class="col-md-4">
												<label for="gst_trans">GST Transaction Mode</label>
												<select class="form-select" name="gst_trans" id="gst_trans">
													<option value="">Select</option>
													<option value="intrastate">Intra State</option>
													<option value="interstate">Inter State</option>
													<option value="union">Union Territory</option>
												</select>
											</div>
											<div class="col-md-4">
												<label for="gst_rate">GST Rate (%)</label>
												<input type="number" name="gst_rate" id="gst_rate" class="form-control" min="0" step="0.01">
											</div>
											<div class="col-md-4">
												<label for="gst_rate">GST Allocation</label>
												<input type="text" name="gst_allocation" id="gst_allocation" class="form-control" readonly>
											</div>
											<div class="col-md-4">
												<label for="gst_amt">Total GST Amount</label>
												<input type="text" name="gst_amt" id="gst_amt" class="form-control" readonly>
											</div>
											
										</div>
									</div>
								</div>
							</div>
						</div>
						
                        <div class="mb-3 col-md-12">
                            <label class="form-label" for="inputEmail4">Notes</label>
                            <textarea class="form-control" name="specification" id="projectDescription" placeholder="Specification"
                                rows="4"></textarea>
                        </div>
						
						<div class="col-md-3 mb-3">
							<label class="form-label">Attachment</label>
							<input type="file" name="income_doc" id="income_doc" class="form-control">
						</div>

                        <div class="col-md-12 text-end">
                            <a href="{{ url('/other-income-list') }}" class="btn btn-danger me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Income</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
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
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-file-text" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Fill this form to register new auxiliary operating revenue or non-operating income.</p></div>'
                },
                {
                    element: 'select[name="propId"]',
                    title: 'Proprietorship Entity',
                    intro: 'Select the specific proprietorship company entity receiving this income.'
                },
                {
                    element: '#dateInput',
                    title: 'Receipt Date',
                    intro: 'Set the official date when the income was transactionally registered.'
                },
                {
                    element: '#incomeType',
                    title: 'Income Classification',
                    intro: 'Choose Operating Revenue vs Non-operating Other Income categories.'
                },
                {
                    element: '#categoryIncome',
                    title: 'Service Subcategory',
                    intro: 'Specify the subclass category (e.g. Service Income, Rental, Dividends).'
                },
                {
                    element: '#customer_id',
                    title: 'Paying Party',
                    intro: 'Select the customer or entity paying this income amount.'
                },
                {
                    element: '#amount',
                    title: 'Transaction Amount',
                    intro: 'Specify the total revenue amount received or receivable.'
                },
                {
                    element: '#pay_status',
                    title: 'Receipt Mode Status',
                    intro: 'Set payment status (Full or Advance). Selecting Advance will display advance and receivable value fields.'
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

	//Income Type and Sub category
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
			categoryDropdown.empty().append('<option value="">Select</option>');

			// ✅ If empty → stop here
			if (!type) {
				$('#otherIncomeCategory').hide();
				return;
			}

			let options = [];

			if (type === 'Revenue') {
				options = revenueOptions;
			} else if (type === 'Other') {
				options = otherOptions;
			}

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
				$('#otherIncomeCategory').show();
			} else {
				$('#otherIncomeCategory').hide();
				$('#otherInput').val(''); // reset value
			}
		});

	});
	
	
	//amount based on pay_status
	function allowOnlyDecimal(el) {
		let value = el.value;

		// Remove invalid characters (allow digits + one dot)
		value = value.replace(/[^0-9.]/g, '');

		// Allow only one decimal point
		let parts = value.split('.');
		if (parts.length > 2) {
			value = parts[0] + '.' + parts.slice(1).join('');
		}

		el.value = value;
	}
	
	$('#amount, #advance_amt, #receivable_amt, #adjust_amt').on('input', function () {
		allowOnlyDecimal(this);
	});
	$(document).ready(function () {

		function resetFields() {
			$('#advance_amt, #receivable_amt, #adjust_amt')
				.val('')
				.prop({ readonly: true, required: false });

			// Hide all optional fields
			$('#advance_amt').closest('.col-md-3').hide();
			$('#receivable_amt').closest('.col-md-3').hide();
			$('#adjust_amt').closest('.col-md-3').hide();
		}

		function calculateAmounts() {

			let amount     = parseFloat($('#amount').val()) || 0;
			let adjustAmt  = parseFloat($('#adjust_amt').val()) || 0;
			let advanceAmt = parseFloat($('#advance_amt').val()) || 0;
			let status     = $('#pay_status').val();

			// ================= FULL =================
			if (status === 'Full') {

				// Show only Adjust
				$('#adjust_amt').closest('.col-md-3').show();
				$('#advance_amt').closest('.col-md-3').hide();
				$('#receivable_amt').closest('.col-md-3').hide();

				$('#adjust_amt')
					.val(amount)
					.prop({ readonly: true, required: true });

				$('#advance_amt').val(0).prop({ required: false });
				$('#receivable_amt').val(0).prop({ required: false });
			}

			// ================= ADVANCE =================
			else if (status === 'Advance') {

				// Show all
				$('#adjust_amt').closest('.col-md-3').show();
				$('#advance_amt').closest('.col-md-3').show();
				$('#receivable_amt').closest('.col-md-3').show();

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

			// ================= DEFAULT =================
			else {
				resetFields();
			}
		}

		// ================= EVENTS =================
		$('#pay_status').on('change', function () {
			resetFields();
			calculateAmounts();
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

		// ================= INITIAL LOAD (EDIT MODE) =================
		resetFields();
		calculateAmounts();

	});
	
	
	//TDS applicable
	$(document).ready(function () {

		function toggleTDS() {
			let val = $('input[name="tds_applicable"]:checked').val();

			if (val === 'yes') {
				$('#tdsContainer').show();
				calculateTDS(); 
			} else {
				$('#tdsContainer').hide();
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

		$('input[name="tds_applicable"]').on('change', function () {
			toggleTDS();
		});

		$('#amount').on('input change', function () {
			if ($('#tdsYes').is(':checked')) {
				calculateTDS();
			}
		});

		toggleTDS();

	});
	
	//GST applicable
	document.addEventListener("DOMContentLoaded", function () {

		document.querySelectorAll(".gst-container").forEach((container) => {

			const gstYes = container.querySelector('#gstYes_ca');
			const gstNo  = container.querySelector('#gstNo_ca');

			if (gstYes && gstNo) {
				toggleGST(container, 'ca');
				gstYes.addEventListener('change', () => toggleGST(container, 'ca'));
				gstNo.addEventListener('change', () => toggleGST(container, 'ca'));
			}

		});

		function toggleGST(container, type) {

			let isYes = false;

			if (type === 'ca') {
				isYes = container.querySelector('#gstYes_ca')?.checked;
			} else {
				isYes = container.querySelector('#gstYes_nca')?.checked;
			}

			let fields = [];

			if (type === 'ca') {
				fields = container.querySelectorAll('#gst_trans, #gst_rate, #gst_allocation,#gst_amt');
			} else {
				
			}

			fields.forEach(field => {
				field.closest(".col-md-4").style.display = isYes ? "block" : "none";
			});
		}

	});
	
	//GST calculation
	function calculateGST() 
	{

		let invoiceValue = parseFloat($('#amount').val()) || 0;

		let gstRate  = parseFloat($('#gst_rate').val()) || 0;
		let gstTrans = $('#gst_trans').val();

		// ================= GST CALC =================
		let gstAmount = (invoiceValue * gstRate) / 100;

		// ================= GST SPLIT =================
		let cgst = 0, sgst = 0, igst = 0;

		if (gstTrans === 'intrastate' || gstTrans === 'union') {
			cgst = gstAmount / 2;
			sgst = gstAmount / 2;
		} else if (gstTrans === 'interstate') {
			igst = gstAmount;
		}

		// ================= SET VALUES =================
		$('#gst_amt').val(gstAmount.toFixed(2));

		if (igst > 0) {
			$('#gst_allocation').val(`IGST: ${igst.toFixed(2)}`);
		} else {
			$('#gst_allocation').val(`CGST: ${cgst.toFixed(2)} | SGST: ${sgst.toFixed(2)}`);
		}
	}
	
	$('#amount, #gst_rate, #gst_trans').on('change keyup', function () {
		calculateGST();
	});

	//Submit form
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
                    }, 2000);
                } else {
                    showToast(response.message, "error");
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