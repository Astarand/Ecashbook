@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Payroll Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Attendance List</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-employee-attendance-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Employee Attendance List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end mt-2">
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card table-card" id="attendance-table-card">
                <div class="card-body table-card">
                    <div class="table-responsive">
                        <table class="table tbl-product" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th class="text-end">#</th>
                                    <th>Name</th>
                                    <th>Contact Number</th>
                                    <th>Deperatment</th>
                                    <th>Designation</th>
                                    <th id="ontime-header">On-time Day Count</th>
                                    <th id="late-header">Late Day Count</th>
                                    <th id="absent-header">Absent Day Count</th>
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
                                                    $profileImg = $employee->profile_img ? asset('storage/user_employee/' . $employee->profile_img) : asset('assets/images/user/avatar-2.jpg');
                                                    @endphp
                                                    <img src="{{ $profileImg }}" alt="user-image" class="wid-40 rounded">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <h6 class="mb-1">{{ $employee->name }}</h6>
                                                <a class="text-muted f-12 text-hover-primary" href="mailto:{{ $employee->email_id ?? 'N/A' }}">{{ $employee->email_id ?? 'N/A' }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td><a class="text-muted text-hover-primary" href="tel:{{ $employee->phone }}">{{ $employee->phone }}</a></td>
                                    <td><a class="text-muted text-hover-primary" href="#">{{ $employee->dept_name }}</a></td>
                                    <td><a class="text-muted text-hover-primary" href="#">{{ $employee->designation_name }}</a></td>
                                    <td><a class="text-muted text-hover-primary"><span class="badge bg-success ms-2">{{ $employee->on_time_days }}</span></a></td>
                                    <td><a class="text-muted text-hover-primary"><span class="badge bg-warning ms-2">{{ $employee->late_days }}</span></a></td>
                                    <td><a class="text-muted text-hover-primary"><span class="badge bg-danger ms-2">{{ $employee->absent_days }}</span></a></td>
                                    <td>
                                        <span class="badge {{ $employee->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $employee->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td><a href="{{ url('employee-details/' . $encodedId) }}" class="text-muted"><i class="ti ti-eye f-20"></i></a></td>
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

@endsection

@section('page-script')
<script>
    function startEmployeeAttendanceTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Employee Attendance Register',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-calendar-event" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Oversee the monthly logs, late count registers, absences, and presence stats for all employees.</p></div>'
                },
                {
                    element: '#attendance-table-card',
                    title: 'Attendance History Roster',
                    intro: 'View a database table containing employee details, department, designation, and presence records.'
                },
                {
                    element: '#ontime-header',
                    title: 'On-time Counts',
                    intro: 'Indicates the number of days the employee clocked in on time during the current month.'
                },
                {
                    element: '#late-header',
                    title: 'Late Clock-ins',
                    intro: 'Displays how many days the employee arrived late at work.'
                },
                {
                    element: '#absent-header',
                    title: 'Absent Counters',
                    intro: 'Monitors absolute absences and missing logs.'
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
        $('#start-employee-attendance-tour').on('click', function(e) {
            e.preventDefault();
            startEmployeeAttendanceTour();
        });
    });
</script>
@endsection