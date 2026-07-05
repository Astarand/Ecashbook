@extends('App.Layout')

@section('container')

<div class="pc-content">

    <!-- Breadcrumb -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/ca-compliances-list') }}">Statutory Compliance</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Statutory Compliance</h2>
                    </div>
                </div>          
                <div class="col-md-8 text-end">
                    <a href="javascript:void(0);" onclick="startCACompliancesTour();" id="start-ca-compliances-tour" class="text-primary d-inline-flex align-items-center gap-1 fw-semibold me-3" style="font-size: 0.95rem; vertical-align: middle;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        @if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4)
        <div class="col-sm-12 pb-4 d-flex justify-content-end">
            <a class="btn btn-primary" id="add-compliance-btn" href="{{ url('/addstatutory') }}">
                <i class="fa fa-plus-circle me-2"></i>Add Compliances / Statutory
            </a>
        </div>
        @endif

        <div class="col-sm-12">
            <div class="card card-body table-card" id="compliances-table-card">

                <!-- Notification Bell -->
                <div class="d-flex justify-content-end mb-3">
                    @if (Auth::user())
                    @php
                        $userId = currentOwnerId();
                        $notifications = Helper::getNotification($userId);
                        $count = count((array)$notifications);
                    @endphp

                    <div class="dropdown" id="notification-bell-dropdown">

                        <a class="dropdown-toggle bell-wrapper" data-bs-toggle="dropdown">
                            <i class="ph-duotone ph-bell bell-icon"></i>
							<span class="noti-badge notiCount">
								{{ $count }}
							</span>
                        </a>

                        <!-- Dropdown -->
                        <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0 notification-dropdown">

                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                <h6 class="mb-0 fw-bold">Notifications</h6>
                                <small class="text-muted notiCount">{{ $count }} New</small>
                            </div>

                            <!-- Body -->
                            <div class="notification-body">
                                <ul class="list-group list-group-flush notification-list">

                                    @if ($count > 0)
                                        @foreach ($notifications as $notiVal)
                                            <li class="list-group-item notification-item"
                                                onclick="openNotification('{{ $notiVal->id ?? '' }}')">

                                                <div class="d-flex gap-2">

                                                    <!-- Avatar -->
                                                    <div>
                                                        <div class="rounded-circle bg-light-primary d-flex align-items-center justify-content-center avatar-box">
                                                            @if(isset($notiVal->avatar) && $notiVal->avatar)
                                                                <img src="{{ asset($notiVal->avatar) }}" class="rounded-circle" width="40">
                                                            @else
                                                                <i class="ph-duotone ph-bell-ringing"></i>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Content -->
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between">
                                                            <h6 class="mb-1">
                                                                {{ $notiVal->noti_title ?? 'Notification' }}
                                                            </h6>
                                                            <small class="text-muted">
                                                                {{ \Carbon\Carbon::parse($notiVal->created_at)->diffForHumans() }}
                                                            </small>
                                                        </div>

                                                        <p class="mb-0 small text-muted">
                                                            {{ $notiVal->msg }}
                                                        </p>
                                                    </div>

                                                </div>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="list-group-item text-center py-4">
                                            <i class="ph-duotone ph-bell-slash fs-2 text-muted"></i>
                                            <p class="mt-2 mb-0 text-muted">No notifications</p>
                                        </li>
                                    @endif

                                </ul>
                            </div>

                            <!-- Footer -->
                            <div class="p-2 border-top">
                                <div class="d-flex gap-2">
                                    <a href="{{ url('/view-all-notification') }}" class="btn btn-sm btn-primary w-100">
                                        View All
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary w-100"
                                            onclick="clearNoti({{ $userId }})">
                                        Mark Read
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table tbl-product" id="pc-dt-simple">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Date</th>
                                <th>Company Name</th>
                                <th>Statutory & Compliance</th>
                                <th>Due Date</th>
                                <th>Message By</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php $i = 1; @endphp

                            @foreach ($statutory as $val)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ date('d-m-Y', strtotime($val->created_at)) }}</td>
                                <td>{{ $val->comp_name }}</td>
                                <td>{{ $val->statutory_doc }}</td>
                                <td>{{ date('d-m-Y', strtotime($val->statutory_due_date)) }}</td>
                                <td>{{ $val->messages_by }}</td>

                                <td>
                                    @if($val->status == 0)
                                        <span class="badge text-bg-danger">Pending</span>
                                    @elseif($val->status == 1)
                                        <span class="badge text-bg-success">Complete</span>
                                    @else
                                        <span class="badge text-bg-warning">On-going</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ url('/chat-response/'.base64_encode($userId).'/'.base64_encode($val->compId).'/'.base64_encode($val->id)) }}" 
                                       class="btn btn-outline-success position-relative">

                                        <i class="ti ti-message-circle"></i>
                                        <span class="mcCircle">{{ count($val->messages) }}</span>
                                    </a>
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

<style>

/* Bell wrapper */
.bell-wrapper {
    position: relative;
    cursor: pointer;
    display: inline-block;
}

/* Bell Icon */
.bell-icon {
    font-size: 30px;
    color: #333;
    transition: 0.2s;
}

/* Hover effect */
.bell-wrapper:hover .bell-icon {
    color: #0d6efd;
    transform: scale(1.1);
}

/* Badge */
.noti-badge {
    position: absolute;
    top: -5px;
    right: -10px;

    min-width: 20px;
    height: 20px;

    background: #ff3b3b;
    color: #fff;

    border-radius: 50%;
    font-size: 11px;
    font-weight: bold;

    display: flex;
    align-items: center;
    justify-content: center;

    box-shadow: 0 0 0 2px #fff;
}

/* Dropdown */
.notification-dropdown {
    width: 350px;
    border-radius: 12px;
}

/* Scroll */
.notification-body {
    max-height: 300px;
    overflow-y: auto;
}

/* Notification item */
.notification-item {
    transition: 0.2s;
    cursor: pointer;
}

.notification-item:hover {
    background: #f5f7fa;
}

/* Avatar */
.avatar-box {
    width: 40px;
    height: 40px;
}

/* Scrollbar */
.notification-body::-webkit-scrollbar {
    width: 5px;
}
.notification-body::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}

</style>

@endsection

@section('page-script')
<script>
    function startCACompliancesTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Statutory Compliance Register',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-shield-check" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Oversee tax filings, corporate compliance registries, and due dates for your onboarded client accounts.</p></div>'
                },
                {
                    element: '#add-compliance-btn',
                    title: 'Record New Compliance',
                    intro: 'Click here to create a new compliance task, assign client companies, specify due dates, and input custom statutory criteria.',
                    skipIfNoElement: true
                },
                {
                    element: '#notification-bell-dropdown',
                    title: 'Notifications Hub',
                    intro: 'Real-time notification indicator for compliance updates, client submissions, and feedback responses.'
                },
                {
                    element: '#compliances-table-card',
                    title: 'Compliances Registry',
                    intro: 'Roster of compliance documents showing the filing date, client name, statutory title, due date, message indicators, and completion status.'
                },
                {
                    element: '.mcCircle',
                    title: 'Client Conversations',
                    intro: 'The message circle indicates unread messages. Click the action button to launch the client communication thread for specific filings.',
                    skipIfNoElement: true
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
        $('#start-ca-compliances-tour').on('click', function(e) {
            e.preventDefault();
            startCACompliancesTour();
        });
    });
</script>
@endsection