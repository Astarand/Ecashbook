@extends('App.Layout')

@section('page-style')
<style>
    .day-box {
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    .day-box:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
    }

    .reason-label,
    .in-reason-label,
    .out-reason-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
    }

    .in-reason-text,
    .out-reason-text {
        font-size: 0.9rem;
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }

    .save-btn {
        background: linear-gradient(45deg, #007bff, #0056b3);
        border: none;
        transition: all 0.3s ease;
    }

    .save-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
    }
</style>
@endsection

@section('container')

<div class="pc-content">
    <div class="row">
        <div class="row">
            <div class="card shadow-sm">
                <div class="card-body">

                    <!-- Month Slider -->
                    <div class="slider-container text-center mb-4">
                        <button id="prevMonth" class="btn btn-secondary">&lt;</button>
                        <h5 id="currentMonth">January 2025</h5>
                        <button id="nextMonth" class="btn btn-secondary">&gt;</button>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="loadingIndicator" class="text-center py-5 d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-2">
                            <span class="text-muted">Loading attendance data...</span>
                        </div>
                    </div>

                    <div class="row" id="attendanceGrid"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Removed Working button functionality as per requirement




document.addEventListener('DOMContentLoaded', function () {
    const attendanceGrid = document.getElementById('attendanceGrid');
    const currentMonthDisplay = document.getElementById('currentMonth');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');

    const monthNames = [
        "January", "February", "March", "April", "May",
        "June", "July", "August", "September", "October", "November", "December"
    ];

    let currentDate = new Date();
    let currentYear = currentDate.getFullYear();
    let currentMonth = currentDate.getMonth();
    const userId = <?php echo $id; ?>; // Fetch userId from PHP
    
    // Weekly schedules data from PHP
    const weeklySchedules = @json($weeklySchedules);
    
    // Holidays data from PHP
    const holidays = @json($holidays);
    
    // Create a map for quick day status lookup
    const dayStatusMap = {};
    const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    
    weeklySchedules.forEach(schedule => {
        dayStatusMap[schedule.day.toLowerCase()] = schedule.status;
    });
    
    // Create a map for holidays lookup
    const holidaysMap = {};
    holidays.forEach(holiday => {
        const holidayDate = new Date(holiday.holidayDate);
        const dateKey = `${holidayDate.getFullYear()}-${holidayDate.getMonth() + 1}-${holidayDate.getDate()}`;
        holidaysMap[dateKey] = holiday.holidayName;
    });

    function renderCalendar(month, year, attendanceData = [], leavesData = {}) {
        attendanceGrid.innerHTML = "";
        currentMonthDisplay.textContent = `${monthNames[month]} ${year}`;

        const daysInMonth = new Date(year, month + 1, 0).getDate();

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const formattedDate = `${String(day).padStart(2, '0')}/${String(month + 1).padStart(2, '0')}/${year}`;
            const dayOfWeek = date.getDay(); // 0 = Sunday, 1 = Monday, etc.
            const dayName = dayNames[dayOfWeek];
            const isDayOpen = dayStatusMap[dayName.toLowerCase()] === 'open';
            
            // Check if this date is a holiday
            const dateKey = `${year}-${month + 1}-${day}`;
            const holidayName = holidaysMap[dateKey];
            const isHoliday = !!holidayName;

            // Check if this date is a leave day
            const leaveKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const leaveInfo = leavesData[leaveKey];
            const isLeaveDay = !!leaveInfo;

            let attendance = attendanceData.find(a => new Date(a.present_date).getDate() === day);
            let inTime = attendance ? attendance.in_time : "";
            let outTime = attendance ? attendance.out_time : "";
            let presentStatus = attendance ? attendance.present_status : "";
            let reason = attendance ? attendance.reason : "";
            
            // Parse combined reason into separate parts
            let inReason = "";
            let outReason = "";
            if (reason) {
                if (reason.includes('In:')) {
                    const inMatch = reason.match(/In:\s*([^|]+)/);
                    inReason = inMatch ? inMatch[1].trim() : "";
                }
                if (reason.includes('Out:')) {
                    const outMatch = reason.match(/Out:\s*(.+)$/);
                    outReason = outMatch ? outMatch[1].trim() : "";
                }
                // If no format markers found, treat as general reason
                if (!reason.includes('In:') && !reason.includes('Out:')) {
                    if (inTime && !outTime) {
                        inReason = reason;
                    } else if (!inTime && outTime) {
                        outReason = reason;
                    } else if (inTime && outTime) {
                        inReason = reason;
                        outReason = reason;
                    }
                }
            }

            const dayBox = document.createElement('div');
            dayBox.classList.add('col-md-4', 'mb-4');
            const isDisabled = !isDayOpen || isHoliday || isLeaveDay;
            
            // Determine background class based on priority: leave > holiday > day status
            let bgClass = 'bg-white';
            if (isLeaveDay) {
                bgClass = 'bg-info bg-opacity-25';
            } else if (isHoliday) {
                bgClass = 'bg-warning bg-opacity-25';
            } else if (!isDayOpen) {
                bgClass = 'bg-light';
            }
            
            dayBox.innerHTML = `
                <div class="day-box shadow-sm p-3 ${bgClass} rounded" data-date="${year}-${month + 1}-${day}">
                    <div class="date mb-3 text-primary fw-bold">
                        Day: ${formattedDate} (${dayName})
                        ${isLeaveDay ? `<span class="badge bg-info ms-2">Leave</span>` :
                          (isHoliday ? `<span class="badge bg-warning ms-2">Holiday</span>` : 
                          (!isDayOpen ? '<span class="badge bg-secondary ms-2">Closed</span>' : '<span class="badge bg-success ms-2">Open</span>'))}
                    </div>
                    ${isHoliday ? `<div class="alert alert-warning py-2 mb-3"><strong>🎉 ${holidayName}</strong></div>` : ''}
                    ${isLeaveDay ? `<div class="alert alert-info py-2 mb-3">
                        <strong>📅 ${leaveInfo.leave_type.charAt(0).toUpperCase() + leaveInfo.leave_type.slice(1)} Leave</strong><br>
                        <small>${leaveInfo.reason}</small>
                    </div>` : ''}
                    <div class="fw-bold text-success present-status">${presentStatus}</div>

                    <div class="row mb-3 time-fields">
                        <div class="col-6">
                            <label class="form-label">In-Time:</label>
                            <input type="time" class="form-control in-time" style="width: 100%;" value="${inTime}" ${isDisabled ? 'disabled' : ''} />
                        </div>
                        <div class="col-6">
                            <label class="form-label">Out-Time:</label>
                            <input type="time" class="form-control out-time" style="width: 100%;" value="${outTime}" ${isDisabled ? 'disabled' : ''} />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label in-reason-label ${inTime && !isDisabled ? '' : 'd-none'}">In-Time Reason <span class="text-danger">*</span>:</label>
                            <input type="text" class="form-control in-reason-text ${inTime && !isDisabled ? '' : 'd-none'}"
                                    value="${inReason}" 
                                    placeholder="Enter reason for in-time" ${isDisabled ? 'disabled' : ''}>
                            <div class="invalid-feedback">Please provide a reason for in-time.</div>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label out-reason-label ${outTime && !isDisabled ? '' : 'd-none'}">Out-Time Reason <span class="text-danger">*</span>:</label>
                            <input type="text" class="form-control out-reason-text ${outTime && !isDisabled ? '' : 'd-none'}"
                                    value="${outReason}" 
                                    placeholder="Enter reason for out-time" ${isDisabled ? 'disabled' : ''}>
                            <div class="invalid-feedback">Please provide a reason for out-time.</div>
                        </div>
                        <div class="col-12 mb-3">
                            <button class="btn btn-primary w-100 save-btn ${(inTime || outTime) && !isDisabled ? '' : 'd-none'}" ${isDisabled ? 'disabled' : ''}>Save Attendance</button>
                        </div>
                    </div>
                </div>
            `;

            attendanceGrid.appendChild(dayBox);
        }
    }


    function fetchAttendance(month, year) {
        // Show loading indicator
        const loadingIndicator = document.getElementById('loadingIndicator');
        const attendanceGrid = document.getElementById('attendanceGrid');
        
        loadingIndicator.classList.remove('d-none');
        attendanceGrid.style.opacity = '0.5';
        
        fetch(`/get_user_attendance?user_id=${userId}&month=${month + 1}&year=${year}`)
            .then(response => response.json())
            .then(data => {
                // Extract attendance and leaves data from the response object
                const attendanceData = data.attendance || [];
                const leavesData = data.leaves || {};
                renderCalendar(month, year, attendanceData, leavesData);
            })
            .catch(error => {
                // console.error("Error fetching attendance data:", error);
                showToast("Error fetching attendance data:"+ error, "error");
                renderCalendar(month, year, [], {});
            })
            .finally(() => {
                // Hide loading indicator
                loadingIndicator.classList.add('d-none');
                attendanceGrid.style.opacity = '1';
            });
    }

    // function fetchAttendance(month, year) {
    //     fetch(`/get_user_attendance?user_id=${userId}&month=${month + 1}&year=${year}`)
    //         .then(response => response.json())
    //         .then(data => {
    //             console.log("Attendance Data:", data); // ✅ Show response data in console
    //             // renderCalendar(month, year, data); // ❌ Commented
    //         })
    //         .catch(error => {
    //             console.error("Error fetching attendance data:", error); // ✅ keep error visible in console
    //             showToast("Error fetching attendance data: " + error, "error");
    //             // renderCalendar(month, year, []); // ❌ Commented
    //         });
    // }

    function updateAttendance(userId, date, presentStatus, inTime, outTime, reason = "") {
        $.ajax({
            url: "/update_attendance",
            type: "POST",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                user_id: userId,
                present_date: date,
                present_status: presentStatus,
                in_time: inTime,
                out_time: outTime,
                reason: reason
            },
            success: function (data) {
                showToast("Attendance updated successfully", "success");
                
                // Update the present status display
                const dayBox = document.querySelector(`[data-date="${date}"]`);
                if (dayBox) {
                    const presentStatusElement = dayBox.querySelector('.present-status');
                    if (data.status) {
                        presentStatusElement.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                        
                        // Update status color based on status
                        presentStatusElement.className = 'fw-bold present-status';
                        if (data.status === 'present') {
                            presentStatusElement.classList.add('text-success');
                        } else if (data.status === 'working') {
                            presentStatusElement.classList.add('text-warning');
                        } else {
                            presentStatusElement.classList.add('text-info');
                        }
                    }
                    
                    // Clear validation states after successful save
                    const inReasonText = dayBox.querySelector('.in-reason-text');
                    const outReasonText = dayBox.querySelector('.out-reason-text');
                    
                    if (inReasonText) inReasonText.classList.remove('is-invalid');
                    if (outReasonText) outReasonText.classList.remove('is-invalid');
                }
                
                if (data.month !== undefined && data.year !== undefined) {
                    fetchAttendance(data.month - 1, data.year);
                }
            },
            error: function (xhr, status, error) {
                let errorMessage = "Error updating attendance";
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showToast(errorMessage, "error");
                
                // If validation failed, highlight the reason fields
                if (xhr.responseJSON && xhr.responseJSON.error === 'validation_failed') {
                    const dayBox = document.querySelector(`[data-date="${date}"]`);
                    if (dayBox) {
                        const inReasonText = dayBox.querySelector('.in-reason-text');
                        const outReasonText = dayBox.querySelector('.out-reason-text');
                        
                        // Highlight fields that need attention
                        if (inReasonText && !inReasonText.classList.contains('d-none') && !inReasonText.value.trim()) {
                            inReasonText.classList.add('is-invalid');
                            inReasonText.focus();
                        }
                        if (outReasonText && !outReasonText.classList.contains('d-none') && !outReasonText.value.trim()) {
                            outReasonText.classList.add('is-invalid');
                            if (!inReasonText || inReasonText.value.trim()) {
                                outReasonText.focus();
                            }
                        }
                    }
                }
            }
        });
    }

    // Event Delegation for Dynamic Elements - Working button functionality removed

    attendanceGrid.addEventListener('change', function (event) {
        if (event.target.classList.contains('in-time') || event.target.classList.contains('out-time')) {
            const dayBox = event.target.closest('.day-box');
            const inTime = dayBox.querySelector('.in-time').value;
            const outTime = dayBox.querySelector('.out-time').value;
            
            const inReasonText = dayBox.querySelector('.in-reason-text');
            const inReasonLabel = dayBox.querySelector('.in-reason-label');
            const outReasonText = dayBox.querySelector('.out-reason-text');
            const outReasonLabel = dayBox.querySelector('.out-reason-label');
            const saveBtn = dayBox.querySelector('.save-btn');

            // Show/hide in-time reason field
            if (inTime) {
                inReasonText.classList.remove('d-none');
                inReasonLabel.classList.remove('d-none');
                inReasonText.setAttribute('required', 'required');
                
                // Focus on in-reason field if it's empty
                if (!inReasonText.value.trim()) {
                    inReasonText.focus();
                }
            } else {
                inReasonText.classList.add('d-none');
                inReasonLabel.classList.add('d-none');
                inReasonText.removeAttribute('required');
                inReasonText.value = '';
            }

            // Show/hide out-time reason field
            if (outTime) {
                outReasonText.classList.remove('d-none');
                outReasonLabel.classList.remove('d-none');
                outReasonText.setAttribute('required', 'required');
                
                // Focus on out-reason field if it's empty and in-reason is filled
                if (!outReasonText.value.trim() && (!inTime || inReasonText.value.trim())) {
                    outReasonText.focus();
                }
            } else {
                outReasonText.classList.add('d-none');
                outReasonLabel.classList.add('d-none');
                outReasonText.removeAttribute('required');
                outReasonText.value = '';
            }

            // Show/hide save button
            if (inTime || outTime) {
                saveBtn.classList.remove('d-none');
            } else {
                saveBtn.classList.add('d-none');
            }
        }
    });

    // Add event listener for save button
    attendanceGrid.addEventListener('click', function (event) {
        if (event.target.classList.contains('save-btn')) {
            const dayBox = event.target.closest('.day-box');
            const date = dayBox.dataset.date;
            const inTime = dayBox.querySelector('.in-time').value;
            const outTime = dayBox.querySelector('.out-time').value;
            
            const inReasonText = dayBox.querySelector('.in-reason-text');
            const outReasonText = dayBox.querySelector('.out-reason-text');
            const inReason = inReasonText.value.trim();
            const outReason = outReasonText.value.trim();

            // Validate reasons are provided for respective time entries
            let hasError = false;
            
            if (inTime && !inReason) {
                inReasonText.classList.add('is-invalid');
                showToast("Please provide a reason for in-time", "error");
                hasError = true;
            } else {
                inReasonText.classList.remove('is-invalid');
            }
            
            if (outTime && !outReason) {
                outReasonText.classList.add('is-invalid');
                showToast("Please provide a reason for out-time", "error");
                hasError = true;
            } else {
                outReasonText.classList.remove('is-invalid');
            }

            if (hasError) {
                return;
            }

            // Combine reasons into a single string for database storage
            let combinedReason = '';
            if (inTime && inReason) {
                combinedReason += `In: ${inReason}`;
            }
            if (outTime && outReason) {
                if (combinedReason) combinedReason += ' | ';
                combinedReason += `Out: ${outReason}`;
            }
            
            // Ensure we have a reason if any time is provided
            if ((inTime || outTime) && !combinedReason) {
                showToast("Please provide appropriate reasons for the time entries", "error");
                return;
            }
            
            // Determine present status based on time entries
            let presentStatus = 'present';
            if (inTime && !outTime) {
                presentStatus = 'working';
            } else if (inTime && outTime) {
                presentStatus = 'present';
            } else if (!inTime && outTime) {
                // Only out-time provided
                presentStatus = 'present';
            }

            console.log('Saving attendance with combined reason:', combinedReason);
            updateAttendance(userId, date, presentStatus, inTime, outTime, combinedReason);
        }
    });

    prevMonthBtn.addEventListener('click', () => fetchAttendance(--currentMonth, currentYear));
    nextMonthBtn.addEventListener('click', () => fetchAttendance(++currentMonth, currentYear));

    fetchAttendance(currentMonth, currentYear);
});

// Toast notification function
function showToast(message, type = 'info') {
    // Check if toast container exists, if not create it
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        `;
        document.body.appendChild(toastContainer);
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show`;
    toast.style.cssText = `
        min-width: 300px;
        margin-bottom: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    `;
    
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    toastContainer.appendChild(toast);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 5000);
}

</script>

@endsection