@extends('App.Layout')

@section('container')

<div class="pc-content">

    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Secure Documents Locker</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Secure Documents Locker</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
		@if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
			<div class="card alert {{ $lockerSecured ? 'alert-success' : 'alert-danger' }} p-0">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class="flex-grow-1 me-3">

						<h3 class="alert-heading">
							{{ $lockerSecured ? 'Locker Secured!' : 'Security Alert!' }}
						</h3>

						<p class="mb-2">
							@if($lockerSecured)
								Your document locker is protected with a passcode. Your documents are secure.
							@else
								Your document locker is not secured yet. Please set a 6-digit passcode to protect your sensitive documents and maintain privacy.
							@endif
						</p>

						@if (Auth::user()->u_type == 2)
						<a href="#" class="alert-link" data-bs-toggle="modal" data-bs-target="#passcodeModal">
							<u>{{ $lockerSecured ? 'Reset Passcode' : 'Set a Passcode Now' }}</u>
							<i class="ti ti-arrow-up-right-circle"></i>
						</a>
						@endif
					</div>

					<div class="flex-shrink-0">
						<img src="../assets/images/application/img-accout-password-alert.png"
							alt="img" class="img-fluid wid-80">
					</div>

				</div>
			</div>
		</div>
		@endif

    <!-- Document Upload Section -->
    <div class="row">
        <!-- First Row - Document Upload Card -->
				@if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
						<div class="col-md-12">
					<form name="uploadDocumentForm" id="uploadDocumentForm" action="javascript:void(0);" method="post" enctype="multipart/form-data" >
					@csrf
						<div class="card">
							<div class="card-header">
								<h5>Upload New Document</h5>
							</div>
							<div class="card-body">
								<div class="row g-4">
									<div class="col-md-6">
										<!-- Document Type Dropdown -->
										<div class="mb-3">
											<label class="form-label">Document Type <span class="text-danger">*</span></label>
											<select class="form-select" id="document_type" name="document_type">
												<option value="">Select Document Type</option>
												<option value="Company & Ownership Documents">Company & Ownership Documents</option>
												<option value="Statutory & Compliance – ROC">Statutory & Compliance – ROC</option>
												<option value="Owner / Director KYC & Other">Owner / Director KYC & Other</option>
												<option value="Licensing & Registration">Licensing & Registration</option>
												<option value="Statutory & Compliance Documents - GST">Statutory & Compliance Documents - GST</option>
												<option value="Statutory & Compliance Documents - Income Tax">Statutory & Compliance Documents - Income Tax</option>
												<option value="Statutory & Compliance – PF-ESI & Labor Law">Statutory & Compliance – PF-ESI & Labor Law</option>
												<option value="Financial & Accounting Documents">Financial & Accounting Documents</option>
												<option value="Audit Reports">Audit Reports</option>
												<option value="Banking & Funding Documents">Banking & Funding Documents</option>
												<option value="Property & Asset Documents">Property & Asset Documents</option>
												<option value="Legal & Contractual Documents">Legal & Contractual Documents</option>
												<option value="Employee Master Records">Employee Master Records</option>
												<option value="HR & Employee Records">HR & Employee Records</option>
												<option value="CSR & Trust-Related Documents">CSR & Trust-Related Documents</option>
												<option value="Board & Management Records">Board & Management Records</option>
											</select>
											<div class="invalid-feedback" id="error-document_type"></div>
										</div>
										<div class="mb-3">
											<label class="form-label">File Name <span class="text-danger">*</span></label>
											<select name="file_type" id="file_type" class="form-select">
												<option value="">Select File Name</option>
											</select>
											<div class="invalid-feedback" id="error-file_type"></div>
										</div>
										<!-- Document Name Field -->
										<div class="mb-3">
											<label class="form-label">Document Name</label>
											<input type="text" class="form-control" id="document_name" name="document_name" placeholder="Enter document name">
											<div class="invalid-feedback" id="error-document_name"></div>
										</div>
									</div>
									<div class="col-md-6 d-flex flex-column justify-content-between">
										<div class="mb-3 flex-grow-1 d-flex flex-column">
											<label class="form-label">Upload Document <span class="text-danger">*</span></label>
											<label class="upload-area flex-grow-1 d-flex flex-column align-items-center justify-content-center" for="document_file" style="min-height: 180px;">
												<i class="ti ti-cloud-upload text-muted mb-2" id="locker_file_icon" style="font-size: 2.2rem;"></i>
												<span class="upload-text text-center px-3" id="locker_file_label" style="font-weight: 500;">
													Drag & Drop or Click to Upload File
												</span>
												<input type="file" class="d-none" id="document_file" name="document_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" onchange="updateLockerFileName(this)">
											</label>
											<small class="form-text text-muted text-center d-block mt-2">Accepted formats: PDF, JPG, PNG, DOC, DOCX (Max: 5MB)</small>
											<div class="invalid-feedback text-center d-block" id="error-document_file"></div>
										</div>
										<div class="mt-2">
											<button type="submit" class="btn btn-primary w-100 py-2.5">
												<i class="ti ti-upload me-1"></i> Upload Document
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
						</div>
				@endif
        <!-- Second Row - Documents List (Placeholder) -->
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h5>Uploaded Documents</h5>
                </div>
                <div class="card-body">
                   <div class="table-responsive">
                        <table class="table tbl-product my-3"  id="pc-dt-simple">
                            <thead>
                                <tr style="background-color: #cbcbcb;">
                                    <th>#</th>
                                    <th>Document Type</th>
                                    <th>File Type</th>
																		<th>Company type</th>
																		@if(in_array(Auth::user()->u_type, [2,5]))
                                    <th>Granted To</th>
																		@else
                                    <th>Granted By</th>
																		@endif
                                    <th>Permission</th>
                                    <th>Granted On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $key => $doc)
																<tr>
																	<td>{{ $key + 1 }}</td>

																	<td>
																		<span class="text-muted text-hover-primary">
																			{{ $doc->document_type }}
																		</span>
																	</td>

																	<td>
																		<span class="text-muted text-hover-primary">
																			{{ $doc->file_type }}
																		</span>
																	</td>
																	<td>
																		<span class="text-muted text-hover-primary">
																			{{ !empty($doc->comp_name) ? 'Proprietorship ('.$doc->comp_name.')' : '' }}
																		</span>
																	</td>
																	
																	<td>
																		{{ $doc->granted_to_name ?? 'Not Shared' }}
																	</td>

																	<td>
																		{{ $doc->doc_permission ?? '-' }}
																	</td>

																	<td>
																		<span class="text-muted text-hover-primary">
																			{{ $doc->granted_at ? date('d-m-Y', strtotime($doc->granted_at)) : '—' }}
																		</span>
																	</td>

																	<td>
																		<span><i class="ti ti-dots-vertical f-20"></i></span>
																		<div class="prod-action-links">
																			<ul class="list-inline me-auto mb-0">
																				<!-- View -->
																				@if(in_array(Auth::user()->u_type, [2,5]))
																				<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View Document">
																					<a href="javascript:void(0)"
																						class="avtar avtar-xs btn-link-success btn-pc-default verify-passcode"
																						data-action="view"
																						data-id="{{ $doc->id }}"
																						data-ext="{{ pathinfo($doc->file_name, PATHINFO_EXTENSION) }}"
																						data-path="{{ asset($doc->file_path) }}"
																						data-bs-toggle="modal"
																						data-bs-target="#verifypasscodeModal">
																						<i class="ti ti-eye f-18"></i>
																					</a>
																				</li>
																				@elseif(in_array(Auth::user()->u_type, [1,4]) && $doc->doc_permission === 'view')
																				<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View Document">
																					<a href="javascript:void(0)"
																						class="avtar avtar-xs btn-link-success btn-pc-default direct-view"
																						data-action="view"
																						data-id="{{ $doc->id }}"
																						data-ext="{{ pathinfo($doc->file_name, PATHINFO_EXTENSION) }}"
																						data-path="{{ asset($doc->file_path) }}">
																						<i class="ti ti-eye f-18"></i>
																					</a>
																				</li>
																				@endif

																				<!-- Download -->
																				@if(in_array(Auth::user()->u_type, [2,5]))
																				<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Download Document">
																					<a href="javascript:void(0)"
																						class="avtar avtar-xs btn-link-warning btn-pc-default verify-passcode"
																						data-action="download"
																						data-id="{{ $doc->id }}"
																						data-bs-toggle="modal"
																						data-bs-target="#verifypasscodeModal">
																						<i class="ti ti-file-download f-18"></i>
																					</a>
																				</li>
																				@elseif(in_array(Auth::user()->u_type, [1,4]) && $doc->doc_permission === 'download')
																				<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Download Document">
																					<a target="_blank" href='{{ url("/documents/$doc->id/download") }}'
																						class="avtar avtar-xs btn-link-warning btn-pc-default  direct-download"
																						data-action="download"
																						data-id="{{ $doc->id }}">
																						<i class="ti ti-file-download f-18"></i>
																					</a>
																				</li>
																				@endif

																				@if(in_array(Auth::user()->u_type, [2,5]))
																				<!-- Give Access -->
																				<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Give Access">
																					<a href="javascript:void(0)"
																						class="avtar avtar-xs btn-link-danger btn-pc-default verify-passcode"
																						data-action="share"
																						data-id="{{ $doc->id }}"
																						data-bs-toggle="modal"
																						data-bs-target="#verifypasscodeModal">
																						<i class="ti ti-user-plus f-18"></i>
																					</a>
																				</li>

																				<!-- Delete -->
																				<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
																					<a href="javascript:void(0)"
																						class="avtar avtar-xs btn-link-danger btn-pc-default verify-passcode"
																						data-action="delete"
																						data-id="{{ $doc->id }}"
																						data-bs-toggle="modal"
																						data-bs-target="#verifypasscodeModal">
																						<i class="ti ti-trash f-18"></i>
																					</a>
																				</li>
																				@endif

																			</ul>
																		</div>
																	</td>
																</tr>
																@empty
																<tr>
																	<td colspan="8" class="text-center text-muted">
																		No documents uploaded yet
																	</td>
																</tr>
																@endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Passcode Modal -->
<div class="modal custom-modal fade" id="passcodeModal" tabindex="-1" aria-labelledby="passcodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passcodeModalLabel"><i class="ti ti-lock"></i> {{ $lockerSecured ? 'Reset Passcode' : 'Set Passcode' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img src="../assets/images/application/img-accout-password-alert.png" alt="Security" class="img-fluid" style="max-width: 100px;">
                    <p class="text-muted mt-3">Create a secure 6-digit passcode to protect your documents</p>
                </div>
                
                <form id="passcodeForm">
                    <div class="mb-4">
                        <label class="form-label">Enter 6-Digit Passcode <span class="text-danger">*</span></label>
                        <div class="d-flex justify-content-between gap-2">
                            <input type="text" class="form-control text-center passcode-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="digit1" required>
                            <input type="text" class="form-control text-center passcode-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="digit2" required>
                            <input type="text" class="form-control text-center passcode-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="digit3" required>
                            <input type="text" class="form-control text-center passcode-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="digit4" required>
                            <input type="text" class="form-control text-center passcode-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="digit5" required>
                            <input type="text" class="form-control text-center passcode-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="digit6" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirm 6-Digit Passcode <span class="text-danger">*</span></label>
                        <div class="d-flex justify-content-between gap-2">
                            <input type="text" class="form-control text-center passcode-input-confirm" maxlength="1" pattern="[0-9]" inputmode="numeric" id="confirm_digit1" required>
                            <input type="text" class="form-control text-center passcode-input-confirm" maxlength="1" pattern="[0-9]" inputmode="numeric" id="confirm_digit2" required>
                            <input type="text" class="form-control text-center passcode-input-confirm" maxlength="1" pattern="[0-9]" inputmode="numeric" id="confirm_digit3" required>
                            <input type="text" class="form-control text-center passcode-input-confirm" maxlength="1" pattern="[0-9]" inputmode="numeric" id="confirm_digit4" required>
                            <input type="text" class="form-control text-center passcode-input-confirm" maxlength="1" pattern="[0-9]" inputmode="numeric" id="confirm_digit5" required>
                            <input type="text" class="form-control text-center passcode-input-confirm" maxlength="1" pattern="[0-9]" inputmode="numeric" id="confirm_digit6" required>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="ti ti-info-circle"></i> <strong>Note:</strong> Remember this passcode. You will need it to access your documents.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="setPasscodeBtn">Set Passcode</button>
            </div>
        </div>
    </div>
</div>

<!-- Verify Passcode Modal -->
<div class="modal custom-modal fade" id="verifypasscodeModal" tabindex="-1" aria-labelledby="passcodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img src="../assets/images/application/img-accout-password-alert.png" alt="Security" class="img-fluid" style="max-width: 100px;">
                    <p class="text-muted mt-3">Enter Your  6-digit passcode to perform your action</p>
                </div>
                
                <form id="passcodeForm2">
                    <div class="mb-4">
                        <label class="form-label">Enter 6-Digit Passcode <span class="text-danger">*</span></label>
                        <div class="d-flex justify-content-between gap-2">
                            <input type="password" class="form-control text-center passcode-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="digt1" required>
                            <input type="password" class="form-control text-center passcode-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="digt2" required>
                            <input type="password" class="form-control text-center passcode-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="digt3" required>
                            <input type="password" class="form-control text-center passcode-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="digt4" required>
                            <input type="password" class="form-control text-center passcode-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="digt5" required>
                            <input type="password" class="form-control text-center passcode-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="digt6" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="verifyPasscodeBtn">Verify</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Document</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="confirmDelete" class="w-100 btn btn-danger">
                                Delete
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-secondary paid-cancel-btn">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Document Modal -->
<div class="modal fade" id="viewDocumentModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">View Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0" style="height:80vh;">
                <iframe id="documentViewer" src="" style="width:100%; height:100%; border:0;">
                </iframe>
            </div>

        </div>
    </div>
</div>


<!-- Give CA Access Modal -->
<div class="modal fade" id="giveAccessModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Give Access to CA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="giveAccessForm">
                @csrf
                <input type="hidden" id="access_document_id">

                <div class="modal-body">

                    <!-- CA List -->
                    <div class="mb-3">
                        <label class="form-label">Select CA <span class="text-danger">*</span></label>
                        <select class="form-select" name="ca_id" id="ca_id">
                            <option value="">Select CA</option>
                        </select>
                        <div class="invalid-feedback" id="error-ca_id"></div>
                    </div>
					
					<div class="mb-3">
						<label class="form-label">Document Type <span class="text-danger">*</span></label>
						<select class="form-select" id="documentType" name="documentType">
							<option value="">Select Document Type</option>
							<option value="Company & Ownership Documents">Company & Ownership Documents</option>
							<option value="Statutory & Compliance – ROC">Statutory & Compliance – ROC</option>
							<option value="Owner / Director KYC & Other">Owner / Director KYC & Other</option>
							<option value="Licensing & Registration">Licensing & Registration</option>
							<option value="Statutory & Compliance Documents - GST">Statutory & Compliance Documents - GST</option>
							<option value="Statutory & Compliance Documents - Income Tax">Statutory & Compliance Documents - Income Tax</option>
							<option value="Statutory & Compliance – PF-ESI & Labor Law">Statutory & Compliance – PF-ESI & Labor Law</option>
							<option value="Financial & Accounting Documents">Financial & Accounting Documents</option>
							<option value="Audit Reports">Audit Reports</option>
							<option value="Banking & Funding Documents">Banking & Funding Documents</option>
							<option value="Property & Asset Documents">Property & Asset Documents</option>
							<option value="Legal & Contractual Documents">Legal & Contractual Documents</option>
							<option value="Employee Master Records">Employee Master Records</option>
							<option value="HR & Employee Records">HR & Employee Records</option>
							<option value="CSR & Trust-Related Documents">CSR & Trust-Related Documents</option>
							<option value="Board & Management Records">Board & Management Records</option>
						</select>
						<div class="invalid-feedback" id="error-documentType"></div>
					</div>

                    <!-- Permission -->
                    <div class="mb-3">
                        <label class="form-label">Permission <span class="text-danger">*</span></label>
                        <select class="form-select" id="doc_permission">
                            <option value="">Select Permission</option>
                            <option value="view">View</option>
                            <option value="download">Download</option>
                        </select>
                        <div class="invalid-feedback" id="error-doc_permission"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Give Access
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>





<script>
	// Toast Notification Function
	function showToast(message, type = 'success') {
		Toastify({
			text: message,
			duration: 3000,
			close: true,
			gravity: "top",
			position: "right",
			style: {
				fontSize: "16px",
				padding: "12px 24px",
				borderRadius: "8px",
				background: type === "success" ? "#10b981" : "#ef4444",
				color: "#fff",
				boxShadow: "0px 4px 12px rgba(0, 0, 0, 0.15)",
			},
		}).showToast();
	}

	function updateLockerFileName(input) {
		const label = document.getElementById('locker_file_label');
		const icon = document.getElementById('locker_file_icon');
		if (input.files && input.files[0]) {
			label.innerHTML = `<strong>Selected:</strong> ${input.files[0].name}`;
			icon.className = "ti ti-circle-check text-success";
			icon.style.fontSize = "2.2rem";
			$('#uploadDocumentForm .upload-area').removeClass('is-invalid');
		} else {
			label.innerText = "Drag & Drop or Click to Upload File";
			icon.className = "ti ti-cloud-upload text-muted";
			icon.style.fontSize = "2.2rem";
		}
	}

	let currentAction = null;
	let currentDocumentId = null;
	let fileExtension = null;
	let fileUrl = null;
	
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	
	$('#viewDocumentModal').on('hidden.bs.modal', function () {
		$('#documentViewer').attr('src', '');
	});

	
	// Wait for modal to be shown, then setup inputs
	$('#passcodeModal').on('shown.bs.modal', function () {
		// Focus on first input when modal opens
		$('#digit1').focus();
	});

	// Setup auto-focus for passcode inputs
	$(document).on('input', '.passcode-input, .passcode-input-confirm', function(e) {
		let $this = $(this);
		
		// Only allow numbers
		let value = $this.val().replace(/[^0-9]/g, '');
		$this.val(value);
		
		// If value entered, move to next input
		if (value.length >= 1) {
			// Get all inputs in the same group
			let $inputs = $this.closest('.d-flex').find('input');
			let currentIndex = $inputs.index($this);
			
			// Move to next input if available
			if (currentIndex < $inputs.length - 1) {
				$inputs.eq(currentIndex + 1).focus().select();
			}
		}
	});

	// Handle backspace
	$(document).on('keydown', '.passcode-input, .passcode-input-confirm', function(e) {
		let $this = $(this);
		
		if (e.key === 'Backspace' || e.keyCode === 8) {
			if ($this.val().length === 0) {
				// Get all inputs in the same group
				let $inputs = $this.closest('.d-flex').find('input');
				let currentIndex = $inputs.index($this);
				
				// Move to previous input if available
				if (currentIndex > 0) {
					$inputs.eq(currentIndex - 1).focus().select();
				}
			}
		}
	});

	// Handle paste
	$(document).on('paste', '.passcode-input, .passcode-input-confirm', function(e) {
		e.preventDefault();
		let $this = $(this);
		let pasteData = e.originalEvent.clipboardData.getData('text');
		let digits = pasteData.replace(/[^0-9]/g, '').split('');
		
		// Get all inputs in the same group
		let $inputs = $this.closest('.d-flex').find('input');
		let currentIndex = $inputs.index($this);
		
		// Fill inputs with pasted digits
		digits.forEach(function(digit, i) {
			if (currentIndex + i < $inputs.length) {
				$inputs.eq(currentIndex + i).val(digit);
			}
		});
		
		// Focus on next empty or last input
		let nextIndex = Math.min(currentIndex + digits.length, $inputs.length - 1);
		$inputs.eq(nextIndex).focus().select();
	});

	// Set Passcode button click
	$(document).on('click', '#setPasscodeBtn', function() {
        let passcode = '';
        let confirmPasscode = '';
        
        // Get passcode
        for(let i = 1; i <= 6; i++) {
            let digit = $('#digit' + i).val();
            if(!digit) {
                showToast('Please enter all 6 digits for passcode', 'error');
                $('#digit' + i).focus();
                return;
            }
            passcode += digit;
        }
        
        // Get confirm passcode
        for(let i = 1; i <= 6; i++) {
            let digit = $('#confirm_digit' + i).val();
            if(!digit) {
                showToast('Please enter all 6 digits for confirm passcode', 'error');
                $('#confirm_digit' + i).focus();
                return;
            }
            confirmPasscode += digit;
        }
        
        // Check if passcodes match
        if(passcode !== confirmPasscode) {
            showToast('Passcodes do not match! Please try again.', 'error');
            // Clear confirm passcode fields
            for(let i = 1; i <= 6; i++) {
                $('#confirm_digit' + i).val('');
            }
            $('#confirm_digit1').focus();
            return;
        }
        
        // Success - Here you can add AJAX call to save passcode
		$.ajax({
			url: '/document-locker/set-passcode',
			type: 'POST',
			data: { passcode },
			success: function () {
				showToast('Passcode set successfully', 'success');
				$('#passcodeModal').modal('hide');
				$('.passcode-input, .passcode-input-confirm').val('');
				setTimeout(() => { location.reload(); }, 1000);
			},
			error: function () {
				showToast('Failed to set passcode', 'error');
			}
		});
    });

    // Clear fields when modal is closed
    $('#passcodeModal').on('hidden.bs.modal', function () {
        $('.passcode-input, .passcode-input-confirm').val('');
    });
	
	//start upload document
	$('form#uploadDocumentForm').on('submit', function (e) {
		e.preventDefault();
		let formData = new FormData(this);
		$.ajax({
			url: "{{ route('documents.upload') }}",
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,
			cache: false,

			beforeSend: function () {
				$('form#uploadDocumentForm button[type=submit]')
					.prop('disabled', true)
					.text('Uploading...');
			},

			success: function (res) {
				showToast(res.message ?? 'Document uploaded successfully', 'success');
				$('form#uploadDocumentForm')[0].reset();
				setTimeout(() => { location.reload(); }, 1000);
			},

			error: function (xhr) {
				if (xhr.status === 422) {
					let errors = xhr.responseJSON.errors;

					// Clear previous errors
					$('.is-invalid').removeClass('is-invalid');
					$('.invalid-feedback').text('');

					$.each(errors, function (field, messages) {
						let input = $('#' + field);
						input.addClass('is-invalid');
						if (field === 'document_file') {
							$('#uploadDocumentForm .upload-area').addClass('is-invalid');
						}
						$('#error-' + field).text(messages[0]);
					});
				} else {
					showToast('Upload failed. Please try again.', 'error');
				}
			},

			complete: function () {
				$('#uploadDocumentForm button[type=submit]')
					.prop('disabled', false)
					.html('<i class="ti ti-upload"></i> Upload Document');
			}
		});
	});

	
	$(document).on('click', '.verify-passcode', function () {
		currentAction = $(this).data('action');
		currentDocumentId = $(this).data('id');
		fileExtension = $(this).data('ext').toLowerCase();
		fileUrl = $(this).data('path');

		$('#verifypasscodeModal input').val('');
	});
	//verify passcode
	$('#verifyPasscodeBtn').on('click', function () {

		let passcode = '';
		for (let i = 1; i <= 6; i++) {
			passcode += $('#digt' + i).val();
		}
		if (passcode.length !== 6) {
			showToast('Enter 6-digit passcode', 'error');
			return;
		}
		$("#loader").show();
		$.ajax({
			url: '/document-locker/verify-passcode',
			type: 'POST',
			data: { passcode },
			success: function () {
				$("#loader").hide();
				$('#verifypasscodeModal').modal('hide');

				if (currentAction === 'view' || currentAction === 'print') {			
					let ts = new Date().getTime();
					let url = fileUrl + '?v=' + ts;
					if (fileExtension === 'pdf') {
						url += '#toolbar=0&navpanes=0&scrollbar=0';
					}
					if (fileExtension === 'doc' || fileExtension === 'docx') {
						url = 'https://view.officeapps.live.com/op/embed.aspx?src=' +
							  encodeURIComponent(url);
					}
					$('#documentViewer').attr('src', url);
					$('#viewDocumentModal').modal('show');
				}
				
				if (currentAction === 'share') {
					$('#access_document_id').val(currentDocumentId);
					loadCAList();
					$('#giveAccessModal').modal('show');
				}

				if (currentAction === 'download') {
					window.location.href = `/documents/${currentDocumentId}/download`;
				}

				if (currentAction === 'delete') {
					$('#delete_modal').modal('show');
				}
			},
			error: function () {
				$("#loader").hide();
				showToast('Invalid passcode', 'error');
			}
		});
	});

	//delete document
	$('#confirmDelete').on('click', function () {
		$.ajax({
			url: `/documents/${currentDocumentId}`,
			type: 'DELETE',
			success: function () {
				showToast('Document deleted', 'success');
				$('#delete_modal').modal('hide');
				setTimeout(() => { location.reload(); }, 1000);
			},
			error: function () {
				showToast('Delete failed', 'error');
			}
		});
	});
	
	function loadCAList() {
		$.get('/cas/list', function (res) {
			let options = '<option value="">Select CA</option>';
			res.forEach(ca => {
				options += `<option value="${ca.id}">${ca.name} (${ca.email})</option>`;
			});
			$('#ca_id').html(options);
		});
	}
	
	$('#giveAccessForm').on('submit', function (e) {
		e.preventDefault();

		let data = {
			document_id: $('#access_document_id').val(),
			ca_id: $('#ca_id').val(),
			documentType: $('#documentType').val(),
			doc_permission: $('#doc_permission').val(),
		};

		$.ajax({
			url: '/documents/give-access',
			type: 'POST',
			data: data,

			success: function () {
				showToast('Access granted successfully', 'success');
				$('#giveAccessModal').modal('hide');
				setTimeout(() => { location.reload(); }, 1000);
			},

			error: function (xhr) {
				if (xhr.status === 422) {
					let errors = xhr.responseJSON.errors;
					$('.is-invalid').removeClass('is-invalid');
					$('.invalid-feedback').text('');

					$.each(errors, function (field, msg) {
						$('#' + field).addClass('is-invalid');
						$('#error-' + field).text(msg[0]);
					});
				}
			}
		});
	});


	$(document).on('click', '.direct-view', function () {
		let ext = $(this).data('ext').toLowerCase();
		let path = $(this).data('path'); 

		let absoluteUrl = window.location.origin + path;
		let viewUrl = absoluteUrl;

		if (ext === 'pdf') {
			viewUrl += '#toolbar=0&navpanes=0&scrollbar=0';
		}

		if (ext === 'doc' || ext === 'docx') {
			viewUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' +
					  encodeURIComponent(absoluteUrl);
		}

		$('#documentViewer').attr('src', viewUrl);
		$('#viewDocumentModal').modal('show');
	});

	$(document).on('click', '.direct-download', function () {
		let id = $(this).data('id');
		window.location.href = `/documents/${id}/download`;
	});


	const fileTypes = {
		"Company & Ownership Documents": [
			"Certificate of Incorporation",
			"CIN / LLPIN / Firm Registration Certificate",
			"Memorandum of Association (MOA)",
			"Articles of Association (AOA)",
			"LLP Agreement",
			"Partnership Deed",
			"Proprietor Declaration",
			"Shareholders Agreement",
			"Joint Venture Agreement",
			"MCA Documents",
			"Founding Documents",
			"Amendment Documents",
			"Other"
		],

		"Statutory & Compliance – ROC": [
			"Annual Return (MGT-7 / MGT-7A)",
			"Financial Statements (AOC-4)",
			"DIR-3 KYC",
			"PAS-3 / SH-7 / INC-22",
			"Board Resolution filings",
			"Other"
		],

		"Owner / Director KYC & Other": [
			"PAN Card (Individual)",
			"Voter ID Card",
			"Aadhar Card",
			"Passport",
			"Individual PAN Card",
			"Latest Photo",
			"Latest Photograph",
			"Digital Signature Certificate (DSC)",
			"Other"
		],

		"Licensing & Registration": [
			"Company PAN Card",
			"TAN Certificate",
			"GST Registration Certificate",
			"Shops & Establishment Registration",
			"Udyam Registration (MSME)",
			"PF Establishment Code Letter",
			"ESIC Code Letter",
			"Trade License",
			"Fire License",
			"Pollution Control Consent",
			"Factory License",
			"IT/ITES Permission",
			"SEZ / STPI Approval",
			"State Registration Documents",
			"Central Registration Documents",
			"Other"
		],

		"Statutory & Compliance Documents - GST": [
			"GST Returns (GSTR-1, GSTR-3B, GSTR-9)",
			"GST Audit Report",
			"GST LUT",
			"GST Notices & Replies",
			"Other"
		],

		"Statutory & Compliance Documents - Income Tax": [
			"ITR Acknowledgement",
			"Tax Audit Report (Form 3CA/3CB, 3CD)",
			"TDS Returns (24Q, 26Q)",
			"Income Tax Notices & Orders",
			"Other"
		],

		"Statutory & Compliance – PF-ESI & Labor Law": [
			"PF Returns & Challans",
			"ESIC Returns",
			"Professional Tax Returns",
			"Labour License Renewals",
			"Other"
		],

		"Financial & Accounting Documents": [
			"General Ledger",
			"Trial Balance",
			"Cash Book",
			"Bank Book",
			"Balance Sheet",
			"Profit & Loss Account",
			"Cash Flow Statement",
			"Notes to Accounts",
			"Other"
		],

		"Audit Reports": [
			"Statutory Audit Report",
			"Internal Audit Report",
			"CARO Report",
			"Other"
		],

		"Banking & Funding Documents": [
			"Bank Statements",
			"Bank Account Opening Forms",
			"Sanction Letters",
			"Loan Agreements",
			"EMI Schedules",
			"Security / Hypothecation Documents",
			"CSR Funding Agreements",
			"Other"
		],

		"Property & Asset Documents": [
			"Sale Deed / Lease Deed",
			"Property Tax Receipt",
			"Mutation Certificate",
			"Fire NOC",
			"Occupancy Certificate",
			"HIDCO / WBIDC Allotment Letters",
			"Asset Purchase Invoice",
			"Warranty Documents",
			"AMC Contracts",
			"Other"
		],

		"Legal & Contractual Documents": [
			"Office Lease/Rent Agreement",
			"Office Rent Invoice",
			"Office Latest Property Tax",
			"Office Signed NOC",
			"Client Agreements",
			"Vendor Agreements",
			"Service Contracts",
			"NDA / Confidentiality Agreements",
			"Arbitration Documents",
			"Court Notices & Replies",
			"Other"
		],

		"Employee Master Records": [
			"Employee CV",
			"Appointment Letters",
			"Employee ID Proofs",
			"Employee Address Proof",
			"Educational Certificates",
			"Educational Proof",
			"Experience Certificate",
			"Latest Photograph",
			"Other"
		],

		"HR & Employee Records": [
			"Salary Slips",
			"Form 16",
			"Bonus / Incentive Letters",
			"PF Nomination",
			"ESIC Declarations",
			"Resignation Letter",
			"Termination Letter",
			"Show Cause Letter",
			"Other"
		],

		"CSR & Trust-Related Documents": [
			"CSR Policy",
			"12A / 80G Certificate",
			"Utilization Certificate",
			"Donor Agreements",
			"CSR Impact Reports",
			"Other"
		],

		"Board & Management Records": [
			"Board Resolutions (Signed)",
			"Minutes of Meeting",
			"Attendance Registers",
			"Powers of Attorney",
			"Other"
		]
	};

	document.getElementById('document_type').addEventListener('change', function () {
		let type = this.value;
		let fileTypeSelect = document.getElementById('file_type');

		fileTypeSelect.innerHTML = '<option value="">Select File Name</option>';

		if (fileTypes[type]) {
			fileTypes[type].forEach(function (item) {
				let option = document.createElement('option');
				option.value = item;
				option.text = item;
				fileTypeSelect.appendChild(option);
			});
		}
	});


</script>

<style>
  /* 💎 Premium Secure Document Locker Style Overrides 💎 */

  /* Alert Cards - Modern warning and success styles with left borders */
  .alert-success.card {
    background-color: #ecfdf5 !important; /* Emerald soft bg */
    border: 1px solid #a7f3d0 !important; /* Emerald outline */
    border-left: 6px solid #10b981 !important; /* Emerald accent bar */
    border-radius: 12px !important;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.04) !important;
    overflow: hidden !important;
  }
  .alert-success .alert-heading {
    color: #065f46 !important;
    font-weight: 700 !important;
    font-size: 1.1rem !important;
  }
  .alert-success p {
    color: #047857 !important;
    font-weight: 500 !important;
    font-size: 0.9rem !important;
  }
  .alert-success .alert-link {
    color: #065f46 !important;
    font-weight: 600 !important;
  }

  .alert-danger.card {
    background-color: #fef2f2 !important; /* Rose soft bg */
    border: 1px solid #fecaca !important; /* Rose outline */
    border-left: 6px solid #ef4444 !important; /* Rose accent bar */
    border-radius: 12px !important;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.04) !important;
    overflow: hidden !important;
  }
  .alert-danger .alert-heading {
    color: #991b1b !important;
    font-weight: 700 !important;
    font-size: 1.1rem !important;
  }
  .alert-danger p {
    color: #b91c1c !important;
    font-weight: 500 !important;
    font-size: 0.9rem !important;
  }
  .alert-danger .alert-link {
    color: #991b1b !important;
    font-weight: 600 !important;
  }

  /* Form controls styling */
  .form-control, .form-select {
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 10px 14px !important;
    font-size: 0.9rem !important;
    transition: all 0.2s ease !important;
  }

  .form-control:focus, .form-select:focus {
    border-color: #422f90 !important;
    box-shadow: 0 0 0 3px rgba(66, 47, 144, 0.15) !important;
    outline: none !important;
  }

  /* Upload Dropzone styling */
  .upload-area {
    border: 2px dashed #cbd5e1 !important;
    padding: 30px 20px !important;
    border-radius: 12px !important;
    color: #5e6e82 !important;
    background-color: #f8fafc !important;
    transition: all 0.2s ease-in-out !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    width: 100% !important;
    max-width: 100% !important;
    cursor: pointer !important;
  }
  .upload-area:hover {
    border-color: #422f90 !important;
    background-color: #f5f4fa !important;
    color: #422f90 !important;
  }
  .upload-area.is-invalid {
    border-color: #ef4444 !important;
    background-color: #fef2f2 !important;
    color: #ef4444 !important;
  }

  /* Standardizing primary buttons to match brand styling */
  .btn-primary {
    background-color: #422f90 !important;
    border-color: #422f90 !important;
    border-radius: 8px !important;
    padding: 10px 20px !important;
    font-weight: 600 !important;
    transition: all 0.2s ease !important;
    color: #ffffff !important;
  }
  .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
    background-color: #2d1f6a !important;
    border-color: #2d1f6a !important;
    color: #ffffff !important;
  }

  /* Passcode Inputs in Modals */
  .passcode-input,
  .passcode-input-confirm {
    font-size: 24px !important;
    font-weight: 700 !important;
    height: 50px !important;
    width: 50px !important;
    padding: 0 !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    text-align: center !important;
    transition: all 0.2s ease !important;
  }
  .passcode-input:focus,
  .passcode-input-confirm:focus {
    border-color: #422f90 !important;
    box-shadow: 0 0 0 3px rgba(66, 47, 144, 0.15) !important;
    outline: none !important;
  }


</style>

@endsection