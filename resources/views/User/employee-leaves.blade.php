@extends('App.Layout')

@section('styles')
<style>
.legend-color {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 2px;
    margin-right: 5px;
}

.legend-item {
    font-size: 12px;
    margin-right: 15px;
}

.leave-summary-progress {
    height: 6px;
    border-radius: 3px;
}

.table th {
    font-weight: 600;
    font-size: 13px;
    color: #6c757d;
    border-bottom: 2px solid #e9ecef;
}

.badge {
    font-size: 11px;
    padding: 4px 8px;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.form-label {
    font-weight: 500;
    color: #495057;
}

.card-header h5 {
    color: #495057;
    font-weight: 600;
}

.text-muted {
    color: #6c757d !important;
}

.progress {
    background-color: #e9ecef;
}

.chat-avtar {
    position: relative;
}

.wid-90 {
    width: 90px;
    height: 90px;
    object-fit: cover;
}

/* Modal specific styles */
.modal-header.bg-light {
    border-bottom: 1px solid #dee2e6;
}

.modal-body .row {
    margin-bottom: 0;
}

.modal-body .row + .row {
    margin-top: 0.75rem;
}

.modal-body .bg-light {
    background-color: #f8f9fa !important;
    border: 1px solid #e9ecef;
}

.modal-body .bg-light-warning {
    background-color: #fff3cd !important;
    border: 1px solid #ffeaa7;
    color: #856404;
}

.modal-body strong.text-muted {
    font-weight: 600;
    font-size: 0.9rem;
}

.spinner-border {
    width: 2rem;
    height: 2rem;
}
</style>
@endsection

@section('container')

<!-- [ Main Content ] start -->
<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.EmployeeList') }}">Employees</a></li>
                        <li class="breadcrumb-item" aria-current="page">Leave Management</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-employee-leaves-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title d-flex align-items-center justify-content-between">
                        <h2 class="mb-0">Leave Management - {{ $employee->name }}</h2>
                        <a href="{{ route('view_user_employee', $encodedId) }}" class="btn btn-outline-secondary">
                            <i class="ph-duotone ph-arrow-left me-2"></i>Back to Employee
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Employee Info Card -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="chat-avtar d-inline-flex mx-auto mb-3">
                        @php
                        $profileImg = $employee->profile_img
                        ? asset('storage/user_employee/' . $employee->profile_img)
                        : asset('storage/profile/e-cashbook.png');
                        @endphp
                        <img class="rounded-circle img-fluid wid-90 img-thumbnail" src="{{ $profileImg }}"
                            alt="User image">
                    </div>
                    <h5 class="mb-1">{{ $employee->name }}</h5>
                    <span class="badge bg-light-info text-dark mb-2" style="font-size: 13px; padding: 6px 14px;">
                        Employee ID: {{ $employee->employee_id }}
                    </span>
                    <p class="text-muted mb-0">{{ $employee->dept_name }}</p>
                    <p class="text-muted mb-1">{{ $employee->designation_name }}</p>
                </div>
            </div>

            <!-- Leave Summary Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Leave Summary {{ $currentYear }}</h5>
                </div>
                <div class="card-body">
                    @if($leaveSummary->count() > 0)
                        @foreach($leaveSummary as $summary)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-capitalize">{{ ucfirst($summary->leave_type) }} Leave</span>
                                <span class="badge bg-primary">{{ $summary->approved_days }} days</span>
                            </div>
                            <div class="progress leave-summary-progress">
                                <div class="progress-bar bg-success" style="width: {{ $summary->approved_days > 0 ? min(($summary->approved_days / 30) * 100, 100) : 0 }}%"></div>
                            </div>
                            <small class="text-muted">
                                Approved: {{ $summary->approved_days }} |
                                Pending: {{ $summary->pending_days }} |
                                Rejected: {{ $summary->rejected_days }}
                            </small>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="ph-duotone ph-calendar-blank f-32 text-muted mb-2"></i>
                            <p class="text-muted mb-0">No leave records found for {{ $currentYear }}</p>
                            <small class="text-muted">Leave requests will appear here once submitted</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Leave Details -->
        <div class="col-lg-8">
            <!-- Leave Statistics Cards -->
           <div class="row mb-4 g-3">
                <!-- Approved -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                    <i class="ph-duotone ph-calendar-check text-primary" style="font-size: 2rem;"></i>
                                </div>
                                <span class="badge bg-success rounded-pill px-3 py-2">Active</span>
                            </div>
                            <h2 class="mb-1 fw-bold text-primary">{{ $leaves->where('status', 'approved')->count() }}</h2>
                            <p class="mb-0 text-muted fw-medium">Approved Leaves</p>
                            <div class="progress mt-3" style="height: 4px;">
                                <div class="progress-bar bg-primary" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                    <i class="ph-duotone ph-clock text-warning" style="font-size: 2rem;"></i>
                                </div>
                                <span class="badge bg-warning rounded-pill px-3 py-2">Wait</span>
                            </div>
                            <h2 class="mb-1 fw-bold text-warning">{{ $leaves->where('status', 'pending')->count() }}</h2>
                            <p class="mb-0 text-muted fw-medium">Pending Leaves</p>
                            <div class="progress mt-3" style="height: 4px;">
                                <div class="progress-bar bg-warning" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rejected -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                                    <i class="ph-duotone ph-x-circle text-danger" style="font-size: 2rem;"></i>
                                </div>
                                <span class="badge bg-danger rounded-pill px-3 py-2">Denied</span>
                            </div>
                            <h2 class="mb-1 fw-bold text-danger">{{ $leaves->where('status', 'rejected')->count() }}</h2>
                            <p class="mb-0 text-muted fw-medium">Rejected Leaves</p>
                            <div class="progress mt-3" style="height: 4px;">
                                <div class="progress-bar bg-danger" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Days -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                    <i class="ph-duotone ph-calendar text-info" style="font-size: 2rem;"></i>
                                </div>
                                <span class="badge bg-info rounded-pill px-3 py-2">Total</span>
                            </div>
                            <h2 class="mb-1 fw-bold text-info">{{ $leaves->where('status', 'approved')->sum('total_days') }}</h2>
                            <p class="mb-0 text-muted fw-medium">Total Days</p>
                            <div class="progress mt-3" style="height: 4px;">
                                <div class="progress-bar bg-info" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leave Requests Table -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Leave Requests</h5>
                        <div class="d-flex align-items-center">
                            <label class="form-label me-2 mb-0">Filter by Year:</label>
                            <select class="form-select" id="yearFilter" style="width: auto;">
                                <option value="all">All Years</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if($leaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover" id="leavesTable">
                                <thead>
                                    <tr>
                                        <th>Leave Type</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Days</th>
                                        <th>Status</th>
                                        <th>Reason</th>
                                        <th>Applied On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaves as $leave)
                                    <tr data-year="{{ date('Y', strtotime($leave->start_date)) }}">
                                        <td>
                                            <span class="badge bg-light-primary text-capitalize">
                                                {{ ucfirst($leave->leave_type) }} Leave
                                            </span>
                                        </td>
                                        <td>{{ date('d M Y', strtotime($leave->start_date)) }}</td>
                                        <td>{{ date('d M Y', strtotime($leave->end_date)) }}</td>
                                        <td>{{ $leave->total_days }}</td>
                                        <td>
                                            @if($leave->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($leave->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif


                                        </td>
                                        <td>
                                            <span data-bs-toggle="tooltip" title="{{ $leave->reason }}">
                                                {{ Str::limit($leave->reason, 30) }}
                                            </span>
                                        </td>
                                        <td>{{ date('d M Y', strtotime($leave->created_at)) }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary btn-sm" onclick="viewLeave({{ $leave->id }})">
                                                    <i class="ph-duotone ph-eye"></i>
                                                </button>
                                                @if($leave->status == 'pending')
                                                    <button class="btn btn-outline-success btn-sm"
                                                        onclick="updateLeaveStatus({{ $leave->id }}, 'approved')"
                                                        title="Approve">
                                                        <i class="ph-duotone ph-check"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger btn-sm"
                                                        onclick="updateLeaveStatus({{ $leave->id }}, 'rejected')"
                                                        title="Reject">
                                                        <i class="ph-duotone ph-x"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div id="noDataMessage" class="text-center py-4">
                            <i class="ph-duotone ph-calendar-x f-48 text-muted mb-3"></i>
                            <h5 class="text-muted">No Leave Requests Found</h5>
                            <p class="text-muted">This employee hasn't submitted any leave requests yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



<!-- View Leave Modal -->
<div class="modal fade" id="viewLeaveModal" tabindex="-1" aria-labelledby="viewLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="viewLeaveModalLabel">
                    <i class="ph-duotone ph-calendar-check me-2"></i>Leave Request Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="leaveDetailsContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading leave details...</p>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        // Initialize the table with current year filter on page load
        filterTableByYear({{ $currentYear }});

        // Year filter functionality
        $('#yearFilter').change(function() {
            var selectedYear = $(this).val();
            filterTableByYear(selectedYear);
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Function to filter table by year
    function filterTableByYear(selectedYear) {
        var visibleRows = 0;
        var totalRows = $('#leavesTable tbody tr').length;

        // If there are no rows at all, don't do anything (let the server-side message show)
        if (totalRows === 0) {
            return;
        }

        $('#leavesTable tbody tr').each(function() {
            var rowYear = $(this).data('year');
            if (selectedYear === '' || selectedYear === 'all' || rowYear == selectedYear) {
                $(this).show();
                visibleRows++;
            } else {
                $(this).hide();
            }
        });

        // Show/hide the "no data" message based on visible rows
        if (visibleRows === 0 && totalRows > 0) {
            // Create dynamic message for filtered results
            if ($('#filteredNoDataMessage').length === 0) {
                $('#leavesTable').after(`
                    <div id="filteredNoDataMessage" class="text-center py-4">
                        <i class="ph-duotone ph-calendar-blank f-48 text-muted mb-3"></i>
                        <h5 class="text-muted">No Leave Requests Found</h5>
                        <p class="text-muted">No leave requests found for the selected year (${selectedYear}).</p>
                        <small class="text-muted">Try selecting a different year or "All Years" to see more results.</small>
                    </div>
                `);
            } else {
                $('#filteredNoDataMessage p').text(`No leave requests found for the selected year (${selectedYear}).`);
            }
            $('#leavesTable').hide();
            $('#filteredNoDataMessage').show();
            $('#noDataMessage').hide();
        } else {
            $('#leavesTable').show();
            $('#filteredNoDataMessage').hide();
            $('#noDataMessage').hide();
        }
    }

    // Global functions that can be called from onclick handlers
    function viewLeave(leaveId) {
        // Show modal with loading state
        $('#viewLeaveModal').modal('show');
        $('#leaveDetailsContent').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading leave details...</p>
            </div>
        `);

        $.ajax({
            url: '{{ route("user.getLeave", ":id") }}'.replace(':id', leaveId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const leave = response.data;
                    const leaveDetailsHtml = generateLeaveDetailsHtml(leave);
                    $('#leaveDetailsContent').html(leaveDetailsHtml);
                } else {
                    $('#leaveDetailsContent').html(`
                        <div class="text-center py-4">
                            <i class="ph-duotone ph-warning-circle f-48 text-warning mb-3"></i>
                            <h5 class="text-muted">Error Loading Details</h5>
                            <p class="text-muted">${response.message}</p>
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                let errorMessage = 'Unable to load leave details. Please try again.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                $('#leaveDetailsContent').html(`
                    <div class="text-center py-4">
                        <i class="ph-duotone ph-x-circle f-48 text-danger mb-3"></i>
                        <h5 class="text-muted">Connection Error</h5>
                        <p class="text-muted">${errorMessage}</p>
                        <button class="btn btn-outline-primary btn-sm" onclick="viewLeave(${leaveId})">
                            <i class="ph-duotone ph-arrow-clockwise me-1"></i>Retry
                        </button>
                    </div>
                `);
            }
        });
    }

    function generateLeaveDetailsHtml(leave) {
        const statusBadge = getStatusBadge(leave.status);
        const leaveTypeFormatted = leave.leave_type.charAt(0).toUpperCase() + leave.leave_type.slice(1);

        return `
            <div class="p-3">
                <!-- Status Badge -->
                <div class="text-center mb-3">
                    ${statusBadge}
                </div>

                <!-- Employee Info -->
                <div class="rounded-3 p-2 mb-3" style="background: #f8f9fa;">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                            <i class="ph-duotone ph-user-circle text-primary" style="font-size: 1.1rem;"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block" style="font-size: 0.65rem;">EMPLOYEE</small>
                            <span class="fw-bold text-dark" style="font-size: 0.9rem;">${leave.emp_name || 'N/A'}</span>
                        </div>
                    </div>
                </div>

                <!-- Leave Info Grid -->
                <div class="border rounded-3 p-3 mb-3">
                    <h6 class="fw-bold mb-3 text-uppercase d-flex align-items-center" style="font-size: 0.7rem; letter-spacing: 0.5px; color: #6c757d;">
                        <i class="ph-duotone ph-info me-1" style="font-size: 1rem;"></i>Leave Details
                    </h6>

                    <div class="row g-2">
                        <!-- Leave Type -->
                        <div class="col-6">
                            <small class="text-muted d-block mb-1" style="font-size: 0.65rem;">Type</small>
                            <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size: 0.75rem;">
                                ${leaveTypeFormatted}
                            </span>
                        </div>

                        <!-- Duration -->
                        <div class="col-6">
                            <small class="text-muted d-block mb-1" style="font-size: 0.65rem;">Duration</small>
                            <span class="badge bg-info" style="font-size: 0.75rem;">
                                ${leave.total_days} Day(s)
                            </span>
                        </div>

                        <!-- Start Date -->
                        <div class="col-6">
                            <small class="text-muted d-block mb-1" style="font-size: 0.65rem;">Start Date</small>
                            <span class="fw-semibold text-dark" style="font-size: 0.85rem;">${formatDate(leave.start_date)}</span>
                        </div>

                        <!-- End Date -->
                        <div class="col-6">
                            <small class="text-muted d-block mb-1" style="font-size: 0.65rem;">End Date</small>
                            <span class="fw-semibold text-dark" style="font-size: 0.85rem;">${formatDate(leave.end_date)}</span>
                        </div>

                        <!-- Reason -->
                        <div class="col-12 mt-2">
                            <small class="text-muted d-block mb-1" style="font-size: 0.65rem;">Reason</small>
                            <div class="p-2 rounded" style="background: #f8f9fa; font-size: 0.85rem;">
                                ${leave.reason || 'No reason provided'}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Remarks (if any) -->
                ${leave.admin_remarks ? `
                <div class="rounded-3 p-2 mb-3" style="background: #fff3cd; border-left: 3px solid #ffc107;">
                    <div class="d-flex align-items-start">
                        <i class="ph-duotone ph-warning text-warning me-2" style="font-size: 1rem;"></i>
                        <div>
                            <small class="fw-bold text-warning d-block mb-1" style="font-size: 0.65rem;">ADMIN REMARKS</small>
                            <p class="mb-0" style="font-size: 0.85rem; color: #856404;">${leave.admin_remarks}</p>
                        </div>
                    </div>
                </div>
                ` : ''}

                <!-- Timeline -->
                <div class="rounded-3 p-3" style="background: #f8f9fa;">
                    <h6 class="fw-bold mb-2 text-uppercase d-flex align-items-center" style="font-size: 0.7rem; letter-spacing: 0.5px; color: #6c757d;">
                        <i class="ph-duotone ph-clock-clockwise me-1" style="font-size: 1rem;"></i>Timeline
                    </h6>

                    <div class="d-flex align-items-start mb-2">
                        <div class="bg-primary rounded-circle p-1 me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                            <i class="ph-duotone ph-paper-plane-tilt text-white" style="font-size: 0.8rem;"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block" style="font-size: 0.65rem;">Submitted</small>
                            <span class="fw-semibold text-dark" style="font-size: 0.8rem;">${formatDateTime(leave.created_at)}</span>
                        </div>
                    </div>

                    ${leave.approved_at ? `
                    <div class="d-flex align-items-start">
                        <div class="bg-${leave.status === 'approved' ? 'success' : 'danger'} rounded-circle p-1 me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                            <i class="ph-duotone ph-${leave.status === 'approved' ? 'check' : 'x'} text-white" style="font-size: 0.8rem;"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block" style="font-size: 0.65rem;">${leave.status === 'approved' ? 'Approved' : 'Rejected'}</small>
                            <span class="fw-semibold text-dark" style="font-size: 0.8rem;">${formatDateTime(leave.approved_at)}</span>
                        </div>
                    </div>
                    ` : ''}
                </div>
            </div>

            <!-- Compact Footer -->
            ${leave.status === 'pending' ? `
            <div class="modal-footer border-0 p-2" style="background: #f8f9fa;">
                <button class="btn btn-sm btn-light border px-3" data-bs-dismiss="modal">
                    <i class="ph-duotone ph-x me-1"></i>Close
                </button>
                <button class="btn btn-sm btn-danger px-3" onclick="updateLeaveStatusFromModal(${leave.id}, 'rejected')">
                    <i class="ph-duotone ph-x me-1"></i>Reject
                </button>
                <button class="btn btn-sm btn-success px-3" onclick="updateLeaveStatusFromModal(${leave.id}, 'approved')">
                    <i class="ph-duotone ph-check me-1"></i>Approve
                </button>
            </div>
            ` : `
            <div class="modal-footer border-0 p-2" style="background: #f8f9fa;">
                <button class="btn btn-sm btn-light border px-4" data-bs-dismiss="modal">
                    <i class="ph-duotone ph-x me-1"></i>Close
                </button>
            </div>
            `}
        `;
}

    // Compact Status Badge
    function getStatusBadge(status) {
        const statusConfig = {
            'approved': { class: 'bg-success', icon: 'ph-check-circle', text: 'Approved' },
            'pending': { class: 'bg-warning', icon: 'ph-clock', text: 'Pending' },
            'rejected': { class: 'bg-danger', icon: 'ph-x-circle', text: 'Rejected' }
        };

        const config = statusConfig[status.toLowerCase()] || statusConfig['pending'];

        return `
            <span class="badge ${config.class} px-3 py-2 d-inline-flex align-items-center" style="font-size: 0.85rem; border-radius: 20px;">
                <i class="ph-duotone ${config.icon} me-1" style="font-size: 0.95rem;"></i>
                ${config.text}
            </span>
        `;
}

// Compact Date Format
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}

// Compact DateTime Format
function formatDateTime(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    const dateStr = date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short' });
    const timeStr = date.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
    return `${dateStr}, ${timeStr}`;
}


    function getStatusBadge(status) {
        switch(status) {
            case 'pending':
                return '<span class="badge bg-warning"><i class="ph-duotone ph-clock me-1"></i>Pending</span>';
            case 'approved':
                return '<span class="badge bg-success"><i class="ph-duotone ph-check me-1"></i>Approved</span>';
            case 'rejected':
                return '<span class="badge bg-danger"><i class="ph-duotone ph-x me-1"></i>Rejected</span>';
            default:
                return '<span class="badge bg-secondary">Unknown</span>';
        }
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function formatDateTime(dateTimeString) {
        const date = new Date(dateTimeString);
        return date.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function updateLeaveStatusFromModal(leaveId, status) {
        $('#viewLeaveModal').modal('hide');
        updateLeaveStatus(leaveId, status);
    }

    function updateLeaveStatus(leaveId, status) {
        const statusText = status.charAt(0).toUpperCase() + status.slice(1);
        const statusColor = status === 'approved' ? '#28a745' : '#dc3545';
        const statusIcon = status === 'approved' ? 'success' : 'warning';

        Swal.fire({
            title: `${statusText} Leave Request?`,
            text: `Are you sure you want to ${status} this leave request?`,
            icon: statusIcon,
            showCancelButton: true,
            confirmButtonColor: statusColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${statusText} it!`,
            cancelButtonText: 'Cancel',
            input: 'textarea',
            inputLabel: `Remarks for ${status} (Optional)`,
            inputPlaceholder: `Please provide remarks for ${status} this leave...`,
            inputAttributes: {
                'aria-label': `Remarks for ${status}`
            },
            showLoaderOnConfirm: true,
            preConfirm: (remarks) => {
                return fetch(`/update-leave-status/${leaveId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        status: status,
                        admin_remarks: remarks || ''
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Failed to update leave status');
                    }
                    return data;
                })
                .catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error.message}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                showToast(result.value.message, 'success');
                setTimeout(() => location.reload(), 1500);
            }
        }).catch((error) => {
            console.error('Error:', error);
            showToast('An error occurred while updating leave status', 'error');
        });
    }



    function startEmployeeLeavesTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Employee Leave Management Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Track leave requests, approvals, and annual quotas.</p></div>'
                },
                {
                    title: 'Employee Leave Management',
                    intro: 'Track leave requests, approvals, and annual quotas.'
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
        $('#start-employee-leaves-tour').on('click', function(e) {
            e.preventDefault();
            startEmployeeLeavesTour();
        });
    });
</script>
@endsection
