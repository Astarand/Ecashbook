@extends('App.Layout')

@section('container')
<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Helpdesk</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Customer Tickets</li>
                    </ul>
                </div>
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h2 class="mb-0">
							@if(Auth::user()->u_type == 2)
								Support Tickets
							@else
								Customer Support Tickets
							@endif
						</h2>
                    </div>
                </div>
				@if(Auth::user()->u_type == 2)
				<div class="col-md-4 text-end">
                    <button type="button" class="btn btn-primary" id="openModal">Create Ticket</button>
                </div>
				@endif
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">

        <!-- Ticket table -->
        <div class="col-sm-12">
			
            <div class="card card-body table-card py-3">
                <div class="table-responsive">
                    <table class="table tbl-product m-0" id="pc-dt-simple">
                        <thead>
                            <tr style="background-color: #f3f3f3;">
                                <th class="text-center">#</th>
                                <th>Ticket ID</th>
                                <th>Subject</th>
                                <th>Customer</th>
                                <th>Created Date</th>
                                <th>Last Updated</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
							<?php 
								$i = 1; 
								$userId = currentOwnerId();
							?>
							@foreach ($tickets as $val)
                            <tr>
                                <td class="text-center">{{ $i++ }}</td>
                                <td>
									<span class="fw-medium">
									@if(Auth::user()->u_type == 3 || Auth::user()->u_type == 6)
									<a href="{{ url('/ticket-response/'.base64_encode($userId).'/'.base64_encode($val->added_by).'/'.base64_encode($val->id)) }}"  title="View Ticket Status">
										#TKT-{{ sprintf("%05d",$val->id) }}
									</a>
									@else
									<a href="{{ url('/ticket-response/'.base64_encode($userId).'/'.base64_encode($val->compId).'/'.base64_encode($val->id)) }}"  title="View Ticket Status">
										#TKT-{{ sprintf("%05d",$val->id) }}
									</a>
									@endif
									</span>
								</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $val->msg }}</h6>
                                            <small class="text-muted d-block"></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $val->comp_name }}</h6>
                                            <a class="text-muted f-12" href="mailto:{{ $val->comp_email }}">{{ $val->comp_email }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ date('d-m-Y H:i A', strtotime($val->created_at)) }}</td>
                                <td>{{ date('d-m-Y H:i A', strtotime($val->updated_at)) }}</td>
								<td>                  
								  @if( $val->priority ==0 )
									  <span class="badge text-bg-info">Low</span>
								  @elseif( $val->priority ==1 )
									<span class="badge text-bg-warning">Medium</span>
								  @elseif( $val->priority ==2 )
									<span class="badge text-bg-danger">High</span>
								  @endif
								</td>
                                <td>                  
								  @if( $val->isActive ==0 )
								  <span class="badge text-bg-danger">Pending</span>
								  @elseif( $val->isActive ==1 )
								  <span class="badge text-bg-success">Resolved</span>
								  @elseif( $val->isActive ==2 )
								  <span class="badge text-bg-warning">In-progress</span>
								  @elseif( $val->isActive ==3 )
								  <span class="badge text-bg-secondary">Closed</span>
								  @endif
								</td>
                                <td>
                                    <div class="d-flex justify-content-center">
										@if(Auth::user()->u_type == 3 || Auth::user()->u_type == 6)
                                        <a href="{{ url('/ticket-response/'.base64_encode($userId).'/'.base64_encode($val->added_by).'/'.base64_encode($val->id)) }}" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip" title="View Ticket">
                                            <i class="ti ti-eye"></i>
                                        </a>
										@else
										<a href="{{ url('/ticket-response/'.base64_encode($userId).'/'.base64_encode($val->compId).'/'.base64_encode($val->id)) }}" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip" title="View Ticket">
                                            <i class="ti ti-eye"></i>
                                        </a>
										@endif
                                    </div>
                                </td>
                            </tr>
							@endforeach
                            <!-- Medium Priority Ticket - New -->							
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- Ticket View/Reply Modal -->
<div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ticketModalLabel">Ticket #TKT-1024</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <span class="badge bg-warning px-3 py-2">Status: In Progress</span>
                    <span class="badge bg-danger px-3 py-2">Priority: High</span>
                </div>

                <h5>Payment gateway error during checkout</h5>
                <p class="text-muted mb-4">Submitted by John Smith on 2023-06-15 09:32 AM</p>

                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold">Customer Message:</h6>
                        <p>I'm trying to complete a purchase but when I click on "Complete Payment", I get an error message saying "Transaction Failed: Gateway Error". I've tried multiple times with different cards but still getting the same error. Please help me complete my purchase.</p>
                        <p>Order reference: #ORD-78965</p>
                    </div>
                </div>

                <div class="chat-history mb-3">
                    <div class="ticket-reply customer mb-3">
                        <div class="d-flex">
                            <div class="avatar avatar-sm me-3 mt-1 bg-light-primary">
                                <span>JS</span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="card bg-light-primary border-0">
                                    <div class="card-body py-2">
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="mb-0">John Smith</h6>
                                            <small class="text-muted ms-auto">2023-06-15 10:45 AM</small>
                                        </div>
                                        <p class="mb-0">I tried again and it's still not working. Can someone help me complete my order?</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ticket-reply support mb-3">
                        <div class="d-flex">
                            <div class="avatar avatar-sm me-3 mt-1 bg-light-success">
                                <span>AT</span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="card bg-light-success border-0">
                                    <div class="card-body py-2">
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="mb-0">Alex Turner (Support Agent)</h6>
                                            <small class="text-muted ms-auto">2023-06-15 11:30 AM</small>
                                        </div>
                                        <p class="mb-0">Hello John, I apologize for the inconvenience. I've checked your order details and there seems to be an issue with our payment gateway provider. Our technical team is working on resolving this. In the meantime, could you please try using the "Pay with PayPal" option as an alternative?</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ticket-reply customer mb-3">
                        <div class="d-flex">
                            <div class="avatar avatar-sm me-3 mt-1 bg-light-primary">
                                <span>JS</span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="card bg-light-primary border-0">
                                    <div class="card-body py-2">
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="mb-0">John Smith</h6>
                                            <small class="text-muted ms-auto">2023-06-15 02:15 PM</small>
                                        </div>
                                        <p class="mb-0">I tried the PayPal option but I don't have a PayPal account. Is there any other alternative?</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form>
                    <div class="mb-3">
                        <label for="replyMessage" class="form-label">Reply</label>
                        <textarea class="form-control" id="replyMessage" rows="4" placeholder="Type your response..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="attachFiles" class="form-label">Attachments (Optional)</label>
                        <input class="form-control" type="file" id="attachFiles" multiple>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" id="markAsResolved">
                            <label class="form-check-label" for="markAsResolved">
                                Mark as resolved after reply
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="notifyCustomer">
                            <label class="form-check-label" for="notifyCustomer">
                                Send email notification
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Send Reply</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="msgModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="ticketForm">				
                @csrf
				
                <div class="modal-header">
                    <h5 class="modal-title">Create Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
					<div class="message-container"></div>
					<div class="mb-3">
                        <label>Subjet</label>
                        <input type="text" name="msg" id="msg" maxlength="50" class="form-control" required >
                    </div>
                    <div class="mb-3">
                        <label>Message</label>
                        <textarea name="chat_message" id="chat_message" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Priority</label>
                        <select name="priority" class="form-control" required>
                            <option value="">Select</option>
                            <option value="0">Low</option>
                            <option value="1">Medium</option>
                            <option value="2">High</option>
                        </select>
                    </div>

                    <div id="formError" class="text-danger"></div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>

	$(document).ready(function () {

			// Open modal
			$('#openModal').click(function () {
				$('#msgModal').modal('show');
			});

			// Submit form via AJAX
			$('#ticketForm').submit(function (e) {
				e.preventDefault();

				$.ajax({
					url: "{{ route('ticket.createTicket') }}",
					type: "POST",
					data: $(this).serialize(),
					success: function (response) {
						if (response.class == "succ") {
							$(".message-container").html('<div class="alert alert-success">' + response.message + "</div>");
							$('#msgModal').modal('hide');
							$('#ticketForm')[0].reset();
							location.reload();
						} else {
							$(".message-container").html('<div class="alert alert-danger">' + response.message + "</div>");
						}
						
					}
				});
			});

	});
	
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Handle ticket view button click
        const viewButtons = document.querySelectorAll('[title="View Ticket"]');
        viewButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                var ticketModal = new bootstrap.Modal(document.getElementById('ticketModal'));
                ticketModal.show();
            });
        });
    });
</script>
@endsection