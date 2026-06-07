@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Payroll Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Attendance List</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Employee Attendance List</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card table-card">
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
                                    <th>On-time Day Count</th>
                                    <th>Late Day Count</th>
                                    <th>Absent Day Count</th>
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