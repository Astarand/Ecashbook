@extends('App.Layout')

@section('container')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Attendance Details</h3>
        <a href="javascript:void(0);" id="start-employee-attendance-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
            <u>How does this Page works?</u>
        </a>
    </div>
        <!-- Attendance Summary Cards -->
        <div class="row mb-4" id="attendance-summary-cards">
            <div class="col-lg-3 col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ph-duotone ph-calendar-check f-36"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-white">Total Present</h6>
                                <h4 class="mb-0 text-white">{{ $presentThisMonth }} Days</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ph-duotone ph-clock f-36"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-white">Late Arrivals</h6>
                                <h4 class="mb-0 text-white">{{ $lateCountThisMonth }} Days</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ph-duotone ph-x-circle f-36"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-white">Absent</h6>
                                <h4 class="mb-0 text-white">{{ $totalAbsentDays }} Days</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ph-duotone ph-airplane-takeoff f-36"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-white">Leave's </h6>
                                <h4 class="mb-0 text-white">{{ $totalLeaveDaysThisMonth }} Days</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Attendance Calendar & Details -->
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card" id="attendance-calendar-card">
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
                                    <div class="card" id="daily-activity-card">
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


// Calendar day click handler
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('calendar-day') && !e.target.classList.contains('other-month')) {
    const day = e.target.textContent;
    const month = document.querySelector('select option:checked').textContent;
    console.log(`Clicked on ${day} ${month}`);
    // You could open a modal or show details for this specific day
  }
});



</script>
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
    fetch('{{ route("userEmoloyee.getMonthlyAttendanceUserEmployee") }}', {
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
    fetch('{{ route("userEmoloyee.getDailyActivityUserEmployee") }}', {
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

function startEmployeeAttendanceTour() {
    if (typeof introJs !== 'function') return;

    introJs().setOptions({
        steps: [
            {
                title: 'Attendance Details Tour',
                intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-calendar" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Track and review your monthly attendance, leaves, absent days, and daily activity logs.</p></div>'
            },
            {
                element: '#attendance-summary-cards',
                title: 'Monthly Summary Cards',
                intro: 'Overview of your total present days, late arrivals, absent days, and approved leave days for the current month.'
            },
            {
                element: '#attendance-calendar-card',
                title: 'Interactive Attendance Calendar',
                intro: 'View your calendar color-coded by attendance status (Present, Late, Absent, Leave, Holiday). Use year and month filters to change display.'
            },
            {
                element: '#daily-activity-card',
                title: 'Daily Activity Details',
                intro: 'Select any calendar date to see check-in/out times, office hours, and specific leave or holiday remarks.'
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
@endsection
