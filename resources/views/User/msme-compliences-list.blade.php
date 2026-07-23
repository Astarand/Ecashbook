@extends('App.Layout')

@section('container')




<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <ul class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Tax Filing & Returns</a></li>
                            <li class="breadcrumb-item active" aria-current="page">MSME Compliance Reports</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-msme-list-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <i class="ti ti-help-circle f-18"></i> <u>How does this Page works?</u>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">MSME Compliance Reports</h2>
                    </div>
                </div>
				@if(Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
				<div class="col-md-8 text-end">
                    <a href="{{ route('user.MSMECompliance') }}" class="btn btn-primary btn-apply-msme"><i class="ti ti-square-plus"></i> Apply</a>
                </div>
				@endif
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card msme-table-card">
                <div class="table-responsive">
                    <table class="table table-product mt-3">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Company</th>
								<th>Application Status</th>
								<th>Process Date</th>
								<th>Payment</th>
								<th>Service</th>
								<th>Details</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($applications as $key => $row)
							<tr>
								<td>{{ $key + 1 }}</td>
								<td>{{ $row->applicant_name }}</td>
								<td>
									<strong>{{ $row->company_name }}</strong>
									<br>
									<small>
										📞 {{ $row->mobile }} | ✉ {{ $row->email }}
									</small>
								</td>
								<td>
									<span class="badge bg-{{ $row->app_status == 'Processed' ? 'success' : 'warning' }}">
										{{ $row->app_status }}
									</span>
								</td>
								<td>
									{{ $row->process_date ? \Carbon\Carbon::parse($row->process_date)->format('d-m-Y') : '-' }}
								</td>
								<td>
									@if($row->payment_status == 'Full')
										<span class="badge bg-success">Full</span>
									@elseif($row->payment_status == 'Advance')
										<span class="badge bg-warning text-dark">Advance</span>
									@else
										<span class="badge bg-secondary">Pending</span>
									@endif
								</td>
								<td>{{ $row->preferred_service }}</td>
								<td>{{ $row->details }}</td>
								@if(Auth::user()->u_type == 2)
								<!--<td>
									<button class="btn btn-danger btn-sm deleteBtn"
											data-id="{{ $row->id }}">
										Delete
									</button>
								</td>-->
								@endif
								
								<td>
									<div class="prod-action-links">
										<ul class="list-inline me-auto mb-0">
											<li class="list-inline-item" data-bs-toggle="tooltip" title="View Reply">
												<a href="#ticketChatModal"
												   class="avtar avtar-xs btn-link-warning btn-pc-default msme-chat-btn"
												   data-bs-toggle="modal"
												   data-bs-target="#ticketChatModal"
												   onclick="openChat({{ $row->id }},'{{ $row->status }}')">
													<i class="ti ti-message-circle f-18"></i>
												</a>
											</li>
											@if(Auth::user()->u_type == 3 || Auth::user()->u_type == 6)
											<li class="list-inline-item" data-bs-toggle="tooltip" title="Update Status">
												<a href="javascript:void(0)"
												   class="avtar avtar-xs btn-link-primary btn-pc-default"
												   onclick="openStatusModal({{ $row->id }}, 'msme_applications')">
													<i class="ti ti-edit f-18"></i>
												</a>
											</li>
											@endif
											@if(Auth::user()->u_type == 2)
											<li>
												<a class="dropdown-item text-danger deleteBtn"
												   href="javascript:void(0)"
												   data-id="{{ $row->id }}">
													<i class="ti ti-x me-2"></i> Delete
												</a>
											</li>
											@endif
										</ul>
									</div>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					
					<div class="d-flex justify-content-end mt-3">
						
					</div>

                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="statusForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Application Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="record_id">
                    <input type="hidden" id="table_flag">

                    <div class="mb-3">
                        <label>Application Status</label>
                        <select class="form-control" id="app_status">
                            <option value="Pending">Pending</option>
                            <option value="Processed">Processed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Process Date</label>
                        <input type="date" class="form-control" id="process_date">
                    </div>

                    <div class="mb-3">
                        <label>Payment Status</label>
						<select class="form-control" name="payment_status" id="payment_status">
                            <option value="">Select</option>
                            <option value="Full">Full</option>
                            <option value="Advance">Advance</option>
                        </select>
                    </div>

                    <div id="statusError" class="text-danger d-none"></div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>



<div class="modal fade" id="ticketChatModal" tabindex="-1" aria-labelledby="ticketChatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
		
        <div class="modal-content">
			<form id="chatForm" enctype="multipart/form-data">
				<div class="modal-header">
					<h5 class="modal-title" id="ticketChatModalLabel">MSME Compliance Conversation</h5>
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
									<h6 class="mb-0">MSME Compliance Desk</h6>
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



<style>
.chat-message .message-out p{
	color:#000;
}

</style>


<script>

	//Update status
	function openStatusModal(id, table) {
		$('#record_id').val(id);
		$('#table_flag').val(table);

		$('#statusModal').modal('show');

		// Optional: fetch existing data
		$.get(`/common/get-status/${table}/${id}`, function(res){
			if(res.status){
				$('#app_status').val(res.data.app_status);
				$('#process_date').val(res.data.process_date);
				$('#payment_status').val(res.data.payment_status);
			}
		});
	}
	
	$('#statusForm').submit(function(e){
		e.preventDefault();

		let data = {
			_token: '{{ csrf_token() }}',
			id: $('#record_id').val(),
			table: $('#table_flag').val(),
			app_status: $('#app_status').val(),
			process_date: $('#process_date').val(),
			payment_status: $('#payment_status').val()
		};

		$.post('/common/update-status', data, function(res){
			if(res.status){
				$('#statusModal').modal('hide');
				showToast('Updated successfully', 'success');
				location.reload();
			} else {
				$('#statusError').removeClass('d-none').text(res.message);
			}
		});
	});

	//Chat
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

		fetch(`/msme-reply/${ticketId}/messages`, {
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
	
	document.getElementById('ticketChatModal').addEventListener('shown.bs.modal', function () {
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

		fetch(`/msme-reply/send-message`, {
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
				fetch(`/msme-reply/${currentTicket}/messages`, {
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
    
	$('.deleteBtn').click(function(){
		if(!confirm('Are you sure?')) return;

		let id = $(this).data('id');

		$.ajax({
			url: '/msme/delete',
			method: 'POST',
			data: {
				_token: '{{ csrf_token() }}',
				id: id
			},
			success: function(res){
				if(res.success){
					$('#row'+id).remove();
					showToast(res.message, 'success');
					location.reload();
				}
			}
		});
	});

	function startMsmeListTour() {
		if (typeof introJs !== 'function') return;

		introJs().setOptions({
			steps: [
				{
					title: 'MSME Compliance Reports',
					intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-help-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Manage and track your MSME application requests, status updates, and compliance support chats.</p></div>'
				},
				{
					element: '.btn-apply-msme',
					title: 'Submit Application',
					intro: 'Click here to apply for a new MSME compliance support request and select preferred services.'
				},
				{
					element: '.msme-table-card',
					title: 'Applications Log',
					intro: 'Track the status of all your submitted MSME application requests, including processing dates and payment records.'
				},
				{
					element: '.msme-chat-btn',
					title: 'Compliance Chat',
					intro: 'Click this icon to converse directly with our experts, respond to queries, and attach compliance documents.'
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
		$('#start-msme-list-tour').on('click', function(e) {
			e.preventDefault();
			startMsmeListTour();
		});
	});
</script>
@endsection