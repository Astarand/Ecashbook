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
                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="settlement_amount"
                                   id="settlement_amount"
                                   class="form-control"
								   readonly
                                   required>
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

                    <div id="thirdPartySettlementBox"
                         style="display:none;">

                        <div class="row">

                            <!-- Settlement Ledger -->
							<div class="col-md-6 mb-3">

								<label class="form-label">
									Settlement Ledger <span class="text-danger">*</span>
								</label>

								<select name="settlement_ledger_id"
										id="settlement_ledger_id"
										class="form-select"
										required>

									<option value="">-- Select Settlement Ledger --</option>

								</select>

							</div>

							<!-- Other Settlement Ledger -->
							<div class="col-md-6 mb-3"
								 id="otherSettlementLedgerBox"
								 style="display:none;">

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
                                <input type="text"
                                       name="settlement_reason"
                                       class="form-control"
                                       placeholder="Enter settlement reason">

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
					$('#settlement_amount').val(
						parseFloat(response.amount || 0).toFixed(2)
					);

				} else {
					$('#settlement_amount').val('');
				}
			},
			error: function () {
				$('#settlement_amount').val('');
			}
		});


		// ==========================================
		// DEFAULT SETTLEMENT MODE = SELF
		// ==========================================

		$('input[name="settlement_mode"][value="Self"]')
			.prop('checked', true);

		$('input[name="settlement_mode"][value="Third Party"]')
			.prop('checked', false);


		// Hide Third Party section
		$('#thirdPartySettlementBox').hide();


		// Reset Settlement Ledger
		$('#settlement_ledger_id')
			.val('')
			.prop('required', false);


		// Reset Other Ledger
		$('#otherSettlementLedgerBox').hide();

		$('#other_settlement_ledger')
			.val('')
			.prop('required', false);


		// ==========================================
		// LOAD SETTLEMENT LEDGERS
		// ==========================================

		const $ledger = $('#settlement_ledger_id');

		$ledger.html(
			'<option value="">Loading...</option>'
		);


		$.ajax({

			url: '/settlement/ledgers',

			type: 'GET',

			data: {
				module_type: moduleType
			},

			success: function (response) {

				$ledger.empty();

				$ledger.append(
					'<option value="">-- Select Settlement Ledger --</option>'
				);

				// Other should be first
				$ledger.append(
					'<option value="other">Other</option>'
				);

				$.each(response.data, function (index, item) {

					$ledger.append(
						$('<option>', {
							value: item.id,
							text: item.name
						})
					);

				});

			},

			error: function () {

				$ledger.html(
					'<option value="">Unable to load settlement ledgers</option>'
				);

			}

		});


		// ==========================================
		// SHOW MODAL
		// ==========================================

		$('#settlementModal').modal('show');

	});
	
	$(document).on('change', '#settlement_ledger_id', function () {

		if ($(this).val() === 'other') {

			$('#otherSettlementLedgerBox').slideDown();

			$('#other_settlement_ledger')
				.prop('required', true);

		} else {

			$('#otherSettlementLedgerBox').slideUp();

			$('#other_settlement_ledger')
				.val('')
				.prop('required', false);

		}

	});


	// Settlement Mode Change
	$(document).on('change','input[name="settlement_mode"]',function () {

		if ($(this).val() === 'Third Party') {

			$('#thirdPartySettlementBox').slideDown();

			$('#settlement_ledger_id')
				.prop('required', true);

		} else {

			$('#thirdPartySettlementBox').slideUp();

			$('#settlement_ledger_id')
				.prop('required', false)
				.val('');

		}

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
		// Self settlement is not allowed
		if (settlementMode === 'Self') {
			showToast(
				'Please select Third Party Settlement before submitting.',
				'error'
			);
			return false;
		}

		$.ajax({
			url: '/settlement/store',
			type: "POST",
			data: $(this).serialize(),
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