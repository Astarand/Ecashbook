<div class="modal fade" id="hsnSacModal" tabindex="-1" aria-labelledby="hsnSacModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hsnSacModalLabel">
                    <i class="ti ti-search"></i>
                    Search HSN / SAC
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- Search -->
                <div class="row g-2 mb-3">
                    <div class="col-md-3">
                        <select id="hsnSacSearchType" class="form-control">
                            <option value="HSN">HSN</option>
                            <option value="SAC">SAC</option>
                        </select>
                    </div>

                    <div class="col-md-7">
                        <input type="text" id="hsnSacSearch" class="form-control" placeholder="Search HSN/SAC code or description...">
                    </div>

                    <div class="col-md-2">
                        <button type="button"
                                class="btn btn-primary w-100"
                                id="hsnSacSearchBtn">
                            <i class="ti ti-search"></i>
                            Search
                        </button>
                    </div>
                </div>

                <!-- Loading -->
                <div id="hsnSacLoader" class="text-center d-none py-3">
                    <div class="spinner-border spinner-border-sm"></div>
                    <span class="ms-2">Searching...</span>
                </div>

                <!-- Results -->
                <div id="hsnSacResults"></div>

                <!-- No Result -->
                <div id="hsnSacNoResult" class="text-center d-none py-4">
                    <i class="ti ti-search-off fs-2 text-muted"></i>
                    <h6 class="mt-2">No HSN/SAC found</h6>
                    <p class="text-muted mb-3">You can manually add the HSN/SAC details.</p>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="showManualHsnSacBtn"><i class="ti ti-plus"></i>Enter Manually</button>
                </div>

                <!-- Manual Entry -->
                <div id="manualHsnSacSection" class="d-none border rounded p-3 mt-3">

                    <h6 class="mb-3"><i class="ti ti-edit"></i>Enter HSN / SAC Manually</h6>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select id="manualCodeType" class="form-control">
                                <option value="">Select Type</option>
                                <option value="HSN">HSN</option>
                                <option value="SAC">SAC</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">HSN / SAC Code<span class="text-danger">*</span></label>
                            <input type="text" id="manualCode" class="form-control" placeholder="Enter code">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">GST Rate (%)<span class="text-danger">*</span></label>
                            <input type="number" id="manualGstRate" class="form-control" min="0" max="100" step="0.01" placeholder="GST Rate">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Product / Service Name<span class="text-danger">*</span></label>
                            <input type="text" id="manualDescription" class="form-control" placeholder="Enter product or service name">
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-light-secondary btn-sm" id="cancelManualHsnSac">Cancel</button>
                        <button type="button" class="btn btn-success btn-sm" id="saveManualHsnSac"><i class="ti ti-device-floppy"></i>Save HSN / SAC</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>	

	let hsnSacCallback = null;
	function openHsnSacModal(callback = null, codeType = '') 
	{
		hsnSacCallback = callback;

		$('#hsnSacSearch').val('');
		$('#hsnSacSearchType').val(codeType);

		$('#hsnSacResults').html('');
		$('#hsnSacNoResult').addClass('d-none');
		$('#manualHsnSacSection').addClass('d-none');

		$('#manualCodeType').val('');
		$('#manualCode').val('');
		$('#manualGstRate').val('');
		$('#manualDescription').val('');

		// Show/hide type options according to selection
		if (codeType === 'HSN') {

			$('#hsnSacSearchType option[value="HSN"]').show();
			$('#hsnSacSearchType option[value="SAC"]').hide();

		} else if (codeType === 'SAC') {

			$('#hsnSacSearchType option[value="HSN"]').hide();
			$('#hsnSacSearchType option[value="SAC"]').show();

		} else {

			$('#hsnSacSearchType option[value="HSN"]').show();
			$('#hsnSacSearchType option[value="SAC"]').show();
		}

		$('#hsnSacModal').modal('show');
	}
	
	function openHsnSacForItem() 
	{
		const itemType = $('input[name="item_type"]:checked').val();

		if (itemType === 'product') {

			openHsnSacModal(function (data) {

				$('#item_name').val(data.description || '');
				$('#hsn_code').val(data.code || '');
				$('#gst_rate_prod').val(data.gst_rate ?? '');

				$('#item_name, #hsn_code, #gst_rate_prod').trigger('input').trigger('change');

			}, 'HSN');

		} else if (itemType === 'service') {

			openHsnSacModal(function (data) {

				$('#service_name').val(data.description || '');
				$('#sac_code').val(data.code || '');
				$('#gst_rate_service').val(data.gst_rate ?? '');

				$('#service_name, #sac_code, #gst_rate_service').trigger('input').trigger('change');

			}, 'SAC');

		} else {
			showToast('Please select Product or Service first.', 'error');
		}
	}
	
	$('#hsnSacSearchBtn').on('click', function () {
		searchHsnSac();
	});
	$('#hsnSacSearch').on('keypress', function (e) {
		if (e.which === 13) {
			searchHsnSac();
		}
	});
	
	function searchHsnSac() 
	{
		let search = $('#hsnSacSearch').val().trim();
		let type   = $('#hsnSacSearchType').val();

		if (search.length < 2) {
			showToast('Please enter at least 2 characters.','error');
			return;
		}

		$('#hsnSacLoader').removeClass('d-none');
		$('#hsnSacResults').html('');
		$('#hsnSacNoResult').addClass('d-none');
		$('#manualHsnSacSection').addClass('d-none');

		$.ajax({
			url: '/user-hsn-sac/search',
			type: 'GET',
			data: {
				search: search,
				code_type: type
			},
			success: function (response) {
				$('#hsnSacLoader').addClass('d-none');
				if (response.success && response.data.length > 0) {
					let html = `
						<div class="table-responsive">
							<table class="table tbl-product align-middle"  id="pc-dt-simple">
								<thead>
									<tr>
										<th width="80">Type</th>
										<th width="120">Code</th>
										<th>Description</th>
										<th width="100">GST Rate</th>
										<th width="80">Action</th>
									</tr>
								</thead>
								<tbody>`;

							response.data.forEach(function (row) {

								html += `
									<tr>
										<td>
											<span class="badge bg-light-primary text-primary">
												${escapeHtml(row.code_type)}
											</span>
										</td>
										<td>
											<strong>
												${escapeHtml(row.code)}
											</strong>
										</td>
										<td title="${escapeHtml(row.description ?? '')}">
											${escapeHtml(
												(row.description ?? '').length > 50
													? (row.description ?? '').substring(0, 50) + '...'
													: (row.description ?? '')
											)}
										</td>
										<td>
											<strong>
												${parseFloat(row.gst_rate).toFixed(2)}%
											</strong>
										</td>
										<td>
											<button type="button" class="btn btn-primary selectHsnSac" data-id="${row.id}">Select</button>
										</td>
									</tr>
								`;

							});

					html += `</tbody></table></div>`;

					$('#hsnSacResults').html(html);

				} else {
					$('#hsnSacNoResult').removeClass('d-none');
				}

			},

			error: function () {
				$('#hsnSacLoader').addClass('d-none');
				showToast('Unable to search HSN/SAC.','error');
			}

		});

	}
	
	$(document).on('click', '.selectHsnSac', function () {

		let id = $(this).data('id');
		$.ajax({
			url: '/user-hsn-sac/get/' + id,
			type: 'GET',
			success: function (response) {
				if (!response.success) {
					showToast(response.message || 'HSN/SAC not found.','error');
					return;
				}

				let data = response.data;
				if (typeof hsnSacCallback === 'function') {
					hsnSacCallback(data);
				}
				$('#hsnSacModal').modal('hide');
			},

			error: function () {
				showToast('Unable to load HSN/SAC details.','error');
			}

		});

	});
	
	function syncManualCodeType() {
		const itemType = $('input[name="item_type"]:checked').val();

		if (itemType === 'product') {
			$('#manualCodeType').val('HSN').prop('disabled', true);
		} else if (itemType === 'service') {
			$('#manualCodeType').val('SAC').prop('disabled', true);
		} else {
			$('#manualCodeType').val('').prop('disabled', false);
		}
	}


	// Product / Service radio change
	$('input[name="item_type"]').on('change', function () {
		syncManualCodeType();
	});
	
	// Enter Manually
	$('#showManualHsnSacBtn').on('click', function () {

		syncManualCodeType();

		$('#manualHsnSacSection').removeClass('d-none');
		let search = $('#hsnSacSearch').val().trim();
		$('#manualCode').val('');
		$('#manualDescription').val('');

		if (search) {
			// Check whether search contains only numbers
			if (/^\d+$/.test(search)) {
				$('#manualCode').val(search);

			} else {
				$('#manualDescription').val(search);
			}
		}
	});

	// Initial state
	syncManualCodeType();
	
	$('#cancelManualHsnSac').on('click', function () {
		$('#manualHsnSacSection').addClass('d-none');
	});
	
	$('#saveManualHsnSac').on('click', function () {

		let codeType    = $('#manualCodeType').val();
		let code        = $('#manualCode').val().trim();
		let description = $('#manualDescription').val().trim();
		let gstRate     = $('#manualGstRate').val();

		if (!codeType) {
			showToast('Please select HSN or SAC.', 'error');
			return;
		}

		if (!code) {
			showToast('Please enter HSN/SAC code.', 'error');
			return;
		}

		if (!description) {
			showToast('Please enter product/service name.', 'error');
			return;
		}

		if (gstRate === '') {
			showToast('Please enter GST rate.', 'error');
			return;
		}

		let btn = $('#saveManualHsnSac');
		btn.prop('disabled', true);

		$.ajax({
			url: '/user-hsn-sac/store',
			type: 'POST',
			data: {
				_token: $('meta[name="csrf-token"]').attr('content'),
				code_type: codeType,
				code: code,
				description: description,
				gst_rate: gstRate
			},

			success: function (response) {
				btn.prop('disabled', false);
				if (!response.success) {
					showToast(response.message || 'Unable to save.','error');
					return;
				}

				if (typeof hsnSacCallback === 'function') {
					hsnSacCallback(response.data);
				}

				$('#hsnSacModal').modal('hide');
				showToast(response.message,'success');
			},

			error: function (xhr) {
				btn.prop('disabled', false);
				let message = 'Something went wrong.';
				if (xhr.responseJSON?.errors) {
					message = Object.values(xhr.responseJSON.errors).flat().join('\n');
				} else if (xhr.responseJSON?.message) {
					message = xhr.responseJSON.message;
				}
				showToast(message, 'error');
			}
		});
	});
	
	function escapeHtml(value) 
	{
		return $('<div>').text(value ?? '').html();
	}

</script>