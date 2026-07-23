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
                            <li class="breadcrumb-item active" aria-current="page">MCA/ROC Filings</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-mca-list-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <i class="ti ti-help-circle f-18"></i> <u>How does this Page works?</u>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">MCA/ROC Filings Reports</h2>
                    </div>
                </div>
				@if(Auth::user()->u_type == 2)
				<div class="col-md-8 text-end">
                    <a href="{{ route('user.MCAROCFiling') }}" class="btn btn-primary btn-apply-mca"><i class="ti ti-square-plus"></i> Apply</a>
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
            <div class="card card-body table-card mca-table-card">
                <div class="table-responsive">
					<table class="table table-bordered mt-3" id="pc-dt-simple">
						<thead>
							<tr>
								<th>#</th>
								<th>Company</th>
								<th>Date</th>
								<th>Application Status</th>
								<th>Process Date</th>
								<th>Payment</th>
								<th>Chat Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($applications as $row)
							<tr id="row{{ $row->id }}">
								<td>{{ $loop->iteration }}</td>
								<td>
									<strong>{{ $row->company_name }}</strong>
									<br>
									<small class="text-muted">
										CIN: {{ $row->cin ?? '-' }} | 
										PAN: {{ $row->pan ?? '-' }} | 
										📞 {{ $row->mobile ?? '-' }}
									</small>
								</td>
								<td>{{ $row->created_at->format('d-m-Y') }}</td>
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
								<td class="statusCol">
									@if($row->status == 'Ongoing')
										<span class="badge bg-warning"><i class="ti ti-loader"></i> Ongoing</span>
									@elseif($row->status == 'Done')
										<span class="badge bg-success"><i class="ti ti-check"></i> Done</span>
									@else
										<span class="badge bg-danger"><i class="ti ti-x"></i> Reject</span>
									@endif
								</td>
								<td>
									<div class="d-flex align-items-center gap-2">

										<!-- View -->
										<a href="{{ url('/mca-roc/view/'.$row->id) }}"
										   class="btn btn-sm btn-primary mca-view-btn"
										   title="View">
											<i class="ti ti-eye"></i>
										</a>

										<!-- Chat -->
										<a href="javascript:void(0)"
										   class="btn btn-sm btn-warning mca-chat-btn"
										   title="Chat"
										   data-bs-toggle="modal"
										   data-bs-target="#ticketChatModal"
										   onclick="openChat({{ $row->id }}, '{{ $row->status }}')">
											<i class="ti ti-message-circle"></i>
										</a>

										<!-- Status -->
										@if(Auth::user()->u_type == 3 || Auth::user()->u_type == 6)
										<button class="btn btn-sm btn-info statusBtn"
												title="Change Status"
												data-id="{{ $row->id }}"
												data-status="{{ $row->status }}">
											<i class="ti ti-refresh"></i>
										</button>
										
										<a href="javascript:void(0)"
										   class="avtar avtar-xs btn-link-primary btn-pc-default"
										   onclick="openStatusModal({{ $row->id }}, 'mca_roc_applications')">
											<i class="ti ti-edit f-18"></i>
										</a>
										@endif
										<!-- Delete -->
										@if(Auth::user()->u_type == 2)
										<button class="btn btn-sm btn-danger deleteBtn"
												title="Delete"
												data-id="{{ $row->id }}">
											<i class="ti ti-trash"></i>
										</button>
										@endif

									</div>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					<div class="mt-3">
						
					</div>

                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<div class="modal fade" id="statusAppModal" tabindex="-1">
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

<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">Chat Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="status_id">

                <label>Status</label>
                <select id="status_value" class="form-select">
                    <option value="Ongoing">Ongoing</option>
                    <option value="Done">Done</option>
                    <option value="Reject">Reject</option>
                </select>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="saveStatus">Update</button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="ticketChatModal" tabindex="-1" aria-labelledby="ticketChatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
		
        <div class="modal-content">
			<form id="chatForm" enctype="multipart/form-data">
				<div class="modal-header">
					<h5 class="modal-title" id="ticketChatModalLabel">MCA / ROC Filing Conversation</h5>
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
									<h6 class="mb-0">MCA / ROC Filing Desk</h6>
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

		$('#statusAppModal').modal('show');

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
				$('#statusAppModal').modal('hide');
				showToast('Updated successfully', 'success');
				location.reload();
			} else {
				$('#statusError').removeClass('d-none').text(res.message);
			}
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

		fetch(`/mcaroc-reply/${ticketId}/messages`, {
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

		fetch(`/mcaroc-reply/send-message`, {
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
				fetch(`/mcaroc-reply/${currentTicket}/messages`, {
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
	
	
	
	$('.statusBtn').click(function(){

		let id = $(this).data('id');
		let status = $(this).data('status');

		$('#status_id').val(id);
		$('#status_value').val(status);

		$('#statusModal').modal('show');
	});
	
	$('#saveStatus').click(function(){

		let id = $('#status_id').val();
		let status = $('#status_value').val();

		$.post('/mca-roc/status-update', {
			_token: '{{ csrf_token() }}',
			id: id,
			status: status
		}, function(res){

			if(res.success){
				$('#statusModal').modal('hide');
				showToast(res.message, 'success');

				// Update badge dynamically
				let badge = '';

				if(status == 'Ongoing'){
					badge = '<span class="badge bg-warning"><i class="ti ti-loader"></i> Ongoing</span>';
				}else if(status == 'Done'){
					badge = '<span class="badge bg-success"><i class="ti ti-check"></i> Done</span>';
				}else{
					badge = '<span class="badge bg-danger"><i class="ti ti-x"></i> Reject</span>';
				}

				$('#row'+id).find('.statusCol').html(badge);
			}

		});
	});

	$('.deleteBtn').click(function(){
		if(!confirm('Delete this record?')) return;

		let id = $(this).data('id');

		$.post('/mca-roc/delete',{
			_token:'{{ csrf_token() }}',
			id:id
		},function(res){
			if(res.success){
				$('#row'+id).remove();
				showToast(res.message,'success');
			}
		});
	});

	function startMcaListTour() {
		if (typeof introJs !== 'function') return;

		introJs().setOptions({
			steps: [
				{
					title: 'MCA/ROC Filings List',
					intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-help-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Manage and monitor your MCA/ROC filing applications and compliance status.</p></div>'
				},
				{
					element: '.btn-apply-mca',
					title: 'Apply for Filing',
					intro: 'Click here to start a new MCA/ROC filing request by filling out the application form.'
				},
				{
					element: '.mca-table-card',
					title: 'Filing Records',
					intro: 'Monitor all your submitted company filings, CIN/PAN details, processing status, and payment records.'
				},
				{
					element: '.mca-view-btn',
					title: 'View Details',
					intro: 'Click this button to see the complete application details and verified credentials.'
				},
				{
					element: '.mca-chat-btn',
					title: 'Filing Desk Chat',
					intro: 'Interact directly with professionals, ask questions, and upload required documents for this specific filing.'
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
		$('#start-mca-list-tour').on('click', function(e) {
			e.preventDefault();
			startMcaListTour();
		});
	});
</script>
@endsection