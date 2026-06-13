@extends('App.Layout')
@section('container')
<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">HR & Payroll Management</a></li>
                        <li class="breadcrumb-item"><a href="#">HR, Payroll & Attendance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Leave Management</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-leave-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Leave Management</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end mt-2">
                    <a href="#" class="btn btn-primary" id="add-leave-btn" data-bs-toggle="modal"
                        data-bs-target="#addLeaveModal"><i class="ti ti-square-plus"></i> Add Leave Request</a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row" id="leave-stats-row">
        <!-- Statistics Cards -->
        <div class="col-md-12 col-xxl-3">
            <div class="card statistics-card-1">
                <div class="card-body"><img src="../assets/images/widget/img-status-2.svg" alt="img" class="img-fluid img-bg">
                    <div class="d-flex align-items-center">
                        <div class="avtar bg-brand-color-1 text-white me-3"><i class="ti ti-calendar-event f-26"></i></div>
                        <div>
                            <p class="text-muted mb-0">Total Leaves</p>
                            <div class="d-flex align-items-end">
                                <h2 class="mb-0 f-w-500">{{ $totalLeaves }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xxl-3">
            <div class="card statistics-card-1">
                <div class="card-body"><img src="../assets/images/widget/img-status-1.svg" alt="img" class="img-fluid img-bg">
                    <div class="d-flex align-items-center">
                        <div class="avtar bg-brand-color-4 text-white me-3"><i class="ti ti-clock f-26"></i></div>
                        <div>
                            <p class="text-muted mb-0">Pending Leaves</p>
                            <div class="d-flex align-items-end">
                                <h2 class="mb-0 f-w-500">{{ $pendingLeaves }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xxl-3">
            <div class="card statistics-card-1">
                <div class="card-body"><img src="../assets/images/widget/img-status-3.svg" alt="img" class="img-fluid img-bg">
                    <div class="d-flex align-items-center">
                        <div class="avtar bg-brand-color-2 text-white me-3"><i class="ti ti-check f-26"></i></div>
                        <div>
                            <p class="text-muted mb-0">Approved Leaves</p>
                            <div class="d-flex align-items-end">
                                <h2 class="mb-0 f-w-500">{{ $approvedLeaves }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xxl-3">
            <div class="card statistics-card-1">
                <div class="card-body"><img src="../assets/images/widget/img-status-4.svg" alt="img" class="img-fluid img-bg">
                    <div class="d-flex align-items-center">
                        <div class="avtar bg-brand-color-3 text-white me-3"><i class="ti ti-x f-26"></i></div>
                        <div>
                            <p class="text-muted mb-0">Rejected Leaves</p>
                            <div class="d-flex align-items-end">
                                <h2 class="mb-0 f-w-500">{{ $rejectedLeaves }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Management Table -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-body table-card" id="leave-table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Leave Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Reason</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaves as $index => $leave)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $leave->emp_name }}</strong>
                                        <br><small class="text-muted">ID: {{ $leave->employee_id }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($leave->leave_type) }}</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $leave->total_days }} {{ $leave->total_days
                                            == 1 ? 'day' : 'days' }}</span>
                                </td>
                                <td>
                                    @if($leave->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @elseif($leave->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                    @elseif($leave->status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-truncate" style="max-width: 150px; display: inline-block;"
                                        title="{{ $leave->reason }}">
                                        {{ $leave->reason }}
                                    </span>
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            @if($leave->status == 'pending')
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" aria-label="Approve" data-bs-original-title="Approve">
                                                <a href="#"
                                                    onclick="updateLeaveStatus({{ $leave->id }}, 'approved')"
                                                    class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-check f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" aria-label="Reject" data-bs-original-title="Reject">
                                                <a href="#"
                                                    onclick="updateLeaveStatus({{ $leave->id }}, 'rejected')"
                                                    class="avtar avtar-xs btn-link-danger btn-pc-default">
                                                    <i class="ti ti-x f-18"></i>
                                                </a>
                                            </li>
                                            @endif
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" aria-label="View" data-bs-original-title="View">
                                                <a href="#"
                                                    onclick="viewLeaveDetails({{ $leave->id }})"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="Edit">
                                                <a href="#"
                                                    onclick="editLeave({{ $leave->id }})"
                                                    class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" aria-label="Delete" data-bs-original-title="Delete">
                                                <a href="#"
                                                    onclick="deleteLeave({{ $leave->id }})"
                                                    class="avtar avtar-xs btn-link-danger btn-pc-default">
                                                    <i class="ti ti-trash f-18"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="ti ti-calendar-off" style="font-size: 3rem; color: #6c757d;"></i>
                                        <h5 class="mt-2 text-muted">No Leave Requests Found</h5>
                                        <p class="text-muted">Click "Add Leave Request" to create your first leave
                                            request.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Leave Modal -->
    <div class="modal fade" id="addLeaveModal" tabindex="-1" aria-labelledby="addLeaveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <!-- Enhanced Header without bg-color -->
                <div class="modal-header border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-s me-3 rounded-circle">
                            <i class="ti ti-calendar-plus text-primary"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0 fw-bold" id="addLeaveModalLabel">Add Leave Request</h5>
                            <small class="text-muted">Submit your leave application</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <form id="leaveformadd">
                        <!-- Employee & Leave Type Section -->
                        <div class="card border mb-4">
                            <div class="card-body p-3">
                                <h6 class="card-title mb-3 text-primary">
                                    <i class="ti ti-user-circle me-2"></i>Employee Information
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="employee_id" name="employee_id" required>
                                                <option value="">Choose Employee</option>
                                                @foreach($employees as $employee)
                                                <option value="{{ $employee->employee_id }}">
                                                    {{ $employee->name }} ({{ $employee->employee_id }})
                                                </option>
                                                @endforeach
                                            </select>
                                            <label for="employee_id">Employee <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="leave_type" name="leave_type" required>
                                                <option value="">Choose Leave Type</option>
                                                <option value="sick">Sick Leave</option>
                                                <option value="casual">Casual Leave</option>
                                                <option value="annual">Annual Leave</option>
                                                <option value="maternity">Maternity Leave</option>
                                                <option value="paternity">Paternity Leave</option>
                                                <option value="emergency">Emergency Leave</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <label for="leave_type">Leave Type <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Leave Duration Section -->
                        <div class="card border mb-4">
                            <div class="card-body p-3">
                                <h6 class="card-title mb-3 text-warning">
                                    <i class="ti ti-clock me-2"></i>Leave Duration
                                </h6>
                                <div class="form-floating">
                                    <select class="form-select" id="leave_duration" name="leave_duration" required>
                                        <option value="">Select Duration Type</option>
                                        <option value="single_day">Single Day Leave</option>
                                        <option value="multiple_day">Multiple Day Leave</option>
                                    </select>
                                    <label for="leave_duration">Duration Type <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <!-- Date Selection Section -->
                        <div class="card border mb-4">
                            <div class="card-body p-3">
                                <h6 class="card-title mb-3 text-success">
                                    <i class="ti ti-calendar-event me-2"></i>Date Selection
                                </h6>

                                <!-- Single Date Field (Hidden by default) -->
                                <div id="single_date_field" style="display: none;">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="single_date" name="single_date">
                                        <label for="single_date">Leave Date <span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <!-- Multiple Date Fields (Hidden by default) -->
                                <div id="multiple_date_fields" style="display: none;">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="date" class="form-control" id="start_date" name="start_date">
                                                <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="date" class="form-control" id="end_date" name="end_date">
                                                <label for="end_date">End Date <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Enhanced Working Days Info -->
                                <div class="mt-3" id="workingDaysInfo" style="display: none;">
                                    <div class="alert alert-info border d-flex align-items-center">
                                        <i class="ti ti-info-circle fs-4 me-3 text-info"></i>
                                        <div class="flex-grow-1">
                                            <strong>Working Days:</strong> <span id="workingDaysList">Loading...</span>
                                            <small class="d-block text-muted mt-1">Only working days are counted for leave calculation</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Enhanced Total Days Display -->
                                <div class="mt-3" id="totalDaysAlert" style="display: none;">
                                    <div class="alert alert-success border d-flex align-items-center">
                                        <i class="ti ti-calendar-stats fs-4 me-3 text-success"></i>
                                        <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                                            <span><strong>Total Working Days:</strong></span>
                                            <span class="badge bg-success fs-6 px-3 py-2 rounded-pill">
                                                <span id="totalDaysCount">0</span> days
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reason Section -->
                        <div class="card border">
                            <div class="card-body p-3">
                                <h6 class="card-title mb-3 text-info">
                                    <i class="ti ti-message-circle me-2"></i>Leave Details
                                </h6>
                                <div class="form-floating">
                                    <textarea class="form-control" id="reason" name="reason" style="height: 120px"
                                        placeholder="Describe your reason for leave..." required></textarea>
                                    <label for="reason">Reason for Leave <span class="text-danger">*</span></label>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="ti ti-bulb me-1"></i>
                                    Provide clear and detailed reason for better approval process
                                </small>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Enhanced Footer -->
                <div class="modal-footer border-top p-4">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <small class="text-muted d-flex align-items-center">
                            <i class="ti ti-shield-check me-1 text-success"></i>
                            Information will be reviewed by HR
                        </small>
                        <div class="btn-group shadow-sm">
                            <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">
                                <i class="ti ti-x me-1"></i>Cancel
                            </button>
                            <button type="submit" id="submitLeaveBtn" form="leaveformadd" class="btn btn-primary px-4">
                                <i class="ti ti-send me-1"></i>Submit Request
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('leaveformadd');

        // Leave Duration Change Handler - NEW ADDITION
        const leaveDurationSelect = document.getElementById('leave_duration');
        const singleDateField = document.getElementById('single_date_field');
        const multipleDateFields = document.getElementById('multiple_date_fields');
        const singleDateInput = document.getElementById('single_date');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        // Handle leave duration selection change - NEW FUNCTION
        if (leaveDurationSelect) {
            leaveDurationSelect.addEventListener('change', function() {
                const selectedDuration = this.value;

                // Hide both fields first
                if (singleDateField) singleDateField.style.display = 'none';
                if (multipleDateFields) multipleDateFields.style.display = 'none';

                // Clear all date inputs
                if (singleDateInput) singleDateInput.value = '';
                if (startDateInput) startDateInput.value = '';
                if (endDateInput) endDateInput.value = '';

                // Hide total days alert
                const totalDaysAlert = document.getElementById('totalDaysAlert');
                if (totalDaysAlert) totalDaysAlert.style.display = 'none';

                // Show appropriate fields based on selection
                if (selectedDuration === 'single_day') {
                    if (singleDateField) singleDateField.style.display = 'block';
                    // Make single date required, remove requirement from start/end dates
                    if (singleDateInput) singleDateInput.setAttribute('required', 'required');
                    if (startDateInput) startDateInput.removeAttribute('required');
                    if (endDateInput) endDateInput.removeAttribute('required');
                } else if (selectedDuration === 'multiple_day') {
                    if (multipleDateFields) multipleDateFields.style.display = 'block';
                    // Make start/end dates required, remove requirement from single date
                    if (startDateInput) startDateInput.setAttribute('required', 'required');
                    if (endDateInput) endDateInput.setAttribute('required', 'required');
                    if (singleDateInput) singleDateInput.removeAttribute('required');
                }
            });
        }

        // Form Submission Handler

        form.addEventListener('submit', function (e) {
                e.preventDefault();

                const employeeId = document.getElementById('employee_id').value;
                const leaveType = document.getElementById('leave_type').value;
                const leaveDuration = document.getElementById('leave_duration').value;

                const singleDate = document.getElementById('single_date') ? document.getElementById('single_date').value : '';
                let startDate = document.getElementById('start_date') ? document.getElementById('start_date').value : '';
                let endDate = document.getElementById('end_date') ? document.getElementById('end_date').value : '';
                const reason = document.getElementById('reason').value;

                if (!employeeId || !leaveType || !leaveDuration || !reason) {
                showToast('Please fill in all required fields', 'error');
                return;
                }

                if (leaveDuration === 'single_day') {
                if (!singleDate) {
                    showToast('Please select leave date', 'error');
                    return;
                }
                startDate = singleDate;
                endDate = singleDate;
                } else if (leaveDuration === 'multiple_day') {
                if (!startDate || !endDate) {
                    showToast('Please select both start and end dates', 'error');
                    return;
                }
                if (new Date(endDate) < new Date(startDate)) {
                    showToast('End date must be greater than or equal to start date', 'error');
                    return;
                }
                } else {
                showToast('Please select leave duration', 'error');
                return;
                }

                const leaveId = form.getAttribute('data-leave-id');
                const action = form.getAttribute('data-action');
                const isUpdate = action === 'update' && leaveId;

                const data = {
                employee_id: employeeId,
                leave_type: leaveType,
                // leave_duration: leaveDuration,
                start_date: startDate,
                end_date: endDate,
                reason: reason,
                _token: '{{ csrf_token() }}'
                };

                const submitBtn = document.getElementById('submitLeaveBtn');
                if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i>' + (isUpdate ? 'Updating...' : 'Submitting...');
                }

                const url = isUpdate ? `/update-leave/${leaveId}` : '{{ route("user.storeLeave") }}';
                const method = isUpdate ? 'PUT' : 'POST';

                fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
                })
                .then(r => r.json())
                .then(resp => {
                if (resp.success) {
                    showToast(resp.message, 'success');
                    setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addLeaveModal'));
                    if (modal) modal.hide();
                    resetLeaveForm();
                    location.reload();
                    }, 1500);
                } else {
                    showToast(resp.message || 'An error occurred while processing the leave request.', 'error');
                }
                })
                .catch(err => {
                console.error('Error:', err);
                showToast('An error occurred while processing the leave request.', 'error');
                })
                .finally(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="ti ti-device-floppy me-1"></i>' + (isUpdate ? 'Update Leave Request' : 'Submit Leave Request');
                }
                });
            });

        // Reset form when modal is hidden
        document.getElementById('addLeaveModal').addEventListener('hidden.bs.modal', function() {
            resetLeaveForm();
        });
    });

    // UPDATED: Function to reset the leave form
    function resetLeaveForm() {
        const form = document.getElementById('leaveformadd');
        form.reset();
        form.removeAttribute('data-leave-id');
        form.removeAttribute('data-action');

        // Reset modal title and button text
        document.getElementById('addLeaveModalLabel').textContent = 'Add Leave Request';
        document.getElementById('submitLeaveBtn').innerHTML = '<i class="ti ti-device-floppy me-1"></i>Submit Leave Request';

        // Hide total days alert
        const totalDaysAlert = document.getElementById('totalDaysAlert');
        if (totalDaysAlert) totalDaysAlert.style.display = 'none';

        // NEW: Hide date fields and reset leave duration
        const singleDateField = document.getElementById('single_date_field');
        const multipleDateFields = document.getElementById('multiple_date_fields');
        if (singleDateField) singleDateField.style.display = 'none';
        if (multipleDateFields) multipleDateFields.style.display = 'none';
    }

    // DOM Content Loaded Event
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate total days when dates change
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const singleDateInput = document.getElementById('single_date'); // NEW

        // Fetch working days and disable non-working days
        let workingDays = [];

        function fetchWorkingDays() {
            fetch('/get-working-days', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        workingDays = data.working_days;
                        displayWorkingDaysInfo(data.working_day_names);
                        setupDateRestrictions();
                    } else {
                        console.error('Failed to fetch working days:', data.message);
                        // Default to Monday-Friday if fetch fails
                        workingDays = [1, 2, 3, 4, 5];
                        displayWorkingDaysInfo(['monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
                        setupDateRestrictions();
                    }
                })
                .catch(error => {
                    console.error('Error fetching working days:', error);
                    // Default to Monday-Friday if fetch fails
                    workingDays = [1, 2, 3, 4, 5];
                    displayWorkingDaysInfo(['monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
                    setupDateRestrictions();
                });
        }

        function displayWorkingDaysInfo(workingDayNames) {
            const workingDaysInfo = document.getElementById('workingDaysInfo');
            const workingDaysList = document.getElementById('workingDaysList');

            if (workingDaysInfo && workingDaysList) {
                // Capitalize first letter of each day
                const capitalizedDays = workingDayNames.map(day =>
                    day.charAt(0).toUpperCase() + day.slice(1)
                );

                workingDaysList.textContent = capitalizedDays.join(', ');
                workingDaysInfo.style.display = 'block';
            }
        }

        function setupDateRestrictions() {
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            if (startDateInput) startDateInput.setAttribute('min', today);
            if (endDateInput) endDateInput.setAttribute('min', today);
            if (singleDateInput) singleDateInput.setAttribute('min', today); // NEW

            // Add event listeners to validate selected dates
            if (startDateInput) {
                startDateInput.addEventListener('input', function() {
                    validateWorkingDay(this);
                    updateEndDateMin();
                });
            }

            if (endDateInput) {
                endDateInput.addEventListener('input', function() {
                    validateWorkingDay(this);
                });
            }

            // NEW: Add event listener for single date
            if (singleDateInput) {
                singleDateInput.addEventListener('input', function() {
                    validateWorkingDay(this);
                });
            }
        }

        function validateWorkingDay(input) {
            const selectedDate = new Date(input.value);
            const dayOfWeek = selectedDate.getDay();

            if (!workingDays.includes(dayOfWeek)) {
                const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                const selectedDayName = dayNames[dayOfWeek];

                // More specific error messages
                let errorMessage = `${selectedDayName} is not a working day.`;
                if (dayOfWeek === 0 || dayOfWeek === 6) { // Sunday or Saturday
                    errorMessage += ' Weekends are not allowed.';
                }
                errorMessage += ' Please select a working day.';

                showToast(errorMessage, 'error');
                input.value = '';

                // Hide total days alert if date is cleared
                const totalDaysAlert = document.getElementById('totalDaysAlert');
                if (totalDaysAlert) totalDaysAlert.style.display = 'none';
            }
        }

        function updateEndDateMin() {
            if (startDateInput && endDateInput && startDateInput.value) {
                endDateInput.setAttribute('min', startDateInput.value);
            }
        }

        // Initialize working days fetch
        fetchWorkingDays();

        // UPDATED: Calculate total days function
        function calculateTotalDays() {
            const leaveDuration = document.getElementById('leave_duration').value;
            const totalDaysAlert = document.getElementById('totalDaysAlert');
            const totalDaysCount = document.getElementById('totalDaysCount');

            if (leaveDuration === 'single_day') {
                const singleDate = singleDateInput.value;
                if (singleDate) {
                    if (totalDaysCount) totalDaysCount.textContent = '1';
                    if (totalDaysAlert) totalDaysAlert.style.display = 'block';
                } else {
                    if (totalDaysAlert) totalDaysAlert.style.display = 'none';
                }
            } else if (leaveDuration === 'multiple_day') {
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;

                if (startDate && endDate) {
                    const start = new Date(startDate);
                    const end = new Date(endDate);

                    if (end >= start) {
                        // Show loading state
                        if (totalDaysCount) totalDaysCount.textContent = 'Calculating...';
                        if (totalDaysAlert) totalDaysAlert.style.display = 'block';

                        // Call backend API to calculate working days
                        fetch('/calculate-leave-days', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    start_date: startDate,
                                    end_date: endDate
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    if (totalDaysCount) {
                                        totalDaysCount.textContent = data.total_days;
                                    }
                                    if (totalDaysAlert) {
                                        totalDaysAlert.style.display = 'block';
                                    }
                                } else {
                                    if (totalDaysAlert) totalDaysAlert.style.display = 'none';
                                    showToast(data.message || 'Failed to calculate working days', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                if (totalDaysAlert) totalDaysAlert.style.display = 'none';
                                showToast('Error calculating working days', 'error');
                            });
                    } else {
                        if (totalDaysAlert) totalDaysAlert.style.display = 'none';
                        showToast('End date must be greater than or equal to start date', 'error');
                    }
                } else {
                    if (totalDaysAlert) totalDaysAlert.style.display = 'none';
                }
            }
        }

        // UPDATED: Event listeners for date changes
        if (startDateInput && endDateInput) {
            startDateInput.addEventListener('change', calculateTotalDays);
            endDateInput.addEventListener('change', calculateTotalDays);
        }

        // NEW: Event listener for single date
        if (singleDateInput) {
            singleDateInput.addEventListener('change', calculateTotalDays);
        }
    });

    // Function to update leave status
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

    // Function to view leave details
    function viewLeaveDetails(leaveId) {
        fetch(`/get-leave/${leaveId}`, {
            method: 'GET',
            headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
            showToast(data.message || 'Failed to fetch leave details', 'error');
            return;
            }
            const leave = data.data;

            // Clean up any existing view modal/backdrops to avoid stacking/focus issues
            const existing = document.getElementById('viewLeaveModal');
            if (existing) existing.remove();
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());

            const fmtDate = (iso) => {
            try { return new Date(iso).toLocaleDateString(); } catch { return '-'; }
            };
            const statusColor = leave.status === 'approved' ? 'success' : (leave.status === 'rejected' ? 'danger' : 'warning');
            const duration = leave.start_date && leave.end_date &&
                            new Date(leave.start_date).toDateString() === new Date(leave.end_date).toDateString()
                            ? 'Single day' : 'Multiple days';

            const modalContent = `
            <div class="modal fade" id="viewLeaveModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-s me-3 rounded-circle">
                        <i class="ti ti-eye text-warning"></i>
                        </div>
                        <div>
                        <h5 class="modal-title mb-0 fw-bold">Leave Request Details</h5>
                        <small class="text-muted">Quick view of the submitted leave</small>
                        </div>
                    </div>
                    <span class="badge bg-${statusColor} ms-3 text-uppercase">${leave.status}</span>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                    <div class="card border mb-3">
                        <div class="card-body p-3">
                        <h6 class="card-title mb-3 text-primary">
                            <i class="ti ti-user-circle me-2"></i>Employee
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                            <div class="text-muted mb-1">Name</div>
                            <div class="fw-semibold" title="${leave.emp_name || ''}">${leave.emp_name || '-'}</div>
                            </div>
                            <div class="col-md-6">
                            <div class="text-muted mb-1">Employee ID</div>
                            <div class="fw-semibold">${leave.employee_id || '-'}</div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="card border mb-3">
                        <div class="card-body p-3">
                        <h6 class="card-title mb-3 text-info">
                            <i class="ti ti-calendar-event me-2"></i>Leave Info
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                            <div class="text-muted mb-1">Leave Type</div>
                            <span class="badge bg-info text-uppercase">${(leave.leave_type || '').toString()}</span>
                            </div>
                            <div class="col-md-6">
                            <div class="text-muted mb-1">Duration</div>
                            <div class="fw-semibold">${duration}</div>
                            </div>
                            <div class="col-md-4">
                            <div class="text-muted mb-1">Start Date</div>
                            <div class="fw-semibold">${fmtDate(leave.start_date)}</div>
                            </div>
                            <div class="col-md-4">
                            <div class="text-muted mb-1">End Date</div>
                            <div class="fw-semibold">${fmtDate(leave.end_date)}</div>
                            </div>
                            <div class="col-md-4">
                            <div class="text-muted mb-1">Total Days</div>
                            <span class="badge bg-success">${leave.total_days ?? '-'}</span>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="card border">
                        <div class="card-body p-3">
                        <h6 class="card-title mb-3 text-warning">
                            <i class="ti ti-message-circle me-2"></i>Details
                        </h6>
                        <div class="mb-3">
                            <div class="text-muted mb-1">Reason</div>
                            <div class="fw-normal" style="white-space: pre-wrap;">${leave.reason || '-'}</div>
                        </div>
                        ${leave.admin_remarks ? `
                        <div class="mb-2">
                            <div class="text-muted mb-1">Admin Remarks</div>
                            <div class="fw-normal" style="white-space: pre-wrap;">${leave.admin_remarks}</div>
                        </div>` : ''}
                        <div class="text-muted small mt-2">
                            <i class="ti ti-clock me-1"></i>
                            Applied on: ${fmtDate(leave.created_at)}
                            ${leave.approved_at ? ` • Updated: ${fmtDate(leave.approved_at)}` : ''}
                        </div>
                        </div>
                    </div>
                    </div>

                    <div class="modal-footer border-top p-3">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <small class="text-muted d-flex align-items-center">
                        <i class="ti ti-shield-check me-1 text-success"></i>
                        HR policy applies for all leave approvals
                        </small>
                        <div class="btn-group">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                            <i class="ti ti-x me-1"></i>Close
                        </button>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>`;

            document.body.insertAdjacentHTML('beforeend', modalContent);

            const modalEl = document.getElementById('viewLeaveModal');
            const instance = new bootstrap.Modal(modalEl, { backdrop: true, focus: true });

            // Wire footer actions
            const editBtn = modalEl.querySelector('#viewEditBtn');
            const delBtn = modalEl.querySelector('#viewDeleteBtn');

            // Optional rule: disable edit for non-pending
            if (leave.status !== 'pending') {
            editBtn?.setAttribute('disabled', 'disabled');
            editBtn?.classList.add('disabled');
            }

            editBtn?.addEventListener('click', () => {
            instance.hide();
            // Ensure modal is fully gone before opening edit modal
            modalEl.addEventListener('hidden.bs.modal', () => {
                // Clean up view modal to prevent stacking
                instance.dispose?.();
                modalEl.remove();
                // Remove stray backdrops if any
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                // Open shared Add/Edit modal
                editLeave(leaveId);
            }, { once: true });
            });

            delBtn?.addEventListener('click', () => {
            instance.hide();
            modalEl.addEventListener('hidden.bs.modal', () => {
                instance.dispose?.();
                modalEl.remove();
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                deleteLeave(leaveId);
            }, { once: true });
            });

            // Dispose on hidden to avoid stale backdrops
            modalEl.addEventListener('hidden.bs.modal', () => {
            instance.dispose?.();
            modalEl.remove();
            }, { once: true });

            instance.show();
        })
        .catch(err => {
            console.error('Error:', err);
            showToast('An error occurred while fetching leave details', 'error');
        });
        }


    // UPDATED: Function to edit leave
    function editLeave(leaveId) {
        fetch(`/get-leave/${leaveId}`, {
            method: 'GET',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) { showToast(data.message || 'Failed to fetch leave details', 'error'); return; }
            const leave = data.data;

            // Remove any existing view modal and backdrops to avoid focus block
            const existingView = document.getElementById('viewLeaveModal');
            if (existingView) existingView.remove();
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());

            const form = document.getElementById('leaveformadd');
            const labelEl = document.getElementById('addLeaveModalLabel');
            const submitBtn = document.getElementById('submitLeaveBtn');

            // Format dates to YYYY-MM-DD
            const toInput = (iso) => new Date(iso).toISOString().split('T')[0];
            const startDate = toInput(leave.start_date);
            const endDate = toInput(leave.end_date);
            const duration = startDate === endDate ? 'single_day' : 'multiple_day';

            // Fill form
            document.getElementById('employee_id').value = leave.employee_id;
            document.getElementById('leave_type').value = leave.leave_type;

            // Set duration and toggle fields without clearing prefilled values
            const durationSel = document.getElementById('leave_duration');
            durationSel.value = duration;

            // Temporarily disable validation during programmatic set
            const singleWrap = document.getElementById('single_date_field');
            const multiWrap = document.getElementById('multiple_date_fields');
            if (singleWrap) singleWrap.style.display = duration === 'single_day' ? 'block' : 'none';
            if (multiWrap) multiWrap.style.display = duration === 'multiple_day' ? 'block' : 'none';

            const singleDateInput = document.getElementById('single_date');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            // Relax min on edit so past dates don’t get cleared
            [singleDateInput, startDateInput, endDateInput].forEach(inp => {
            if (inp) inp.removeAttribute('min');
            });

            if (duration === 'single_day') {
            if (singleDateInput) singleDateInput.value = startDate;
            } else {
            if (startDateInput) startDateInput.value = startDate;
            if (endDateInput) endDateInput.value = endDate;
            }

            document.getElementById('reason').value = leave.reason || '';

            // Mark form as update
            form.setAttribute('data-leave-id', leaveId);
            form.setAttribute('data-action', 'update');

            // Update UI labels safely
            if (labelEl) labelEl.textContent = 'Edit Leave Request';
            if (submitBtn) submitBtn.innerHTML = '<i class="ti ti-device-floppy me-1"></i>Update Leave Request';

            // Defer calculation until after render
            setTimeout(() => {
            const totalDaysCount = document.getElementById('totalDaysCount');
            const totalDaysAlert = document.getElementById('totalDaysAlert');
            if (totalDaysCount && totalDaysAlert) {
                totalDaysCount.textContent = String(leave.total_days || (duration === 'single_day' ? 1 : ''));
                totalDaysAlert.style.display = 'block';
            }

            const modalEl = document.getElementById('addLeaveModal');
            const instance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl, { backdrop: true, focus: true });
            instance.show();
            }, 0);
        })
        .catch(err => {
            console.error('Error:', err);
            showToast('An error occurred while fetching leave details', 'error');
        });
        }


    // Function to delete leave
    function deleteLeave(leaveId) {
        Swal.fire({
            title: 'Delete Leave Request?',
            text: "You won't be able to revert this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(`/delete-leave/${leaveId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message || 'Failed to delete leave request');
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
            showToast('An error occurred while deleting leave request', 'error');
        });
    }

    function startLeaveTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Leave Management Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-calendar" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Submit and manage leave applications, check approval states, and review statistics.</p></div>'
                },
                {
                    element: '#leave-stats-row',
                    title: 'Leave Stats Panel',
                    intro: 'Monitor your organisation\'s overall metrics: Total leaves, Pending approvals, Approved status, and Rejected requests.'
                },
                {
                    element: '#add-leave-btn',
                    title: 'Apply For Leave',
                    intro: 'Click here to open the Leave Application form. Choose the employee, leave type, select single/multiple days, and provide a reason.'
                },
                {
                    element: '#leave-table-card',
                    title: 'Leave Logs',
                    intro: 'Review leave history table including start/end dates, total day calculations, leave reason, and status badges.'
                },
                {
                    element: '.prod-action-links',
                    title: 'Review Controls',
                    intro: 'Approve or Reject pending requests directly from here, view detailed comments, or delete applications.'
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
        $('#start-leave-tour').on('click', function(e) {
            e.preventDefault();
            startLeaveTour();
        });
    });
</script>
@endsection