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
                        <li class="breadcrumb-item active" aria-current="page">Customer List</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead>
                            <tr style="background-color: #cbcbcb;">
                                <th class="text-end">#</th>
                                <th>Customer ID</th>
                                <th>Name</th>
                                <th>Contact Number</th>
                                <th>Joined Through</th>
                                <th>Join Date</th>
                                <th>Subscribed Plan</th>
                                <th>Plan Duration</th>
                                <th>Assigned CA</th>
                                <th>Verify Status</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        {{-- <tbody>
                            <?php $i = 1; ?>
							@foreach ($users as $user)
                            <tr>
                                <td class="text-end"><?php echo $i++; ?></td>
                                <td><span class="text-muted text-hover-primary">CUST{{$user->id}}</span></td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="mb-1">{{ $user->comp_name }}</h6>
                                            <a class="text-muted f-12 text-hover-primary" href="mailto:john@example.com">{{ $user->comp_email }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td><a class="text-muted text-hover-primary" href="#">+91 {{ $user->comp_phone }}</a></td>
                                <td><span class="text-muted text-hover-primary">{{ $user->assignCa }}</span></td>
                                <td><span class="text-muted text-hover-primary">{{ date('d-m-Y', strtotime($user->created_at)) }}</span></td>
                                <td><span class="text-muted text-hover-primary">Basic Plan</span></td>
                                <td><span class="text-muted text-hover-primary">Monthly</span></td>
                                <td><span class="text-muted text-hover-primary">Rishi Basu</span></td>
                                <td>
                                    <span class="badge bg-success">Verified</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a href="{{ url('/customer-details/'.base64_encode($user->id)) }}" class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Message">
                                                <a href="#" class="avtar avtar-xs btn-link-warning btn-pc-default message-customer-btn" data-id="1">
                                                    <i class="ph-duotone ph-chat-circle-text f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Deactivate">
                                                <a href="#" data-id="1" data-status="0" class="status-change avtar avtar-xs btn-link-danger btn-pc-default status_update" data-bs-toggle="modal" data-bs-target="#customer_status_update">
                                                    <i class="ti ti-bell-off f-18"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>                            
                            @endforeach
                        </tbody> --}}
                        <tbody>
                        @php $i = 1; @endphp

                        @foreach ($users as $user)

                        <tr>

                            <td class="text-end">{{ $i++ }}</td>

                            <td>
                                <span class="text-muted text-hover-primary">
                                    CUST{{ $user->id }}
                                </span>
                            </td>

                            <td>
                                <div class="row align-items-center">

                                    <div class="col-auto pe-0">

                                        @if(!empty($user->comp_logo))

                                            <img src="{{ asset('storage/profile/'.$user->comp_logo) }}"
                                                class="wid-40 rounded"
                                                alt="logo">

                                        @else

                                            <div class="avtar avtar-s bg-light-primary">
                                                <span class="f-16 text-primary">
                                                    {{ strtoupper(substr($user->comp_name, 0, 1)) }}
                                                </span>
                                            </div>

                                        @endif

                                    </div>

                                    <div class="col">
                                        <h6 class="mb-1">
                                            {{ $user->comp_name }}
                                        </h6>

                                        <a class="text-muted f-12 text-hover-primary"
                                        href="mailto:{{ $user->comp_email }}">

                                            {{ $user->comp_email }}
                                        </a>
                                    </div>

                                </div>
                            </td>

                            <td>
                                <a class="text-muted text-hover-primary" href="#">
                                    +91 {{ $user->comp_phone }}
                                </a>
                            </td>

                            <td>
                                @if(!empty($user->assignCa))
                                    <span class="badge bg-light-primary text-primary">
                                        {{ $user->assignCa }}
                                    </span>
                                @else
                                    <span class="badge bg-light-secondary text-secondary">
                                        Self
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="text-muted text-hover-primary">
                                    {{ !empty($user->created_at) ? date('d-m-Y', strtotime($user->created_at)) : '-' }}
                                </span>
                            </td>

                            <td>

                                @if(!empty($user->plan_type))

                                    <div class="d-flex flex-column">

                                        <span class="fw-semibold text-dark">
                                            {{ ucfirst($user->plan_title) }}
                                        </span>

                                        <small class="text-muted mt-1">
                                            <span class="badge bg-light-success text-success">
                                                {{ ucfirst($user->plan_type) }}
                                            </span>
                                        </small>

                                    </div>

                                @else

                                    <span class="badge bg-light-danger text-danger">
                                        No Active Plan
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if(!empty($user->start_at) && !empty($user->end_at))

                                    <small class="d-block">
                                        {{ date('d M Y', strtotime($user->start_at)) }}
                                    </small>

                                    <small class="text-muted">
                                        To
                                        {{ date('d M Y', strtotime($user->end_at)) }}
                                    </small>

                                @else

                                    <span class="text-muted">-</span>

                                @endif

                            </td>

                            <td>

                                @if(!empty($user->assignCa))

                                    <span class="badge bg-info">
                                        {{ $user->assignCa }}
                                    </span>

                                @else

                                    <span class="badge bg-warning">
                                        Not Assigned
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if(!empty($user->comp_email))

                                    <span class="badge bg-success">
                                        Verified
                                    </span>

                                @else

                                    <span class="badge bg-warning">
                                        Pending
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if($user->status == 1)

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            <td>

                                <span>
                                    <i class="ti ti-dots-vertical f-20"></i>
                                </span>

                                <div class="prod-action-links">

                                    <ul class="list-inline me-auto mb-0">

                                        <li class="list-inline-item align-bottom"
                                            data-bs-toggle="tooltip"
                                            title="View">

                                            <a href="{{ url('/customer-details/'.base64_encode($user->id)) }}"
                                            class="avtar avtar-xs btn-link-warning btn-pc-default">

                                                <i class="ti ti-eye f-18"></i>
                                            </a>
                                        </li>

                                        <li class="list-inline-item align-bottom"
                                            data-bs-toggle="tooltip"
                                            title="Message">

                                            <a href="#"
                                            class="avtar avtar-xs btn-link-info btn-pc-default message-customer-btn"
                                            data-id="{{ $user->id }}">

                                                <i class="ph-duotone ph-chat-circle-text f-18"></i>
                                            </a>
                                        </li>

                                        <li class="list-inline-item align-bottom"
                                            data-bs-toggle="tooltip"
                                            title="Deactivate">

                                            <a href="#"
                                            data-id="{{ $user->id }}"
                                            data-status="0"
                                            class="status-change avtar avtar-xs btn-link-danger btn-pc-default status_update"
                                            data-bs-toggle="modal"
                                            data-bs-target="#customer_status_update">

                                                <i class="ti ti-bell-off f-18"></i>
                                            </a>
                                        </li>

                                    </ul>

                                </div>

                            </td>

                        </tr>

                        @endforeach

                    </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">Send Message to Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="caMessageForm" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="customer_id" id="customer_id" value="">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="attachment" class="form-label">Attachment</label>
                        <input type="file" class="form-control" id="attachment" name="attachment">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="sendMessageBtn">Send Message</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Open message modal and set Customer ID
        const messageButtons = document.querySelectorAll('.message-customer-btn');
        messageButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const customerId = this.getAttribute('data-id');
                document.getElementById('customer_id').value = customerId;
                var messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
                messageModal.show();
            });
        });

        // Handle send message
        document.getElementById('sendMessageBtn').addEventListener('click', function() {
            // You can add AJAX submission here
            document.getElementById('caMessageForm').submit();
        });
    });
</script>
@endsection