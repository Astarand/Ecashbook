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
						<?php if($quotes->utype =='1'){ ?>
						<li class="breadcrumb-item"><a href="{{ url('/ca-ticket') }}">CA Tickets</a></li>
						<?php } if($quotes->utype =='2'){ ?>
						<li class="breadcrumb-item"><a href="{{ url('/customer-ticket') }}">Customer Tickets</a></li>
						<?php } ?>
                        
                        <li class="breadcrumb-item active" aria-current="page">Ticket Details</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Ticket #{{ sprintf("%05d",$quotes->id) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Ticket details and conversation -->
        <div class="col-lg-8">
            <div class="card rounded-3 overflow-hidden shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <div>
                        <h5 class="mb-0">{{ $quotes->msg }}</h5>
                        <small class="text-muted">Opened on: {{ date('d-m-Y h:i A', strtotime($quotes->created_at)) }}</small>
                    </div>
                    <div>
						@if( $quotes->isActive ==0 )
						<span class="badge bg-warning px-3 py-2">Status: Pending</span>
						@elseif( $quotes->isActive ==1 )
						<span class="badge bg-success px-3 py-2">Status: Resolved</span>
						@elseif( $quotes->isActive ==2 )
						<span class="badge bg-warning px-3 py-2">Status: In-progress</span>
						@elseif( $quotes->isActive ==3 )
						<span class="badge bg-secondary px-3 py-2">Status: Closed</span>
						@endif
                        
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h6 class="mb-1">Customer Information</h6>
                            <p class="mb-1"><strong>Name:</strong> {{ $quotes->user->comp_name }}</p>
                            <p class="mb-1"><strong>Email:</strong> <a href="mailto:{{ $quotes->user->comp_email }}">{{ $quotes->user->comp_email }}</a></p>
                        </div>
                        <div>
                            <h6 class="mb-1">Ticket Information</h6>
                            <p class="mb-1"><strong>Priority:</strong> 
							  @if( $quotes->priority ==0 )
								  <span class="badge bg-info">Low</span>
							  @elseif( $quotes->priority ==1 )
								<span class="badge bg-warning">Medium</span>
							  @elseif( $quotes->priority ==2 )
								<span class="badge bg-danger">High</span>
							  @endif
							</p>
                            <p class="mb-1"><strong>Last Updated:</strong> {{ date('d-m-Y h:i A', strtotime($quotes->updated_at)) }}</p>
                        </div>
                    </div>

                    <!-- Original Message -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Initial Request</h6>
                            <p>{{ $quotes->msg }}</p>
                            <p class="mb-0"><strong>Order reference:</strong> #TKT-{{ sprintf("%05d",$quotes->id) }}</p>
                        </div>
                    </div>

                    <!-- Conversation Timeline -->
                    <h6 class="mb-3 border-bottom pb-2">Conversation History</h6>

                    <!--<div class="ticket-conversation">
                        
                        <div class="message customer-message mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-sm bg-light-primary rounded-circle">
                                        <span>JS</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="message-header d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h6 class="mb-0">John Smith</h6>
                                            <small class="text-muted">Customer</small>
                                        </div>
                                        <small class="text-muted">2023-06-15 10:45 AM</small>
                                    </div>
                                    <div class="message-content bg-light-primary p-3 rounded">
                                        <p class="mb-0">I tried again and it's still not working. Can someone help me complete my order?</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="message agent-message mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-sm bg-light-success rounded-circle">
                                        <span>AT</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="message-header d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h6 class="mb-0">You</h6>
                                            <small class="text-muted">Support Agent</small>
                                        </div>
                                        <small class="text-muted">2023-06-15 11:30 AM</small>
                                    </div>
                                    <div class="message-content bg-light-success p-3 rounded">
                                        <p class="mb-0">Hello John, I apologize for the inconvenience. I've checked your order details and there seems to be an issue with our payment gateway provider. Our technical team is working on resolving this. In the meantime, could you please try using the "Pay with PayPal" option as an alternative?</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="message customer-message mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-sm bg-light-primary rounded-circle">
                                        <span>JS</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="message-header d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h6 class="mb-0">John Smith</h6>
                                            <small class="text-muted">Customer</small>
                                        </div>
                                        <small class="text-muted">2023-06-15 02:15 PM</small>
                                    </div>
                                    <div class="message-content bg-light-primary p-3 rounded">
                                        <p class="mb-0">I tried the PayPal option but I don't have a PayPal account. Is there any other alternative?</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
					
					<div class="ticketConversationSection">
						@foreach($quotes->messages as $message)

						<?php
						//for same user
						if ($message->from_user_id == Auth::user()->id) {
							if ($message->attached != "") {
								$ext = pathinfo($message->attached, PATHINFO_EXTENSION);
								$filepath = asset('/uploads/chat/' . $message->attached);
								if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png') {
						?>
									<div class="message-out">
										<div class="d-flex align-items-end flex-column">
											<p class="mb-1 text-muted"><small>{{ date('d-m-Y h:i A', strtotime($message->timestamp)) }}</small></p>
											<div class="message d-flex align-items-end flex-column">
												<div class="d-flex align-items-center mb-1 chat-msg">
													<div class="flex-grow-1 ms-3">
														<div class="msg-content bg-primary">
															<a href="<?php echo $filepath; ?>" target="_blank"><img src="<?php echo $filepath; ?>" alt="" class="img-fluid"></a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php
								} else {
								?>
									<div class="message-out">
										<div class="d-flex align-items-end flex-column">
											<p class="mb-1 text-muted"><small>{{ date('d-m-Y h:i A', strtotime($message->timestamp)) }}</small> <i class="ph-duotone ph-user-circle"></i></p>
											<div class="message d-flex align-items-end flex-column">
												<div class="d-flex align-items-center mb-1 chat-msg">
													<div class="flex-grow-1 ms-3">
														<div class="msg-content bg-primary">
															<div class="fileAttechmentInner">
																<div class="file-icon">
																	<?php
																	$icon = '';
																	switch (strtolower($ext)) {
																		case 'pdf':
																			$icon = '<i class="ti ti-file-text"></i>';
																			break;
																		case 'doc':
																		case 'docx':
																			$icon = '<i class="ti ti-file-text"></i>';
																			break;
																		case 'xls':
																		case 'xlsx':
																			$icon = '<i class="ti ti-file-spreadsheet"></i>';
																			break;
																		case 'ppt':
																		case 'pptx':
																			$icon = '<i class="ti ti-presentation"></i>';
																			break;
																		case 'zip':
																		case 'rar':
																			$icon = '<i class="ti ti-file-zip"></i>';
																			break;
																		case 'txt':
																			$icon = '<i class="ti ti-file-text"></i>';
																			break;
																		default:
																			$icon = '<i class="ti ti-file"></i>';
																	}
																	echo $icon;
																	?>
																</div>
																<div class="file-details">
																	<div class="file-name"><?php echo $message->attached; ?></div>
																</div>
																<a href="<?php echo $filepath; ?>" target="_blank" class="download-btn">
																	<i class="ti ti-download"></i> Download
																</a>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php
								}
							}

							if ($message->chat_message != "") {
								?>
								<div class="message-out">
									<div class="d-flex align-items-end flex-column">
										<p class="mb-1 text-muted"><small>{{ date('d-m-Y h:i A', strtotime($message->timestamp)) }}</small> <i class="ph-duotone ph-user-circle"></i></p>
										<div class="message d-flex align-items-end flex-column">
											<div class="d-flex align-items-center mb-1 chat-msg">
												<div class="flex-grow-1 ms-3">
													<div class="msg-content bg-primary">
														<p class="mb-0">{{ $message->chat_message }}</p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php
							}
						} else {
							//for different user
							if ($message->attached != "") {
								$ext = pathinfo($message->attached, PATHINFO_EXTENSION);
								$filepath = asset('/uploads/chat/' . $message->attached);
								if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png') {
								?>
									<div class="message-in">
										<div class="d-flex">
											<div class="flex-grow-1 mx-3">
												<div class="d-flex align-items-start flex-column">
													<p class="mb-1 text-muted"><i class="ph-duotone ph-user-circle-gear"></i> <small>{{ date('d-m-Y h:i A', strtotime($message->timestamp)) }}</small></p>
													<div class="message d-flex align-items-start flex-column">
														<div class="d-flex align-items-center mb-1 chat-msg">
															<div class="flex-grow-1 me-3">
																<div class="msg-content card mb-0">
																	<a href="<?php echo $filepath; ?>" target="_blank"><img src="<?php echo $filepath; ?>" alt="" class="img-fluid"></a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php
								} else {
								?>
									<div class="message-in">
										<div class="d-flex">
											<div class="flex-grow-1 mx-3">
												<div class="d-flex align-items-start flex-column">
													<p class="mb-1 text-muted"><i class="ph-duotone ph-user-circle-gear"></i> <small>{{ date('d-m-Y h:i A', strtotime($message->timestamp)) }}</small></p>
													<div class="message d-flex align-items-start flex-column">
														<div class="d-flex align-items-center mb-1 chat-msg">
															<div class="flex-grow-1 me-3">
																<div class="msg-content card mb-0">
																	<div class="fileAttechmentInner">
																		<div class="file-icon">
																			<?php
																			$icon = '';
																			switch (strtolower($ext)) {
																				case 'pdf':
																					$icon = '<i class="ti ti-file-text"></i>';
																					break;
																				case 'doc':
																				case 'docx':
																					$icon = '<i class="ti ti-file-text"></i>';
																					break;
																				case 'xls':
																				case 'xlsx':
																					$icon = '<i class="ti ti-file-spreadsheet"></i>';
																					break;
																				case 'ppt':
																				case 'pptx':
																					$icon = '<i class="ti ti-presentation"></i>';
																					break;
																				case 'zip':
																				case 'rar':
																					$icon = '<i class="ti ti-file-zip"></i>';
																					break;
																				case 'txt':
																					$icon = '<i class="ti ti-file-text"></i>';
																					break;
																				default:
																					$icon = '<i class="ti ti-file"></i>';
																			}
																			echo $icon;
																			?>
																		</div>
																		<div class="file-details">
																			<div class="file-name"><?php echo $message->attached; ?></div>
																		</div>
																		<a href="<?php echo $filepath; ?>" target="_blank" class="download-btn">
																			<i class="ti ti-download"></i> Download
																		</a>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php
								}
							}

							if ($message->chat_message != "") {
								?>
								<div class="message-in">
									<div class="d-flex">
										<div class="flex-grow-1 mx-3">
											<div class="d-flex align-items-start flex-column">
												<p class="mb-1 text-muted"><i class="ph-duotone ph-user-circle-gear"></i> <small>{{ date('d-m-Y h:i A', strtotime($message->timestamp)) }}</small></p>
												<div class="message d-flex align-items-start flex-column">
													<div class="d-flex align-items-center mb-1 chat-msg">
														<div class="flex-grow-1 me-3">
															<div class="msg-content card mb-0">
																<p class="mb-0">{{ $message->chat_message }}</p>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
						<?php
							}
						}
						?>
						@endforeach
					</div>
					
					
					
                </div>
            </div>

            <!-- Reply Form -->
            <div class="card rounded-3 overflow-hidden shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Reply to Ticket</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="javascript:void(0);" accept-charset="UTF-8" class="reply-form" id="reply-form" autocomplete="off" novalidate="novalidate">
                        @csrf
						<input type="hidden" name="base_url" id="base_url" value="{{ url('/') }}">
						<!--@if(isset($quotes->caid))
						<input type="hidden" name="to_user_id" id="to_user_id" value="{{ $quotes->caid }}">
						@else
						<input type="hidden" name="to_user_id" id="to_user_id" value="{{ $quotes->uid }}">
						@endif-->
						<input type="hidden" name="to_user_id" id="to_user_id" value="{{ $quotes->uid }}">
						<input type="hidden" name="from_user_id" id="from_user_id" value="{{ Auth::user()->id }}">
						<input type="hidden" name="c_qid" id="c_qid" value="{{ $quotes->id }}">
						
						
                        <div class="mb-3">
                            <textarea name="chat-widget-message-text" id="chat-widget-message-text" class="form-control" rows="4" placeholder="Type your reply here..."></textarea>
                        </div>
						
						<input type="hidden" name="message_file" id="chat-widget-message-file" value="">
                        <div class="mb-3">
                            <label for="attachments" class="form-label">Attachments (optional)</label>
                            <!--<input type="file" class="form-control" id="attachments" multiple>-->
							<ul class="list-inline me-auto mb-0">
								<li class="list-inline-item">
									<a href="javascript:;" onclick="fetch_file_ticket()" class="avtar avtar-xs btn-link-secondary">
										<i class="ti ti-paperclip f-18"></i>
									</a>
								</li>
							</ul>
                        </div>
                        <div class="d-flex gap-2 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notifyCustomer" name="notifyCustomer" {{ Auth::user()->u_type != 3 ? 'disabled' : '' }} >
                                <label class="form-check-label" for="notifyCustomer">
                                    Notify customer via email
                                </label>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
							@if( $quotes->isActive !=3 )
                            <button type="button" id="reply-btn" class="btn btn-primary">Send Reply</button>							
                            <button type="button" id="resolve-ticket-btn" onclick="resolvedTicket()" class="btn btn-outline-success">Resolve Ticket</button>
                            @endif
							<button type="button" id="close-ticket-btn" onclick="confirmAndCall()" class="btn btn-outline-secondary">Close Ticket</button>
							<button class="btn btn-outline-secondary" onclick="history.back()">Back</button>
						</div>
						<div class="message-container"></div>
                    </form>
                </div>
				 <div style="display:none;">
					<form method="POST" action="javascript:void(0);" accept-charset="UTF-8" class="form-file-upload" id="form-file-upload" enctype="multipart/form-data">
						@csrf
						<input name="attachment_file" id="attachment_file" style="display:none" onchange="preview_file_ticket(this.files)" type="file">
					</form>
				</div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Ticket Information -->
            <div class="card rounded-3 overflow-hidden shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Ticket Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                            <span class="fw-medium">Ticket ID:</span>
                            <span>#TKT-{{ sprintf("%05d",$quotes->id) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                            <span class="fw-medium">Created:</span>
                            <span>{{ date('d-m-Y h:i A', strtotime($quotes->created_at)) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                            <span class="fw-medium">Last Updated:</span>
                            <span>{{ date('d-m-Y h:i A', strtotime($quotes->updated_at)) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                            <span class="fw-medium">Status:</span>
							@if( $quotes->isActive ==0 )
							<span class="badge bg-warning">Pending</span>
							@elseif( $quotes->isActive ==1 )
							<span class="badge bg-success">Resolved</span>
							@elseif( $quotes->isActive ==2 )
							<span class="badge bg-warning">In progress</span>
							@elseif( $quotes->isActive ==3 )
							<span class="badge bg-secondary">Closed</span>
							@endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                            <span class="fw-medium">Priority:</span>
							  @if( $quotes->priority ==0 )
								  <span class="badge bg-info">Low</span>
							  @elseif( $quotes->priority ==1 )
								<span class="badge bg-warning">Medium</span>
							  @elseif( $quotes->priority ==2 )
								<span class="badge bg-danger">High</span>
							  @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                            <span class="fw-medium">Subject:</span>
                            <span>{{$quotes->msg}}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Customer Information -->
			@if(Auth::user()->u_type == 3)
            <div class="card rounded-3 overflow-hidden shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body pb-0">
                    <div class="text-center mb-3">
                        <h5 class="mb-0">{{ $quotes->user->comp_name }}</h5>
                        <p class="text-muted">
						<?php 
						if($quotes->utype =='1'){ 
							echo "CA";
						} else if($quotes->utype =='2'){ 
							echo "Customer";
						} else{
							echo "Other";
						}
						?>
						</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Email:</span>
                            <a href="mailto:john@example.com" class="text-truncate">{{ $quotes->user->comp_email }}</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Total Ticket:</span>
                            <span>{{ $quotes->totalTickets }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Previous Tickets:</span>
                            <span>{{ $quotes->prevTickets }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Customer Since:</span>
                            <span>{{ date('d-m-Y h:i A', strtotime($quotes->user->created_at)) }}</span>
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-light p-3 border-top">
                    <a href="javascript:void(0);" class="btn btn-outline-primary w-100">View Customer Profile</a>
                </div>
            </div>
			@endif
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>


<style>
/* Chat Section */
.ticketConversationSection {
    padding: 20px;
    background: #f5f7fb;
    border-radius: 10px;
}

/* Common Message Container */
.message-in,
.message-out {
    margin-bottom: 18px;
}

/* Align Left (Incoming) */
.message-in {
    display: flex;
    justify-content: flex-start;
}

/* Align Right (Outgoing) */
.message-out {
    display: flex;
    justify-content: flex-end;
}

/* Timestamp Styling */
.message-in p.small,
.message-out p.small {
    color: #888;
    font-size: 12px;
}

/* Message Bubble */
.msg-content {
    padding: 10px 14px;
    border-radius: 12px;
    max-width: 320px;
    word-wrap: break-word;
    font-size: 14px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

/* Incoming Bubble */
.message-in .msg-content {
    background: #ffffff;
    border: 1px solid #e2e2e2;
}

/* Outgoing Bubble */
.message-out .msg-content.bg-primary {
    background: #007bff;
    color: white;
}

/* Remove default margin from paragraphs inside bubbles */
.msg-content p {
    margin: 0;
}

/* Image Preview Box */
.image-message-preview {
    position: relative;
    max-width: 250px;
}

.image-message-preview img {
    width: 100%;
    border-radius: 10px;
    display: block;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

/* Timestamp on Image */
.image-timestamp {
    position: absolute;
    bottom: 6px;
    right: 10px;
    background: rgba(0,0,0,0.35);
    color: white;
    padding: 2px 6px;
    font-size: 11px;
    border-radius: 4px;
}

/* User Icons Formatting */
.ph-duotone {
    font-size: 18px;
    vertical-align: middle;
}

/* Grow message vertical stack spacing */
.chat-msg {
    margin-top: 5px;
}


</style>
@section('page-script')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection

@endsection
