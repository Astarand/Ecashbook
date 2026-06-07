@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Helpdesk</a></li>
                        <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Dashboard</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Support Request Card -->
        <div class="col-md-4">
            <div class="card rounded-3 overflow-hidden shadow-sm">
                <div class="card-body pb-0">
                    <h2 class="mb-1 fw-bold">{{ $ticketCustCounts->total_tickets ?? 0 }}</h2>
                    <h6 class="text-info mb-2">Customer Ticket</h6>
                    <p class="text-muted mb-4">Total number of Customer Query that come in.</p>

                    <div class="position-relative">
                        <canvas id="supportRequestsChart" style="height: 150px; width: 100%;"></canvas>
                    </div>
                </div>
                <div class="bg-info text-white py-3">
                    <div class="row text-center mb-0">
                        <div class="col-4 border-end">
                            <h4 class="mb-1 text-white">{{ $ticketCustCounts->open_tickets ?? 0 }}</h4>
                            <p class="mb-0 text-white-50 small">Open</p>
                        </div>
                        <div class="col-4 border-end">
                            <h4 class="mb-1 text-white">{{ $ticketCustCounts->running_tickets ?? 0 }}</h4>
                            <p class="mb-0 text-white-50 small">Running</p>
                        </div>
                        <div class="col-4">
                            <h4 class="mb-1 text-white">{{ $ticketCustCounts->resolved_tickets ?? 0 }}</h4>
                            <p class="mb-0 text-white-50 small">Solved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agent Response Card -->
        <div class="col-md-4">
            <div class="card rounded-3 overflow-hidden shadow-sm">
                <div class="card-body pb-0">
                    <h2 class="mb-1 fw-bold">{{ $ticketCaCounts->total_tickets ?? 0 }}</h2>
                    <h6 class="text-info mb-2">CA Tickets</h6>
                    <p class="text-muted mb-4">Total number of CA Query that come in.</p>

                    <div class="position-relative">
                        <canvas id="agentResponseChart" style="height: 150px; width: 100%;"></canvas>
                    </div>
                </div>
                <div class="bg-info text-white py-3">
                    <div class="row text-center mb-0">
                        <div class="col-4 border-end">
                            <h4 class="mb-1 text-white">{{ $ticketCaCounts->open_tickets ?? 0 }}</h4>
                            <p class="mb-0 text-white-50 small">Open</p>
                        </div>
                        <div class="col-4 border-end">
                            <h4 class="mb-1 text-white">{{ $ticketCaCounts->running_tickets ?? 0 }}</h4>
                            <p class="mb-0 text-white-50 small">Running</p>
                        </div>
                        <div class="col-4">
                            <h4 class="mb-1 text-white">{{ $ticketCaCounts->resolved_tickets ?? 0 }}</h4>
                            <p class="mb-0 text-white-50 small">Solved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Resolved Card -->
        <div class="col-md-4">
            <div class="card rounded-3 overflow-hidden shadow-sm">
                <div class="card-body pb-0">
                    <h2 class="mb-1 fw-bold">{{ $ticketTotalCounts->total_tickets ?? 0 }}</h2>
                    <h6 class="text-success mb-2">Total Support Resolved</h6>
                    <p class="text-muted mb-4">Total number of Ticket Resolved.</p>

                    <div class="position-relative">
                        <canvas id="supportResolvedChart" style="height: 150px; width: 100%;"></canvas>
                    </div>
                </div>
                <div class="bg-success text-white py-3">
                    <div class="row text-center mb-0">
                        <div class="col-4 border-end">
                            <h4 class="mb-1 text-white">{{ $ticketTotalCounts->total_tickets ?? 0 }}</h4>
                            <p class="mb-0 text-white-50 small">Total Ticket</p>
                        </div>
                        <div class="col-4 border-end">
                            <h4 class="mb-1 text-white">{{ $ticketTotalCounts->ca_tickets ?? 0 }}</h4>
                            <p class="mb-0 text-white-50 small">Customer Ticket</p>
                        </div>
                        <div class="col-4">
                            <h4 class="mb-1 text-white">{{ $ticketTotalCounts->customer_tickets ?? 0 }}</h4>
                            <p class="mb-0 text-white-50 small">CA Ticket</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Satisfaction Chart -->
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Customer Review & Rating</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Here is the customer review and rating.
                    </p>
                    <div class="row">
                        <div class="col-md-8">
                            <canvas id="customerSatisfactionChart" style="height: 250px; width: 100%;"></canvas>
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="bg-primary d-inline-block rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                    <span>Very Satisfied (35.7%)</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="bg-info d-inline-block rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                    <span>Satisfied (26.4%)</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="bg-warning d-inline-block rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                    <span>Neutral (21.5%)</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="bg-danger d-inline-block rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                    <span>Poor (16.4%)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Activity -->
        <div class="col-lg-4 col-md-12">
            <div class="card rounded-3 overflow-hidden shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Latest Activity</h5>
                    <div class="dropdown">
                        <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-dots-vertical f-18"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Today</a></li>
                            <li><a class="dropdown-item" href="#">Yesterday</a></li>
                            <li><a class="dropdown-item" href="#">Last Week</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="activity-scroll" style="max-height: 390px; overflow-y: auto;">
                        <ul class="list-group list-group-flush">
                            <!-- Closed Tickets -->
							@foreach ($recentTickets as $ticket)
								<li class="list-group-item d-flex align-items-center justify-content-between border-0">
									<div class="d-flex align-items-center">
										@if( $ticket->isActive ==0 )
										<div class="avatar avatar-sm bg-light-primary rounded me-2">
											<i class="ti ti-check text-primary"></i>
										</div>
										@elseif( $ticket->isActive ==1 )
										<div class="avatar avatar-sm bg-light-success rounded me-2">
											<i class="ti ti-check text-success"></i>
										</div>
										@elseif( $ticket->isActive ==2 )
										<div class="avatar avatar-sm bg-light-warning rounded me-2">
											<i class="ti ti-clock text-warning"></i>
										</div>
										@endif
										<span>{{ $ticket->msg }}</span>
									</div>

									<!-- Time ago -->
									<small class="text-muted">
										{{ \Carbon\Carbon::parse($ticket->updated_at)->diffForHumans() }}
									</small>
								</li>
							@endforeach
                            <!--<li class="list-group-item d-flex align-items-center justify-content-between border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-light-success rounded me-2">
                                        <i class="ti ti-check text-success"></i>
                                    </div>
                                    <span>Ticket #1024 has been closed</span>
                                </div>
                                <small class="text-muted">Just Now</small>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-light-success rounded me-2">
                                        <i class="ti ti-check text-success"></i>
                                    </div>
                                    <span>Support request #789 resolved</span>
                                </div>
                                <small class="text-muted">2h ago</small>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-light-success rounded me-2">
                                        <i class="ti ti-check text-success"></i>
                                    </div>
                                    <span>Ticket #532 completed by agent</span>
                                </div>
                                <small class="text-muted">4h ago</small>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-light-success rounded me-2">
                                        <i class="ti ti-check text-success"></i>
                                    </div>
                                    <span>CA issue #298 closed successfully</span>
                                </div>
                                <small class="text-muted">6h ago</small>
                            </li>

                           
                            <li class="list-group-item d-flex align-items-center justify-content-between border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-light-primary rounded me-2">
                                        <i class="ti ti-plus text-primary"></i>
                                    </div>
                                    <span>New ticket #1095 from John Doe</span>
                                </div>
                                <small class="text-muted">3h ago</small>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-light-primary rounded me-2">
                                        <i class="ti ti-plus text-primary"></i>
                                    </div>
                                    <span>New CA request #453 received</span>
                                </div>
                                <small class="text-muted">5h ago</small>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-light-primary rounded me-2">
                                        <i class="ti ti-plus text-primary"></i>
                                    </div>
                                    <span>Customer support ticket #678 created</span>
                                </div>
                                <small class="text-muted">8h ago</small>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-light-primary rounded me-2">
                                        <i class="ti ti-plus text-primary"></i>
                                    </div>
                                    <span>New billing support request #321</span>
                                </div>
                                <small class="text-muted">10h ago</small>
                            </li>

                            
                            <li class="list-group-item d-flex align-items-center justify-content-between border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-light-warning rounded me-2">
                                        <i class="ti ti-clock text-warning"></i>
                                    </div>
                                    <span>Ticket #875 is being processed</span>
                                </div>
                                <small class="text-muted">1d ago</small>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-light-warning rounded me-2">
                                        <i class="ti ti-clock text-warning"></i>
                                    </div>
                                    <span>CA ticket #642 in progress</span>
                                </div>
                                <small class="text-muted">2d ago</small>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-light-warning rounded me-2">
                                        <i class="ti ti-clock text-warning"></i>
                                    </div>
                                    <span>Customer issue #456 under review</span>
                                </div>
                                <small class="text-muted">3d ago</small>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-light-warning rounded me-2">
                                        <i class="ti ti-clock text-warning"></i>
                                    </div>
                                    <span>Technical support #777 assigned</span>
                                </div>
                                <small class="text-muted">4d ago</small>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-light-warning rounded me-2">
                                        <i class="ti ti-clock text-warning"></i>
                                    </div>
                                    <span>Ongoing investigation for ticket #123</span>
                                </div>
                                <small class="text-muted">5d ago</small>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-light-warning rounded me-2">
                                        <i class="ti ti-clock text-warning"></i>
                                    </div>
                                    <span>CA request #222 waiting for approval</span>
                                </div>
                                <small class="text-muted">1w ago</small>
                            </li>-->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('page-script')
<!-- Load Chart.js directly from CDN with specific version -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<!-- Debug script to confirm Chart.js is loaded -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM content loaded for ticket management page');
        console.log('Chart.js available:', typeof Chart !== 'undefined');

        // Create a test chart to verify Chart.js functionality
        setTimeout(function() {
            try {
                // Try manually creating a simple chart
                const testCanvas = document.createElement('canvas');
                testCanvas.id = 'testChart';
                testCanvas.style.display = 'none';
                document.body.appendChild(testCanvas);

                const testChart = new Chart(testCanvas, {
                    type: 'line',
                    data: {
                        labels: ['A', 'B', 'C'],
                        datasets: [{
                            data: [1, 2, 3],
                            borderColor: 'blue'
                        }]
                    }
                });

                console.log('Test chart created successfully');
                document.body.removeChild(testCanvas);

                // Now load our real charts
                if (typeof window.initTicketCharts === 'function') {
                    window.initTicketCharts();
                } else {
                    console.error('initTicketCharts function not found, loading ticket-chart.js directly');
                    // Load the chart.js script directly if it wasn't loaded
                    const script = document.createElement('script');
                    script.src = "{{ asset('assets/js/ticket-chart.js') }}?v=" + new Date().getTime();
                    script.onload = function() {
                        console.log('ticket-chart.js loaded manually');
                        if (typeof window.initTicketCharts === 'function') {
                            window.initTicketCharts();
                        }
                    };
                    document.body.appendChild(script);
                }
            } catch (error) {
                console.error('Error creating test chart:', error);
            }
        }, 500);

        // Make activity section scrollable
        const activityContainer = document.querySelector('.activity-scroll');
        if (activityContainer) {
            if (typeof PerfectScrollbar !== 'undefined') {
                new PerfectScrollbar(activityContainer);
            } else {
                console.log('PerfectScrollbar not available, using native scrolling');
            }
        }
    });
</script>
<!-- Load our chart configuration -->
<script src="{{ asset('assets/js/ticket-chart.js') }}?v={{ time() }}"></script>
@endsection