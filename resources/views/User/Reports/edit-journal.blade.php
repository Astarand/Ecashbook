@extends('App.Layout')

@section('container')

<div class="pc-content">

    <div class="page-header">
		<div class="page-block">
			<div class="row align-items-center">
				<div class="col-md-12">
					<ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Financial Reports</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/journal-list') }}">Journal Entry</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Journal</li>
                    </ul>
				</div>
				<div class="col-md-12">
					<div class="page-header-title">
						<h2 class="mb-0">Edit Journal</h2>
					</div>
				</div>
			</div>
		</div>
	</div>

    <form id="editJournalForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="journal_id" value="{{ $journal->id }}">

		<div class="col-12 mb-3">
			<div class="card">
				<div class="card-body">

					<div class="row">
						<div class="col-sm-3 mb-3">
							<label class="form-label">Journal No <span class="text-danger">*</span></label>
							<input type="text" name="journal_no" id="journal_no" class="form-control" value="{{ 'JV-' . $journal->journal_no ?? 'JV-00001' }}" readonly >
						</div>
						<div class="col-sm-3 mb-3">
							<label class="form-label">Date <span class="text-danger">*</span></label>
							<input type="date" name="journal_date" id="journal_date" class="form-control" value="{{ $journal->journal_date ?? '' }}" >
						</div>
						<div class="col-sm-3 mb-3">
							<label class="form-label">Reference Type</label>
							<select class="form-select" name="reference_type" id="reference_type" >
								<option value="" {{ (($journal->reference_type ?? '') === '') ? 'selected' : '' }}>Please Select</option>
								<option value="New Ref" {{ (($journal->reference_type ?? '') === 'New Ref') ? 'selected' : '' }}>New Reference</option>
								<option value="Against Ref" {{ (($journal->reference_type ?? '') === 'Against Ref') ? 'selected' : '' }}>Against Reference</option>
								<!--<option value="Advance" {{ (($journal->reference_type ?? '') === 'Advance') ? 'selected' : '' }}>Advance</option>-->
								<option value="On Account" {{ (($journal->reference_type ?? '') === 'On Account') ? 'selected' : '' }}>On Account</option>
							</select>
						</div>

						<div class="col-sm-3 mb-3">
							<label class="form-label">Reference No (Invoice/Bill)</label>
							<input type="text" name="reference_no" id="reference_no" class="form-control" value="{{ $journal->reference_no ?? '' }}" >
						</div>

						<div class="col-sm-6 mb-3">
							<label class="form-label">Purpose of Entry <span class="text-danger">*</span></label>
							<select class="form-select" name="entry_type" id="entry_type" >
								<option value="" {{ (($journal->entry_type ?? '') === '') ? 'selected' : '' }}>Please Select</option>
								<option value="Adjustment" {{ (($journal->entry_type ?? '') === 'Adjustment') ? 'selected' : '' }}>Adjustment</option>
								<!--<option value="Advance" {{ (($journal->entry_type ?? '') === 'Advance') ? 'selected' : '' }}>Advance</option>-->
								<option value="Accrual" {{ (($journal->entry_type ?? '') === 'Accrual') ? 'selected' : '' }}>Accrual</option>
								<option value="Depreciation" {{ (($journal->entry_type ?? '') === 'Depreciation') ? 'selected' : '' }}>Depreciation</option>
								<option value="Opening Balance" {{ (($journal->entry_type ?? '') === 'Opening Balance') ? 'selected' : '' }}>Opening Balance</option>
								<option value="Correction" {{ (($journal->entry_type ?? '') === 'Correction') ? 'selected' : '' }}>Correction</option>
								<option value="Transfer" {{ (($journal->entry_type ?? '') === 'Transfer') ? 'selected' : '' }}>Transfer</option>
								<option value="Provision" {{ (($journal->entry_type ?? '') === 'Provision') ? 'selected' : '' }}>Provision</option>
								<option value="Third Party Settlement" {{ (($journal->entry_type ?? '') === 'Third Party Settlement') ? 'selected' : '' }}>Third Party Settlement</option>
							</select>
						</div>
						{{-- <div class="col-sm-6 mb-3">
							<label class="form-label">Voucher Type <span class="text-danger">*</span></label>
							<select class="form-select" name="source" id="source" >
								<option value="" {{ (($journal->source ?? '') === '') ? 'selected' : '' }}>Please Select</option>
								<option value="Sales" {{ (($journal->source ?? '') === 'Sales') ? 'selected' : '' }}>Sales</option>
								<option value="Purchase" {{ (($journal->source ?? '') === 'Purchase') ? 'selected' : '' }}>Purchase</option>
								<option value="Payment" {{ (($journal->source ?? '') === 'Payment') ? 'selected' : '' }}>Payment</option>
								<option value="Receipt" {{ (($journal->source ?? '') === 'Receipt') ? 'selected' : '' }}>Receipt</option>
								<option value="Contra" {{ (($journal->source ?? '') === 'Contra') ? 'selected' : '' }}>Contra</option>
								<option value="Journal" {{ (($journal->source ?? '') === 'Journal') ? 'selected' : '' }}>Journal</option>
							</select>
						</div> --}}

						<div class="col-sm-6 mb-3" id="settlementTypeDiv" style="{{ ($journal->entry_type == 'Third Party Settlement') ? '' : 'display:none;' }}">
							<label class="form-label">Settlement Type <span class="text-danger">*</span></label>
							<select class="form-select" name="settlement_type" id="settlement_type">
								<option value="" {{ (($journal->settlement_type ?? '') == '') ? 'selected' : '' }}>Please Select</option>
								<option value="Third Party Settlement" {{ (($journal->settlement_type ?? '') == 'Third Party Settlement') ? 'selected' : '' }}>Third Party Settlement</option>
								<option value="Advance Adjustment" {{ (($journal->settlement_type ?? '') == 'Advance Adjustment') ? 'selected' : '' }}>Advance Adjustment</option>
								<option value="Payable Adjustment" {{ (($journal->settlement_type ?? '') == 'Payable Adjustment') ? 'selected' : '' }}>Payable Adjustment</option>
								<option value="Receivable Adjustment" {{ (($journal->settlement_type ?? '') == 'Receivable Adjustment') ? 'selected' : '' }}>Receivable Adjustment</option>
							</select>
						</div>

						<div class="col-sm-6 mb-3" id="againstLedgerDiv" style="{{ ($journal->entry_type == 'Third Party Settlement') ? '' : 'display:none;' }}">
							<label class="form-label">Against Ledger <span class="text-danger">*</span></label>
							<input type="text"
								class="form-control"
								name="against_ledger"
								id="against_ledger"
								value="{{ old('against_ledger', $journal->against_ledger) }}">
						</div>

						<div class="col-sm-12 mb-3" id="narrationDiv" style="{{ !empty($journal->settlement_type) ? '' : 'display:none;' }}">
							<label class="form-label">Narration <span class="text-danger">*</span></label>
							<textarea class="form-control" name="narration" id="narration" rows="3">{{ old('narration', $journal->narration) }}</textarea>
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
							<input type="text" name="ledger" id="ledger" class="form-control" value="{{ $journal->ledger ?? '' }}" >
						</div>

						<div class="col-sm-4 mb-3">
							<label class="form-label">Related Party </label>
							<input type="text" name="party_name" id="party_name" class="form-control" value="{{ $journal->party_name ?? '' }}" >
						</div>

						<div class="col-sm-4 mb-3">
							<label class="form-label">Debit/Credit (Auto from Ledger) <span class="text-danger">*</span></label>							
                            <select id="debit_credit" class="form-select" name="debit_credit">
								<option value="Debit" <?= ($journal->debit_credit == 'Debit')?'selected':'' ?>>Debit</option>
								<option value="Credit" <?= ($journal->debit_credit == 'Credit')?'selected':'' ?>>Credit</option>
							</select>
						</div>

						<div class="col-sm-6 mb-3">
							<label class="form-label">Amount <span class="text-danger">*</span></label>
							<input type="number" min="0" step="0.01" name="amount" id="amount" class="form-control" value="{{ $journal->amount ?? '' }}" >
						</div>

						<div class="col-sm-6 mb-3">
							<label class="form-label">Narration / Notes <span class="text-danger">*</span></label>
							<textarea class="form-control"
									name="notes"
									id="notes"
									rows="3"
									placeholder="Enter notes">{{ old('notes', $journal->notes) }}</textarea>
						</div>

						<div class="col-sm-12 mb-3" id="otherNoteBox" style="display: {{ (($journal->notes ?? '') === 'Other') ? 'block' : 'none' }};">
							<label class="form-label">Other Note <span class="text-danger">*</span></label>
							<textarea name="other_note" id="other_note" class="form-control" rows="3" >{{ $journal->other_note ?? '' }}</textarea>
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
										<option value="{{ $purpose->tds_rate . '-' . $purpose->id }}"  {{ ($purpose->id == $journal->tds_id) ? 'selected' : '' }}>
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
									@php
										$gstApplicable = strtolower(trim($journal->gst_applicable ?? 'no'));
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
									<label for="hsn_sac_code">HSN/SAC Code</label>
									<input type="text" name="hsn_sac_code" id="hsn_sac_code" value="{{$journal->hsn_sac_code}}" class="form-control">
								</div>
								<div class="col-md-4">
									<label for="gst_rate">GST Rate (%)</label>
									<input type="number" name="gst_rate" id="gst_rate" value="{{$journal->gst_rate}}" class="form-control" min="0" step="0.01">
								</div>
								<div class="col-md-4">
									<label for="gst_trans">GST Transaction Mode</label>
									<select class="form-select" name="gst_trans" id="gst_trans">
										<option value="">Select</option>
										<option value="intrastate" <?= ($journal->gst_trans == 'intrastate')?'selected':'' ?>>Intra State</option>
										<option value="interstate" <?= ($journal->gst_trans == 'interstate')?'selected':'' ?>>Inter State</option>
										<option value="union" <?= ($journal->gst_trans == 'union')?'selected':'' ?>>Union Territory</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

        <!-- Existing Attachments -->
        <div class="card mt-3">
            <div class="card-body">
                <h5>Attachments</h5>

                <div class="row">
                    @foreach($journal->attachments as $file)
                        <div class="col-md-3 text-center mb-2">
                            <a href="{{ asset($file->file_path) }}" target="_blank">
								<img src="{{ asset($file->file_path) }}" width="100%" style="cursor:pointer;">
							</a>
                            <br>
                            <button type="button"
                                    class="btn btn-danger btn-sm delete-file"
                                    data-id="{{ $file->id }}">
                                Delete
                            </button>
                        </div>
                    @endforeach
                </div>
                <hr>
                <input type="file" name="attachments[]" multiple class="form-control">
            </div>
        </div>

        <div class="text-end mt-3">
            <button class="btn btn-success">Update</button>
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
		transition: all 0.2s ease;
	}
</style>
<script>
	$("#editJournalForm").submit(function(e){
		e.preventDefault();

		let formData = new FormData(this);
		let id = $("#journal_id").val();

		$.ajax({
			url: "/update-journal/" + id,
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

			success: function(res){
				if(res.status){
					showToast(res.message, 'success');
					window.location.href = "/journal-list";
				}
			}
		});
	});

	// Delete attachment
	$(".delete-file").click(function(){
		let id = $(this).data("id");

		if(confirm("Delete this file?")){
			$.ajax({
				url: "/delete-journal-file/" + id,
				type: "GET",
				success: function(){
					location.reload();
				}
			});
		}
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

	$(document).ready(function () {

		function toggleSettlementFields() {

			if ($('#entry_type').val() === 'Third Party Settlement') {

				$('#settlementTypeDiv').show();
				$('#againstLedgerDiv').show();
				$('#narrationDiv').show();

			} else {

				$('#settlementTypeDiv').hide();
				$('#settlement_type').val('');

				$('#againstLedgerDiv').hide();
				$('#against_ledger').val('');

				$('#narrationDiv').hide();
				$('#narration').val('');
			}
		}

		$('#entry_type').on('change', function () {
			toggleSettlementFields();
		});

		// Initial load
		toggleSettlementFields();

	});
</script>

@endsection