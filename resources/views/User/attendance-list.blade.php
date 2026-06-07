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
                        <li class="breadcrumb-item"><a href="#">HR & Payroll Management</a></li>
                        <li class="breadcrumb-item"><a href="#">HR, Payroll & Attendance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Attendance History</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Attendance History</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Stats Cards ] start -->
    <div class="row mb-4">
        <div class="col-md-12">
            <!-- [ Date Header ] start -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="avtar bg-primary text-white me-3">
                                        <i class="ph-duotone ph-calendar f-20"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">Today's Attendance</h5>
                                        <p class="text-muted mb-0">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h6 class="mb-0 text-primary">{{ \Carbon\Carbon::now()->format('M d, Y') }}</h6>
                                    <small class="text-muted">{{ \Carbon\Carbon::now()->format('l') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Date Header ] end -->
            <div class="d-flex flex-wrap gap-3">
                <div class="flex-fill">
                    <div class="card statistics-card-1">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avtar bg-brand-color-2 text-white me-3">
                                    <i class="ph-duotone ph-users-three f-26"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0">Total Present</p>
                                    <div class="d-flex align-items-end">
                                        <h4 class="mb-0">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-fill">
                    <div class="card statistics-card-1">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avtar bg-success text-white me-3">
                                    <i class="ph-duotone ph-clock f-26"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0">OnTime Present</p>
                                    <div class="d-flex align-items-end">
                                        <h4 class="mb-0">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-fill">
                    <div class="card statistics-card-1">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avtar bg-warning text-white me-3">
                                    <i class="ph-duotone ph-clock-countdown f-26"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0">Late Present</p>
                                    <div class="d-flex align-items-end">
                                        <h4 class="mb-0">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-fill">
                    <div class="card statistics-card-1">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avtar bg-danger text-white me-3">
                                    <i class="ph-duotone ph-user-minus f-26"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0">Absent</p>
                                    <div class="d-flex align-items-end">
                                        <h4 class="mb-0">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-fill">
                    <div class="card statistics-card-1">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avtar bg-info text-white me-3">
                                    <i class="ph-duotone ph-calendar-x f-26"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0">Leave</p>
                                    <div class="d-flex align-items-end">
                                        <h4 class="mb-0">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- [ Stats Cards ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card table-card">
                <!-- [ Date Filter Section ] start -->
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-0">Employee Attendance Records</h5>
                        </div>
                        <div class="col-md-4">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-6">
                                    <label class="form-label mb-1">From Date</label>
                                    <input type="date" class="form-control" id="fromDate"
                                        value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-1">To Date</label>
                                    <input type="date" class="form-control" id="toDate"
                                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ Date Filter Section ] end -->
                <div class="card-body table-card py-3">

                    <div class="table-responsive">
                        <table class="table tbl-product" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th class="text-end">#</th>
                                    <th>Name</th>
                                    <th>Total Days</th>
                                    <th>Total Present</th>

                                    <th>On-time Day Count</th>
                                    <th>Late Day Count</th>
                                    <th>Absent Day Count</th>
                                    <th>Total Leave</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($employees as $employee)
                                <?php $encodedId = base64_encode($employee->id); ?>
                                <tr>
                                    <td class="text-end">{{ $i++ }}</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-auto pe-0">
                                                <div class="avtar avtar-s btn-light-primary">
                                                    @php
                                                    $profileImg = $employee->profile_img ?
                                                    asset('storage/user_employee/' . $employee->profile_img) :
                                                    asset('assets/images/user/avatar-2.jpg');
                                                    @endphp
                                                    <img src="{{ $profileImg }}" alt="user-image"
                                                        class="wid-40 hei-40 rounded">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <h6 class="mb-1">{{ $employee->name }}</h6>
                                                <a class="text-muted f-12 text-hover-primary"
                                                    href="mailto:{{ $employee->email_id ?? 'N/A' }}">{{
                                                    $employee->email_id ?? 'N/A' }}</a>
                                                <a class="text-muted f-12 text-hover-primary"
                                                    href="tel:{{ $employee->phone }}">{{ $employee->phone }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary ms-2">{{
                                            $employee->total_working_days }}</span>
                                    </td>
                                    <td><span class="badge bg-info ms-2">{{
                                            $employee->total_present }}</span></td>
                                    <td><span class="badge bg-success ms-2">{{
                                            $employee->total_ontime }}</span></td>
                                    <td><span class="badge bg-warning ms-2">{{
                                            $employee->total_late }}</span></td>
                                    <td><span class="badge bg-danger ms-2">{{
                                            $employee->total_absent }}</span></td>
                                    <td><span class="badge bg-secondary ms-2">{{ $employee->total_leaves }}</span>
                                    </td>

                                    <td>
                                        <span class="badge {{ $employee->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $employee->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <!-- <td>
                                        <div class="d-flex gap-2 justify-content-start">
                                            <a href="{{ url('employee-details/' . $encodedId) }}"
                                                class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1"
                                                data-bs-toggle="tooltip" title="View Employee Attendence">
                                                <i class="ti ti-eye"></i>
                                                <span class="d-none d-md-inline">Attendence</span>
                                            </a>

                                            <a href="{{ url('view_user_employee/' . $encodedId) }}"
                                                class="btn btn-sm btn-outline-success d-flex align-items-center gap-1"
                                                data-bs-toggle="tooltip" title="View Employee Profile">
                                                <i class="ti ti-user"></i>
                                                <span class="d-none d-md-inline">Profile</span>
                                            </a>
                                        </div>
                                    </td> -->
                                    <td>
                                        <span><i class="ti ti-dots-vertical f-20"></i></span>
                                        <div class="prod-action-links">
                                            <ul class="list-inline me-auto mb-0">
                                                <!-- View Employee -->
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                    title="Attendence">
                                                    <a href="{{ url('employee-details/' . $encodedId) }}"
                                                        class="avtar avtar-xs btn-link-success btn-pc-default">
                                                        <i class="ti ti-bell-plus f-18"></i>
                                                    </a>
                                                </li>
                                                <!-- Edit Employee -->
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                    title="Profile">
                                                    <a href="{{ url('view_user_employee/' . $encodedId) }}"
                                                        class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                        <i class="ph-duotone ph-user-circle f-18"></i>
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
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<script>
    $(document).ready(function() {
        $('#filterSubmit').on('click', function() {
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();

            if (!fromDate || !toDate) {
                showToast('Please select both from and to dates', 'error');
                return;
            }

            if (fromDate > toDate) {
                showToast('From date cannot be greater than to date', 'error');
                return;
            }

            // Show loading state
            $(this).prop('disabled', true).html('<i class="ph-duotone ph-spinner ph-spin me-2"></i>Loading...');

            $.ajax({
                url: '{{ route("user.AttendanceListFilter") }}',
                type: 'POST',
                data: {
                    from_date: fromDate,
                    to_date: toDate,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        updateAttendanceTable(response.data);
                    } else {
                        showToast('Error filtering data', 'error');
                    }
                },
                error: function() {
                    showToast('Error occurred while filtering data', 'error');
                },
                complete: function() {
                    // Reset button state
                    $('#filterSubmit').prop('disabled', false).html('<i class="ph-duotone ph-funnel me-2"></i>Filter');
                }
            });
        });

        $('#downloadPdf').on('click', function() {
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();

            if (!fromDate || !toDate) {
                alert('Please select both from and to dates');
                return;
            }

            if (fromDate > toDate) {
                alert('From date cannot be greater than to date');
                return;
            }

            // Show loading state
            $(this).prop('disabled', true).html('<i class="ph-duotone ph-spinner ph-spin me-2"></i>Generating...');

            // Create a form and submit it to download PDF
            var form = $('<form>', {
                'method': 'POST',
                'action': '{{ route("user.AttendanceListPDF") }}',
                'target': '_blank'
            });

            form.append($('<input>', {
                'type': 'hidden',
                'name': 'from_date',
                'value': fromDate
            }));

            form.append($('<input>', {
                'type': 'hidden',
                'name': 'to_date',
                'value': toDate
            }));

            form.append($('<input>', {
                'type': 'hidden',
                'name': '_token',
                'value': '{{ csrf_token() }}'
            }));

            $('body').append(form);
            form.submit();
            form.remove();

            // Reset button state after a short delay
            setTimeout(function() {
                $('#downloadPdf').prop('disabled', false).html('<i class="ph-duotone ph-download me-2"></i>Download PDF');
            }, 2000);
        });

        function updateAttendanceTable(employees) {
            var tbody = $('#pc-dt-simple tbody');
            tbody.empty();

            if (employees.length === 0) {
                tbody.append('<tr><td colspan="10" class="text-center">No records found for the selected date range</td></tr>');
                return;
            }

            $.each(employees, function(index, employee) {
                var encodedId = btoa(employee.id);
                var profileImg = employee.profile_img ?
                    '{{ asset("storage/user_employee/") }}/' + employee.profile_img :
                    '{{ asset("assets/images/user/avatar-2.jpg") }}';

                var row = '<tr>' +
                    '<td class="text-end">' + (index + 1) + '</td>' +
                    '<td>' +
                    '<div class="row">' +
                    '<div class="col-auto pe-0">' +
                    '<div class="avtar avtar-s btn-light-primary">' +
                    '<img src="' + profileImg + '" alt="user-image" class="wid-40 hei-40 rounded">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col">' +
                    '<h6 class="mb-1">' + employee.name + '</h6>' +
                    '<a class="text-muted f-12 text-hover-primary" href="mailto:' + (employee.email_id || 'N/A') + '">' + (employee.email_id || 'N/A') + '</a><br>' +
                    '<a class="text-muted f-12 text-hover-primary" href="tel:' + employee.phone + '">' + employee.phone + '</a>' +
                    '</div>' +
                    '</div>' +
                    '</td>' +
                    '<td><span class="badge bg-primary ms-2">' + employee.total_working_days + '</span></td>' +
                    '<td><span class="badge bg-info ms-2">' + employee.total_present + '</span></td>' +
                    '<td><span class="badge bg-success ms-2">' + employee.total_ontime + '</span></td>' +
                    '<td><span class="badge bg-warning ms-2">' + employee.total_late + '</span></td>' +
                    '<td><span class="badge bg-danger ms-2">' + employee.total_absent + '</span></td>' +
                    '<td><span class="badge bg-secondary ms-2">' + employee.total_leaves + '</span></td>' +
                    '<td>' +
                    '<span class="badge ' + (employee.status == 1 ? 'bg-success' : 'bg-danger') + '">' +
                    (employee.status == 1 ? 'Active' : 'Inactive') +
                    '</span>' +
                    '</td>' +
                    '<td><a href="{{ url("employee-details/") }}/' + encodedId + '" class="text-muted"><i class="ti ti-eye f-20"></i></a></td>' +
                    '</tr>';

                tbody.append(row);
            });
        }
    });
</script>

@endsection