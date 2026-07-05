@extends('App.Layout')

@section('container')
<meta name="csrf-token" content="{{ csrf_token() }}">
@php
use Illuminate\Support\Str;
@endphp

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
                        <li class="breadcrumb-item active" aria-current="page">Remote Work</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Remote Work Management
                            @if($employee)
                            - {{ $employee->name }}
                            @endif
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Work from Home Request Form -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-house me-2"></i>
                        Request Work from Home
                        @if($employee)
                        <small class="text-muted">for {{ $employee->name }}</small>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <form id="wfhRequestForm">
                        @if($encodedId)
                        <input type="hidden" id="employeeId" value="{{ $encodedId }}">
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="fromDate" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="toDate" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <textarea class="form-control" id="reason" rows="3"
                                placeholder="Please provide reason for work from home request..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Work Plan</label>
                            <textarea class="form-control" id="workPlan" rows="4"
                                placeholder="Describe your work plan and deliverables for the WFH period..."
                                required></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="ph-duotone ph-paper-plane-tilt me-2"></i>
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- WFH Access Calendar -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-calendar-check me-2"></i>
                        WFH Access Calendar
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="calendar-container">
                                <div class="calendar-header d-flex justify-content-between align-items-center mb-3">
                                    <button class="btn btn-sm btn-outline-primary" onclick="previousMonth()">
                                        <i class="ph-duotone ph-caret-left"></i>
                                    </button>
                                    <h6 class="mb-0" id="currentMonth">January 2024</h6>
                                    <button class="btn btn-sm btn-outline-primary" onclick="nextMonth()">
                                        <i class="ph-duotone ph-caret-right"></i>
                                    </button>
                                </div>

                                <div class="calendar-grid">
                                    <div class="calendar-days">
                                        <div class="day-header">Sun</div>
                                        <div class="day-header">Mon</div>
                                        <div class="day-header">Tue</div>
                                        <div class="day-header">Wed</div>
                                        <div class="day-header">Thu</div>
                                        <div class="day-header">Fri</div>
                                        <div class="day-header">Sat</div>
                                    </div>
                                    <div class="calendar-dates" id="calendarDates">
                                        <!-- Calendar dates will be populated by JavaScript -->
                                    </div>
                                    <!-- Loading overlay -->
                                    <div class="calendar-loading" id="calendarLoading" style="display: none;">
                                        <div class="loading-content">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="mt-2 mb-0 text-muted">Loading calendar data...</p>
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

    <!-- WFH Requests History -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-clock-clockwise me-2"></i>
                        Work from Home History
                    </h5>
                </div>
                <div class="card-body">
                    <!-- class="tablion -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Month</label>
                            <select class="form-select" id="filterMonth" onchange="filterWFHHistory()">
                                <option value="">All Months</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Year</label>
                            <select class="form-select" id="filterYear" onchange="filterWFHHistory()">
                                <option value="">All Years</option>
                                @php
                                $currentYear = date('Y');
                                $startYear = $currentYear - 5;
                                $endYear = $currentYear + 2;
                                @endphp
                                @for($year = $endYear; $year >= $startYear; $year--)
                                <option value="{{ $year }}" {{ $year==$currentYear ? 'selected' : '' }}>{{ $year }}
                                </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                    <i class="ph-duotone ph-x me-1"></i>Clear Filters
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Request Date</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Days</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="wfhHistoryTable">
                                @if($wfhHistory && count($wfhHistory) > 0)
                                @foreach($wfhHistory as $wfh)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($wfh->created_at)->format('Y-m-d') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($wfh->from_date)->format('Y-m-d') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($wfh->to_date)->format('Y-m-d') }}</td>
                                    <td>{{ $wfh->total_days }}</td>
                                    <td>{{ Str::limit($wfh->reason, 30) }}</td>
                                    <td>
                                        @if($wfh->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                        @elseif($wfh->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                        @else
                                        <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-info"
                                                onclick="viewWFHDetails({{ $wfh->id }})" title="View Details">
                                                <i class="ph-duotone ph-eye"></i>
                                            </button>
                                            @if($wfh->status == 'pending')
                                            <button class="btn btn-sm btn-outline-success"
                                                onclick="acceptWFHRequest({{ $wfh->id }})" title="Accept Request">
                                                <i class="ph-duotone ph-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="rejectWFHRequest({{ $wfh->id }})" title="Reject Request">
                                                <i class="ph-duotone ph-x"></i>
                                            </button>
                                            @endif
                                            {{-- <button class="btn btn-sm btn-outline-danger"
                                                onclick="deleteWFHRequest({{ $wfh->id }})" title="Delete Request">
                                                <i class="ph-duotone ph-trash"></i>
                                            </button> --}}
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No work from home requests found</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        </!-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <style>
        .calendar-container {
            max-width: 100%;
        }

        .calendar-grid {
            border: 1px solid #e9ecef;
            border-radius: 0.375rem;
        }

        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            background-color: #f8f9fa;
        }

        .day-header {
            padding: 10px;
            text-align: center;
            font-weight: 600;
            border-right: 1px solid #e9ecef;
            border-bottom: 1px solid #e9ecef;
        }

        .day-header:last-child {
            border-right: none;
        }

        .calendar-dates {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }

        .calendar-date {
            padding: 10px;
            text-align: center;
            border-right: 1px solid #e9ecef;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
            transition: background-color 0.2s;
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .calendar-date:last-child {
            border-right: none;
        }

        .calendar-date:hover {
            background-color: #f8f9fa;
        }

        .calendar-date.wfh-approved {
            background-color: #d4edda;
            color: #155724;
            font-weight: 600;
        }

        .calendar-date.wfh-pending {
            background-color: #fff3cd;
            color: #856404;
            font-weight: 600;
        }

        .calendar-date.wfh-rejected {
            background-color: #f8d7da;
            color: #721c24;
            font-weight: 600;
        }

        .calendar-date:hover:not(.other-month) {
            background-color: #e9ecef;
            cursor: pointer;
        }

        .calendar-date[title] {
            cursor: help;
        }

        .calendar-date.other-month {
            color: #6c757d;
            background-color: #f8f9fa;
        }

        .wfh-indicator {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .wfh-indicator.approved {
            background-color: #28a745;
        }

        .wfh-indicator.pending {
            background-color: #ffc107;
        }

        .wfh-indicator.rejected {
            background-color: #dc3545;
        }

        .calendar-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
            z-index: 10;
        }

        .loading-content {
            text-align: center;
        }

        .calendar-container {
            position: relative;
        }

        .calendar-header button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>

    <script>
        // WFH data - will be populated from backend
let wfhData = {};
let currentDate = new Date();

function showWorkFromHome(encodedId) {
    // This function will be called from the main page
    window.location.href = '/work-from-home/' + (encodedId || '');
}

// Show calendar loading state
function showCalendarLoading() {
    const loadingElement = document.getElementById('calendarLoading');
    if (loadingElement) {
        loadingElement.style.display = 'flex';
    }
}

// Hide calendar loading state
function hideCalendarLoading() {
    const loadingElement = document.getElementById('calendarLoading');
    if (loadingElement) {
        loadingElement.style.display = 'none';
    }
    
    // Re-enable navigation buttons
    setNavigationButtonsState(true);
}

// Load WFH data for calendar
function loadWFHData(year, month) {
    const employeeId = document.getElementById('employeeId') ? document.getElementById('employeeId').value : null;
    
    // Show loading state
    showCalendarLoading();
    
    fetch('/get-wfh-calendar-data', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            year: year,
            month: month + 1, // JavaScript months are 0-indexed
            employeeId: employeeId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            wfhData = data.data;
            generateCalendar(year, month);
        } else {
            console.error('Failed to load WFH data:', data.message);
            generateCalendar(year, month);
        }
    })
    .catch(error => {
        console.error('Error loading WFH data:', error);
        generateCalendar(year, month);
    })
    .finally(() => {
        // Hide loading state
        hideCalendarLoading();
    });
}

function generateCalendar(year, month) {
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();
    
    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"];
    
    document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
    
    const calendarDates = document.getElementById('calendarDates');
    calendarDates.innerHTML = '';
    
    // Add empty cells for days before the first day of the month
    for (let i = 0; i < startingDayOfWeek; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'calendar-date other-month';
        calendarDates.appendChild(emptyCell);
    }
    
    // Add days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const dateCell = document.createElement('div');
        dateCell.className = 'calendar-date';
        dateCell.textContent = day;
        
        const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        
        // Check if there's WFH data for this date
        if (wfhData[dateString]) {
            const indicator = document.createElement('div');
            indicator.className = `wfh-indicator ${wfhData[dateString].status}`;
            dateCell.appendChild(indicator);
            dateCell.classList.add(`wfh-${wfhData[dateString].status}`);
            
            // Add tooltip with WFH details
            dateCell.title = `WFH: ${wfhData[dateString].status.charAt(0).toUpperCase() + wfhData[dateString].status.slice(1)}\nReason: ${wfhData[dateString].reason}`;
        }
        
        // Add click event to show WFH details
        dateCell.addEventListener('click', function() {
            if (wfhData[dateString]) {
                showWFHDateDetails(dateString, wfhData[dateString]);
            }
        });
        
        calendarDates.appendChild(dateCell);
    }
}

function previousMonth() {
    // Disable navigation buttons during loading
    setNavigationButtonsState(false);
    
    currentDate.setMonth(currentDate.getMonth() - 1);
    loadWFHData(currentDate.getFullYear(), currentDate.getMonth());
}

function nextMonth() {
    // Disable navigation buttons during loading
    setNavigationButtonsState(false);
    
    currentDate.setMonth(currentDate.getMonth() + 1);
    loadWFHData(currentDate.getFullYear(), currentDate.getMonth());
}

// Enable/disable navigation buttons
function setNavigationButtonsState(enabled) {
    const prevButton = document.querySelector('.calendar-header button:first-child');
    const nextButton = document.querySelector('.calendar-header button:last-child');
    
    if (prevButton) {
        prevButton.disabled = !enabled;
    }
    if (nextButton) {
        nextButton.disabled = !enabled;
    }
}

function showWFHDateDetails(dateString, wfhInfo) {
    const formattedDate = new Date(dateString).toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    const statusBadge = wfhInfo.status === 'approved' ? 'success' : 
                       wfhInfo.status === 'pending' ? 'warning' : 'danger';
    
    const modalContent = `
        <div class="modal fade" id="wfhDateModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">WFH Details - ${formattedDate}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <p><strong>Status:</strong> <span class="badge bg-${statusBadge}">${wfhInfo.status.charAt(0).toUpperCase() + wfhInfo.status.slice(1)}</span></p>
                                <p><strong>From Date:</strong> ${new Date(wfhInfo.from_date).toLocaleDateString()}</p>
                                <p><strong>To Date:</strong> ${new Date(wfhInfo.to_date).toLocaleDateString()}</p>
                                <p><strong>Total Days:</strong> ${wfhInfo.total_days}</p>
                                <p><strong>Reason:</strong></p>
                                <p class="text-muted">${wfhInfo.reason}</p>
                                ${wfhInfo.work_plan ? `
                                <p><strong>Work Plan:</strong></p>
                                <p class="text-muted">${wfhInfo.work_plan}</p>
                                ` : ''}
                                ${wfhInfo.rejection_reason ? `
                                <p><strong>Rejection Reason:</strong></p>
                                <p class="text-danger">${wfhInfo.rejection_reason}</p>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        ${wfhInfo.status === 'pending' ? `
                        <button type="button" class="btn btn-success" onclick="acceptWFHRequest(${wfhInfo.id})">Accept</button>
                        <button type="button" class="btn btn-danger" onclick="rejectWFHRequest(${wfhInfo.id})">Reject</button>
                        ` : ''}
                        
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('wfhDateModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalContent);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('wfhDateModal'));
    modal.show();
}

function viewWFHDetails(id) {
    fetch(`/get-wfh-details/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const wfh = data.data;
                const statusBadge = wfh.status === 'approved' ? 'success' : 
                                   wfh.status === 'pending' ? 'warning' : 'danger';
                
                const modalContent = `
                    <div class="modal fade" id="wfhDetailsModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">WFH Request Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Employee:</strong> ${wfh.employee_name}</p>
                                            <p><strong>Employee ID:</strong> ${wfh.employee_id}</p>
                                            <p><strong>From Date:</strong> ${new Date(wfh.from_date).toLocaleDateString()}</p>
                                            <p><strong>To Date:</strong> ${new Date(wfh.to_date).toLocaleDateString()}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Total Days:</strong> ${wfh.total_days}</p>
                                            <p><strong>Status:</strong> <span class="badge bg-${statusBadge}">${wfh.status.charAt(0).toUpperCase() + wfh.status.slice(1)}</span></p>
                                            <p><strong>Request Date:</strong> ${new Date(wfh.created_at).toLocaleDateString()}</p>
                                            ${wfh.approved_by_name ? `<p><strong>Approved By:</strong> ${wfh.approved_by_name}</p>` : ''}
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <p><strong>Reason:</strong></p>
                                            <p class="text-muted">${wfh.reason}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p><strong>Work Plan:</strong></p>
                                            <p class="text-muted">${wfh.work_plan}</p>
                                        </div>
                                    </div>
                                    ${wfh.rejection_reason ? `
                                    <div class="row">
                                        <div class="col-12">
                                            <p><strong>Rejection Reason:</strong></p>
                                            <p class="text-danger">${wfh.rejection_reason}</p>
                                        </div>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Remove existing modal if any
                const existingModal = document.getElementById('wfhDetailsModal');
                if (existingModal) {
                    existingModal.remove();
                }
                
                // Add modal to body
                document.body.insertAdjacentHTML('beforeend', modalContent);
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('wfhDetailsModal'));
                modal.show();
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load WFH details', 'error');
        });
}

function acceptWFHRequest(id) {
    Swal.fire({
        title: 'Accept WFH Request',
        text: 'Are you sure you want to accept this Work from Home request?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Accept',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/accept-wfh-request/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to accept WFH request', 'error');
            });
        }
    });
}

function rejectWFHRequest(id) {
    Swal.fire({
        title: 'Reject WFH Request',
        text: 'Please provide a reason for rejection:',
        input: 'textarea',
        inputPlaceholder: 'Enter rejection reason...',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Reject Request',
        cancelButtonText: 'Cancel',
        inputValidator: (value) => {
            if (!value || value.trim() === '') {
                return 'Rejection reason is required!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/reject-wfh-request/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    rejection_reason: result.value.trim()
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to reject WFH request', 'error');
            });
        }
    });
}

// Form submission
document.getElementById('wfhRequestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;
    const reason = document.getElementById('reason').value;
    const workPlan = document.getElementById('workPlan').value;
    const employeeId = document.getElementById('employeeId') ? document.getElementById('employeeId').value : null;
    
    if (!fromDate || !toDate || !reason || !workPlan) {
        showToast('Please fill all required fields', 'error');
        return;
    }
    
    if (new Date(fromDate) > new Date(toDate)) {
        showToast('From date cannot be later than to date', 'error');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="ph-duotone ph-spinner ph-spin me-2"></i>Submitting...';
    submitBtn.disabled = true;
    
    // Send AJAX request
    fetch('/store-wfh-request', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            employeeId: employeeId,
            fromDate: fromDate,
            toDate: toDate,
            reason: reason,
            workPlan: workPlan
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showToast(data.message, 'success');
            // Reset form
            this.reset();
            // Refresh page after 1 second to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred. Please try again.', 'error');
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Toast notification function
function showToast(message, type) {
    // Check if toast function exists globally, otherwise create a simple alert
    if (typeof window.showToast === 'function') {
        window.showToast(message, type);
    } else {
        // Fallback to alert if toast function not available
        alert(message);
    }
}

// Filter WFH History
function filterWFHHistory() {
    const selectedMonth = document.getElementById('filterMonth').value;
    const selectedYear = document.getElementById('filterYear').value;
    const tableRows = document.querySelectorAll('#wfhHistoryTable tr');
    
    tableRows.forEach(row => {
        // Skip the "no data" row
        if (row.cells.length === 1) {
            return;
        }
        
        const requestDate = row.cells[0].textContent.trim();
        const fromDate = row.cells[1].textContent.trim();
        
        let showRow = true;
        
        if (selectedMonth || selectedYear) {
            const requestDateObj = new Date(requestDate);
            const fromDateObj = new Date(fromDate);
            
            // Filter by month (check both request date and from date)
            if (selectedMonth) {
                const requestMonth = String(requestDateObj.getMonth() + 1).padStart(2, '0');
                const fromMonth = String(fromDateObj.getMonth() + 1).padStart(2, '0');
                
                if (requestMonth !== selectedMonth && fromMonth !== selectedMonth) {
                    showRow = false;
                }
            }
            
            // Filter by year (check both request date and from date)
            if (selectedYear && showRow) {
                const requestYear = requestDateObj.getFullYear().toString();
                const fromYear = fromDateObj.getFullYear().toString();
                
                if (requestYear !== selectedYear && fromYear !== selectedYear) {
                    showRow = false;
                }
            }
        }
        
        row.style.display = showRow ? '' : 'none';
    });
    
    // Check if any rows are visible
    const visibleRows = Array.from(tableRows).filter(row => 
        row.style.display !== 'none' && row.cells.length > 1
    );
    
    // Show/hide "no data" message
    const noDataRow = document.querySelector('#wfhHistoryTable tr td[colspan="7"]');
    if (noDataRow) {
        const noDataRowParent = noDataRow.parentElement;
        if (visibleRows.length === 0) {
            noDataRowParent.style.display = '';
            noDataRow.textContent = 'No work from home requests found for selected filters';
        } else {
            noDataRowParent.style.display = 'none';
        }
    }
}

// Clear all filters
function clearFilters() {
    document.getElementById('filterMonth').value = '';
    document.getElementById('filterYear').value = '';
    filterWFHHistory();
}

// Set current month and year on page load
function setCurrentMonthYear() {
    const now = new Date();
    const currentMonth = String(now.getMonth() + 1).padStart(2, '0');
    const currentYear = now.getFullYear().toString();
    
    document.getElementById('filterMonth').value = currentMonth;
    document.getElementById('filterYear').value = currentYear;
    
    // Apply initial filter
    filterWFHHistory();
}

function deleteWFHRequest(id) {
    Swal.fire({
        title: 'Delete WFH Request',
        text: 'Are you sure you want to delete this Work from Home request? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/delete-wfh-request/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to delete WFH request', 'error');
            });
        }
    });
}

// Initialize calendar and filters on page load
document.addEventListener('DOMContentLoaded', function() {
    loadWFHData(currentDate.getFullYear(), currentDate.getMonth());
    setCurrentMonthYear();
});
    </script>

    @endsection