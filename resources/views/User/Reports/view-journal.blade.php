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
						<li class="breadcrumb-item active" aria-current="page">View Journal</li>
					</ul>
				</div>
				<div class="col-md-12">
					<div class="page-header-title">
						<h2 class="mb-0">View Journal</h2>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- [ breadcrumb ] end -->

	<form action="javascript:void(0);" method="post" enctype="multipart/form-data">
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
							<input type="text" name="journal_no" id="journal_no" class="form-control" value="{{ 'JV-' . $journal->journal_no ?? 'JV-00001' }}" readonly>
						</div>
						<div class="col-sm-3 mb-3">
							<label class="form-label">Date <span class="text-danger">*</span></label>
							<input type="date" name="journal_date" id="journal_date" class="form-control" value="{{ $journal->journal_date ?? '' }}" readonly>
						</div>
						<div class="col-sm-3 mb-3">
							<label class="form-label">Reference Type</label>
							<select class="form-select" name="reference_type" id="reference_type" disabled>
								<option value="" {{ (($journal->reference_type ?? '') === '') ? 'selected' : '' }}>Please Select</option>
								<option value="New Ref" {{ (($journal->reference_type ?? '') === 'New Ref') ? 'selected' : '' }}>New Reference</option>
								<option value="Against Ref" {{ (($journal->reference_type ?? '') === 'Against Ref') ? 'selected' : '' }}>Against Reference</option>
								<!--<option value="Advance" {{ (($journal->reference_type ?? '') === 'Advance') ? 'selected' : '' }}>Advance</option>-->
								<option value="On Account" {{ (($journal->reference_type ?? '') === 'On Account') ? 'selected' : '' }}>On Account</option>
							</select>
						</div>

						<div class="col-sm-3 mb-3">
							<label class="form-label">Reference No (Invoice/Bill)</label>
							<input type="text" name="reference_no" id="reference_no" class="form-control" value="{{ $journal->reference_no ?? '' }}" readonly>
						</div>

						<div class="col-sm-6 mb-3">
							<label class="form-label">Purpose of Entry <span class="text-danger">*</span></label>
							<select class="form-select" name="entry_type" id="entry_type" disabled>
								<option value="" {{ (($journal->entry_type ?? '') === '') ? 'selected' : '' }}>Please Select</option>
								<option value="Adjustment" {{ (($journal->entry_type ?? '') === 'Adjustment') ? 'selected' : '' }}>Adjustment</option>
								<!--<option value="Advance" {{ (($journal->entry_type ?? '') === 'Advance') ? 'selected' : '' }}>Advance</option>-->
								<option value="Accrual" {{ (($journal->entry_type ?? '') === 'Accrual') ? 'selected' : '' }}>Accrual</option>
								<option value="Depreciation" {{ (($journal->entry_type ?? '') === 'Depreciation') ? 'selected' : '' }}>Depreciation</option>
								<option value="Opening Balance" {{ (($journal->entry_type ?? '') === 'Opening Balance') ? 'selected' : '' }}>Opening Balance</option>
								<option value="Correction" {{ (($journal->entry_type ?? '') === 'Correction') ? 'selected' : '' }}>Correction</option>
								<option value="Transfer" {{ (($journal->entry_type ?? '') === 'Transfer') ? 'selected' : '' }}>Transfer</option>
								<option value="Provision" {{ (($journal->entry_type ?? '') === 'Provision') ? 'selected' : '' }}>Provision</option>
							</select>
						</div>
						<div class="col-sm-6 mb-3">
							<label class="form-label">Voucher Type <span class="text-danger">*</span></label>
							<select class="form-select" name="source" id="source" disabled>
								<option value="" {{ (($journal->source ?? '') === '') ? 'selected' : '' }}>Please Select</option>
								<option value="Sales" {{ (($journal->source ?? '') === 'Sales') ? 'selected' : '' }}>Sales</option>
								<option value="Purchase" {{ (($journal->source ?? '') === 'Purchase') ? 'selected' : '' }}>Purchase</option>
								<option value="Payment" {{ (($journal->source ?? '') === 'Payment') ? 'selected' : '' }}>Payment</option>
								<option value="Receipt" {{ (($journal->source ?? '') === 'Receipt') ? 'selected' : '' }}>Receipt</option>
								<option value="Contra" {{ (($journal->source ?? '') === 'Contra') ? 'selected' : '' }}>Contra</option>
								<option value="Journal" {{ (($journal->source ?? '') === 'Journal') ? 'selected' : '' }}>Journal</option>
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
							<input type="text" name="ledger" id="ledger" class="form-control" value="{{ $journal->ledger ?? '' }}" readonly>
						</div>

						<div class="col-sm-4 mb-3">
							<label class="form-label">Party Name <span class="text-danger">*</span></label>
							<input type="text" name="party_name" id="party_name" class="form-control" value="{{ $journal->party_name ?? '' }}" readonly>
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
							<input type="number" min="0" step="0.01" name="amount" id="amount" class="form-control" value="{{ $journal->amount ?? '' }}" readonly>
						</div>

						<div class="col-sm-6 mb-3">
							<label class="form-label">Notes <span class="text-danger">*</span></label>
							<select class="form-select" name="notes" id="notes" disabled>
								<option value="" {{ (($journal->notes ?? '') === '') ? 'selected' : '' }}>Please Select</option>
								<option value="Wrong entry correction" {{ (($journal->notes ?? '') === 'Wrong entry correction') ? 'selected' : '' }}>Wrong entry correction</option>
								<option value="Amount rectification" {{ (($journal->notes ?? '') === 'Amount rectification') ? 'selected' : '' }}>Amount rectification</option>
								<option value="Advance adjusted against invoice" {{ (($journal->notes ?? '') === 'Advance adjusted against invoice') ? 'selected' : '' }}>Advance adjusted against invoice</option>
								<option value="Outstanding expense" {{ (($journal->notes ?? '') === 'Outstanding expense') ? 'selected' : '' }}>Outstanding expense</option>
								<option value="Provision for audit fees" {{ (($journal->notes ?? '') === 'Provision for audit fees') ? 'selected' : '' }}>Provision for audit fees</option>
								<option value="Depreciation for this FY" {{ (($journal->notes ?? '') === 'Depreciation for this FY') ? 'selected' : '' }}>Depreciation for this FY</option>
								<option value="Opening balance" {{ (($journal->notes ?? '') === 'Opening balance') ? 'selected' : '' }}>Opening balance</option>
								<option value="Cash transferred to bank" {{ (($journal->notes ?? '') === 'Cash transferred to bank') ? 'selected' : '' }}>Cash transferred to bank</option>
								<option value="Inter-bank transfer" {{ (($journal->notes ?? '') === 'Inter-bank transfer') ? 'selected' : '' }}>Inter-bank transfer</option>
								<option value="Other" {{ (($journal->notes ?? '') === 'Other') ? 'selected' : '' }}>Other</option>
							</select>
						</div>

						<div class="col-sm-12 mb-3" id="otherNoteBox" style="display: {{ (($journal->notes ?? '') === 'Other') ? 'block' : 'none' }};">
							<label class="form-label">Other Note <span class="text-danger">*</span></label>
							<textarea name="other_note" id="other_note" class="form-control" rows="3" readonly>{{ $journal->other_note ?? '' }}</textarea>
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

		<div class="col-12 mb-3">
			<div class="card mt-3">
				<div class="card-body">
					<h5>Attachments</h5>

					<div class="row">
						@foreach($journal->attachments as $file)
							<div class="col-md-3 text-center mb-2">
								<a href="{{ asset($file->file_path) }}" target="_blank">
									<img src="{{ asset($file->file_path) }}" width="100%" style="cursor:pointer;">
								</a>
							</div>
						@endforeach
					</div>

				</div>
			</div>
		</div>

		<div class="col-12 text-end mb-3">
			<a href="{{ url('/journal-list') }}" class="btn btn-primary cancel me-2">Back</a>
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

@endsection
