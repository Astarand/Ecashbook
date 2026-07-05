@extends('App.Layout')

@section('container')

<div class="pc-content">
    <div class="col-md-4">
		<div class="page-header-title">
			<h2 class="mb-0">View Other Income</h2>
		</div>
	</div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form data-route="{{ route('income.update', $income->id) }}" id="incomeForm"  enctype="multipart/form-data">
                
                    @csrf
                    <div class="row">
                
						<div class="mb-3 col-md-3">
							<label class="form-label">Proprietorship Company</label>
							<select name="propId" class="form-control">
								<option value="">{{ parentCompanyName() }}</option>
								@foreach($proprietorships as $company)
									<option value="{{ $company->id }}" <?=($income->propId == $company->id) ? 'selected' : '' ?>>
										{{ $company->comp_name }}
									</option>
								@endforeach
							</select>
						</div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="dateInput" required id="permanent-address1" class="form-control" placeholder="Project Name"
                                value="{{$income->dateInput}}">
                        </div>
						<!-- INCOME TYPE -->
						<div class="col-md-3 mb-3">
							<label class="form-label">Income Type <span class="text-danger">*</span></label>
							<select id="incomeType" name="incomeType" class="form-select" required>
								<option value="">Select</option>
								<option value="Revenue" {{ $income->incomeType == 'Revenue' ? 'selected' : '' }}>
									Other Operating Income
								</option>
								<option value="Other" {{ $income->incomeType == 'Other' ? 'selected' : '' }}>
									Other Non-Operating Income
								</option>
							</select>
						</div>
						
						<div class="col-sm-3 mb-3">
                            <label class="form-label">Income Category<span class="text-danger">*</span></label>
                            <select id="categoryIncome" name="categoryIncome" required class="form-select">
                                <option value="">Select Category</option>                                
                                <!--<option value="Other Income" <?=($income->categoryIncome == "Other Income") ? 'selected' : '' ?>>Other Income</option>-->
                            </select>
                        </div>
                        <div class="mb-3 col-sm-3" id="otherIncomeCategory" style="display: none;">
                            <label for="otherInput" class="form-label">Other</label>
                            <input type="text" name="other_income" class="form-control" id="otherInput"
                                placeholder="Enter other category" value="{{$income->other_income}}">
                        </div>
												
						<div class="col-md-3 mb-3">
							<label class="form-label">Party / Source Name</label>
							<input type="text" name="customer_name" id="customer_name" class="form-control" value="{{$income->customer_name}}">
							<!---<select name="customer_id" id="customer_id" required class="form-control">
								<option value="">Select</option>
								@foreach($customers as $customer)
									<option value="{{ $customer->id }}" {{ (isset($income->customer_id) && $income->customer_id == $customer->id) ? 'selected' : '' }}>
										{{ $customer->cust_name }}
									</option>
								@endforeach
							</select>-->
						</div>
						<div class="col-md-3 mb-3">
							<label class="form-label">Invoice / Reference Number</label>
							<input type="text" name="invoice_no" id="invoice_no" value="{{$income->invoice_no}}" class="form-control">
						</div>
						<div class="col-md-3 mb-3">
                            <label class="form-label">Income Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" id="amount" value="{{$income->amount}}" required class="form-control" placeholder="Amount">
                        </div>
						<div class="col-md-3 mb-3">
							<label class="form-label">Received Amount<span class="text-danger">*</span></label>
							<select name="pay_status" id="pay_status" required class="form-select">
								<option value="">Select</option>
								<option value="Full" <?=($income->pay_status == "Full") ? 'selected' : '' ?>>Full</option>
								<option value="Advance" <?=($income->pay_status == "Advance") ? 'selected' : '' ?>>Advance</option>														
								<option value="Due" <?=($income->pay_status == "Due") ? 'selected' : '' ?>>Due</option>														
							</select>
						</div>
						<div class="col-md-3 mb-3">
							<label class="form-label">Payment Mode<span class="text-danger">*</span></label>
							<select name="pay_mode" id="pay_mode" required class="form-select">
								<option value="">Select</option>
								<option value="Cash" <?=($income->pay_mode == "Cash") ? 'selected' : '' ?>>Cash</option>
								<option value="Bank" <?=($income->pay_mode == "Bank") ? 'selected' : '' ?>>Bank</option>														
								<option value="UPI" <?=($income->pay_mode == "UPI") ? 'selected' : '' ?>>UPI</option>														
							</select>
						</div>
						<div class="col-md-3 mb-3">
                            <label class="form-label">Advance Amount</label>
                            <input type="number" step="0.01" name="advance_amt" id="advance_amt" value="{{$income->advance_amt}}" class="form-control" placeholder="Amount">
                        </div>
						<div class="col-md-3 mb-3">
                            <label class="form-label">Balance Receivable Amount</label>
                            <input type="number" step="0.01" name="receivable_amt" id="receivable_amt" value="{{$income->receivable_amt}}" class="form-control" placeholder="Amount">
                        </div>
						<div class="col-md-3 mb-3">
                            <label class="form-label">Adjust Now (Amount)</label>
                            <input type="number" step="0.01" name="adjust_amt" id="adjust_amt" value="{{$income->adjust_amt}}" class="form-control" placeholder="Amount">
                        </div>
						<!--<div class="col-md-3 mb-3" id="dueDateContainer" style="display:none;">
							<label class="form-label">Due Date<span class="text-danger">*</span></label>
							<input type="date" name="due_date" id="due_date" value="{{$income->due_date}}" class="form-control">
						</div>-->
						           
						
						<div class="card">
							<div class="card-header">
								<h5>GST & TDS</h5>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<label class="form-label">TDS Applicable</label>
										<div class="row">
											<div class="col-6">
												<div class="card shadow-sm border-0 p-3 m-2">
													<div class="form-check">
														<input class="form-check-input" type="radio" name="tds_applicable" value="yes" id="tdsYes" {{ old('tds_applicable', $income->tds_applicable ?? '') == 'yes' ? 'checked' : '' }}>
														<label class="form-check-label" for="tdsYes">Yes</label>
													</div>
												</div>
											</div>
											<div class="col-6">
												<div class="card shadow-sm border-0 p-3 m-2">
													<div class="form-check">
														<input class="form-check-input" type="radio" name="tds_applicable" value="no" id="tdsNo" {{ old('tds_applicable', $income->tds_applicable ?? '') == 'no' ? 'checked' : '' }}>
														<label class="form-check-label" for="tdsNo">No</label>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="tds-container col-md-12"  id="tdsContainer">
										<div class="row">
											<div class="col-md-6">
												<div id="tds_dropdown_universal">
													<label for="tds_percent" class="form-label">TDS Percentage</label>
													<select name="tds_percent" id="tds_percent" class="form-control">
														@foreach ($purposes_of_tds as $purpose)
														<option value="{{ $purpose->tds_rate . '-' . $purpose->id }}"  {{ ($purpose->id == $income->tds_id) ? 'selected' : '' }}>
															{{ $purpose->category }} ({{ $purpose->tds_rate }}%)
														</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<label for="tds_amount">TDS Amount</label>
												<input type="text"  id="tds_amount"  value="{{$income->tds_amount}}" class="form-control" readonly>
											</div>
										</div>
									</div>

									<div class="gst-container col-md-12">
										<div class="mb-3">
											<label class="form-label">GST Applicable</label>
											<div class="row">
												@php
													$gstApplicable = strtolower(trim($income->gst_applicable ?? 'no'));
												@endphp
												<div class="col-6">
													<div class="card shadow-sm border-0 p-3 m-2">
														<div class="form-check">
															<input class="form-check-input" type="radio" name="gst_applicable" value="yes" id="gstYes_ca" {{ ($gstApplicable === 'yes') ? 'checked' : '' }}>
															<label class="form-check-label" for="gstYes_ca">Yes</label>
														</div>
													</div>
												</div>
												<div class="col-6">
													<div class="card shadow-sm border-0 p-3 m-2">
														<div class="form-check">
															<input class="form-check-input" name="gst_applicable" value="no" type="radio" id="gstNo_ca" {{ ($gstApplicable !== 'yes') ? 'checked' : '' }}>
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
													<option value="intrastate" <?= ($income->gst_trans == 'intrastate')?'selected':'' ?>>Intra State</option>
													<option value="interstate" <?= ($income->gst_trans == 'interstate')?'selected':'' ?>>Inter State</option>
													<option value="union" <?= ($income->gst_trans == 'union')?'selected':'' ?>>Union Territory</option>
												</select>
											</div>
											<div class="col-md-4">
												<label for="gst_rate">GST Rate (%)</label>
												<input type="number" name="gst_rate" id="gst_rate" value="{{$income->gst_rate}}" class="form-control" min="0" step="0.01">
											</div>
											<div class="col-md-4">
												<label for="gst_allocation">GST Allocation</label>
												<input type="text" name="gst_allocation" id="gst_allocation" value="{{$income->gst_allocation}}" class="form-control" readonly>
											</div>
											<div class="col-md-4">
												<label for="gst_amt">Total GST Amount</label>
												<input type="text" name="gst_amt" id="gst_amt" value="{{$income->gst_amt}}" class="form-control" readonly>
											</div>                                                    
										</div>
									</div>
								</div>
							</div>
						</div>
						
						
						
                        <div class="mb-3 col-md-12">
                            <label class="form-label" for="inputEmail4">Notes</label>
                            <textarea class="form-control" name="specification" id="projectDescription" placeholder="Specification"
                                rows="4">{{$income->specification}}</textarea>
                        </div>
						
						<div class="col-md-3 mb-3">
							<label class="form-label">Attachment</label>

							<!-- FILE INPUT -->
							<input type="file" name="income_doc" id="income_doc" class="form-control">

							<!-- OLD FILE (HIDDEN) -->
							<input type="hidden" name="old_income_doc" value="{{ $income->income_doc }}">

							<!-- VIEW LINK -->
							@if($income->income_doc)
								<div class="mt-2">
									<a href="{{ asset('uploads/income_docs/'.$income->income_doc) }}" target="_blank">
										📄 View Document
									</a>
								</div>
							@endif
						</div>

                        <div class="col-md-12 text-end">
                            <button type="button" id="#" class="btn btn-secondary me-2">
                                <a href="/other-income-list">Cancel</a>
                            </button>
                            
                        </div>
                
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>

	document.querySelectorAll('input, textarea, select').forEach(el => {
			el.disabled = true;
	});

	
	$('#income_doc').on('change', function () {
		let fileName = this.files[0]?.name;
		if (fileName) {
			$(this).after(`<small class="text-success">Selected: ${fileName}</small>`);
		}
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

		// Get old values from blade
		let selectedType = "{{ $income->incomeType }}";
		let selectedCategory = "{{ $income->categoryIncome }}";

		function loadCategory(type, selected = '') {

			let categoryDropdown = $('#categoryIncome');
			categoryDropdown.empty().append('<option value="">Select</option>');

			if (!type) {
				categoryDropdown.prop('disabled', true);
				return;
			}

			categoryDropdown.prop('disabled', false);

			let options = [];

			if (type === 'Revenue') {
				options = revenueOptions;
			} else if (type === 'Other') {
				options = otherOptions;
			}

			options.forEach(function (item) {
				let isSelected = (item === selected) ? 'selected' : '';
				categoryDropdown.append(`<option value="${item}" ${isSelected}>${item}</option>`);
			});
		}

		// INITIAL LOAD (Edit mode)
		loadCategory(selectedType, selectedCategory);

		// On change
		$('#incomeType').on('change', function () {
			loadCategory($(this).val());
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

		// Show "Other" if already selected in edit
		if (selectedCategory === "Miscellaneous Non-Operating Income" || selectedCategory === "Miscellaneous Operating Income") {
			$('#otherIncomeCategory').show();
		}

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
				.prop({ readonly: true, required: false });

			$('#advance_amt').closest('.col-md-3').hide();
			$('#receivable_amt').closest('.col-md-3').hide();
			$('#adjust_amt').closest('.col-md-3').hide();
		}

		function calculateAmounts() {

			let amount     = parseFloat($('#amount').val()) || 0;
			let adjustAmt  = parseFloat($('#adjust_amt').val()) || 0;
			let advanceAmt = parseFloat($('#advance_amt').val()) || 0;

			let status = ($('#pay_status').val() || '').trim().toLowerCase();

			// ==========================================
			// FULL
			// ==========================================

			if (status === 'full') {

				$('#adjust_amt').closest('.col-md-3').show();

				$('#advance_amt').closest('.col-md-3').hide();
				$('#receivable_amt').closest('.col-md-3').hide();

				$('#adjust_amt')
					.val(amount.toFixed(2))
					.prop({
						readonly: true,
						required: true
					});

				$('#advance_amt').val(0);
				$('#receivable_amt').val(0);
			}

			// ==========================================
			// ADVANCE
			// ==========================================

			else if (status === 'advance') {

				$('#advance_amt').closest('.col-md-3').show();
				$('#receivable_amt').closest('.col-md-3').show();
				$('#adjust_amt').closest('.col-md-3').show();

				$('#advance_amt').prop({
					readonly: false,
					required: true
				});

				$('#adjust_amt').prop({
					readonly: false,
					required: true
				});

				$('#receivable_amt').prop({
					readonly: true,
					required: true
				});

				let usedAmount = adjustAmt || advanceAmt;

				if (usedAmount > amount) {

					usedAmount = amount;

					$('#adjust_amt').val(amount.toFixed(2));
					$('#advance_amt').val(amount.toFixed(2));

					alert('Amount cannot exceed total income amount');
				}

				let balance = amount - usedAmount;

				$('#receivable_amt').val(balance.toFixed(2));
			}

			// ==========================================
			// DEFAULT
			// ==========================================

			else {

				resetFields();
			}
		}

		// ==========================================
		// EVENTS
		// ==========================================

		$('#pay_status').on('change', function () {

			resetFields();
			calculateAmounts();
		});

		$('#amount').on('input', function () {

			calculateAmounts();
		});

		$('#adjust_amt').on('input', function () {

			$('#advance_amt').val($(this).val());

			calculateAmounts();
		});

		$('#advance_amt').on('input', function () {

			$('#adjust_amt').val($(this).val());

			calculateAmounts();
		});

		// ==========================================
		// INITIAL LOAD EDIT MODE
		// ==========================================

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
    $(document).on("submit", "#incomeForm", function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        let formAction = $("#incomeForm").data("route");
        
        $("#loader").show();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: formAction,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $("#loader").hide();
                if (response.status === "success") {
                    showToast(response.message, "success");
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2000);
                } else {
                    showToast(response.message, "error");
                    console.error("Error Response:", response);
                }
            },
            error: function (xhr, status, error) {
                $("#loader").hide();
                // console.error("AJAX Error:", xhr.responseText);
                showToast("Something went wrong! Please try again.", "error");
            }
        });
    });


</script>
@endsection