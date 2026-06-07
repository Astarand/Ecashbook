@extends('App.Layout')

@section('container')

    <div class="pc-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">GST Management</a></li>
                        <li class="breadcrumb-item" aria-current="page">GST Compliance Support List</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">GST Compliance Support List</h2>
                    </div>
                </div>
				@if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
                <div class="col-md-8 text-end">
                    <a href="#supportTicketModal" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#supportTicketModal"><i class="ti ti-square-plus"></i> Generate New Support Ticket</a>
                </div>
				@endif
            </div>
        </div>
    </div>
	
	@php
		$queryTypeLabels = [
			'gst_registration_compliance' => 'GST Registration Compliance',
			'gst_monthly_return'          => 'GST Monthly/Quarterly Return Filing',
			'gst_annual_compliance'       => 'GST Annual Compliance',
			'itc_compliance'              => 'Input Tax Credit (ITC) Compliance',
			'e_invoicing'                 => 'E-Invoicing (Turnover-based)',
			'e_way_bill'                  => 'E-Way Bill Compliance',
			'rcm'                         => 'Reverse Charge Mechanism (RCM)',
			'place_of_supply'             => 'Place of Supply & HSN/SAC Code Compliance',
			'tds_tcs'                     => 'TDS/TCS under GST',
			'ledger_reconciliation'       => 'GST Ledger & Reconciliation',
			'notice_management'           => 'GST Notice Management & Reply',
			'invoicing_compliance'        => 'GST Invoicing Compliance',
			'record_keeping'              => 'GST Record Keeping (6 Years Minimum)',
			'other'                       => 'Other',
		];
	@endphp


    <div class="row">
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
                                <th>Date</th>
                                <th>Queries About</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
						
							@forelse($tickets as $key => $ticket)
								<tr>
									<td class="text-end">{{ $key + 1 }}</td>

									<td>
										<span class="d-block fw-semibold text-dark">
											{{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y') }}
										</span>
										<span class="text-muted f-12">
											Ticket ID: {{ $ticket->ticket_no }}
										</span>
									</td>

									<td>
										<span class="text-muted text-hover-primary">
											{{ $queryTypeLabels[$ticket->query_type] ?? ucfirst($ticket->query_type) }}
										</span>

										@if($ticket->query_type === 'other' && $ticket->other_query)
											<br>
											<small class="text-muted">({{ $ticket->other_query }})</small>
										@endif
									</td>

									<td>
										@if($ticket->status === 'open')
											<span class="badge bg-warning text-dark">Open</span>
										@elseif($ticket->status === 'resolved')
											<span class="badge bg-success">Resolved</span>
										@else
											<span class="badge bg-secondary">Closed</span>
										@endif
									</td>

									<td>
										<span><i class="ti ti-dots-vertical f-20"></i></span>

										<div class="prod-action-links">
											<ul class="list-inline me-auto mb-0">
												<li class="list-inline-item" data-bs-toggle="tooltip" title="View Chat">
													<a href="#ticketChatModal"
													   class="avtar avtar-xs btn-link-warning btn-pc-default"
													   data-bs-toggle="modal"
													   data-bs-target="#ticketChatModal"
													   onclick="openChat({{ $ticket->id }},'{{ $ticket->status }}')">
														<i class="ti ti-message-circle f-18"></i>
													</a>
												</li>
												
												@if($ticket->status === 'open')
													<li>
														<a class="dropdown-item text-success"
														   href="javascript:void(0)"
														   onclick="resolveTicket({{ $ticket->id }})">
															<i class="ti ti-check me-2"></i> Resolve Ticket
														</a>
													</li>
												@endif

												@if(in_array($ticket->status, ['open','resolved']))
													<li>
														<a class="dropdown-item text-danger"
														   href="javascript:void(0)"
														   onclick="closeTicket({{ $ticket->id }})">
															<i class="ti ti-x me-2"></i> Close Ticket
														</a>
													</li>
												@endif
											</ul>
										</div>
									</td>
								</tr>
								@empty
								<tr>
									<td colspan="5" class="text-center text-muted">
										No support tickets found
									</td>
								</tr>
								@endforelse                           
                        </tbody>
                    </table>
					<div class="d-flex justify-content-end mt-3">
						{{ $tickets->links('pagination::bootstrap-5') }}
					</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ticketChatModal" tabindex="-1" aria-labelledby="ticketChatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
		
        <div class="modal-content">
			<form id="chatForm" enctype="multipart/form-data">
				<div class="modal-header">
					<h5 class="modal-title" id="ticketChatModalLabel">Support Ticket Conversation</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-0">
					<div class="chat-wrapper">
						<div class="chat-content">
							<div class="d-flex align-items-center px-3 py-3 border-bottom">
								<div class="flex-shrink-0">
									<div class="chat-avtar">
										<img class="rounded-circle img-fluid wid-40" src="../assets/images/user/avatar-5.jpg" alt="User image">
										<i class="chat-badge bg-success"></i>
									</div>
								</div>
								<div class="flex-grow-1 ms-3">
									<h6 class="mb-0">GST Compliance Desk</h6>
									<span class="text-muted">Active now</span>
								</div>
							</div>
							<div class="card bg-body shadow-none mb-0">
								<div class="scroll-block chat-message" style="max-height: 420px;">
									<div class="card-body">
										
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="modal-footer">
					<div class="input-group">						
						<input type="text" class="form-control" placeholder="Type your reply..." id="chatReplyInput">						
						<button class="btn btn-outline-secondary" type="button" title="Attach file" onclick="document.getElementById('chatAttachment').click()">
							<i class="ti ti-paperclip"></i>
						</button>
						<input type="file" id="chatAttachment" name="chatAttachment" class="d-none" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
						<button class="btn btn-primary" type="submit"><i class="ti ti-send"></i></button>
					</div>
					<div id="chatError" class="text-danger small mb-1 d-none"></div>
					<!-- Preview Area -->
					<div id="attachmentPreview" class="mt-2 text-muted"></div>
				</div>
				
			</form>
        </div>
		
    </div>
</div>

<div class="modal fade" id="supportTicketModal" tabindex="-1" aria-labelledby="supportTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
		<form id="supportTicketForm" name="supportTicketForm" action="javascript:void(0)" method="post" enctype="multipart/form-data">
			@csrf
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="supportTicketModalLabel">Generate New Support Ticket</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					
						<div class="row g-3">
							<div class="col-md-6">
								<label for="created_at" class="form-label">Date</label>
								<input type="date" class="form-control" id="created_at" name="created_at">
								<small class="text-danger error-text created_at_error"></small>
							</div>
							<div class="col-md-6">
								<label for="query_type" class="form-label">Queries About</label>
								<select class="form-select" id="query_type" name="query_type">
									<option value="">Select a query</option>
									<option value="gst_registration_compliance">GST Registration Compliance</option>
									<option value="gst_monthly_return">GST Monthly/Quarterly Return Filing</option>
									<option value="gst_annual_compliance">GST Annual Compliance</option>
									<option value="itc_compliance">Input Tax Credit (ITC) Compliance</option>
									<option value="e_invoicing">E-Invoicing (Turnover-based)</option>
									<option value="e_way_bill">E-Way Bill Compliance</option>
									<option value="rcm">Reverse Charge Mechanism (RCM)</option>
									<option value="place_of_supply">Place of Supply &amp; HSN/SAC Code Compliance</option>
									<option value="tds_tcs">TDS/TCS under GST</option>
									<option value="ledger_reconciliation">GST Ledger &amp; Reconciliation</option>
									<option value="notice_management">GST Notice Management &amp; Reply</option>
									<option value="invoicing_compliance">GST Invoicing Compliance</option>
									<option value="record_keeping">GST Record Keeping (6 Years Minimum)</option>
									<option value="other">Other</option>
								</select>
								<small class="text-danger error-text query_type_error"></small>
							</div>
							<div class="col-12 d-none" id="otherQueryWrapper">
								<label for="other_query" class="form-label">Please specify the query</label>
								<input type="text" class="form-control" id="other_query" name="other_query" placeholder="Describe your query">
								<small class="text-danger error-text other_query_error"></small>
							</div>
							<div class="col-12">
								<label for="message" class="form-label">Message</label>
								<textarea class="form-control" id="message" name="message" rows="4" placeholder="Share the details of your request"></textarea>
								<small class="text-danger error-text message_error"></small>
							</div>
							<div class="col-12">
								<label for="attachment" class="form-label">Attachment (Optional)</label>
								<input type="file" class="form-control" id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
								<small class="text-muted">Accepted formats: PDF, JPG, PNG, DOC, DOCX (Max 5MB)</small>
								<small class="text-danger error-text attachment_error"></small>								
							</div>
						</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary" id="submitTicketBtn">Submit Ticket</button>
				</div>
			</div>
		</form>
    </div>
</div>

<!-- Open Ticket Warning Modal -->
<div class="modal fade" id="openTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-warning">
                    <i class="ti ti-alert-circle me-2"></i> Action Not Allowed
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="mb-0">
                    You already have an <strong>open support ticket</strong>.
                    Please wait until it is closed before creating a new one.
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>


<style>
.chat-message .message-out p{
	color:#000;
}


</style>


<script>
	function showOpenTicketModal() {
        const modal = new bootstrap.Modal(
            document.getElementById('openTicketModal')
        );
        modal.show();
    }


	//create ticket
	document.addEventListener('DOMContentLoaded', function () {

		const form = document.getElementById('supportTicketForm');
		const submitBtn = document.getElementById('submitTicketBtn');

		form.addEventListener('submit', function (e) {
			e.preventDefault();

			clearErrors();

			submitBtn.disabled = true;
			submitBtn.innerText = 'Submitting...';

			let formData = new FormData(form);

			$("#loader").show();
			fetch("{{ url('/support-ticket/store') }}", {
				method: "POST",
				headers: {
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
					'Accept': 'application/json'
				},
				body: formData
			})
			.then(async response => {
				$("#loader").hide();
				submitBtn.disabled = false;
				submitBtn.innerText = 'Submit Ticket';

				if (response.status === 422) {
					const data = await response.json();
					showErrors(data.errors);
					return;
				}

				const data = await response.json();

				if (data.status) {
					alert(data.message);

					form.reset();
					document.getElementById('otherQueryWrapper').classList.add('d-none');

					bootstrap.Modal.getInstance(
						document.getElementById('supportTicketModal')
					).hide();

					location.reload();
				}
			})
			.catch(err => {
				$("#loader").hide();

				submitBtn.disabled = false;
				submitBtn.innerText = 'Submit Ticket';
				showOpenTicketModal();
				console.error(err);
			});
		});

		function showErrors(errors) {
			Object.keys(errors).forEach(function (key) {
				const errorEl = document.querySelector(`.${key}_error`);
				const inputEl = document.querySelector(`[name="${key}"]`);

				if (errorEl) errorEl.innerText = errors[key][0];
				if (inputEl) inputEl.classList.add('is-invalid');
			});
		}

		function clearErrors() {
			document.querySelectorAll('.error-text').forEach(el => el.innerText = '');
			document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
		}

		document.getElementById('query_type').addEventListener('change', function () {
			document
				.getElementById('otherQueryWrapper')
				.classList.toggle('d-none', this.value !== 'other');
		});

	});
	
	let currentTicket = null;
	let ticketStatus = null;

	function openChat(ticketId, status) 
	{
		currentTicket = ticketId;
		ticketStatus = status;
		
		const chatInput = document.getElementById('chatReplyInput');
		const sendBtn = document.querySelector('.ti-send')?.closest('button');

		if (ticketStatus === 'closed') {
			chatInput.disabled = true;
			chatInput.placeholder = 'Ticket is closed';
			sendBtn.disabled = true;
		} else {
			chatInput.disabled = false;
			chatInput.placeholder = 'Type your reply...';
			sendBtn.disabled = false;
		}

		fetch(`/support-ticket/${ticketId}/messages`, {
			headers: { 'Accept': 'application/json' }
		})
		.then(res => res.json())
		.then(data => {
			let html = '';

			data.forEach(msg => {
				const isMe = msg.sender_id === {{ auth()->id() }};
				const time = new Date(msg.created_at).toLocaleTimeString([], {
					hour: '2-digit',
					minute: '2-digit'
				});

				const messageHtml = buildMessageContent(msg);

				/* ================= MESSAGE OUT ================= */
				if (isMe) {
					html += `
					<div class="message-out">
						<div class="d-flex align-items-end flex-column">
							<p class="mb-1 text-muted"><small>${time}</small></p>
							<div class="message d-flex align-items-end flex-column">
								<div class="d-flex align-items-center mb-1 chat-msg justify-content-end">
									<div class="msg-content">
										${messageHtml}
									</div>
								</div>
							</div>
						</div>
					</div>`;
				}

				/* ================= MESSAGE IN ================= */
				else {
					html += `
					<div class="message-in">
						<div class="d-flex">
							<div class="flex-shrink-0">
								<div class="chat-avtar">
									<img class="rounded-circle img-fluid wid-40"
										 src="${msg.sender?.avatar ?? '/assets/images/user/avatar-2.jpg'}"
										 alt="">
									<i class="chat-badge bg-success"></i>
								</div>
							</div>
							<div class="flex-grow-1 mx-3">
								<div class="d-flex align-items-start flex-column">
									<p class="mb-1 text-muted">
										${msg.sender?.name ?? 'Support'}
										<small>${time}</small>
									</p>
									<div class="message d-flex align-items-start flex-column">
										<div class="d-flex align-items-center mb-1 chat-msg">
											<div class="flex-grow-1 me-3">
												<div class="msg-content">
													${messageHtml}
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>`;
				}
			});

			const body = document.querySelector('.chat-message .card-body');
			body.innerHTML = html;
			//body.scrollTop = body.scrollHeight;	
			// small delay for DOM paint
			setTimeout(() => {
				scrollChatToBottom(true);
			}, 50);

			
		});
	}
	
	document.getElementById('ticketChatModal')
    .addEventListener('shown.bs.modal', function () {
			// Scroll immediately
			scrollChatToBottom(true);

			// Scroll again after images load
			document
				.querySelectorAll('.chat-message img')
				.forEach(img => {
					img.onload = () => scrollChatToBottom(true);
				});
	});

	/* ================= MESSAGE CONTENT BUILDER ================= */
	function buildMessageContent(msg) {
		let html = '';

		/* ---- TEXT MESSAGE ---- */
		if (msg.message) {
			html += `<p class="mb-1">${escapeHtml(msg.message)}</p>`;
		}

		/* ---- ATTACHMENT ---- */
		if (msg.attachment) {
			const fileUrl = `/${msg.attachment}`;
			const ext = msg.attachment.split('.').pop().toLowerCase();

			// Image preview
			if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
				html += `
					<div class="mt-1">
						<a href="${fileUrl}" target="_blank">
							<img src="${fileUrl}" class="img-fluid rounded" style="max-width:200px;">
						</a>
					</div>`;
			}
			// Other files
			else {
				html += `
					<div class="mt-1">
						<a href="${fileUrl}" target="_blank" download class="text-decoration-none">
							<i class="ti ti-paperclip"></i> Download file
						</a>
					</div>`;
			}
		}

		return html || '<em class="text-muted">No content</em>';
	}

	/* ================= XSS SAFE ================= */
	function escapeHtml(text) {
		return text
			.replace(/&/g, "&amp;")
			.replace(/</g, "&lt;")
			.replace(/>/g, "&gt;")
			.replace(/"/g, "&quot;")
			.replace(/'/g, "&#039;");
	}

	
	
	document.getElementById('chatForm').addEventListener('submit', function (e) {
		e.preventDefault();

		hideChatError();

		let message = document.getElementById('chatReplyInput').value.trim();
		let fileInput = document.getElementById('chatAttachment');
		let file = fileInput.files[0];

		let formData = new FormData();
		formData.append('ticket_id', currentTicket);

		if (message !== '') {
			formData.append('message', message);
		}

		if (file) {
			formData.append('attachment', file);
		}

		$("#loader").show();

		fetch(`/support-ticket/send-message`, {
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
				'Accept': 'application/json'
			},
			body: formData
		})
		.then(async response => {
			$("#loader").hide();

			// Laravel validation error
			if (response.status === 422) {
				const data = await response.json();
				if (data.errors?.message) {
					showChatError(data.errors.message[0]);
				}
				return;
			}

			const res = await response.json();

			if (res.status) {
				document.getElementById('chatReplyInput').value = '';
				document.getElementById('chatAttachment').value = '';
				document.getElementById('attachmentPreview').innerHTML = '';
				hideChatError();
				openChat(currentTicket, ticketStatus);
			}
		})
		.catch(() => {
			$("#loader").hide();
			showChatError("Network error. Please try again.");
		});
	});

	
	function resolveTicket(ticketId) {
		if (!confirm('Mark this ticket as resolved?')) return;

		fetch(`/support-ticket/${ticketId}/resolve`, {
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
				'Accept': 'application/json'
			}
		})
		.then(res => res.json())
		.then(data => {
			showToast(data.message, "success");
			setTimeout(() => {
				location.reload();
			}, 2000);
		});
	}

	function closeTicket(ticketId) {
		if (!confirm('Are you sure you want to close this ticket?')) return;

		fetch(`/support-ticket/${ticketId}/close`, {
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
				'Accept': 'application/json'
			}
		})
		.then(res => res.json())
		.then(data => {
			showToast(data.message, "success");
			setTimeout(() => {
				location.reload();
			}, 2000);
		});
	}
	
	function scrollChatToBottom(force = false) {
		const wrapper = document.querySelector('.chat-message');
		if (!wrapper) return;

		requestAnimationFrame(() => {
			wrapper.scrollTop = wrapper.scrollHeight + 500;
		});
	}

	//start auto-refresh after 5 second
	let chatInterval = null;
	function startChatAutoRefresh() 
	{
		
		if (chatInterval) clearInterval(chatInterval);

		chatInterval = setInterval(() => {
			if (currentTicket && ticketStatus !== 'closed') {
				fetch(`/support-ticket/${currentTicket}/messages`, {
					headers: { 'Accept': 'application/json' }
				})
				.then(res => res.json())
				.then(data => {
					const body = document.querySelector('.chat-message .card-body');

					// Save current scroll position
					const scrollPosition = body.scrollTop;
					const scrollHeight = body.scrollHeight;

					let html = '';
					data.forEach(msg => {
						const isMe = msg.sender_id === {{ auth()->id() }};
						const time = new Date(msg.created_at).toLocaleTimeString([], {
							hour: '2-digit',
							minute: '2-digit'
						});

						const messageHtml = buildMessageContent(msg);

						if (isMe) {
							html += `
							<div class="message-out">
								<div class="d-flex align-items-end flex-column">
									<p class="mb-1 text-muted"><small>${time}</small></p>
									<div class="message d-flex align-items-end flex-column">
										<div class="d-flex align-items-center mb-1 chat-msg justify-content-end">
											<div class="msg-content">
												${messageHtml}
											</div>
										</div>
									</div>
								</div>
							</div>`;
						} else {
							html += `
							<div class="message-in">
								<div class="d-flex">
									<div class="flex-shrink-0">
										<div class="chat-avtar">
											<img class="rounded-circle img-fluid wid-40"
												 src="${msg.sender?.avatar ?? '/assets/images/user/avatar-2.jpg'}"
												 alt="">
											<i class="chat-badge bg-success"></i>
										</div>
									</div>
									<div class="flex-grow-1 mx-3">
										<div class="d-flex align-items-start flex-column">
											<p class="mb-1 text-muted">
												${msg.sender?.name ?? 'Support'}
												<small>${time}</small>
											</p>
											<div class="message d-flex align-items-start flex-column">
												<div class="d-flex align-items-center mb-1 chat-msg">
													<div class="flex-grow-1 me-3">
														<div class="msg-content">
															${messageHtml}
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>`;
						}
					});

					body.innerHTML = html;

					// Keep scroll at bottom if near bottom, else preserve scroll
					if (scrollPosition + 100 >= scrollHeight) {
						scrollChatToBottom(true);
					}
				});
			}
		}, 5000); // 5 seconds
	}

	// Start refreshing when modal opens
	document.getElementById('ticketChatModal')
	.addEventListener('shown.bs.modal', function () {
		scrollChatToBottom(true);
		startChatAutoRefresh();
	});

	// Stop refreshing when modal closes
	document.getElementById('ticketChatModal')
	.addEventListener('hidden.bs.modal', function () {
		if (chatInterval) clearInterval(chatInterval);
		chatInterval = null;
	});
	
	document.getElementById('chatAttachment').addEventListener('change', function () {
		const file = this.files[0];
		const preview = document.getElementById('attachmentPreview');

		if (!file) {
			preview.innerHTML = '';
			return;
		}

		preview.innerHTML = `<i class="ti ti-paperclip"></i> ${file.name}`;
	});
	
	function showChatError(msg) {
		const el = document.getElementById('chatError');
		el.innerText = msg;
		el.classList.remove('d-none');
	}

	function hideChatError() {
		const el = document.getElementById('chatError');
		el.innerText = '';
		el.classList.add('d-none');
	}




</script>
@endsection
