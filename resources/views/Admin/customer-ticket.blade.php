@extends('App.Layout')

@section('container')
<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header mb-4">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 mb-2">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Helpdesk</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Customer Tickets</li>
                    </ul>
                </div>
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h2 class="mb-0 text-dark fw-bold">
							@if(Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
								Support Tickets
							@else
								Customer Support Tickets
							@endif
						</h2>
                    </div>
                </div>
				@if(Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
				<div class="col-md-4 text-end">
                    <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 py-2 shadow-sm rounded-3" id="openModal">
                        <i class="ti ti-plus f-18"></i> Create Ticket
                    </button>
                </div>
				@endif
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- Statistics Section -->
    <div class="row g-4 mb-4" id="stats-container">
        <div class="col-md-3">
            <div class="card ticket-stat-card stat-card-primary h-100 mb-0">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="stat-icon stat-icon-primary me-3">
                        <i class="ti ti-ticket"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 fw-medium">Total Tickets</h6>
                        <h3 class="mb-0 fw-bold" id="stat-total">-</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card ticket-stat-card stat-card-warning h-100 mb-0">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="stat-icon stat-icon-warning me-3">
                        <i class="ti ti-alert-circle"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 fw-medium">Pending & In-Progress</h6>
                        <h3 class="mb-0 fw-bold" id="stat-pending">-</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card ticket-stat-card stat-card-success h-100 mb-0">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="stat-icon stat-icon-success me-3">
                        <i class="ti ti-check-circle"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 fw-medium">Resolved</h6>
                        <h3 class="mb-0 fw-bold" id="stat-resolved">-</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card ticket-stat-card stat-card-secondary h-100 mb-0">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="stat-icon stat-icon-secondary me-3">
                        <i class="ti ti-lock"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 fw-medium">Closed</h6>
                        <h3 class="mb-0 fw-bold" id="stat-closed">-</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Ticket table -->
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table tbl-product m-0 custom-ticket-table align-middle" id="pc-dt-simple">
                            <thead>
                                <tr class="bg-light-header">
                                    <th class="text-center py-3 ps-4" style="width: 60px;">#</th>
                                    <th class="py-3">Ticket ID</th>
                                    <th class="py-3">Subject</th>
                                    <th class="py-3">Customer</th>
                                    <th class="py-3">Created Date</th>
                                    <th class="py-3">Last Updated</th>
                                    <th class="py-3">Priority</th>
                                    <th class="py-3">Status</th>
                                    <th class="text-center py-3 pe-4" style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i = 1; 
                                    $userId = currentOwnerId();
                                ?>
                                @foreach ($tickets as $val)
                                <tr>
                                    <td class="text-center ps-4 fw-medium text-muted">{{ $i++ }}</td>
                                    <td>
                                        <span class="fw-bold">
                                        @if(Auth::user()->u_type == 3 || Auth::user()->u_type == 6)
                                        <a href="{{ url('/ticket-response/'.base64_encode($userId).'/'.base64_encode($val->added_by).'/'.base64_encode($val->id)) }}" class="ticket-link text-decoration-none" title="View Ticket Status">
                                            #TKT-{{ sprintf("%05d",$val->id) }}
                                        </a>
                                        @else
                                        <a href="{{ url('/ticket-response/'.base64_encode($userId).'/'.base64_encode($val->compId).'/'.base64_encode($val->id)) }}" class="ticket-link text-decoration-none" title="View Ticket Status">
                                            #TKT-{{ sprintf("%05d",$val->id) }}
                                        </a>
                                        @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-0 fw-semibold text-dark">{{ $val->msg }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-0 text-dark font-14">{{ $val->comp_name }}</h6>
                                                <a class="text-muted f-12 text-hover-primary text-decoration-none" href="mailto:{{ $val->comp_email }}">{{ $val->comp_email }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-muted font-14">{{ date('d-m-Y H:i A', strtotime($val->created_at)) }}</td>
                                    <td class="text-muted font-14">{{ date('d-m-Y H:i A', strtotime($val->updated_at)) }}</td>
                                    <td>                  
                                      @if( $val->priority == 0 )
                                          <span class="badge-pill-custom badge-pill-low"><span class="dot-indicator bg-primary"></span> Low</span>
                                      @elseif( $val->priority == 1 )
                                          <span class="badge-pill-custom badge-pill-medium"><span class="dot-indicator bg-warning"></span> Medium</span>
                                      @elseif( $val->priority == 2 )
                                          <span class="badge-pill-custom badge-pill-high"><span class="dot-indicator bg-danger"></span> High</span>
                                      @endif
                                    </td>
                                    <td>                  
                                      @if( $val->isActive == 0 )
                                          <span class="badge-pill-custom badge-pill-pending">Pending</span>
                                      @elseif( $val->isActive == 1 )
                                          <span class="badge-pill-custom badge-pill-resolved">Resolved</span>
                                      @elseif( $val->isActive == 2 )
                                          <span class="badge-pill-custom badge-pill-inprogress">In-progress</span>
                                      @elseif( $val->isActive == 3 )
                                          <span class="badge-pill-custom badge-pill-closed">Closed</span>
                                      @endif
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex justify-content-center">
                                            @if(Auth::user()->u_type == 3 || Auth::user()->u_type == 6)
                                            <a href="{{ url('/ticket-response/'.base64_encode($userId).'/'.base64_encode($val->added_by).'/'.base64_encode($val->id)) }}" class="btn-action-view" data-bs-toggle="tooltip" title="View Ticket">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            @else
                                            <a href="{{ url('/ticket-response/'.base64_encode($userId).'/'.base64_encode($val->compId).'/'.base64_encode($val->id)) }}" class="btn-action-view" data-bs-toggle="tooltip" title="View Ticket">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- Modal -->
<div class="modal fade" id="msgModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form id="ticketForm">				
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark font-20">Create Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body py-4">
					<div class="message-container"></div>
					<div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="msg" id="msg" maxlength="50" class="form-control rounded-3" placeholder="Enter ticket subject" required >
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Message <span class="text-danger">*</span></label>
                        <textarea name="chat_message" id="chat_message" class="form-control rounded-3" rows="4" placeholder="Detail your issue here..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Priority <span class="text-danger">*</span></label>
                        <select name="priority" class="form-select rounded-3" required>
                            <option value="">Select Priority</option>
                            <option value="0">Low</option>
                            <option value="1">Medium</option>
                            <option value="2">High</option>
                        </select>
                    </div>

                    <div id="formError" class="text-danger mt-2"></div>
                </div>

                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success rounded-3 px-4 shadow-sm">Submit Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Ticket Stat Cards styling */
.ticket-stat-card {
    border: none;
    border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    position: relative;
    border: 1px solid #f1f5f9;
}
.ticket-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
}
.ticket-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
}
.stat-card-primary::before { background: #4f46e5; }
.stat-card-warning::before { background: #f59e0b; }
.stat-card-success::before { background: #10b981; }
.stat-card-secondary::before { background: #6b7280; }

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.stat-icon-primary { background: rgba(79, 70, 229, 0.08); color: #4f46e5; }
.stat-icon-warning { background: rgba(245, 158, 11, 0.08); color: #f59e0b; }
.stat-icon-success { background: rgba(16, 185, 129, 0.08); color: #10b981; }
.stat-icon-secondary { background: rgba(107, 114, 128, 0.08); color: #6b7280; }

/* Custom Badge Styling */
.badge-pill-custom {
    padding: 6px 14px;
    border-radius: 50rem;
    font-weight: 600;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    letter-spacing: 0.3px;
}
.dot-indicator {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
}
.badge-pill-low { background: rgba(79, 70, 229, 0.08); color: #4f46e5; border: 1px solid rgba(79, 70, 229, 0.15); }
.badge-pill-medium { background: rgba(245, 158, 11, 0.08); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.15); }
.badge-pill-high { background: rgba(239, 68, 68, 0.08); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.15); }

.badge-pill-pending { background: rgba(239, 68, 68, 0.08); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.15); }
.badge-pill-inprogress { background: rgba(245, 158, 11, 0.08); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.15); }
.badge-pill-resolved { background: rgba(16, 185, 129, 0.08); color: #059669; border: 1px solid rgba(16, 185, 129, 0.15); }
.badge-pill-closed { background: rgba(107, 114, 128, 0.08); color: #4b5563; border: 1px solid rgba(107, 114, 128, 0.15); }

/* Table enhancements */
.bg-light-header {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.custom-ticket-table th {
    font-weight: 600;
    color: #475569;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.custom-ticket-table td {
    padding: 16px 12px !important;
}
.custom-ticket-table tbody tr {
    transition: background-color 0.2s ease;
}
.custom-ticket-table tbody tr:hover {
    background-color: #f8fafc;
}
.ticket-link {
    color: #4f46e5;
    font-weight: 600;
    transition: color 0.2s;
}
.ticket-link:hover {
    color: #312e81;
}

/* Action button view */
.btn-action-view {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #4f46e5;
    background: rgba(79, 70, 229, 0.08);
    border: none;
    transition: all 0.2s;
    text-decoration: none;
}
.btn-action-view:hover {
    color: #ffffff;
    background: #4f46e5;
}

.font-14 {
    font-size: 14px;
}
</style>

<script>
	$(document).ready(function () {
        // Fetch real-time statistics
        function loadTicketStats() {
            $.ajax({
                url: "{{ url('/customer-ticket-stats') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if(data) {
                        $('#stat-total').text(data.total || 0);
                        const openAndProgress = (parseInt(data.open) || 0) + (parseInt(data.running) || 0);
                        $('#stat-pending').text(openAndProgress);
                        $('#stat-resolved').text(data.resolved || 0);
                        $('#stat-closed').text(data.closed || 0);
                    }
                },
                error: function() {
                    console.log("Failed to load ticket statistics.");
                }
            });
        }
        
        loadTicketStats();

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
    });
</script>
@endsection