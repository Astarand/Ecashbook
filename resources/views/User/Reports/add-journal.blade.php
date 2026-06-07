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
                        <li class="breadcrumb-item"><a href="{{ url('/journal-list') }}">Journal Entry</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add New Journal</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Add New Journal</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->


    <form method="post" id="journalForm" action="{{ route('user.saveJournal') }}" enctype="multipart/form-data">
        @csrf

        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Basic Information <span class="text-danger">*</span></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3 mb-3">
                            <label class="form-label">Journal No <span class="text-danger">*</span></label>
                            <input type="text" name="journal_no" id="journal_no" class="form-control" value="{{ 'JV-' . $journalNo }}" readonly required>
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="journal_date" id="journal_date" class="form-control" required>
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label class="form-label">Reference Type</label>
                            <select class="form-select" name="reference_type" id="reference_type">
                                <option value="" selected>Please Select</option>
                                <option value="New Ref">New Reference</option>
                                <option value="Against Ref">Against Reference</option>
                                <!--<option value="Advance">Advance</option>-->
                                <option value="On Account">On Account</option>
                            </select>
                        </div>

                        <div class="col-sm-3 mb-3">
                            <label class="form-label">Reference No (Invoice/Bill)</label>
                            <input type="text" name="reference_no" id="reference_no" class="form-control" placeholder="Enter invoice or bill number">
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Purpose of Entry<span class="text-danger">*</span></label>
                            <select class="form-select" name="entry_type" id="entry_type" required>
                                <option value="" selected>Please Select</option>
                                <option value="Adjustment">Adjustment</option>
                                <!--<option value="Advance">Advance</option>-->
                                <option value="Accrual">Accrual</option>
                                <option value="Depreciation">Depreciation</option>
                                <option value="Opening Balance">Opening Balance</option>
                                <option value="Correction">Correction</option>
                                <option value="Transfer">Transfer</option>
                                <option value="Provision">Provision</option>
                            </select>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Voucher Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="source" id="source" required>
                                <option value="" selected>Please Select</option>
                                <option value="Sales">Sales</option>
                                <option value="Purchase">Purchase</option>
                                <option value="Payment">Payment</option>
                                <option value="Receipt">Receipt</option>
                                <option value="Contra">Contra</option>
                                <option value="Journal">Journal</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Accounting Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Ledger <span class="text-danger">*</span></label>
                            <input type="text" name="ledger" id="ledger" class="form-control" placeholder="Enter ledger" required>
                        </div>

                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Party Name <span class="text-danger">*</span></label>
                            <input type="text" name="party_name" id="party_name" class="form-control" placeholder="Enter party name" required>
                        </div>

                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Debit/Credit (Auto from Ledger) <span class="text-danger">*</span></label>
                            <select id="debit_credit" class="form-select" name="debit_credit">
								<option value="">Select Debit/Credit</option>
								<option value="Debit">Debit</option>
								<option value="Credit">Credit</option>
							</select>
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" min="0" step="0.01" name="amount" id="amount" class="form-control" placeholder="Enter amount" required>
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Notes <span class="text-danger">*</span></label>
                            <select class="form-select" name="notes" id="notes" required>
                                <option value="" selected>Please Select</option>
                                <option value="Wrong entry correction">Wrong entry correction</option>
                                <option value="Amount rectification">Amount rectification</option>
                                <option value="Advance adjusted against invoice">Advance adjusted against invoice</option>
                                <option value="Outstanding expense">Outstanding expense</option>
                                <option value="Provision for audit fees">Provision for audit fees</option>
                                <option value="Depreciation for this FY">Depreciation for this FY</option>
                                <option value="Opening balance">Opening balance</option>
                                <option value="Cash transferred to bank">Cash transferred to bank</option>
                                <option value="Inter-bank transfer">Inter-bank transfer</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="col-sm-12 mb-3" id="otherNoteBox" style="display: none;">
                            <label class="form-label">Other Note <span class="text-danger">*</span></label>
                            <textarea name="other_note" id="other_note" class="form-control" rows="3" placeholder="Enter custom note"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
		<div class="col-12 mb-3">
			<div class="card">
				<div class="card-header">
					<h5>GST & TDS</h5>
				</div>
				<div class="card-body">
					<div class="row currAsset">
						<div class="tds-container col-md-12">
							<div class="mb-3">
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
						</div>

						<div class="gst-container col-md-12">
							<div class="mb-3">
								<label class="form-label">GST Applicable <span class="text-danger">*</span></label>
								<div class="row">
									<div class="col-6">
										<div class="card shadow-sm border-0 p-3 m-2">
											<div class="form-check">
												<input class="form-check-input" type="radio" name="gst_applicable" value="yes" id="gstYes_ca" checked>
												<label class="form-check-label" for="gstYes_ca">Yes</label>
											</div>
										</div>
									</div>
									<div class="col-6">
										<div class="card shadow-sm border-0 p-3 m-2">
											<div class="form-check">
												<input class="form-check-input" name="gst_applicable" value="no" type="radio" id="gstNo_ca">
												<label class="form-check-label" for="gstNo_ca">No</label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-md-4">
									<label for="hsn_sac_code">HSN/SAC Code</label>
									<input type="text" name="hsn_sac_code" id="hsn_sac_code" class="form-control">
								</div>
								<div class="col-md-4">
									<label for="gst_rate">GST Rate (%)</label>
									<input type="number" name="gst_rate" id="gst_rate" class="form-control" min="0" step="0.01">
								</div>
								<div class="col-md-4">
									<label for="gst_trans">GST Transaction Mode</label>
									<select class="form-select" name="gst_trans" id="gst_trans">
										<option value="">Select</option>
										<option value="intrastate">Intra State</option>
										<option value="interstate">Inter State</option>
										<option value="union">Union Territory</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Attachments</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label class="form-label" for="journalImageInput">Upload Attachments (Max 3)</label>
                            <div class="media-uploader-wrapper">
                                <input type="file" class="media-uploader-input" name="attachments[]" id="journalImageInput" accept="image/*" multiple>
                                <div class="media-uploader-dropzone" id="journalUploadDropzone">
                                    <div class="media-uploader-content text-center">
                                        <i class="ti ti-upload f-24 mb-2"></i>
                                        <p class="mb-0 fw-semibold">Click to Upload or Drag and Drop</p>
                                        <p class="text-muted small mb-0">Supported formats: JPEG, PNG, GIF (Max 3 files)</p>
                                    </div>
                                </div>
                                <div id="journalPreviewContainer" class="media-preview-container mt-3" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted" id="journalSelectedCount">0 files selected</small>
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="clearJournalImages">Remove All</button>
                                    </div>
                                    <div id="journalPreviewGrid" class="media-preview-grid"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 text-end mb-3">
            <a href="{{ url('/journal-list') }}" class="btn btn-primary cancel me-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Journal</button>
        </div>
    </form>
</div>

<style>
    .media-uploader-input {
        display: none;
    }

    .media-uploader-dropzone {
        border: 1px dashed #c8cedb;
        border-radius: 12px;
        background: #f8f9fc;
        min-height: 130px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .media-uploader-dropzone:hover,
    .media-uploader-dropzone.is-dragging {
        border-color: #0d6efd;
        background: #eef4ff;
    }

    .media-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 12px;
    }

    .media-preview-item {
        border: 1px solid #e8ecf5;
        border-radius: 10px;
        background: #fff;
        overflow: hidden;
    }

    .media-preview-thumb {
        width: 100%;
        height: 85px;
        background: #f3f5fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .media-preview-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .media-preview-name {
        font-size: 12px;
        padding: 8px;
        color: #5b6375;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<script>
    function createPreviewCard(file) {
        const previewCard = document.createElement('div');
        previewCard.className = 'media-preview-item';

        const thumb = document.createElement('div');
        thumb.className = 'media-preview-thumb';

        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = file.name;
            thumb.appendChild(img);
        } else {
            thumb.innerHTML = '<i class="ti ti-file f-24 text-muted"></i>';
        }

        const fileName = document.createElement('div');
        fileName.className = 'media-preview-name';
        fileName.textContent = file.name;

        previewCard.appendChild(thumb);
        previewCard.appendChild(fileName);

        return previewCard;
    }

    function bindMediaUploader(config) {
        const input = document.getElementById(config.inputId);
        const dropzone = document.getElementById(config.dropzoneId);
        const previewContainer = document.getElementById(config.previewContainerId);
        const previewGrid = document.getElementById(config.previewGridId);
        const countLabel = document.getElementById(config.countLabelId);
        const clearButton = document.getElementById(config.clearButtonId);
        const maxFiles = config.maxFiles || 3;
        let selectedFiles = Array.from(input ? input.files || [] : []);

        if (!input || !dropzone || !previewContainer || !previewGrid || !countLabel || !clearButton) {
            return;
        }

        const syncInputFiles = () => {
            const transfer = new DataTransfer();
            selectedFiles.forEach((file) => transfer.items.add(file));
            input.files = transfer.files;
        };

        const renderPreview = () => {
            previewGrid.innerHTML = '';

            if (!selectedFiles.length) {
                previewContainer.style.display = 'none';
                countLabel.textContent = '0 files selected';
                return;
            }

            countLabel.textContent = `${selectedFiles.length} file${selectedFiles.length > 1 ? 's' : ''} selected`;
            previewContainer.style.display = 'block';

            selectedFiles.forEach((file) => {
                previewGrid.appendChild(createPreviewCard(file));
            });
        };

        const assignFilesToInput = (newFiles, append = true) => {
            const existingFiles = append ? [...selectedFiles] : [];
            const incomingFiles = Array.from(newFiles || []);
            const mergedFiles = [...existingFiles, ...incomingFiles];

            // Prevent duplicate previews when the same file is selected multiple times.
            const uniqueFiles = [];
            const seenSignatures = new Set();

            mergedFiles.forEach((file) => {
                const signature = `${file.name}_${file.size}_${file.lastModified}`;
                if (!seenSignatures.has(signature)) {
                    seenSignatures.add(signature);
                    uniqueFiles.push(file);
                }
            });

            const limitedFiles = uniqueFiles.slice(0, maxFiles);
            if (uniqueFiles.length > maxFiles) {
                showToast(`You can upload maximum ${maxFiles} files.`, 'error');
            }

            selectedFiles = limitedFiles;
            syncInputFiles();
            renderPreview();
        };

        dropzone.addEventListener('click', () => input.click());

        dropzone.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropzone.classList.add('is-dragging');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('is-dragging');
        });

        dropzone.addEventListener('drop', (event) => {
            event.preventDefault();
            dropzone.classList.remove('is-dragging');
            if (event.dataTransfer && event.dataTransfer.files.length) {
                assignFilesToInput(event.dataTransfer.files, true);
            }
        });

        input.addEventListener('change', (event) => {
            assignFilesToInput(event.target.files, true);
        });

        clearButton.addEventListener('click', () => {
            selectedFiles = [];
            syncInputFiles();
            renderPreview();
        });

        renderPreview();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const journalNoInput = document.getElementById('journal_no');
        if (journalNoInput && (!journalNoInput.value || journalNoInput.value === 'AUTO')) {
            const now = new Date();
            const y = now.getFullYear().toString().slice(-2);
            const m = String(now.getMonth() + 1).padStart(2, '0');
            const d = String(now.getDate()).padStart(2, '0');
            const h = String(now.getHours()).padStart(2, '0');
            const min = String(now.getMinutes()).padStart(2, '0');
            journalNoInput.value = `JV-${y}${m}${d}-${h}${min}`;
        }

        const ledgerInput = document.getElementById('ledger');
        const debitCreditInput = document.getElementById('debit_credit');
        const syncDebitCredit = () => {
            if (!ledgerInput || !debitCreditInput) {
                return;
            }

            const ledgerValue = ledgerInput.value.trim().toLowerCase();

            if (!ledgerValue) {
                debitCreditInput.value = '';
                return;
            }

            if (ledgerValue.includes('sales') || ledgerValue.includes('income') || ledgerValue.includes('provision')) {
                debitCreditInput.value = 'Credit';
                return;
            }

            if (ledgerValue.includes('purchase') || ledgerValue.includes('expense') || ledgerValue.includes('cash')) {
                debitCreditInput.value = 'Debit';
                return;
            }

            debitCreditInput.value = '';
        };

        if (ledgerInput && debitCreditInput) {
            ledgerInput.addEventListener('input', syncDebitCredit);
            syncDebitCredit();
        }

        const notesSelect = document.getElementById('notes');
        const otherNoteBox = document.getElementById('otherNoteBox');
        const otherNoteInput = document.getElementById('other_note');
        const syncOtherNoteVisibility = () => {
            if (!notesSelect || !otherNoteBox || !otherNoteInput) {
                return;
            }

            const isOther = notesSelect.value === 'Other';
            otherNoteBox.style.display = isOther ? 'block' : 'none';
            otherNoteInput.required = isOther;

            if (!isOther) {
                otherNoteInput.value = '';
            }
        };

        if (notesSelect) {
            notesSelect.addEventListener('change', syncOtherNoteVisibility);
            syncOtherNoteVisibility();
        }

        bindMediaUploader({
            inputId: 'journalImageInput',
            dropzoneId: 'journalUploadDropzone',
            previewContainerId: 'journalPreviewContainer',
            previewGridId: 'journalPreviewGrid',
            countLabelId: 'journalSelectedCount',
            clearButtonId: 'clearJournalImages',
            maxFiles: 3
        });
    });
	

	document.getElementById('journalForm').addEventListener('submit', function(e) {
		e.preventDefault();

		let form = this;
		let formData = new FormData(form);

		// Disable button
		let submitBtn = form.querySelector('button[type="submit"]');
		submitBtn.disabled = true;
		submitBtn.innerText = 'Saving...';

		$.ajax({
			url: form.action,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,

			success: function(response) {

				// ✅ Success Message
				showToast('Journal saved successfully', 'success');

				// Reset form
				form.reset();

				// Clear preview
				$('#journalPreviewGrid').html('');
				$('#journalPreviewContainer').hide();
				$('#journalSelectedCount').text('0 files selected');

				// Redirect (optional)
				setTimeout(() => {
					window.location.href = "{{ route('user.JournalList') }}";
				}, 1000);
			},

			error: function(xhr) {

				let errorMsg = 'Something went wrong';

				if (xhr.responseJSON && xhr.responseJSON.errors) {
					errorMsg = Object.values(xhr.responseJSON.errors)
						.map(err => err[0])
						.join('<br>');
				}

				showToast(errorMsg, 'error');
			},

			complete: function() {
				submitBtn.disabled = false;
				submitBtn.innerText = 'Save Journal';
			}
		});
	});
	
	//GST applicable
	document.addEventListener("DOMContentLoaded", function () {

		document.querySelectorAll(".gst-container").forEach((container) => {

			// ✅ CURRENT ASSET GST
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
			}
			let fields = [];

			if (type === 'ca') {
				fields = container.querySelectorAll('#hsn_sac_code, #gst_rate, #gst_trans');
			}

			fields.forEach(field => {
				field.closest(".col-md-4").style.display = isYes ? "block" : "none";
			});
		}

	});

</script>

@endsection
