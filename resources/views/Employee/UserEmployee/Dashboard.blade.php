@extends('App.Layout')

@section('container')

<!-- Phosphor Icons -->
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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

    .bar-stars .ph-star {
        font-size: 14px;
        margin-right: 2px;
    }

    .bar-stars .text-warning {
        color: #f59e0b !important;
    }

    .bar-stars .opacity-20 {
        opacity: .2;
    }
</style>

<div class="pc-content py-3">
    <div class="container-fluid px-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0 text-secondary fw-semibold">Employee Dashboard</h4>
            <a href="javascript:void(0);" id="start-employee-dashboard-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                <u>How does this Page works?</u>
            </a>
        </div>

        <!-- 1. Employee Welcome Panel (Top Banner) -->
        <div class="card border-0 shadow-sm profile-banner mb-4" id="employee-welcome-banner">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row align-items-center gap-4">

                    <!-- Profile Image -->
                    <div class="position-relative">
                        <img src="{{ asset('assets/images/user/avatar-2.jpg') }}"
                            alt="Employee Photo"
                            id="employeePhoto"
                            class="rounded-circle profile-photo shadow">

                        <span id="employeeOnlineStatus"
                            class="position-absolute bottom-0 end-0 badge rounded-pill bg-success border border-2 border-dark px-2 py-1 fs-12">
                            <i class="ph-fill ph-circle text-white me-1" style="font-size:7px;"></i>
                            Online
                        </span>
                    </div>

                    <!-- Employee Info -->
                    <div class="flex-grow-1 text-center text-md-start">

                        <div class="d-flex flex-column flex-md-row align-items-center gap-2 mb-2 justify-content-center justify-content-md-start">

                            <h3 class="mb-0 fw-bold text-white fs-4" id="employeeName">
                                Loading...
                            </h3>

                            <span class="badge bg-white-20 text-white rounded-pill px-3 py-1 fs-11 fw-semibold"
                                id="employeeId">
                                ID: --
                            </span>

                            <span class="badge rounded-pill px-3 py-1 fs-11 fw-bold bg-success text-white"
                                id="employeeStatus">
                                Active
                            </span>

                        </div>

                        <p class="text-white-80 mb-3 fs-14 fw-medium">
                            <i class="ph-duotone ph-briefcase me-1 text-warning"></i>
                            <span id="employeeDesignation">Loading...</span>

                            <span class="mx-2 opacity-50">|</span>

                            <i class="ph-duotone ph-buildings me-1 text-warning"></i>
                            <span id="employeeDepartment">Loading...</span>
                        </p>

                        <div class="row g-2 text-white-70 fs-13">

                            <div class="col-sm-6 col-md-3 d-flex align-items-center justify-content-center justify-content-md-start">
                                <i class="ph-duotone ph-user me-2 text-warning fs-16"></i>
                                <span>
                                    <strong>Manager:</strong>
                                    <span id="managerName">Loading...</span>
                                </span>
                            </div>

                            <div class="col-sm-6 col-md-4 d-flex align-items-center justify-content-center justify-content-md-start">
                                <i class="ph-duotone ph-storefront me-2 text-warning fs-16"></i>
                                <span>
                                    <strong>Company:</strong>
                                    <span id="companyName">Loading...</span>
                                </span>
                            </div>

                            <div class="col-sm-6 col-md-3 d-flex align-items-center justify-content-center justify-content-md-start">
                                <i class="ph-duotone ph-map-pin-line me-2 text-warning fs-16"></i>
                                <span>
                                    <strong>Location:</strong>
                                    <span id="employeeLocation">Loading...</span>
                                </span>
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
                                <div class="border rounded p-3 bg-light" id="clock-details-card">
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
                                <div class="border rounded p-3 bg-white h-100" id="attendance-summary-card">
                                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2 text-uppercase fs-11 letter-spacing-05">
                                        <i class="ph-duotone ph-chart-bar me-1.5"></i>
                                        <span id="atten-summary-title">Weekly Summary Details</span>
                                    </h6>
                                    <div class="stat-box-container">
                                        <div class="stat-box-item">
                                            <div class="stat-box-title">Present Days</div>
                                            <div class="stat-box-value text-success" id="atten-present">0</div>
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
                                            <div class="stat-box-value text-primary" id="atten-wfh">0</div>
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
                <div class="card dashboard-card" id="tasks-projects-card">
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
                                        <h5 class="mb-0 fw-extrabold text-dark" id="kpi-pending">0</h5>
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
                                        <h5 class="mb-0 fw-extrabold text-dark" id="kpi-in-progress">0</h5>
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
                                        <h5 class="mb-0 fw-extrabold text-dark" id="kpi-completed">0</h5>
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
                                        <h5 class="mb-0 fw-extrabold text-dark" id="kpi-overdue">0</h5>
                                        <small class="text-muted fw-bold">>7d Overdue</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Upcoming Tasks (Filtered Date wise / Weekly / Monthly) -->
                <div class="card dashboard-card" id="upcoming-tasks-card">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-semibold text-dark d-flex align-items-center gap-2">
                            <i class="ph-duotone ph-list-bullets text-primary fs-5"></i>
                            Upcoming Tasks Listing
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <input type="text" id="task-datepicker" class="form-control form-control-sm bg-light border text-dark rounded-pill px-3" style="width: 120px; font-size: 0.72rem; cursor: pointer; height: 28px;" placeholder="Pick Date Filter">
                            <select class="form-select form-select-sm rounded-pill text-dark border bg-light" id="task-table-filter" onchange="filterUpcomingTasks(this.value)" style="width: 120px; font-size: 0.72rem; cursor: pointer; height: 28px;">
                                <option value="today" selected>Today Tasks</option>
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
                <div class="card dashboard-card" id="performance-review-card">
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

                                    <circle class="radial-svg-circle radial-progress"
                                        cx="45"
                                        cy="45"
                                        r="40"
                                        style="
                                            stroke: url(#gradientPerformance);
                                            stroke-width: 8;
                                            fill: none;
                                            stroke-linecap: round;
                                            transform: rotate(-90deg);
                                            transform-origin: center;
                                        ">
                                    </circle>
                                </svg>

                                <div class="radial-label-text">
                                    <span id="performancePercentage" style="font-size: medium;">0%</span>
                                </div>
                            </div>

                            <div>
                                <h6 class="fw-bold text-dark mb-1 fs-15">
                                    Performance Score
                                </h6>

                                <span
                                    id="performanceStatus"
                                    class="badge text-white px-2.5 py-1 fs-11"
                                    style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 2px 8px rgba(16,185,129,0.2);">
                                    Loading...
                                </span>

                                <div class="mt-1">
                                    <small class="text-muted" id="reviewMonthText">
                                        Loading review...
                                    </small>
                                </div>
                            </div>

                        </div>

                        <!-- Rating Categories -->
                        <div class="d-flex flex-column gap-1 mb-3">

                            <!-- Work Performance -->
                            <div class="bar-row">
                                <div class="bar-label-group">
                                    <span>Work Performance</span>

                                    <div class="bar-stars" id="workStars">
                                        <i class="ph-fill ph-star opacity-20"></i>
                                        <i class="ph-fill ph-star opacity-20"></i>
                                        <i class="ph-fill ph-star opacity-20"></i>
                                        <i class="ph-fill ph-star opacity-20"></i>
                                        <i class="ph-fill ph-star opacity-20"></i>
                                    </div>
                                </div>

                                <div class="bar-container">
                                    <div class="bar-fill" id="workRatingBar" style="width:0%;"></div>
                                </div>
                            </div>

                            <!-- Skill -->
                            <div class="bar-row">
                                <div class="bar-label-group">
                                    <span>Skill & Knowledge</span>

                                    <div class="bar-stars" id="skillStars">
                                        <i class="ph-fill ph-star opacity-20"></i>
                                        <i class="ph-fill ph-star opacity-20"></i>
                                        <i class="ph-fill ph-star opacity-20"></i>
                                        <i class="ph-fill ph-star opacity-20"></i>
                                        <i class="ph-fill ph-star opacity-20"></i>
                                    </div>
                                </div>

                                <div class="bar-container">
                                    <div class="bar-fill" id="skillRatingBar" style="width:0%;"></div>
                                </div>
                            </div>

                            <!-- Attendance -->
                            <div class="bar-row">
                                <div class="bar-label-group">
                                    <span>Attendance & Punctuality</span>

                                    <div class="bar-stars" id="attendanceStars">
                                        <i class="ph-fill ph-star opacity-20"></i>
                                        <i class="ph-fill ph-star opacity-20"></i>
                                        <i class="ph-fill ph-star opacity-20"></i>
                                        <i class="ph-fill ph-star opacity-20"></i>
                                        <i class="ph-fill ph-star opacity-20"></i>
                                    </div>
                                </div>

                                <div class="bar-container">
                                    <div class="bar-fill" id="attendanceRatingBar" style="width:0%;"></div>
                                </div>
                            </div>

                            <!-- Teamwork -->
                            <div class="bar-row">
                                <div class="bar-label-group">
                                    <span>Teamwork / Behaviour</span>

                                    <div class="bar-stars" id="teamworkStars">
                                        <i class="ph-fill ph-star opacity-20"></i>
                                        <i class="ph-fill ph-star opacity-20"></i>
                                        <i class="ph-fill ph-star opacity-20"></i>
                                        <i class="ph-fill ph-star opacity-20"></i>
                                        <i class="ph-fill ph-star opacity-20"></i>
                                    </div>
                                </div>

                                <div class="bar-container">
                                    <div class="bar-fill" id="teamworkRatingBar" style="width:0%;"></div>
                                </div>
                            </div>

                        </div>

                        <!-- Review Comment -->
                        <div class="border rounded p-3 bg-light position-relative"
                            style="border-left:4px solid #764ba2 !important;">

                            <span
                                class="position-absolute end-0 top-0 p-2 text-muted-50"
                                style="font-size:1.5rem; opacity:.15; line-height:1;">
                                <i class="ph-fill ph-quotes"></i>
                            </span>

                            <small
                                class="text-muted fw-bold d-block mb-1 text-uppercase letter-spacing-05 fs-10">
                                Manager Review Remarks
                            </small>

                            <p
                                id="managerReview"
                                class="mb-0 text-dark fs-12 italic fw-normal leading-relaxed"
                                style="line-height:1.45;">
                                Loading review...
                            </p>

                        </div>

                    </div>
                </div>

                <!-- 6. Calendar & Events -->
                {{-- <div class="card dashboard-card" id="calendar-events-card">
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
                </div> --}}

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
            <label class="form-label fw-bold">Description</label>
            <textarea
                id="task_description"
                class="form-control"
                rows="4"
                readonly></textarea>
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

    // Static Upcoming Tasks Dataset
    const UPCOMING_TASKS = [];

    let ATTENDANCE_STATS = {};

    $(document).ready(function () {

        fetchAttendanceData();
        fetchAttendanceSummary();
        toggleTasksFilter('month');
        loadUpcomingTasks('today');
        loadPerformanceReview();

        flatpickr("#task-datepicker", {
            dateFormat: "Y-m-d",
            clickOpens: true,
            allowInput: false,
            onChange: function(selectedDates, dateStr) {

                // clear dropdown selection
                $('#task-table-filter').val('');

                loadUpcomingTasks('', dateStr);
            }
        });
    });

    // 1. Attendance Toggle (Weekly/Monthly)
    function fetchAttendanceSummary()
    {
        $.ajax({
            url: "{{ route('attendance.summary') }}",
            type: "GET",
            dataType: "json",

            success: function(response)
            {
                if(response.status)
                {
                    ATTENDANCE_STATS = {
                        weekly: response.weekly,
                        monthly: response.monthly
                    };

                    toggleAttendance('weekly');
                }
            }
        });
    }

    function toggleAttendance(type)
    {
        if (!ATTENDANCE_STATS[type]) {
            return;
        }

        if (type === 'weekly') {

            $('#btn-atten-week').addClass('active');
            $('#btn-atten-month').removeClass('active');

            $('#atten-summary-title').text('Weekly Summary Details');

        } else {

            $('#btn-atten-month').addClass('active');
            $('#btn-atten-week').removeClass('active');

            $('#atten-summary-title').text('Monthly Summary Details');
        }

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
        // Show loading state or 0 while fetching
        $('#kpi-pending, #kpi-in-progress, #kpi-completed, #kpi-overdue').text('...');

        // Fetch dynamic stats from backend
        $.ajax({
            url: '/tasks_counters',
            type: 'GET',
            data: { range: range },
            dataType: 'json',
            success: function (counters) {
                // console.log(counters);

                // Update UI elements dynamically with response data
                $('#kpi-pending').text(counters.pending);
                $('#kpi-in-progress').text(counters.progress);
                $('#kpi-completed').text(counters.completed);
                $('#kpi-overdue').text(counters.overdue);
            },
            error: function (xhr, status, error) {
                // console.error("Failed to fetch task counters:", error);
                // Fallback to 0 on error
                $('#kpi-pending, #kpi-in-progress, #kpi-completed, #kpi-overdue').text('0');
            }
        });
    }

    // 4. Render and Filter Upcoming Tasks table
    function loadUpcomingTasks(filter = 'today', date = '')
    {
        const tbody = $("#pc-dt-simple tbody");

        // Show loading
        tbody.html(`
            <tr>
                <td colspan="6" class="text-center py-4">
                    <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                    Loading tasks...
                </td>
            </tr>
        `);

        $.ajax({
            url: "{{ url('/employee/upcoming-tasks') }}",
            type: "GET",
            data: {
                filter: filter,
                date: date
            },
            success: function(response) {
                renderUpcomingTasks(response);
            },
            error: function(xhr) {

                tbody.html(`
                    <tr>
                        <td colspan="6" class="text-center text-danger py-4">
                            Failed to load tasks
                        </td>
                    </tr>
                `);

                console.log(xhr.responseText);
            }
        });
    }

    function renderUpcomingTasks(taskList) {
        const tbody = $("#pc-dt-simple tbody");
        tbody.empty();

        if (taskList.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No matching tasks found
                    </td>
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
                    <td>${task.title}</td>
                    <td>${task.due_date}</td>
                    <td>${priorityBadge}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <button
                            class="btn btn-sm btn-link-primary p-1"
                            onclick='viewTaskDetails(${JSON.stringify(task)})'>
                            <i class="ti ti-edit-circle fs-5"></i>
                        </button>
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
    function filterUpcomingTasks(filterType)
    {
        $('#task-datepicker').val('');
        loadUpcomingTasks(filterType);
    }

    // Modal display for static upcoming tasks
    function viewTaskDetails(task)
    {
        $('#task_id').val(task.id);
        $('#task_title').val(task.title);
        $('#task_description').val(task.description ?? '');
        $('#task_priority').val(task.priority);
        $('#task_deadline').val(task.due_date);
        $('#task_status').val(task.status);

        if (task.status.toLowerCase() === 'completed') {

            $('#task_status').prop('disabled', true);

            $('#updateBtn')
                .prop('disabled', true)
                .html('<i class="ti ti-check me-1"></i> Already Completed');

        } else {

            $('#task_status').prop('disabled', false);

            $('#updateBtn')
                .prop('disabled', false)
                .html('<i class="ti ti-refresh me-1"></i> Update Status');
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

    function startEmployeeDashboardTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Welcome to Employee Dashboard',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-dashboard" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Manage your attendance, daily clock, tasks, performance reviews, and company events here.</p></div>'
                },
                {
                    element: '#employee-welcome-banner',
                    title: 'Employee Profile Summary',
                    intro: 'View your profile picture, designation, department, work location, and manager information.'
                },
                {
                    element: '#clock-details-card',
                    title: 'Today\'s Clock Details',
                    intro: 'Monitor your check-in time, check-out time, and total working hours for today.'
                },
                {
                    element: '#attendance-summary-card',
                    title: 'Attendance Summary',
                    intro: 'Analyze your weekly or monthly attendance summary, including present, late, and absent days.'
                },
                {
                    element: '#tasks-projects-card',
                    title: 'Tasks KPI Cards',
                    intro: 'Keep track of your pending, in-progress, completed, and overdue tasks at a glance.'
                },
                {
                    element: '#upcoming-tasks-card',
                    title: 'Upcoming Tasks Listing',
                    intro: 'A table listing your upcoming tasks with title, category, due date, priority, and actions to update status.'
                },
                {
                    element: '#performance-review-card',
                    title: 'Performance & Review Score',
                    intro: 'View your performance rating percentages and overall performance score assigned by your manager.'
                },
                {
                    element: '#calendar-events-card',
                    title: 'Calendar & Corporate Events',
                    intro: 'Keep up with upcoming corporate events, holidays, and official updates.'
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
        $('#start-employee-dashboard-tour').on('click', function(e) {
            e.preventDefault();
            startEmployeeDashboardTour();
        });

        //-- Fetch Employee Details via AJAX and populate the dashboard
        loadEmployeeDetails();
    });

    // AJAX function to load employee details
    function loadEmployeeDetails()
    {
        $.ajax({
            url: "{{ route('employee.dashboard.data') }}",
            type: "GET",
            dataType: "json",

            success: function(response)
            {
                if(response.status)
                {
                    let emp = response.data;

                    $('#employeePhoto').attr('src', emp.profile_image);

                    $('#employeeName').text(emp.name);

                    $('#employeeId').text('ID: ' + (emp.employee_id ?? '--'));

                    $('#employeeDesignation').text(emp.designation ?? '--');

                    $('#employeeDepartment').text(emp.department ?? '--');

                    $('#managerName').text(emp.manager ?? '--');

                    $('#companyName').text(emp.company ?? '--');

                    $('#employeeLocation').text(emp.location ?? '--');

                    let statusClass = 'bg-success text-white';

                    if(emp.emp_status)
                    {
                        let status = emp.emp_status.toLowerCase();

                        if(status.includes('probation'))
                        {
                            statusClass = 'bg-warning text-dark';
                        }
                        else if(status.includes('notice'))
                        {
                            statusClass = 'bg-danger text-white';
                        }
                    }

                    $('#employeeStatus')
                        .removeClass()
                        .addClass('badge rounded-pill px-3 py-1 fs-11 fw-bold ' + statusClass)
                        .text(emp.emp_status ?? 'Active');


                    //---- Employee Online/Offline Status based on emp_status
                    let empStatus = (emp.emp_status || '').toLowerCase();

                    if (empStatus === 'resigned' || empStatus === 'terminated') {

                        $('#employeeOnlineStatus')
                            .removeClass('bg-success')
                            .addClass('bg-danger')
                            .html('<i class="ph-fill ph-circle text-white me-1" style="font-size:7px;"></i>Offline');

                    } else {

                        $('#employeeOnlineStatus')
                            .removeClass('bg-danger')
                            .addClass('bg-success')
                            .html('<i class="ph-fill ph-circle text-white me-1" style="font-size:7px;"></i>Online');
                    }
                }
            },

            error: function(xhr)
            {
                console.log(xhr.responseText);
            }
        });
    }

    //-- Update Task -----
    $('#updateTaskForm').on('submit', function(e) {

        e.preventDefault();

        $.ajax({
            url: '/employee/update-task-status',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                task_id: $('#task_id').val(),
                status: $('#task_status').val()
            },

            success: function(response) {

                $('#editTaskModal').modal('hide');

                loadUpcomingTasks(
                    $('#task-table-filter').val(),
                    $('#task-datepicker').val()
                );

                toggleTasksFilter(document.getElementById('task-range-filter').value);

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Task status updated successfully'
                });
            },

            error: function() {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update task status'
                });
            }
        });
    });

    //--------- Review data-------

    function loadPerformanceReview() {

        $.ajax({
            url: "{{ url('/employee/performance-review') }}",
            type: "GET",

            beforeSend: function () {

                $('#performancePercentage').text('...');
                $('#performanceStatus').text('Loading...');
                $('#reviewMonthText').text('Loading review...');
                $('#managerReview').text('Loading review...');

                $('#workStars, #skillStars, #attendanceStars, #teamworkStars').html(
                    '<i class="ph-fill ph-star opacity-20"></i>'.repeat(5)
                );
            },

            success: function (response) {

                if (!response.status) {

                    $('#performancePercentage').text('0%');
                    $('#performanceStatus').text('No Review');
                    $('#reviewMonthText').text('');
                    $('#managerReview').text('No review available.');

                    return;
                }

                let data = response.data;

                // Performance %
                $('#performancePercentage').text(data.total_percentage + '%');

                // Status
                let status = 'Needs Improvement';

                if (data.total_percentage >= 80) {
                    status = 'Excellent';
                } else if (data.total_percentage >= 60) {
                    status = 'Good';
                } else if (data.total_percentage >= 40) {
                    status = 'Average';
                }

                $('#performanceStatus').text(status);

                // Review Month
                const months = [
                    'January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ];

                $('#reviewMonthText').text(
                    months[parseInt(data.review_month) - 1] +
                    ' ' +
                    data.review_year +
                    ' Review'
                );

                // Progress Bars
                $('#workRatingBar').css('width', (parseInt(data.work_rating) * 20) + '%');
                $('#skillRatingBar').css('width', (parseInt(data.skill_rating) * 20) + '%');
                $('#attendanceRatingBar').css('width', (parseInt(data.attendance_rating) * 20) + '%');
                $('#teamworkRatingBar').css('width', (parseInt(data.teamwork_rating) * 20) + '%');

                // Stars
                renderStars('#workStars', data.work_rating);
                renderStars('#skillStars', data.skill_rating);
                renderStars('#attendanceStars', data.attendance_rating);
                renderStars('#teamworkStars', data.teamwork_rating);

                // Review
                $('#managerReview').text(
                    data.review ? data.review : 'No remarks provided.'
                );

                // Circle Progress
                updateRadialChart(parseFloat(data.total_percentage));
            },

            error: function () {

                $('#performancePercentage').text('0%');
                $('#performanceStatus').text('Error');
                $('#reviewMonthText').text('');
                $('#managerReview').text('Unable to load review.');
            }
        });
    }

    /**
     * Dynamic Stars
     * Example:
     * 4 => ★★★★☆
     * 5 => ★★★★★
     */
    function renderStars(selector, rating) {

        rating = parseInt(rating) || 0;

        let html = '';

        for (let i = 1; i <= 5; i++) {

            if (i <= rating) {
                html += '<i class="ph-fill ph-star text-warning"></i>';
            } else {
                html += '<i class="ph-fill ph-star opacity-20"></i>';
            }
        }

        $(selector).html(html);
    }

    /**
     * Radial Progress Circle
     */
    function updateRadialChart(percentage)
    {
        const circle = document.querySelector('.radial-progress');

        if (!circle) return;

        const radius = 40;
        const circumference = 2 * Math.PI * radius;

        circle.style.strokeDasharray = circumference;

        if (percentage <= 0) {
            circle.style.strokeDashoffset = circumference;
            return;
        }

        const offset = circumference - (percentage / 100) * circumference;

        circle.style.strokeDashoffset = offset;
    }

</script>

@endsection
