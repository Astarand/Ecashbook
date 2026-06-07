@extends('App.Layout')

@section('container')

<!-- [ Main Content ] start -->
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
                        <li class="breadcrumb-item"><a href="{{ url('/employee-list') }}">Employee List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Account Profile</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Account Profile</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-lg-5 col-xxl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body position-relative">
                            <div class="text-center mt-3">

                                <div class="chat-avtar d-inline-flex mx-auto">
                                    @php
                                    $profileImg = $employee->profile_img
                                        ? asset('storage/user_employee/' . $employee->profile_img)
                                        : asset('storage/profile/e-cashbook.png');
                                    @endphp

                                    <img class="rounded-circle img-fluid wid-90 img-thumbnail" src="{{ $profileImg }}"
                                        alt="User image">

                                    {{-- Status Badge --}}
                                    @if(!empty($employee->regine_date))
                                        <i class="chat-badge bg-danger me-2 mb-2"></i>
                                    @else
                                        <i class="chat-badge bg-success me-2 mb-2"></i>
                                    @endif
                                </div>

                                <h5 class="mb-2 mt-3">{{ $employee->name }}</h5>

                                <h5 class="mb-0">
                                    <span class="badge bg-primary">{{ $employee->employee_id }}</span>
                                </h5>

                                {{-- Resignation Info --}}
                                @if(!empty($employee->regine_date))
                                    <div class="mt-2">
                                        <span class="badge bg-danger">Resigned</span><br>
                                        <small class="text-muted">
                                            Date: {{ \Carbon\Carbon::parse($employee->regine_date)->format('d M Y') }}
                                        </small>
                                    </div>
                                @endif

                            </div>
                            {{-- <div class="text-center mt-3">

                                <div class="chat-avtar d-inline-flex mx-auto">
                                    @php
                                    $profileImg = $employee->profile_img
                                    ? asset('storage/user_employee/' . $employee->profile_img)
                                    : asset('storage/profile/e-cashbook.png');
                                    @endphp
                                    <img class="rounded-circle img-fluid wid-90 img-thumbnail" src="{{ $profileImg }}"
                                        alt="User image">

                                    <i class="chat-badge bg-success me-2 mb-2"></i>
                                </div>

                                <h5 class="mb-2 mt-3">{{ $employee->name }}</h5>
                                <h5 class="mb-0"><span class="badge bg-primary">{{ $employee->employee_id }}</span></h5>
                            </div> --}}
                        </div>
                        <div class="nav flex-column nav-pills list-group list-group-flush account-pills mb-0"
                            id="user-set-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link list-group-item list-group-item-action active" id="user-set-profile-tab"
                                data-bs-toggle="pill" href="#user-set-profile" role="tab"
                                aria-controls="user-set-profile" aria-selected="true">
                                <span class="f-w-500"><i class="ph-duotone ph-user-circle m-r-10"></i>Profile
                                    Overview</span>
                            </a>
                            <a class="nav-link list-group-item list-group-item-action" id="user-attendance-details-tab"
                                data-bs-toggle="pill" href="#user-attendance-details" role="tab"
                                aria-controls="user-attendance-details" aria-selected="false">
                                <span class="f-w-500"><i class="ph-duotone ph-key m-r-10"></i>Check Employee Overview</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-xxl-9">
                    <div class="tab-content" id="user-set-tabContent">
                        <div class="tab-pane fade show active" id="user-set-profile" role="tabpanel"
                            aria-labelledby="user-set-profile-tab">
                            
                            <div class="card">
                                <div class="card-header">
                                    <h5>Personal Details</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">

                                        <!-- Basic Info -->
                                        <li class="list-group-item px-0 pt-0">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Full Name</p>
                                                    <p class="mb-0">{{ $employee->name }}</p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Designation</p>
                                                    <p class="mb-0">{{ $designationName }} - {{ $departmentName }}</p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Phone</p>
                                                    <p class="mb-0">{{ $employee->phone }}</p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Alternate Phone</p>
                                                    <p class="mb-0">{{ $employee->alt_phone }}</p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Email</p>
                                                    <p class="mb-0">{{ $employee->email_id }}</p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Gender</p>
                                                    <p class="mb-0">{{ $employee->gender }}</p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">DOB</p>
                                                    <p class="mb-0">{{ $employee->dob }}</p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Marital Status</p>
                                                    <p class="mb-0">{{ $employee->marital_status }}</p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Qualification</p>
                                                    <p class="mb-0">{{ $employee->qualification }}</p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Experience</p>
                                                    <p class="mb-0">{{ $employee->experience_years ?? '0' }} Years</p>
                                                </div>
                                            </div>
                                        </li>

                                        <!-- Address -->
                                        <li class="list-group-item px-0">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p class="mb-1 text-muted">Address</p>
                                                    <p class="mb-0">
                                                        {{ $employee->c_addr_lineone ?? '' }},
                                                        {{ $employee->c_addr_linetwo ?? '' }},
                                                        {{ $stateName }},
                                                        {{ $cityName }},
                                                        {{ $employee->pincode }}
                                                    </p>
                                                </div>
                                            </div>
                                        </li>

                                        <!-- Identity Details -->
                                        <li class="list-group-item px-0">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Aadhaar Number</p>
                                                    <p class="mb-0">{{ $employee->aadhaar_number }}</p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">PAN Number</p>
                                                    <p class="mb-0">{{ $employee->pan_number }}</p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Emergency Contact</p>
                                                    <p class="mb-0">
                                                        {{ $employee->emergency_name }} -
                                                        {{ $employee->emergency_mobile }}
                                                    </p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Employee Type</p>
                                                    <p class="mb-0">{{ $employee->emp_type }}</p>
                                                </div>
                                            </div>
                                        </li>

                                        <!-- Salary Details -->
                                        <li class="list-group-item px-0">
                                            <h6 class="mb-3">Salary Details</h6>

                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <p class="mb-1 text-muted">Basic Salary</p>
                                                    <p class="mb-0">₹ {{ number_format($employee->basic_sal,2) }}</p>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <p class="mb-1 text-muted">Basic %</p>
                                                    <p class="mb-0">{{ $employee->basic_percentage }}%</p>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <p class="mb-1 text-muted">HRA</p>
                                                    <p class="mb-0">₹ {{ number_format($employee->hra,2) }}</p>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <p class="mb-1 text-muted">Convayance</p>
                                                    <p class="mb-0">₹ {{ number_format($employee->convayance,2) }}</p>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <p class="mb-1 text-muted">Medical Allowance</p>
                                                    <p class="mb-0">₹ {{ number_format($employee->medical_allowance,2) }}</p>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <p class="mb-1 text-muted">Special Bonus</p>
                                                    <p class="mb-0">₹ {{ number_format($employee->special_bonus,2) }}</p>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <p class="mb-1 text-muted">PF</p>
                                                    <p class="mb-0">₹ {{ number_format($employee->provident_fund,2) }}</p>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <p class="mb-1 text-muted">ESI</p>
                                                    <p class="mb-0">₹ {{ number_format($employee->esi,2) }}</p>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <p class="mb-1 text-muted">PTAX</p>
                                                    <p class="mb-0">₹ {{ number_format($employee->ptax,2) }}</p>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <p class="mb-1 text-muted">TDS</p>
                                                    <p class="mb-0">₹ {{ number_format($employee->tds,2) }}</p>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <p class="mb-1 text-muted">Total Addition</p>
                                                    <p class="mb-0">₹ {{ number_format($employee->total_addition,2) }}</p>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <p class="mb-1 text-muted">Total Deduction</p>
                                                    <p class="mb-0">₹ {{ number_format($employee->total_deduction,2) }}</p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Net Salary</p>
                                                    <p class="mb-0">
                                                        ₹ {{ number_format($employee->net_sal,2) }}
                                                    </p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Salary In Words</p>
                                                    <p class="mb-0">{{ $employee->net_sal_word }}</p>
                                                </div>
                                            </div>
                                        </li>

                                        <!-- Bank Details -->
                                        <li class="list-group-item px-0">
                                            <h6 class="mb-3">Bank Details</h6>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Bank Name</p>
                                                    <p class="mb-0">{{ $employee->bank_name }}</p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Branch</p>
                                                    <p class="mb-0">{{ $employee->bank_branch }}</p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">IFSC</p>
                                                    <p class="mb-0">{{ $employee->ifsc }}</p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Account Holder</p>
                                                    <p class="mb-0">{{ $employee->account_holder_name }}</p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Account Number</p>
                                                    <p class="mb-0">{{ $employee->account_number }}</p>
                                                </div>
                                            </div>
                                        </li>

                                        <!-- Employment Details -->
                                        <li class="list-group-item px-0">
                                            <h6 class="mb-3">Employment Details</h6>

                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <p class="mb-1 text-muted">Joining Date</p>
                                                    <p class="mb-0">{{ $employee->joining_date }}</p>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <p class="mb-1 text-muted">Work Location</p>
                                                    <p class="mb-0">{{ ucwords(str_replace('_',' ',$employee->work_location)) }}</p>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <p class="mb-1 text-muted">Employee Status</p>
                                                    <p class="mb-0">{{ $employee->emp_status }}</p>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="list-group-item px-0">
                                            <h6 class="mb-3">Policy Status</h6>

                                            <div class="row">

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Privacy Policy</p>

                                                    @if($employee->privacy_policy_read == 'read')
                                                        <span class="badge bg-success">Read</span>

                                                    @elseif($employee->privacy_policy_read == 'unread')
                                                        <span class="badge bg-warning text-dark">Unread</span>

                                                    @else
                                                        <span class="badge bg-secondary">Not Created</span>
                                                    @endif
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1 text-muted">Terms & Conditions</p>

                                                    @if($employee->terms_and_conditions == 'read')
                                                        <span class="badge bg-success">Read</span>

                                                    @elseif($employee->terms_and_conditions == 'unread')
                                                        <span class="badge bg-warning text-dark">Unread</span>

                                                    @else
                                                        <span class="badge bg-secondary">Not Created</span>
                                                    @endif
                                                </div>

                                            </div>
                                        </li>

                                        <!-- Documents -->
                                        <li class="list-group-item px-0">
                                            <h6 class="mb-3">Documents</h6>

                                            <div class="row">

                                                <div class="col-md-4 mb-3">
                                                    <a href="{{ asset('/storage/employee_document/'.$employee->pan_doc) }}"
                                                        target="_blank"
                                                        class="btn btn-outline-primary btn-sm w-100">
                                                        PAN Document
                                                    </a>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <a href="{{ asset('/storage/employee_document/'.$employee->aadhar_doc) }}"
                                                        target="_blank"
                                                        class="btn btn-outline-primary btn-sm w-100">
                                                        Aadhaar Document
                                                    </a>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <a href="{{ asset('/storage/employee_document/'.$employee->last_qualification_doc) }}"
                                                        target="_blank"
                                                        class="btn btn-outline-primary btn-sm w-100">
                                                        Qualification Document
                                                    </a>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <a href="{{ asset('/storage/employee_document/'.$employee->cancelled_cheque_doc) }}"
                                                        target="_blank"
                                                        class="btn btn-outline-primary btn-sm w-100">
                                                        Cancelled Cheque
                                                    </a>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <a href="{{ asset('/storage/employee_document/'.$employee->cv_doc) }}"
                                                        target="_blank"
                                                        class="btn btn-outline-primary btn-sm w-100">
                                                        CV Document
                                                    </a>
                                                </div>

                                            </div>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="user-attendance-details" role="tabpanel"
                            aria-labelledby="user-attendance-details-tab">
                            <!-- Attendance Summary Cards -->
                            <div class="row mb-4 g-3">
                                <!-- Total Present -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="card border-0 h-100" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border-left: 4px solid #2196f3 !important;">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div class="bg-primary rounded-circle p-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="ph-duotone ph-calendar-check text-white" style="font-size: 1.8rem;"></i>
                                                </div>
                                                <div class="text-end">
                                                    <h2 class="mb-0 fw-bold" style="color: #1976d2; font-size: 2.5rem;">{{ $presentThisMonth }}</h2>
                                                    <small class="text-muted fw-medium">Days</small>
                                                </div>
                                            </div>
                                            <p class="mb-0 fw-semibold text-uppercase" style="color: #1976d2; font-size: 0.85rem; letter-spacing: 1px;">Total Present</p>
                                            <small class="text-muted">This month attendance</small>
                                        </div>
                                    </div>
                                </div>
                                <!-- Late Arrivals -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="card border-0 h-100" style="background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); border-left: 4px solid #ff9800 !important;">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div class="bg-warning rounded-circle p-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="ph-duotone ph-clock text-white" style="font-size: 1.8rem;"></i>
                                                </div>
                                                <div class="text-end">
                                                    <h2 class="mb-0 fw-bold" style="color: #f57c00; font-size: 2.5rem;">{{ $lateCountThisMonth }}</h2>
                                                    <small class="text-muted fw-medium">Days</small>
                                                </div>
                                            </div>
                                            <p class="mb-0 fw-semibold text-uppercase" style="color: #f57c00; font-size: 0.85rem; letter-spacing: 1px;">Late Arrivals</p>
                                            <small class="text-muted">Delayed check-ins</small>
                                        </div>
                                    </div>
                                </div>
                                <!-- Absent -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="card border-0 h-100" style="background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%); border-left: 4px solid #f44336 !important;">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div class="bg-danger rounded-circle p-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="ph-duotone ph-x-circle text-white" style="font-size: 1.8rem;"></i>
                                                </div>
                                                <div class="text-end">
                                                    <h2 class="mb-0 fw-bold" style="color: #d32f2f; font-size: 2.5rem;">{{ $totalAbsentDays }}</h2>
                                                    <small class="text-muted fw-medium">Days</small>
                                                </div>
                                            </div>
                                            <p class="mb-0 fw-semibold text-uppercase" style="color: #d32f2f; font-size: 0.85rem; letter-spacing: 1px;">Absent</p>
                                            <small class="text-muted">Days not marked</small>
                                        </div>
                                    </div>
                                </div>
                                <!-- Leaves -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="card border-0 h-100" style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-left: 4px solid #4caf50 !important;">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div class="bg-success rounded-circle p-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="ph-duotone ph-airplane-takeoff text-white" style="font-size: 1.8rem;"></i>
                                                </div>
                                                <div class="text-end">
                                                    <h2 class="mb-0 fw-bold" style="color: #388e3c; font-size: 2.5rem;">{{ $totalLeaveDaysThisMonth }}</h2>
                                                    <small class="text-muted fw-medium">Days</small>
                                                </div>
                                            </div>
                                            <p class="mb-0 fw-semibold text-uppercase" style="color: #388e3c; font-size: 0.85rem; letter-spacing: 1px;">Leaves</p>
                                            <small class="text-muted">Approved absences</small>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- HR Actions Panel -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">HR Management Actions</h5>
                                </div>
                                <div class="card-body">
                                    <?php $encodedId = base64_encode($employee->empId); ?>

                                    <div class="row g-3">
                                        <!-- Attendance Entry -->
                                        <div class="col-lg-4 col-md-6">
                                            <a href="{{ url('employee-details/' . $encodedId) }}"
                                            class="btn btn-light-primary w-100 text-start py-3 shadow-sm">
                                                <i class="ph-duotone ph-users-three me-2" style="font-size: 1.2rem;"></i>
                                                Attendance Entry
                                            </a>
                                        </div>

                                        <!-- Leave Management -->
                                        <div class="col-lg-4 col-md-6">
                                            <a href="{{ route('employee.leaves', $encodedId) }}"
                                            class="btn btn-light-success w-100 text-start py-3 shadow-sm position-relative">
                                                <i class="ph-duotone ph-calendar-x me-2" style="font-size: 1.2rem;"></i>
                                                Leave Management
                                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">{{ count($pendingLeaves) }}</span>
                                            </a>
                                        </div>

                                        <!-- Show HR Letter -->
                                        <div class="col-lg-4 col-md-6">
                                            <button onclick="showHrletter('{{ $encodedId }}')"
                                                    class="btn btn-light-info w-100 text-start py-3 shadow-sm">
                                                <i class="ph-duotone ph-file-text me-2" style="font-size: 1.2rem;"></i>
                                                Show HR Letter
                                            </button>
                                        </div>

                                        <!-- Work from Home -->
                                        <div class="col-lg-6 col-md-6">
                                            <button onclick="showWorkFromHome('{{ $encodedId }}')"
                                                    class="btn btn-light-secondary w-100 text-start py-3 shadow-sm">
                                                <i class="ph-duotone ph-house me-2" style="font-size: 1.2rem;"></i>
                                                Work from Home
                                            </button>
                                        </div>

                                        <!-- Add Review -->
                                        <div class="col-lg-6 col-md-6">
                                            <a href="{{ route('user.performace-review', $encodedId) }}"
                                            class="btn btn-light-warning w-100 text-start py-3 shadow-sm">
                                                <i class="ph-duotone ph-star me-2" style="font-size: 1.2rem;"></i>
                                                Add Review
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!-- Attendance Calendar & Details -->
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0">Attendance Calendar</h5>
                                                <div class="d-flex align-items-center">
                                                    <select id="calendarYearSelect"
                                                        class="form-select form-select-sm me-2" style="width: auto;">
                                                        @for($y = 2020; $y <= 2030; $y++) <option value="{{ $y }}" {{
                                                            $y==date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                                            @endfor
                                                    </select>
                                                    <select id="calendarMonthSelect"
                                                        class="form-select form-select-sm me-2" style="width: auto;">
                                                        <option value="1" {{ date('n')==1 ? 'selected' : '' }}>January
                                                        </option>
                                                        <option value="2" {{ date('n')==2 ? 'selected' : '' }}>February
                                                        </option>
                                                        <option value="3" {{ date('n')==3 ? 'selected' : '' }}>March
                                                        </option>
                                                        <option value="4" {{ date('n')==4 ? 'selected' : '' }}>April
                                                        </option>
                                                        <option value="5" {{ date('n')==5 ? 'selected' : '' }}>May
                                                        </option>
                                                        <option value="6" {{ date('n')==6 ? 'selected' : '' }}>June
                                                        </option>
                                                        <option value="7" {{ date('n')==7 ? 'selected' : '' }}>July
                                                        </option>
                                                        <option value="8" {{ date('n')==8 ? 'selected' : '' }}>August
                                                        </option>
                                                        <option value="9" {{ date('n')==9 ? 'selected' : '' }}>September
                                                        </option>
                                                        <option value="10" {{ date('n')==10 ? 'selected' : '' }}>October
                                                        </option>
                                                        <option value="11" {{ date('n')==11 ? 'selected' : '' }}>
                                                            November</option>
                                                        <option value="12" {{ date('n')==12 ? 'selected' : '' }}>
                                                            December</option>
                                                    </select>
                                                    <button class="btn btn-outline-secondary btn-sm"
                                                        onclick="loadCalendar()">
                                                        <i class="ph-duotone ph-calendar"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <!-- Calendar Grid -->
                                            <div class="attendance-calendar">
                                                <div
                                                    class="calendar-header d-flex justify-content-between align-items-center mb-3">
                                                    <div class="calendar-legends d-flex gap-3 flex-wrap">
                                                        <span class="legend-item"><span
                                                                class="legend-color bg-success"></span> Present</span>
                                                        <span class="legend-item"><span
                                                                class="legend-color bg-warning"></span> Late</span>
                                                        <span class="legend-item"><span
                                                                class="legend-color bg-danger"></span> Absent</span>
                                                        <span class="legend-item"><span
                                                                class="legend-color bg-info"></span> Leave</span>
                                                        <span class="legend-item"><span
                                                                class="legend-color bg-secondary"></span> Holiday</span>
                                                        <span class="legend-item"><span
                                                                class="legend-color today-indicator"></span>
                                                            Today</span>
                                                    </div>
                                                </div>

                                                <!-- Calendar Days -->
                                                <div class="calendar-grid">
                                                    <div class="calendar-weekdays">
                                                        <div class="weekday">Sun</div>
                                                        <div class="weekday">Mon</div>
                                                        <div class="weekday">Tue</div>
                                                        <div class="weekday">Wed</div>
                                                        <div class="weekday">Thu</div>
                                                        <div class="weekday">Fri</div>
                                                        <div class="weekday">Sat</div>
                                                    </div>
                                                    <div id="calendarDays" class="calendar-days">
                                                        <!-- Dynamic calendar days will be loaded here -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <!-- Daily Activity Details -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Daily Activity Details</h6>
                                            <small class="text-muted">Click on a calendar date to view details</small>
                                        </div>
                                        <div class="card-body">
                                            <div id="dailyActivityContent">
                                                <div class="text-center text-muted py-4">
                                                    <i class="ph-duotone ph-calendar-check f-36 mb-3"></i>
                                                    <p>Loading today's activity...</p>
                                                    <small>Today's date is automatically selected. Click any other date
                                                        to view its details.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Detailed Attendance Table -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0">Detailed Attendance Log</h5>
                                        <div class="d-flex gap-2">
                                            <select class="form-select form-select-sm" style="width: auto;"
                                                id="attendanceYearSelect">
                                                @php
                                                $currentYear = date('Y');
                                                $startYear = date('Y', strtotime($employee->joining_date));

                                                @endphp
                                                @for($year = $currentYear; $year >= $startYear; $year--)
                                                <option value="{{ $year }}" {{ $year==$currentYear ? 'selected' : '' }}>
                                                    {{ $year }}</option>
                                                @endfor
                                            </select>
                                            <select class="form-select form-select-sm" style="width: auto;"
                                                id="attendanceMonthSelect">
                                                @php
                                                $currentMonth = date('n');
                                                $months = [
                                                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                                                ];
                                                @endphp
                                                @foreach($months as $value => $name)
                                                <option value="{{ $value }}" {{ $value==$currentMonth ? 'selected' : ''
                                                    }}>
                                                    {{ $name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                                    type="button" id="exportDropdown" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="ph-duotone ph-export"></i> Export
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                                    <li><a class="dropdown-item" href="#"
                                                            onclick="exportAttendance('excel')">
                                                            <i class="ph-duotone ph-file-xls me-2"></i>Export as Excel
                                                        </a></li>
                                                    <li><a class="dropdown-item" href="#"
                                                            onclick="exportAttendance('pdf')">
                                                            <i class="ph-duotone ph-file-pdf me-2"></i>Export as PDF
                                                        </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Check In</th>
                                                    <th>Check Out</th>
                                                    <th>Working Hours</th>
                                                    <th>Overtime</th>
                                                    <th>Notes</th>

                                                </tr>
                                            </thead>
                                            <tbody id="attendanceTableBody">
                                                <tr>
                                                    <td colspan="7" class="text-center">
                                                        <div class="spinner-border spinner-border-sm" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                        Loading attendance data...
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <nav aria-label="Attendance pagination" id="attendancePagination"
                                        style="display: none;">
                                        <ul class="pagination pagination-sm justify-content-end" id="paginationList">
                                            <!-- Pagination will be generated dynamically -->
                                        </ul>
                                    </nav>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>



<!-- Modal Dialogs for HR Actions -->

<!-- Mark Attendance Modal -->
<div class="modal fade" id="markAttendanceModal" tabindex="-1" aria-labelledby="markAttendanceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markAttendanceModalLabel">Mark Attendance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="attendanceDate" class="form-label">Date</label>
                                <input type="date" class="form-control" id="attendanceDate" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="attendanceStatus" class="form-label">Status</label>
                                <select class="form-select" id="attendanceStatus">
                                    <option value="present">Present</option>
                                    <option value="late">Late</option>
                                    <option value="absent">Absent</option>
                                    <option value="leave">On Leave</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="checkInTime" class="form-label">Check In Time</label>
                                <input type="time" class="form-control" id="checkInTime" value="09:00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="checkOutTime" class="form-label">Check Out Time</label>
                                <input type="time" class="form-control" id="checkOutTime" value="18:00">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="attendanceNotes" class="form-label">Notes</label>
                        <textarea class="form-control" id="attendanceNotes" rows="3"
                            placeholder="Add any notes or remarks..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Mark Attendance</button>
            </div>
        </div>
    </div>
</div>






@endsection

@section('page-script')
<style>
    /* Attendance Calendar Styles */
    .attendance-calendar {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .legend-item {
        display: flex;
        align-items: center;
        font-size: 0.875rem;
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 2px;
        margin-right: 6px;
        display: inline-block;
    }

    .calendar-grid {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        overflow: hidden;
    }

    .calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background-color: #f8f9fa;
    }

    .weekday {
        padding: 12px 8px;
        text-align: center;
        font-weight: 600;
        font-size: 0.875rem;
        color: #6c757d;
        border-right: 1px solid #e9ecef;
    }

    .weekday:last-child {
        border-right: none;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 1px solid #e9ecef;
        border-bottom: 1px solid #e9ecef;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
        position: relative;
    }

    .calendar-day:last-child {
        border-right: none;
    }

    .calendar-day:hover {
        background-color: #f8f9fa;
    }

    .calendar-day.other-month {
        color: #adb5bd;
        background-color: #f8f9fa;
    }

    .calendar-day.present {
        background-color: #d1e7dd;
        color: #0f5132;
    }

    .calendar-day.late {
        background-color: #fff3cd;
        color: #664d03;
    }

    .calendar-day.absent {
        background-color: #f8d7da;
        color: #721c24;
    }

    .calendar-day.leave {
        background-color: #cff4fc;
        color: #055160;
    }

    .calendar-day.holiday {
        background-color: #e2e3e5;
        color: #41464b;
    }

    /* Timeline Activity Styles */
    .timeline-activity {
        position: relative;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1rem;
        position: relative;
    }

    .activity-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 15px;
        top: 32px;
        width: 2px;
        height: calc(100% + 8px);
        background-color: #e9ecef;
    }

    .activity-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }

    .activity-icon i {
        font-size: 14px;
        color: white;
    }

    .activity-content {
        flex: 1;
        padding-top: 4px;
    }

    .activity-content p {
        margin-bottom: 4px;
        font-size: 0.875rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .calendar-day {
            font-size: 0.75rem;
        }

        .weekday {
            padding: 8px 4px;
            font-size: 0.75rem;
        }

        .legend-item {
            font-size: 0.75rem;
        }
    }
</style>

<script>
    // Update current time
function updateCurrentTime() {
  const now = new Date();
  const timeString = now.toLocaleTimeString('en-US', {
    hour12: true,
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  });
  const timeElement = document.getElementById('currentTime');
  if (timeElement) {
    timeElement.textContent = timeString;
  }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
  // Update time every second
  updateCurrentTime();
  setInterval(updateCurrentTime, 1000);

  // Initialize Bootstrap tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Select all checkbox functionality
  const selectAllCheckbox = document.getElementById('selectAll');
  if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function() {
      const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
      checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
      });
    });
  }
});

// Generate attendance report
function showHrletter(encodedId) {
    window.location.href = '/employee_hr_letter/' + encodedId;
}

// Show Work from Home page
function showWorkFromHome(encodedId) {
  // Navigate to work from home page with employee ID
  window.location.href = '/work-from-home/' + encodedId;
}

// Calendar day click handler
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('calendar-day') && !e.target.classList.contains('other-month')) {
    const day = e.target.textContent;
    const month = document.querySelector('select option:checked').textContent;
    console.log(`Clicked on ${day} ${month}`);
    // You could open a modal or show details for this specific day
  }
});

// Attendance Log Functionality
let currentPage = 1;
const employeeId = {{ $employee->empId }};

function loadAttendanceLog(page = 1) {
    const year = document.getElementById('attendanceYearSelect').value;
    const month = document.getElementById('attendanceMonthSelect').value;

    // Show loading
    document.getElementById('attendanceTableBody').innerHTML = `
        <tr>
            <td colspan="7" class="text-center">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Loading attendance data...
            </td>
        </tr>
    `;

    // Hide pagination during loading
    document.getElementById('attendancePagination').style.display = 'none';

    fetch('/get_employee_attendance_log', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            employee_id: employeeId,
            year: year,
            month: month,
            page: page
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderAttendanceTable(data.data);
            renderPagination(data.pagination);
            currentPage = page;
        } else {
            showError('Failed to load attendance data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('An error occurred while loading attendance data');
    });
}

function renderAttendanceTable(attendanceData) {
    const tbody = document.getElementById('attendanceTableBody');

    if (attendanceData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center text-muted">
                    No attendance records found for the selected period.
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = attendanceData.map(record => `
        <tr>
            <td>${record.date}</td>
            <td><span class="badge ${record.badge_class}">${record.status}</span></td>
            <td>${record.check_in}</td>
            <td>${record.check_out}</td>
            <td>${record.working_hours}</td>
            <td>${record.overtime}</td>
            <td>${record.notes}</td>
        </tr>
    `).join('');
}

function renderPagination(pagination) {
    const paginationContainer = document.getElementById('attendancePagination');
    const paginationList = document.getElementById('paginationList');

    if (pagination.last_page <= 1) {
        paginationContainer.style.display = 'none';
        return;
    }

    let paginationHTML = '';

    // Previous button
    if (pagination.current_page > 1) {
        paginationHTML += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="loadAttendanceLog(${pagination.current_page - 1}); return false;">Previous</a>
            </li>
        `;
    } else {
        paginationHTML += `
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
        `;
    }

    // Page numbers
    const startPage = Math.max(1, pagination.current_page - 2);
    const endPage = Math.min(pagination.last_page, pagination.current_page + 2);

    for (let i = startPage; i <= endPage; i++) {
        if (i === pagination.current_page) {
            paginationHTML += `
                <li class="page-item active">
                    <span class="page-link">${i}</span>
                </li>
            `;
        } else {
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="loadAttendanceLog(${i}); return false;">${i}</a>
                </li>
            `;
        }
    }

    // Next button
    if (pagination.current_page < pagination.last_page) {
        paginationHTML += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="loadAttendanceLog(${pagination.current_page + 1}); return false;">Next</a>
            </li>
        `;
    } else {
        paginationHTML += `
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
        `;
    }

    paginationList.innerHTML = paginationHTML;
    paginationContainer.style.display = 'block';
}

function showError(message) {
    document.getElementById('attendanceTableBody').innerHTML = `
        <tr>
            <td colspan="7" class="text-center text-danger">
                <i class="ph-duotone ph-warning-circle"></i> ${message}
            </td>
        </tr>
    `;
}

// Event listeners for year and month selectors
document.getElementById('attendanceYearSelect').addEventListener('change', function() {
    loadAttendanceLog(1);
});

document.getElementById('attendanceMonthSelect').addEventListener('change', function() {
    loadAttendanceLog(1);
});

// Export functionality
function exportAttendance(format) {
    const year = document.getElementById('attendanceYearSelect').value;
    const month = document.getElementById('attendanceMonthSelect').value;

    // Create a form and submit it to trigger download
    const form = document.createElement('form');
    form.method = 'POST';
    form.style.display = 'none';

    // Set action based on format
    if (format === 'pdf') {
        form.action = '/export_employee_attendance_pdf';
    } else {
        form.action = '/export_employee_attendance';
    }

    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfToken);

    // Add employee ID
    const employeeIdInput = document.createElement('input');
    employeeIdInput.type = 'hidden';
    employeeIdInput.name = 'employee_id';
    employeeIdInput.value = employeeId;
    form.appendChild(employeeIdInput);

    // Add year
    const yearInput = document.createElement('input');
    yearInput.type = 'hidden';
    yearInput.name = 'year';
    yearInput.value = year;
    form.appendChild(yearInput);

    // Add month
    const monthInput = document.createElement('input');
    monthInput.type = 'hidden';
    monthInput.name = 'month';
    monthInput.value = month;
    form.appendChild(monthInput);

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Load initial data when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadAttendanceLog(1);
});

</script>
@endsection
<script>
    // Calendar functionality
let currentAttendanceData = [];
let weeklyScheduleData = {};
let holidaysData = [];
let leavesData = {};

// Load calendar on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCalendar();

    // Add event listeners for dropdowns
    document.getElementById('calendarYearSelect').addEventListener('change', loadCalendar);
    document.getElementById('calendarMonthSelect').addEventListener('change', loadCalendar);
});

function loadCalendar() {
    const year = document.getElementById('calendarYearSelect').value;
    const month = document.getElementById('calendarMonthSelect').value;
    const userId = {{ $employee->empId }};

    // Show loading
    document.getElementById('calendarDays').innerHTML = '<div class="text-center p-4"><i class="ph-duotone ph-spinner ph-spin"></i> Loading...</div>';

    // Fetch attendance data
    fetch('{{ route("user.getMonthlyAttendance") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            year: year,
            month: month,
            user_id: userId
        })
    })
    .then(response => response.json())
    .then(data => {
        currentAttendanceData = data.attendance || data; // Handle both old and new response format
        weeklyScheduleData = data.weeklySchedule || {};
        holidaysData = data.holidays || [];
        leavesData = data.leaves || {};
        generateCalendar(year, month);
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('calendarDays').innerHTML = '<div class="text-center p-4 text-danger">Error loading calendar</div>';
    });
}

function generateCalendar(year, month) {
    const firstDay = new Date(year, month - 1, 1);
    const lastDay = new Date(year, month, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();

    let calendarHTML = '';

    // Add empty cells for days before the first day of the month
    for (let i = 0; i < startingDayOfWeek; i++) {
        const prevMonthDay = new Date(year, month - 1, -startingDayOfWeek + i + 1).getDate();
        calendarHTML += `<div class="calendar-day other-month">${prevMonthDay}</div>`;
    }

    // Add days of the current month
    for (let day = 1; day <= daysInMonth; day++) {
        const dateString = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const attendanceRecord = currentAttendanceData.find(record => record.present_date === dateString);
        const dayOfWeek = new Date(year, month - 1, day).getDay();
        const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        const dayName = dayNames[dayOfWeek];
        const today = new Date();
        const currentDate = new Date(year, month - 1, day);

        let dayClass = 'calendar-day';
        let tooltip = '';

        // Check if this is today's date
        const isToday = currentDate.toDateString() === today.toDateString();
        if (isToday) {
            dayClass += ' today';
        }

        // Check if this day is a declared holiday
        const isHoliday = holidaysData.includes(dateString);

        // Check if this day is closed according to weekly schedule
        const isClosedDay = weeklyScheduleData[dayName] && weeklyScheduleData[dayName].status === 'close';

        // Check if this day is a leave day
        const isLeaveDay = leavesData[dateString];

        if (attendanceRecord) {
            // Use actual_status if available (dynamically calculated), otherwise use present_status
            const actualStatus = attendanceRecord.actual_status || attendanceRecord.present_status;

            switch (actualStatus) {
                case 'present':
                    dayClass += ' present';
                    let presentTooltip = `Present - In: ${formatTime(attendanceRecord.in_time)}`;
                    if (attendanceRecord.out_time) {
                        presentTooltip += `, Out: ${formatTime(attendanceRecord.out_time)}`;
                    }
                    if (attendanceRecord.opening_time) {
                        presentTooltip += ` (On time - Office opens: ${formatTime(attendanceRecord.opening_time)})`;
                    }
                    tooltip = presentTooltip;
                    break;
                case 'working':
                    dayClass += ' present';
                    let workingTooltip = `Working - In: ${formatTime(attendanceRecord.in_time)}`;
                    if (attendanceRecord.opening_time) {
                        workingTooltip += ` (Office opens: ${formatTime(attendanceRecord.opening_time)})`;
                    }
                    tooltip = workingTooltip;
                    break;
                case 'late':
                    dayClass += ' late';
                    let lateTooltip = `Late - In: ${formatTime(attendanceRecord.in_time)}`;
                    if (attendanceRecord.out_time) {
                        lateTooltip += `, Out: ${formatTime(attendanceRecord.out_time)}`;
                    }
                    if (attendanceRecord.opening_time) {
                        lateTooltip += ` (Office opens: ${formatTime(attendanceRecord.opening_time)})`;
                    }
                    tooltip = lateTooltip;
                    break;
                case 'absent':
                    dayClass += ' absent';
                    tooltip = `Absent${attendanceRecord.reason ? ' - ' + attendanceRecord.reason : ''}`;
                    break;
                case 'leave':
                    dayClass += ' leave';
                    tooltip = `Leave${attendanceRecord.reason ? ' - ' + attendanceRecord.reason : ''}`;
                    break;
                default:
                    // Handle cases where present_status doesn't match standard values
                    if (attendanceRecord.is_late) {
                        dayClass += ' late';
                        tooltip = `Late - In: ${formatTime(attendanceRecord.in_time)}${attendanceRecord.out_time ? ', Out: ' + formatTime(attendanceRecord.out_time) : ''}`;
                        if (attendanceRecord.opening_time) {
                            tooltip += ` (Office opens: ${formatTime(attendanceRecord.opening_time)})`;
                        }
                    } else if (attendanceRecord.present_status === 'present' || attendanceRecord.present_status === 'working') {
                        dayClass += ' present';
                        tooltip = `Present - In: ${formatTime(attendanceRecord.in_time)}${attendanceRecord.out_time ? ', Out: ' + formatTime(attendanceRecord.out_time) : ''}`;
                        if (attendanceRecord.opening_time) {
                            tooltip += ` (Office opens: ${formatTime(attendanceRecord.opening_time)})`;
                        }
                    } else if (isLeaveDay) {
                        dayClass += ' leave';
                        tooltip = `${isLeaveDay.leave_type} Leave${isLeaveDay.reason ? ' - ' + isLeaveDay.reason : ''}`;
                    } else if (isHoliday) {
                        dayClass += ' holiday';
                        tooltip = 'Holiday';
                    } else if (isClosedDay) {
                        dayClass += ' holiday';
                        tooltip = 'Closed Day';
                    } else {
                        dayClass += ' absent';
                        tooltip = 'Absent';
                    }
            }
        } else {
            // No attendance record
            if (isLeaveDay) {
                dayClass += ' leave';
                tooltip = `${isLeaveDay.leave_type} Leave${isLeaveDay.reason ? ' - ' + isLeaveDay.reason : ''}`;
            } else if (isHoliday) {
                dayClass += ' holiday';
                tooltip = 'Holiday';
            } else if (isClosedDay) {
                dayClass += ' holiday';
                tooltip = 'Closed Day';
            } else if (currentDate > today) {
                // Future date - no status
                tooltip = 'Future date';
            } else {
                // Past working day with no record - check if it should be working day
                const isWorkingDay = weeklyScheduleData[dayName] && weeklyScheduleData[dayName].status === 'open';
                if (isWorkingDay) {
                    dayClass += ' absent';
                    tooltip = 'Absent';
                } else {
                    // No schedule defined or closed day
                    dayClass += ' holiday';
                    tooltip = 'Non-working day';
                }
            }
        }

        calendarHTML += `<div class="${dayClass}" data-bs-toggle="tooltip" title="${tooltip}" onclick="loadDailyActivity('${dateString}')">${day}</div>`;
    }

    // Add empty cells for days after the last day of the month
    const totalCells = Math.ceil((startingDayOfWeek + daysInMonth) / 7) * 7;
    const remainingCells = totalCells - (startingDayOfWeek + daysInMonth);

    for (let i = 1; i <= remainingCells; i++) {
        calendarHTML += `<div class="calendar-day other-month">${i}</div>`;
    }

    document.getElementById('calendarDays').innerHTML = calendarHTML;

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-load today's activity if we're viewing current month
    const today = new Date();
    const currentYear = parseInt(year);
    const currentMonth = parseInt(month);

    if (currentYear === today.getFullYear() && currentMonth === (today.getMonth() + 1)) {
        const todayString = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
        setTimeout(() => {
            loadDailyActivity(todayString);
        }, 100); // Small delay to ensure DOM is ready
    }
}

function formatTime(timeString) {
    if (!timeString) return 'N/A';

    const time = new Date('1970-01-01T' + timeString + 'Z');
    return time.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true,
        timeZone: 'UTC'
    });
}

function loadDailyActivity(date) {
    const userId = {{ $employee->empId }};
    const activityContent = document.getElementById('dailyActivityContent');

    // Show loading
    activityContent.innerHTML = `
        <div class="text-center py-4">
            <i class="ph-duotone ph-spinner ph-spin f-24 mb-3"></i>
            <p>Loading activity details...</p>
        </div>
    `;

    // Fetch daily activity data
    fetch('{{ route("user.getDailyActivity") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            user_id: userId,
            date: date
        })
    })
    .then(response => response.json())
    .then(data => {
        displayDailyActivity(data);
    })
    .catch(error => {
        console.error('Error:', error);
        activityContent.innerHTML = `
            <div class="text-center py-4 text-danger">
                <i class="ph-duotone ph-warning-circle f-24 mb-3"></i>
                <p>Error loading activity details</p>
            </div>
        `;
    });
}

function displayDailyActivity(data) {
    const activityContent = document.getElementById('dailyActivityContent');

    let statusBadge = `<span class="badge bg-${data.statusColor}"><i class="ph-duotone ${data.statusIcon} me-1"></i>${data.status.charAt(0).toUpperCase() + data.status.slice(1)}</span>`;

    let activityHTML = `
        <div class="daily-activity-details">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">${data.date}</h6>
                ${statusBadge}
            </div>
            <div class="activity-info">
                <div class="row g-3">
    `;

    // Add day information
    activityHTML += `
        <div class="col-12">
            <div class="info-item">
                <i class="ph-duotone ph-calendar text-muted me-2"></i>
                <span class="text-muted">Day:</span>
                <strong class="ms-1">${data.dayName}</strong>
            </div>
        </div>
    `;

    // Add time information based on status
    if (data.status === 'present' || data.status === 'late') {
        if (data.inTime) {
            activityHTML += `
                <div class="col-6">
                    <div class="info-item">
                        <i class="ph-duotone ph-sign-in text-success me-2"></i>
                        <span class="text-muted">In Time:</span>
                        <strong class="ms-1">${formatTime(data.inTime)}</strong>
                    </div>
                </div>
            `;
        }

        if (data.outTime) {
            activityHTML += `
                <div class="col-6">
                    <div class="info-item">
                        <i class="ph-duotone ph-sign-out text-danger me-2"></i>
                        <span class="text-muted">Out Time:</span>
                        <strong class="ms-1">${formatTime(data.outTime)}</strong>
                    </div>
                </div>
            `;
        }

        if (data.workingHours) {
            activityHTML += `
                <div class="col-6">
                    <div class="info-item">
                        <i class="ph-duotone ph-clock text-info me-2"></i>
                        <span class="text-muted">Working Hours:</span>
                        <strong class="ms-1">${data.workingHours}</strong>
                    </div>
                </div>
            `;
        }

        if (data.isLate && data.lateBy) {
            activityHTML += `
                <div class="col-6">
                    <div class="info-item">
                        <i class="ph-duotone ph-warning text-warning me-2"></i>
                        <span class="text-muted">Late By:</span>
                        <strong class="ms-1 text-warning">${data.lateBy}</strong>
                    </div>
                </div>
            `;
        }
    }

    // Add office hours
    if (data.openingTime && data.closingTime) {
        activityHTML += `
            <div class="col-12">
                <div class="info-item">
                    <i class="ph-duotone ph-buildings text-primary me-2"></i>
                    <span class="text-muted">Office Hours:</span>
                    <strong class="ms-1">${formatTime(data.openingTime)} - ${formatTime(data.closingTime)}</strong>
                </div>
            </div>
        `;
    }

    // Add leave information
    if (data.status === 'leave' && data.leaveType) {
        activityHTML += `
            <div class="col-12">
                <div class="info-item">
                    <i class="ph-duotone ph-airplane-takeoff text-info me-2"></i>
                    <span class="text-muted">Leave Type:</span>
                    <strong class="ms-1">${data.leaveType}</strong>
                </div>
            </div>
        `;

        if (data.leaveReason) {
            activityHTML += `
                <div class="col-12">
                    <div class="info-item">
                        <i class="ph-duotone ph-note text-muted me-2"></i>
                        <span class="text-muted">Reason:</span>
                        <span class="ms-1">${data.leaveReason}</span>
                    </div>
                </div>
            `;
        }
    }

    // Add holiday information
    if (data.status === 'holiday' && data.holidayName) {
        activityHTML += `
            <div class="col-12">
                <div class="info-item">
                    <i class="ph-duotone ph-calendar-x text-secondary me-2"></i>
                    <span class="text-muted">Holiday:</span>
                    <strong class="ms-1">${data.holidayName}</strong>
                </div>
            </div>
        `;
    }

    // Add reason for absence
    if (data.status === 'absent' && data.reason) {
        activityHTML += `
            <div class="col-12">
                <div class="info-item">
                    <i class="ph-duotone ph-note text-muted me-2"></i>
                    <span class="text-muted">Reason:</span>
                    <span class="ms-1">${data.reason}</span>
                </div>
            </div>
        `;
    }

    activityHTML += `
                </div>
            </div>
        </div>
    `;

    activityContent.innerHTML = activityHTML;
}

</script>

<style>
    .attendance-calendar .calendar-grid {
        border: 1px solid #e9ecef;
        border-radius: 0.375rem;
        overflow: hidden;
    }

    .calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background-color: #f8f9fa;
    }

    .weekday {
        padding: 0.75rem;
        text-align: center;
        font-weight: 600;
        border-right: 1px solid #e9ecef;
        color: #6c757d;
    }

    .weekday:last-child {
        border-right: none;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
    }

    .calendar-day {
        padding: 0.75rem;
        text-align: center;
        border-right: 1px solid #e9ecef;
        border-bottom: 1px solid #e9ecef;
        cursor: pointer;
        transition: all 0.2s ease;
        min-height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
    }

    .calendar-day:nth-child(7n) {
        border-right: none;
    }

    .calendar-day:hover {
        background-color: #f8f9fa;
    }

    .calendar-day.other-month {
        color: #adb5bd;
        background-color: #f8f9fa;
    }

    .calendar-day.present {
        background-color: #d1e7dd;
        color: #0f5132;
        border-color: #badbcc;
    }

    .calendar-day.late {
        background-color: #fff3cd;
        color: #664d03;
        border-color: #ffecb5;
    }

    .calendar-day.absent {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #f5c2c7;
    }

    .calendar-day.leave {
        background-color: #cff4fc;
        color: #055160;
        border-color: #b6effb;
    }

    .calendar-day.holiday {
        background-color: #e2e3e5;
        color: #41464b;
        border-color: #d3d6d8;
    }

    .legend-item {
        display: flex;
        align-items: center;
        font-size: 0.875rem;
        color: #6c757d;
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 2px;
        margin-right: 0.5rem;
    }

    .ph-spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* Daily Activity Styles */
    .daily-activity-details {
        padding: 0.5rem 0;
    }

    .info-item {
        display: flex;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f1f3f4;
        font-size: 0.875rem;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-item i {
        font-size: 1rem;
        width: 20px;
        text-align: center;
    }

    .calendar-day:hover {
        background-color: #f8f9fa;
        transform: scale(1.05);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .calendar-day.other-month:hover {
        transform: none;
        box-shadow: none;
    }

    /* Today's date highlighting */
    .calendar-day.today {
        border: 2px solid #0d6efd !important;
        font-weight: bold;
        position: relative;
    }

    .calendar-day.today::after {
        content: '';
        position: absolute;
        top: 2px;
        right: 2px;
        width: 6px;
        height: 6px;
        background-color: #0d6efd;
        border-radius: 50%;
    }

    .calendar-day.today:hover {
        border-color: #0a58ca !important;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    /* Today indicator in legend */
    .legend-color.today-indicator {
        border: 2px solid #0d6efd;
        background-color: transparent;
        position: relative;
    }

    .legend-color.today-indicator::after {
        content: '';
        position: absolute;
        top: 1px;
        right: 1px;
        width: 4px;
        height: 4px;
        background-color: #0d6efd;
        border-radius: 50%;
    }
</style>
