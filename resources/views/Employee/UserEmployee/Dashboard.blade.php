@extends('App.Layout')

@section('container')

<!-- Phosphor Icons -->
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@php
    $userId = Auth::user()->id;
    $employeeDetails = null;
    $companyName = 'E-Cashbook Corp';
    $managerName = 'Aditya Sen';

    try {
        $employeeDetails = DB::table('users')
            ->leftJoin('employees', 'users.id', '=', 'employees.empId')
            ->leftJoin('depertments', 'employees.dept_id', '=', 'depertments.id')
            ->leftJoin('designations', 'employees.desig_id', '=', 'designations.id')
            ->where('users.id', $userId)
            ->select(
                'users.name as user_name',
                'users.email as user_email',
                'users.avatar as user_avatar',
                'employees.employee_id',
                'employees.profile_img',
                'employees.work_location',
                'employees.emp_status',
                'depertments.dept_name',
                'designations.designation_name'
            )
            ->first();

        $companyName = DB::table('users')
            ->where('id', Auth::user()->user_add_by)
            ->value('company_name') ?? 'E-Cashbook Corp';

        $managerName = DB::table('users')
            ->where('id', Auth::user()->user_add_by)
            ->value('name') ?? 'Aditya Sen';
    } catch (\Exception $e) {
        // Fallback
    }

    $profileImg = ($employeeDetails && $employeeDetails->profile_img) 
        ? asset('storage/user_employee/' . $employeeDetails->profile_img) 
        : (($employeeDetails && $employeeDetails->user_avatar) 
            ? asset('storage/' . $employeeDetails->user_avatar) 
            : asset('assets/images/user/avatar-2.jpg'));

    function getStatusBadgeClass($status) {
        $s = strtolower($status ?? '');
        if (str_contains($s, 'probation')) return 'bg-warning text-dark';
        if (str_contains($s, 'notice')) return 'bg-danger text-white';
        return 'bg-success text-white';
    }
@endphp

<!-- Custom Premium Dashboard Styling -->
<style>
    /* Card design system */
    .dashboard-card {
        border: 1px solid rgba(226, 230, 238, 0.8);
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        background-color: #ffffff;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        margin-bottom: 20px;
        overflow: hidden;
    }
    .dashboard-card:hover {
        box-shadow: 0 8px 24px rgba(110, 74, 252, 0.06);
    }
    .card-header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #ffffff;
        padding: 14px 20px;
        border-bottom: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-header-gradient h5 {
        margin-bottom: 0;
        font-weight: 600;
        color: #ffffff;
        font-size: 0.95rem;
        letter-spacing: 0.3px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .card-header-gradient i {
        font-size: 1.3rem;
    }
    /* Profile Banner */
    .profile-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.15);
    }
    .profile-banner::after {
        content: '';
        position: absolute;
        width: 320px;
        height: 320px;
        background: rgba(255, 255, 255, 0.04);
        border-radius: 50%;
        bottom: -120px;
        right: -80px;
        pointer-events: none;
    }
    .profile-photo {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.25);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    /* Toggles / Tabs in header */
    .header-btn-group {
        display: flex;
        background: rgba(255, 255, 255, 0.15);
        padding: 2px;
        border-radius: 30px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .header-toggle-btn {
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.85);
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .header-toggle-btn.active {
        background: #ffffff;
        color: #764ba2;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    /* Header Dropdowns */
    .header-select {
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 30px;
        padding: 4px 10px;
        cursor: pointer;
        outline: none;
        transition: all 0.2s ease;
    }
    .header-select option {
        color: #333333;
    }
    .header-select:focus {
        background: #ffffff;
        color: #764ba2;
    }
    /* Stat Blocks */
    .stat-box-container {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: space-between;
    }
    .stat-box-item {
        flex: 1;
        min-width: 100px;
        border-radius: 10px;
        padding: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        text-align: center;
        transition: all 0.2s ease;
    }
    .stat-box-item:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    .stat-box-title {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .stat-box-value {
        font-size: 1.35rem;
        font-weight: 800;
    }
    /* KPI Cards */
    .kpi-card {
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        transition: all 0.2s ease;
    }
    .kpi-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .kpi-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
    }
    /* Radial Performance Score Chart */
    .radial-chart-box {
        position: relative;
        width: 90px;
        height: 90px;
        margin: 0 auto;
    }
    .radial-svg-circle {
        fill: none;
        stroke-width: 4.5;
    }
    .radial-bg {
        stroke: #f1f5f9;
    }
    .radial-progress {
        stroke: url(#gradientPerformance);
        stroke-dasharray: 251.2;
        stroke-dashoffset: calc(251.2 - (251.2 * 76) / 100);
        stroke-linecap: round;
        transform: rotate(-90deg);
        transform-origin: 50% 50%;
        transition: stroke-dashoffset 1s ease-in-out;
    }
    .radial-label-text {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        font-weight: 800;
        color: #1e293b;
    }
    /* Star rating progress bars */
    .bar-row {
        margin-bottom: 12px;
    }
    .bar-label-group {
        display: flex;
        justify-content: space-between;
        font-size: 0.78rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 4px;
    }
    .bar-stars {
        color: #fbbf24;
        font-size: 0.8rem;
        display: flex;
        gap: 1px;
    }
    .bar-container {
        height: 6px;
        background: #f1f5f9;
        border-radius: 30px;
        overflow: hidden;
    }
    .bar-fill {
        height: 100%;
        border-radius: 30px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }
    /* Tasks Table */
    .tasks-table th {
        font-size: 0.72rem;
        text-transform: uppercase;
        font-weight: 700;
        color: #475569;
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 11px 16px;
    }
    .tasks-table td {
        font-size: 0.82rem;
        padding: 11px 16px;
        color: #334155;
    }
    /* Events Timeline */
    .timeline-container {
        position: relative;
        padding-left: 20px;
    }
    .timeline-container::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background: #e2e8f0;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 18px;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-dot {
        position: absolute;
        left: -19px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #ffffff;
        border: 3.5px solid #667eea;
        top: 3px;
        z-index: 2;
    }
    .timeline-content-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s ease;
    }
    .timeline-content-card:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    .timeline-icon-box {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }
</style>

<div class="pc-content py-3">
    <div class="container-fluid px-1">
        
        <!-- 1. Employee Welcome Panel (Top Banner) -->
        <div class="card border-0 shadow-sm profile-banner mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row align-items-center gap-4">
                    <div class="position-relative">
                        <img src="{{ $profileImg }}" alt="Employee Photo" class="rounded-circle profile-photo shadow">
                        <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success border border-2 border-dark px-2 py-1 fs-12">
                            <i class="ph-fill ph-circle text-white me-1" style="font-size: 7px;"></i>Online
                        </span>
                    </div>
                    
                    <div class="flex-grow-1 text-center text-md-start">
                        <div class="d-flex flex-column flex-md-row align-items-center gap-2 mb-2 justify-content-center justify-content-md-start">
                            <h3 class="mb-0 fw-bold text-white fs-4">{{ $employeeDetails ? $employeeDetails->user_name : Auth::user()->name }}</h3>
                            <span class="badge bg-white-20 text-white rounded-pill px-3 py-1 fs-11 fw-semibold">
                                ID: {{ $employeeDetails ? $employeeDetails->employee_id : 'EMP-001' }}
                            </span>
                            <span class="badge rounded-pill px-3 py-1 fs-11 fw-bold {{ getStatusBadgeClass($employeeDetails ? $employeeDetails->emp_status : 'Active') }}">
                                {{ ucfirst($employeeDetails ? $employeeDetails->emp_status : 'Active') }}
                            </span>
                        </div>
                        
                        <p class="text-white-80 mb-3 fs-14 fw-medium">
                            <i class="ph-duotone ph-briefcase me-1 text-warning"></i> {{ $employeeDetails ? $employeeDetails->designation_name : 'Associate Accountant' }}
                            <span class="mx-2 opacity-50">|</span>
                            <i class="ph-duotone ph-buildings me-1 text-warning"></i> {{ $employeeDetails ? $employeeDetails->dept_name : 'Finance & Accounts' }}
                        </p>
                        
                        <div class="row g-2 text-white-70 fs-13">
                            <div class="col-sm-6 col-md-3 d-flex align-items-center justify-content-center justify-content-md-start">
                                <i class="ph-duotone ph-user me-2 text-warning fs-16"></i>
                                <span><strong>Manager:</strong> {{ $managerName }}</span>
                            </div>
                            <div class="col-sm-6 col-md-4 d-flex align-items-center justify-content-center justify-content-md-start">
                                <i class="ph-duotone ph-storefront me-2 text-warning fs-16"></i>
                                <span><strong>Company:</strong> {{ $companyName }}</span>
                            </div>
                            <div class="col-sm-6 col-md-3 d-flex align-items-center justify-content-center justify-content-md-start">
                                <i class="ph-duotone ph-map-pin-line me-2 text-warning fs-16"></i>
                                <span><strong>Location:</strong> {{ $employeeDetails ? $employeeDetails->work_location : 'Head Office' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2nd Row: Only Attendance Details (Changeable Weekly / Monthly) -->
        <div class="row">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header card-header-gradient">
                        <h5>
                            <i class="ph-duotone ph-calendar-check"></i>
                            Attendance Details
                        </h5>
                        <div class="header-btn-group">
                            <button type="button" class="header-toggle-btn active" id="btn-atten-week" onclick="toggleAttendance('weekly')">Weekly</button>
                            <button type="button" class="header-toggle-btn" id="btn-atten-month" onclick="toggleAttendance('monthly')">Monthly</button>
                        </div>
                    </div>
                    <div class="card-body p-3.5">
                        <div class="row g-3 align-items-center">
                            <!-- Clock details (Today) -->
                            <div class="col-lg-4 col-md-12">
                                <div class="border rounded p-3 bg-light">
                                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2 text-uppercase fs-11 letter-spacing-05">
                                        <i class="ph-duotone ph-clock me-1.5"></i>Today's Clock Details
                                    </h6>
                                    <div class="d-flex flex-column gap-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted small fw-bold">Check-In Time</span>
                                            <span class="badge bg-success-light text-success fw-bold punch-in-time">--</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted small fw-bold">Check-Out Time</span>
                                            <span class="badge bg-danger-light text-danger fw-bold punch-out-time">--</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center border-top pt-2">
                                            <span class="text-dark small fw-bold">Total Working Hours</span>
                                            <span class="text-dark fw-extrabold total-working-hours">--</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Static summary counters (Toggled via JS) -->
                            <div class="col-lg-8 col-md-12">
                                <div class="border rounded p-3 bg-white h-100">
                                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2 text-uppercase fs-11 letter-spacing-05">
                                        <i class="ph-duotone ph-chart-bar me-1.5"></i>
                                        <span id="atten-summary-title">Weekly Summary Details</span>
                                    </h6>
                                    <div class="stat-box-container">
                                        <div class="stat-box-item">
                                            <div class="stat-box-title">Present Days</div>
                                            <div class="stat-box-value text-success" id="atten-present">5</div>
                                        </div>
                                        <div class="stat-box-item">
                                            <div class="stat-box-title">Late Marks</div>
                                            <div class="stat-box-value text-warning" id="atten-late">0</div>
                                        </div>
                                        <div class="stat-box-item">
                                            <div class="stat-box-title">Absent Days</div>
                                            <div class="stat-box-value text-danger" id="atten-absent">0</div>
                                        </div>
                                        <div class="stat-box-item">
                                            <div class="stat-box-title">Leave Taken</div>
                                            <div class="stat-box-value text-info" id="atten-leave">0</div>
                                        </div>
                                        <div class="stat-box-item">
                                            <div class="stat-box-title">Work from Home</div>
                                            <div class="stat-box-value text-primary" id="atten-wfh">1</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Side Column: Tasks & Upcoming Tasks -->
            <div class="col-lg-8 col-md-12">
                
                <!-- 3. My Tasks & Projects -->
                <div class="card dashboard-card">
                    <div class="card-header card-header-gradient">
                        <h5>
                            <i class="ph-duotone ph-kanban"></i>
                            My Tasks & Projects
                        </h5>
                        <select class="header-select" id="task-range-filter" onchange="toggleTasksFilter(this.value)">
                            <option value="month">This Month</option>
                            <option value="week">This Week</option>
                            <option value="today">Today</option>
                        </select>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div class="kpi-card" style="border-left: 4px solid #f59e0b;">
                                    <div class="kpi-icon bg-warning-light text-warning">
                                        <i class="ph-bold ph-hourglass-high"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-extrabold text-dark" id="kpi-pending">6</h5>
                                        <small class="text-muted fw-bold">Pending</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="kpi-card" style="border-left: 4px solid #3b82f6;">
                                    <div class="kpi-icon bg-primary-light text-primary">
                                        <i class="ph-bold ph-activity"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-extrabold text-dark" id="kpi-in-progress">8</h5>
                                        <small class="text-muted fw-bold">In Progress</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="kpi-card" style="border-left: 4px solid #10b981;">
                                    <div class="kpi-icon bg-success-light text-success">
                                        <i class="ph-bold ph-checks"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-extrabold text-dark" id="kpi-completed">10</h5>
                                        <small class="text-muted fw-bold">Completed</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="kpi-card" style="border-left: 4px solid #ef4444;">
                                    <div class="kpi-icon bg-danger-light text-danger">
                                        <i class="ph-bold ph-warning-octagon"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-extrabold text-dark" id="kpi-overdue">9</h5>
                                        <small class="text-muted fw-bold">>7d Overdue</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Upcoming Tasks (Filtered Date wise / Weekly / Monthly) -->
                <div class="card dashboard-card">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-semibold text-dark d-flex align-items-center gap-2">
                            <i class="ph-duotone ph-list-bullets text-primary fs-5"></i>
                            Upcoming Tasks Listing
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <input type="text" id="task-datepicker" class="form-control form-control-sm bg-light border text-dark rounded-pill px-3" style="width: 120px; font-size: 0.72rem; cursor: pointer; height: 28px;" placeholder="Pick Date Filter">
                            <select class="form-select form-select-sm rounded-pill text-dark border bg-light" id="task-table-filter" onchange="filterUpcomingTasks(this.value)" style="width: 120px; font-size: 0.72rem; cursor: pointer; height: 28px;">
                                <option value="all">All Tasks</option>
                                <option value="weekly">Weekly Tasks</option>
                                <option value="monthly">Monthly Tasks</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 tasks-table" id="pc-dt-simple">
                                <thead>
                                    <tr>
                                        <th>Task ID</th>
                                        <th>Task Title</th>
                                        <th>Company Name</th>
                                        <th>Task Category</th>
                                        <th>Due Date</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Static/Fallback list will render here via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Side Column: Performance, Calendar & Events -->
            <div class="col-lg-4 col-md-12">
                
                <!-- 5. Performance & Review (Month wise) -->
                <div class="card dashboard-card">
                    <div class="card-header card-header-gradient">
                        <h5>
                            <i class="ph-duotone ph-chart-line-up"></i>
                            Performance & Review
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-4 border-bottom pb-3 mb-3">
                            <div class="radial-chart-box">
                                <svg width="90" height="90" viewBox="0 0 90 90">
                                    <defs>
                                        <linearGradient id="gradientPerformance" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#667eea" />
                                            <stop offset="100%" stop-color="#764ba2" />
                                        </linearGradient>
                                    </defs>
                                    <circle class="radial-svg-circle radial-bg" cx="45" cy="45" r="40" />
                                    <circle class="radial-svg-circle radial-progress" cx="45" cy="45" r="40" />
                                </svg>
                                <div class="radial-label-text">
                                    <span>76%</span>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1 fs-15">Performance Score</h6>
                                <span class="badge text-white px-2.5 py-1 fs-11" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 2px 8px rgba(16,185,129,0.2);">Excellent</span>
                            </div>
                        </div>

                        <!-- Rating Categories with custom progress bars -->
                        <div class="d-flex flex-column gap-1 mb-3">
                            <div class="bar-row">
                                <div class="bar-label-group">
                                    <span>Work Performance</span>
                                    <div class="bar-stars">
                                        <i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i>
                                    </div>
                                </div>
                                <div class="bar-container">
                                    <div class="bar-fill" style="width: 95%;"></div>
                                </div>
                            </div>
                            <div class="bar-row">
                                <div class="bar-label-group">
                                    <span>Skill & Knowledge</span>
                                    <div class="bar-stars">
                                        <i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star opacity-20"></i><i class="ph-fill ph-star opacity-20"></i>
                                    </div>
                                </div>
                                <div class="bar-container">
                                    <div class="bar-fill" style="width: 60%;"></div>
                                </div>
                            </div>
                            <div class="bar-row">
                                <div class="bar-label-group">
                                    <span>Attendance & Punctuality</span>
                                    <div class="bar-stars">
                                        <i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star opacity-20"></i><i class="ph-fill ph-star opacity-20"></i>
                                    </div>
                                </div>
                                <div class="bar-container">
                                    <div class="bar-fill" style="width: 65%;"></div>
                                </div>
                            </div>
                            <div class="bar-row">
                                <div class="bar-label-group">
                                    <span>Teamwork / Behaviour</span>
                                    <div class="bar-stars">
                                        <i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i>
                                    </div>
                                </div>
                                <div class="bar-container">
                                    <div class="bar-fill" style="width: 90%;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Review Comment -->
                        <div class="border rounded p-3 bg-light position-relative" style="border-left: 4px solid #764ba2 !important;">
                            <span class="position-absolute end-0 top-0 p-2 text-muted-50" style="font-size: 1.5rem; opacity: 0.15; line-height: 1;"><i class="ph-fill ph-quotes"></i></span>
                            <small class="text-muted fw-bold d-block mb-1 text-uppercase letter-spacing-05 fs-10">Manager Review Remarks</small>
                            <p class="mb-0 text-dark fs-12 italic fw-normal leading-relaxed" style="line-height: 1.45;">
                                "Demonstrates excellent ownership of assigned projects and collaborates effectively with the team. Needs to focus on clock-in punctuality and continuous skill upskilling on advanced GST reconciliation tools."
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 6. Calendar & Events -->
                <div class="card dashboard-card">
                    <div class="card-header card-header-gradient">
                        <h5>
                            <i class="ph-duotone ph-calendar"></i>
                            Calendar & Events
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="timeline-container">
                            
                            <!-- Today's Meetings -->
                            <div class="timeline-item">
                                <div class="timeline-dot" style="border-color: #667eea;"></div>
                                <div class="timeline-content-card">
                                    <div class="timeline-icon-box bg-primary-light text-primary">
                                        <i class="ph-bold ph-users-three"></i>
                                    </div>
                                    <div>
                                        <div class="event-title">Q2 Financial Review Meeting</div>
                                        <div class="event-meta">Today, 03:30 PM - 04:30 PM (Zoom)</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upcoming Holidays -->
                            <div class="timeline-item">
                                <div class="timeline-dot" style="border-color: #10b981;"></div>
                                <div class="timeline-content-card">
                                    <div class="timeline-icon-box bg-success-light text-success">
                                        <i class="ph-bold ph-sun"></i>
                                    </div>
                                    <div>
                                        <div class="event-title">Independence Day</div>
                                        <div class="event-meta">National Holiday on 15 Aug, 2026</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Birthdays -->
                            <div class="timeline-item">
                                <div class="timeline-dot" style="border-color: #f59e0b;"></div>
                                <div class="timeline-content-card">
                                    <div class="timeline-icon-box bg-warning-light text-warning">
                                        <i class="ph-bold ph-cake"></i>
                                    </div>
                                    <div>
                                        <div class="event-title">Rohan Sharma's Birthday</div>
                                        <div class="event-meta">Tomorrow, 30 May (Send Wishes!)</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Compliance Training -->
                            <div class="timeline-item">
                                <div class="timeline-dot" style="border-color: #ef4444;"></div>
                                <div class="timeline-content-card">
                                    <div class="timeline-icon-box bg-danger-light text-danger">
                                        <i class="ph-bold ph-shield-check"></i>
                                    </div>
                                    <div>
                                        <div class="event-title">GST Compliance & Security Training</div>
                                        <div class="event-meta">June 04, 11:00 AM (Mandatory Session)</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Edit Task Status Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <h5 class="modal-title fw-bold" id="editTaskModalLabel">
          <i class="ti ti-edit-circle me-2"></i> Update Task Status
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="updateTaskForm">
          <input type="hidden" id="task_id">

          <div class="mb-3">
            <label class="form-label fw-bold">Task Title</label>
            <input type="text" id="task_title" class="form-control" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Current Status</label>
            <select id="task_status" class="form-select">
              <option value="Pending">Pending</option>
              <option value="In Progress">In Progress</option>
              <option value="Completed">Completed</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Deadline</label>
            <input type="text" id="task_deadline" class="form-control" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Priority</label>
            <input type="text" id="task_priority" class="form-control" readonly>
          </div>

          <button type="submit" id="updateBtn" class="btn btn-primary w-100 fw-bold">
            <i class="ti ti-refresh me-1"></i> Update Status
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('page-script')
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    // Constants & Static Datasets (Make all statistics static as requested)
    const COMPANY_NAME = "{{ $companyName }}";

    // Static Attendance Datasets
    const ATTENDANCE_STATS = {
        weekly: {
            present: 5,
            late: 0,
            absent: 0,
            leave: 0,
            wfh: 1
        },
        monthly: {
            present: 20,
            late: 2,
            absent: 1,
            leave: 1,
            wfh: 3
        }
    };

    // Static KPI Tasks Datasets
    const TASK_COUNTERS = {
        today: { pending: 1, progress: 2, completed: 3, overdue: 0 },
        week: { pending: 3, progress: 4, completed: 5, overdue: 2 },
        month: { pending: 6, progress: 8, completed: 10, overdue: 9 }
    };

    // Static Upcoming Tasks Dataset
    const UPCOMING_TASKS = [
        { id: 1024, title: "Submit GSTR-1 Monthly Return summary to CA", company: COMPANY_NAME, category: "Taxation", due_date: "2026-06-05", priority: "High", status: "Pending" },
        { id: 1025, title: "Perform weekly Bank Statement Reconciliation", company: COMPANY_NAME, category: "Banking", due_date: "2026-06-07", priority: "Medium", status: "In Progress" },
        { id: 1026, title: "Verify vendor TDS deductions and file logs", company: COMPANY_NAME, category: "Taxation", due_date: "2026-06-10", priority: "High", status: "Pending" },
        { id: 1027, title: "Reconcile Cashbook transactions with supervisor", company: COMPANY_NAME, category: "Accounting", due_date: "2026-06-12", priority: "Low", status: "Completed" },
        { id: 1028, title: "Submit travel expenditure reimbursement claim docs", company: COMPANY_NAME, category: "Payroll", due_date: "2026-06-15", priority: "Medium", status: "Pending" }
    ];

    $(document).ready(function() {
        // Initialize dynamic clock details
        fetchAttendanceData();

        // Load initial toggles
        toggleAttendance('weekly');
        toggleTasksFilter('month');
        renderUpcomingTasks(UPCOMING_TASKS);

        // Initialize task date filter datepicker
        flatpickr("#task-datepicker", {
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                filterUpcomingTasksByDate(dateStr);
            }
        });
    });

    // 1. Attendance Toggle (Weekly/Monthly)
    function toggleAttendance(type) {
        // Toggle active button style
        if (type === 'weekly') {
            $('#btn-atten-week').addClass('active');
            $('#btn-atten-month').removeClass('active');
            $('#atten-summary-title').text('Weekly Summary Details');
        } else {
            $('#btn-atten-month').addClass('active');
            $('#btn-atten-week').removeClass('active');
            $('#atten-summary-title').text('Monthly Summary Details');
        }

        // Load values
        const data = ATTENDANCE_STATS[type];
        $('#atten-present').text(data.present);
        $('#atten-late').text(data.late);
        $('#atten-absent').text(data.absent);
        $('#atten-leave').text(data.leave);
        $('#atten-wfh').text(data.wfh);
    }

    // 2. Today's Punch details loading
    function fetchAttendanceData() {
        $.ajax({
            url: "{{ route('attendance.today') }}",
            method: "GET",
            success: function(response) {
                if (response.status === 'absent') {
                    $('.punch-in-time').text('--');
                    $('.punch-out-time').text('--');
                    $('.total-working-hours').text('--');
                } else {
                    const data = response.data;
                    $('.punch-in-time').text(formatTime(data.in_time));
                    $('.punch-out-time').text(formatTime(data.out_time));
                    $('.total-working-hours').text(data.total_working_hours || '--');
                }
            },
            error: function() {
                // Fail gracefully, keep --
            }
        });
    }

    function formatTime(timeStr) {
        if (!timeStr) return '--';
        const time = new Date('1970-01-01T' + timeStr);
        return time.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    // 3. KPI Tasks Range Toggle
    function toggleTasksFilter(range) {
        const counters = TASK_COUNTERS[range];
        $('#kpi-pending').text(counters.pending);
        $('#kpi-in-progress').text(counters.progress);
        $('#kpi-completed').text(counters.completed);
        $('#kpi-overdue').text(counters.overdue);
    }

    // 4. Render and Filter Upcoming Tasks table
    function renderUpcomingTasks(taskList) {
        const tbody = $("#pc-dt-simple tbody");
        tbody.empty();

        if (taskList.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No matching tasks found</td>
                </tr>
            `);
            return;
        }

        taskList.forEach(task => {
            let priorityBadge = getPriorityBadge(task.priority);
            let statusBadge = getStatusBadge(task.status);
            tbody.append(`
                <tr>
                    <td class="fw-semibold text-primary">#TSK-${task.id}</td>
                    <td>
                        <div class="fw-semibold text-dark mb-0">${task.title}</div>
                    </td>
                    <td><span class="badge bg-light-secondary text-secondary rounded-pill px-2.5 py-1 fs-12 fw-medium">${task.company}</span></td>
                    <td><span class="badge bg-light-primary text-primary rounded-pill px-2.5 py-1 fs-12 fw-medium">${task.category}</span></td>
                    <td>${task.due_date}</td>
                    <td>${priorityBadge}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <a href="#!" class="btn btn-sm btn-link-primary p-1" onclick="viewTaskDetails(${task.id})">
                            <i class="ti ti-edit-circle fs-5"></i>
                        </a>
                    </td>
                </tr>
            `);
        });
    }

    function getPriorityBadge(priority) {
        switch (priority.toLowerCase()) {
            case 'high':
                return `<span class="badge bg-light-danger text-danger rounded-pill px-2.5 py-0.5 fs-11 fw-semibold">High</span>`;
            case 'medium':
                return `<span class="badge bg-light-warning text-warning rounded-pill px-2.5 py-0.5 fs-11 fw-semibold">Medium</span>`;
            default:
                return `<span class="badge bg-light-info text-info rounded-pill px-2.5 py-0.5 fs-11 fw-semibold">Low</span>`;
        }
    }

    function getStatusBadge(status) {
        switch (status.toLowerCase()) {
            case 'pending':
                return `<span class="badge bg-light-warning text-warning rounded-pill px-2.5 py-0.5 fs-11 fw-medium">Pending</span>`;
            case 'in progress':
                return `<span class="badge bg-light-primary text-primary rounded-pill px-2.5 py-0.5 fs-11 fw-medium">In Progress</span>`;
            case 'completed':
            case 'complete':
                return `<span class="badge bg-light-success text-success rounded-pill px-2.5 py-0.5 fs-11 fw-medium">Completed</span>`;
            default:
                return `<span class="badge bg-light-secondary text-secondary rounded-pill px-2.5 py-0.5 fs-11 fw-medium">${status}</span>`;
        }
    }

    // Filter by dropdown select
    function filterUpcomingTasks(filterType) {
        // Reset datepicker value
        document.getElementById('task-datepicker').value = "";

        if (filterType === 'all') {
            renderUpcomingTasks(UPCOMING_TASKS);
            return;
        }

        const today = new Date();
        let targetDate = new Date();

        if (filterType === 'weekly') {
            targetDate.setDate(today.getDate() + 7);
        } else if (filterType === 'monthly') {
            targetDate.setDate(today.getDate() + 30);
        }

        const filtered = UPCOMING_TASKS.filter(task => {
            const taskDate = new Date(task.due_date);
            return taskDate >= today && taskDate <= targetDate;
        });

        renderUpcomingTasks(filtered);
    }

    // Filter by date picker
    function filterUpcomingTasksByDate(dateStr) {
        // Reset select filter dropdown to all
        $('#task-table-filter').val('all');

        if (!dateStr) {
            renderUpcomingTasks(UPCOMING_TASKS);
            return;
        }

        const filtered = UPCOMING_TASKS.filter(task => task.due_date === dateStr);
        renderUpcomingTasks(filtered);
    }

    // Modal display for static upcoming tasks
    function viewTaskDetails(id) {
        const task = UPCOMING_TASKS.find(t => t.id === id);
        if (!task) return;

        $('#task_id').val(task.id);
        $('#task_title').val(task.title);
        $('#task_priority').val(task.priority);
        $('#task_deadline').val(task.due_date);
        $('#task_status').val(task.status);

        if (task.status.toLowerCase() === 'completed') {
            $('#task_status').prop('disabled', true);
            $('#updateBtn').prop('disabled', true).text('Already Completed');
        } else {
            $('#task_status').prop('disabled', false);
            $('#updateBtn').prop('disabled', false).text('Update Status');
        }

        $('#editTaskModal').modal('show');
    }

    $('#updateTaskForm').on('submit', function(e) {
        e.preventDefault();
        const id = parseInt($('#task_id').val());
        const status = $('#task_status').val();

        // Update local static dataset
        const task = UPCOMING_TASKS.find(t => t.id === id);
        if (task) {
            task.status = status;
            renderUpcomingTasks(UPCOMING_TASKS);
            $('#editTaskModal').modal('hide');
            alert('Task status updated successfully (Mock)!');
        }
    });
</script>

@endsection
