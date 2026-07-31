<div class="modal fade" id="settlementModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="settlementForm">

                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">
                        Third Party Settlement
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden"
                           name="module_type"
                           id="settlement_module_type">

                    <input type="hidden"
                           name="p_id"
                           id="settlement_p_id">

                    <div class="row">

                        <!-- Settlement Mode -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Settlement Mode
                            </label>

                            <div>

                                <div class="form-check form-check-inline">

                                    <input type="radio"
                                           class="form-check-input"
                                           name="settlement_mode"
                                           value="Self"
                                           checked>

                                    <label class="form-check-label">
                                        Self
                                    </label>

                                </div>

                                <div class="form-check form-check-inline">

                                    <input type="radio"
                                           class="form-check-input"
                                           name="settlement_mode"
                                           value="Third Party">

                                    <label class="form-check-label">
                                        Third Party
                                    </label>

                                </div>

                            </div>

                        </div>


                        <!-- Settlement Amount -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Settlement Amount
                            </label>
                            <input type="number" step="0.01" min="0" name="settlement_amount" id="settlement_amount" class="form-control" required>
                        </div>
						
						<!-- Settlement Date -->
						<div class="col-md-6 mb-3">
							<label class="form-label">
								Date <span class="text-danger">*</span>
							</label>
							<input type="date"
								   name="settlement_date"
								   id="settlement_date"
								   class="form-control"
								   value="{{ date('Y-m-d') }}"
								   required>
						</div>

						<!-- Party Type -->
						<div class="col-md-6 mb-3">
							<label class="form-label">
								Party Type <span class="text-danger">*</span>
							</label>
							<select name="party_type" id="party_type" class="form-select" required >
								<option value="">-- Select Party Type --</option>
								<option value="Customer">Customer</option>
								<option value="Vendor">Vendor</option>
								<option value="Director">Director/Partner</option>
								<option value="Employee">Employee</option>
								<option value="Group Company">Group Company</option>
								<option value="Other">Other</option>
							</select>
						</div>

						<!-- Party Name -->
						<div class="col-md-6 mb-3">
							<label class="form-label">
								Party Name <span class="text-danger">*</span>
							</label>
							<select name="settlement_ledger_id" id="settlement_ledger_id" class="form-select" required>
									<option value="">-- Select Settlement Ledger --</option>
							</select>
						</div>
						
						<!-- Payment Mode -->
						<div class="col-md-6 mb-3">
							<label class="form-label">
								Payment Mode <span class="text-danger">*</span>
							</label>
							<select name="payment_mode"
									id="settlement_payment_mode"
									class="form-select"
									required>
								<option value="">-- Select Payment Mode --</option>
								<option value="Cash">Cash</option>
								<option value="Bank">Bank</option>
								<option value="UPI">UPI</option>
							</select>
						</div>

						<!-- Bank -->
						<div class="col-md-6 mb-3"
							 id="settlementBankBox"
							 style="display:none;">

							<label class="form-label">
								Bank <span class="text-danger">*</span>
							</label>
							<select name="bank_id"
									id="settlement_bank_id"
									class="form-select">
								<option value="">-- Select Bank --</option>
							</select>
						</div>
                    </div>


                    <!-- Third Party Section -->

                    <div id="thirdPartySettlementBox">

                        <div class="row">
							<!-- Other Settlement Ledger -->
							<div class="col-md-6 mb-3" id="otherSettlementLedgerBox" style="display:none;">
								<label class="form-label">
									Settlement Ledger Name <span class="text-danger">*</span>
								</label>

								<input type="text"
									   name="other_settlement_ledger"
									   id="other_settlement_ledger"
									   class="form-control"
									   placeholder="Enter settlement ledger name">
							</div>


                            <!-- Settlement Reason -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Settlement Reason
                                </label>
                                <input type="text" name="settlement_reason" id="settlement_reason" class="form-control" placeholder="Enter settlement reason">

                            </div>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-primary">
                        Save Settlement
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
<script>
	// Start Journal Settlement
	$(document).on('click', '.settlement-btn', function () {

		const moduleType = $(this).data('module');
		const p_id = $(this).data('id');

		$('#settlement_module_type').val(moduleType);
		$('#settlement_p_id').val(p_id);
		
		// FETCH OUTSTANDING INVOICE AMOUNT
		$('#settlement_amount').val('');
		$.ajax({
			url: '/settlement/amount',
			type: 'GET',
			data: {
				module_type: moduleType,
				p_id: p_id
			},
			success: function (response) {
				if (response.success) {
					$('#settlement_amount').val(parseFloat(response.amount || 0).toFixed(2));
				} else {
					$('#settlement_amount').val('');
				}
			},
			error: function () {
				$('#settlement_amount').val('');
			}
		});

		$('input[name="settlement_mode"][value="Self"]').prop('checked', true);

		$('input[name="settlement_mode"][value="Third Party"]').prop('checked', false);

		// Reset Settlement Ledger
		$('#party_type').val('');
		$('#settlement_ledger_id').val('');
		$('#settlement_payment_mode').val('');
		$('#settlement_bank_id').val('');
		$('#settlement_reason').val('');
		$('#otherSettlementLedgerBox').hide();
		$('#other_settlement_ledger').val('').prop('required', false);

		$('#settlementModal').modal('show');

	});
	
	$('#party_type').change(function () {

		if ($(this).val() === 'Other') {
			$('#otherSettlementLedgerBox').show();
			$('#other_settlement_ledger').prop('required', true);
			$('#settlement_ledger_id').val('').prop('required', false);
		} else {
			$('#otherSettlementLedgerBox').hide();
			$('#other_settlement_ledger').prop('required', false).val('');
			$('#settlement_ledger_id').val('').prop('required', true);
		}
		
		$.get('/settlement/ledgers', {
			party_type: $(this).val()
		}, function (res) {

			let html = '<option value="">Select Party</option>';

			$.each(res.data, function(i, row) {
				html += `<option value="${row.id}" data-party_name="${row.name}">${row.name}</option>`;
			});

			$('#settlement_ledger_id').html(html);

		});

	});

	
	// ==========================================
	// PAYMENT MODE CHANGE
	// ==========================================

	$(document).on('change', '#settlement_payment_mode', function () {

		const paymentMode = $(this).val();

		const $bankBox = $('#settlementBankBox');
		const $bank = $('#settlement_bank_id');

		if (paymentMode === 'Bank' || paymentMode === 'UPI') {
			// Show Bank
			$bankBox.slideDown();
			$bank.prop('required', true);
			$bank.html('<option value="">Loading...</option>');

			$.ajax({
				url: '/get-banks',
				type: 'GET',
				success: function (response) {
					$bank.empty();
					$bank.append('<option value="">-- Select Bank --</option>');

					$.each(response, function (index, item) {
						$bank.append(
							$('<option>', {
								value: item.id,
								text: item.bank_name
							})
						);

					});

				},

				error: function () {
					$bank.html('<option value="">Unable to load banks</option>');
				}

			});

		} else {
			// Cash
			$bankBox.slideUp();
			$bank.val('').prop('required', false);
		}

	});
	
	$('#settlementForm').submit(function(e) {
		e.preventDefault();
		
		const settlementMode = $('input[name="settlement_mode"]:checked').val();
		if (settlementMode === 'Self') {
			showToast(
				'Please select Third Party Settlement before submitting.',
				'error'
			);
			return false;
		}
		
		// Get selected party name
		let partyName = $('#settlement_ledger_id option:selected').data('party_name') || '';
		let formData = $(this).serialize();
		formData += '&party_name=' + encodeURIComponent(partyName);

		$.ajax({
			url: '/settlement/store',
			type: "POST",
			data: formData,
			success: function(response) {

				if (response.success) {
					$('#settlementModal').modal('hide');
					showToast(
						'Settlement saved successfully',
						'success'
					);
					location.reload();
				}

			},

			error: function(xhr) {

				showToast(
					xhr.responseJSON?.message ||
					'Unable to save settlement',
					'error'
				);

			}

		});

	});
	//End Journal settlement
</script>