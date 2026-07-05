@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ Main Content ] start -->
    <div class="row">
      <!-- [ sample-page ] start -->
        <div class="row">
          <div class="card shadow-sm">
            <div class="card-body">
                <!-- Month Slider -->
                <div class="slider-container">
                    <button id="prevMonth" class="btn btn-secondary">&lt;</button>
                    <h5 id="currentMonth">January 2025</h5>
                    <button id="nextMonth" class="btn btn-secondary">&gt;</button>
                </div>
                <div class="row" id="attendanceGrid">
                </div>
              </div>
          </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const attendanceGrid = document.getElementById('attendanceGrid');
        const currentMonthDisplay = document.getElementById('currentMonth');
        const prevMonthBtn = document.getElementById('prevMonth');
        const nextMonthBtn = document.getElementById('nextMonth');
        const monthNames = [
            "January", "February", "March", "April", "May",
            "June", "July", "August", "September", "October", "November", "December"
        ];
        let currentYear = 2025;
        let currentMonth = 0;

        function renderCalendar(month, year) {
            attendanceGrid.innerHTML = ""; // Clear previous grid
            currentMonthDisplay.textContent = `${monthNames[month]} ${year}`;

            // Get the number of days in the month
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            // Generate Day Boxes
            for (let day = 1; day <= daysInMonth; day++) {
                // Format day as DD/MM/YY
                const formattedDate = `${String(day).padStart(2, '0')}/${String(month + 1).padStart(2, '0')}/${year}`;

                const dayBox = document.createElement('div');
                dayBox.classList.add('col-md-4', 'mb-4'); // Responsive column class

                dayBox.innerHTML = `
                    <div class="day-box shadow-sm p-3 bg-white rounded">
                        <div class="date mb-3 text-primary fw-bold">Day: ${formattedDate}</div>
                        
                        <!-- In-Time & Out-Time Row -->
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">In-Time:</label>
                                <div class="input-group">
                                    <input type="time" class="form-control" />
                                    <span class="input-group-text">
                                        <i class="bi bi-clock"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Out-Time:</label>
                                <div class="input-group">
                                    <input type="time" class="form-control" />
                                    <span class="input-group-text">
                                        <i class="bi bi-clock"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Lunch Start & Lunch End Row -->
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">Lunch Start:</label>
                                <div class="input-group">
                                    <input type="time" class="form-control" />
                                    <span class="input-group-text">
                                        <i class="bi bi-clock"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Lunch End:</label>
                                <div class="input-group">
                                    <input type="time" class="form-control" />
                                    <span class="input-group-text">
                                        <i class="bi bi-clock"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                attendanceGrid.appendChild(dayBox);
            }
        }

        prevMonthBtn.addEventListener('click', function () {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar(currentMonth, currentYear);
        });

        nextMonthBtn.addEventListener('click', function () {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar(currentMonth, currentYear);
        });
        renderCalendar(currentMonth, currentYear);
    });
</script>
@endsection