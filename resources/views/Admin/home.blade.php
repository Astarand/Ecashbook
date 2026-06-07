@extends('App.Layout')

@section('container')

<div class="pc-content">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-0">Dashboard Overview</h4>
                        @php
                            $currentYear  = date('Y');
                            $currentMonth = date('n');
                            $currentFY = ($currentMonth < 4) ? $currentYear - 1 : $currentYear;
                        @endphp
                        <p class="mb-0 text-muted mt-2"><small>Financial Year: <strong id="financial-year-display">{{ $currentFY }}-{{ $currentFY + 1 }}</strong></small></p>
                    </div>
                    <div class="d-flex align-items-center">
                        <label class="form-label mb-0 me-2 fw-bold">Select Month: </label>
                        <select class="form-select form-select-lg" id="registration-month-filter" style="min-width: 150px;">
                            <option value="January" {{ date('F') == 'January' ? 'selected' : '' }}>January</option>
                            <option value="February" {{ date('F') == 'February' ? 'selected' : '' }}>February</option>
                            <option value="March" {{ date('F') == 'March' ? 'selected' : '' }}>March</option>
                            <option value="April" {{ date('F') == 'April' ? 'selected' : '' }}>April</option>
                            <option value="May" {{ date('F') == 'May' ? 'selected' : '' }}>May</option>
                            <option value="June" {{ date('F') == 'June' ? 'selected' : '' }}>June</option>
                            <option value="July" {{ date('F') == 'July' ? 'selected' : '' }}>July</option>
                            <option value="August" {{ date('F') == 'August' ? 'selected' : '' }}>August</option>
                            <option value="September" {{ date('F') == 'September' ? 'selected' : '' }}>September</option>
                            <option value="October" {{ date('F') == 'October' ? 'selected' : '' }}>October</option>
                            <option value="November" {{ date('F') == 'November' ? 'selected' : '' }}>November</option>
                            <option value="December" {{ date('F') == 'December' ? 'selected' : '' }}>December</option>
                        </select>
                        <label class="form-label mb-0 ms-3 me-2 fw-bold">Financial Year: </label>
                        <select class="form-select form-select-lg" id="registration-year-filter" style="min-width: 150px;">
                            @php
                                $currentYear  = date('Y');
                                $currentMonth = date('n');

                                // Financial year starts from April
                                $currentFY = ($currentMonth < 4) ? $currentYear - 1 : $currentYear;
                            @endphp

                            @for ($year = $currentFY + 2; $year >= 2020; $year--)
                                <option value="{{ $year }}" {{ $year == $currentFY ? 'selected' : '' }}>
                                    {{ $year }}-{{ $year + 1 }}
                                </option>
                            @endfor
                        </select>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-3">Monthly Registration Stats</h5>
                </div>
                <div class="card-body">
                    <div id="registration-stats-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-3">Monthly Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <p class="mb-0 text-muted">Total Customers</p>
                            <h3 class="mb-0 text-primary">0</h3>
                        </div>
                        <div class="col-6">
                            <p class="mb-0 text-muted">Total CAs</p>
                            <h3 class="mb-0 text-success">0</h3>
                        </div>
                        <div class="col-6">
                            <p class="mb-0 text-muted">New Customers</p>
                            <h3 class="mb-0 text-primary">0</h3>
                        </div>
                        <div class="col-6">
                            <p class="mb-0 text-muted">New CAs</p>
                            <h3 class="mb-0 text-success">0</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-3">Verification Status</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <p class="mb-0 text-muted">Pending</p>
                            <h3 class="mb-0 text-warning">0</h3>
                        </div>
                        <div class="col-6">
                            <p class="mb-0 text-muted">Verified</p>
                            <h3 class="mb-0 text-success">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-3">Revenue Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-4">
                        <div class="col-12">
                            <div id="revenue-radial-chart"></div>
                        </div>
                    </div>
                    <div class="row text-center g-3">
                        <div class="col-4">
                            <p class="mb-0 text-muted">CA Platform Service Fee</p>
                            <h5 class="mb-0 text-primary" id="revenue-ca-commission">₹0</h5>
                        </div>
                        <div class="col-4">
                            <p class="mb-0 text-muted">Direct Platform Usage Credit </p>
                            <h5 class="mb-0 text-success" id="revenue-direct-earning">₹0</h5>
                        </div>
                        <div class="col-4">
                            <p class="mb-0 text-muted">Package Renewal</p>
                            <h5 class="mb-0 text-warning" id="revenue-package-renewal">₹0</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-3">Package Sales Analytics</h5>
                </div>
                <div class="card-body">
                    <div id="subscription-stacked-chart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Today's Employee Attendance</h5>
                        <div class="text-muted d-flex align-items-center">
                            <span id="current-date">{{ date('j M, Y') }}</span>
                            <div class="date-picker-container ms-2">
                                <button id="date-picker-btn" type="button" title="Change Date">
                                    <i class="ph-duotone ph-pencil-simple"></i>
                                </button>
                                <input type="date" id="attendance-date-picker">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="p-3 bg-light-primary rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary text-primary-light rounded fs-20">
                                            <i class="mdi mdi-account-multiple"></i>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="text-muted text-uppercase mt-0">Total Employees</h6>
                                <h3 class="my-2" id="total-employees">0</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light-success rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-success text-success-light rounded fs-20">
                                            <i class="mdi mdi-account-check"></i>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="text-muted text-uppercase mt-0">Present</h6>
                                <h3 class="my-2" id="present-employees">0</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light-warning rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-warning text-warning-light rounded fs-20">
                                            <i class="mdi mdi-clock-alert"></i>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="text-muted text-uppercase mt-0">Late</h6>
                                <h3 class="my-2" id="late-employees">0</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light-danger rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-danger text-danger-light rounded fs-20">
                                            <i class="mdi mdi-account-off"></i>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="text-muted text-uppercase mt-0">Absent</h6>
                                <h3 class="my-2" id="absent-employees">0</h3>
                            </div>
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
    .date-picker-container {
        position: relative;
    }

    #date-picker-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 4px;
        transition: all 0.2s;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        cursor: pointer;
    }

    #date-picker-btn:hover {
        background-color: #e9ecef;
        border-color: #ced4da;
    }

    #date-picker-btn:active {
        background-color: #dee2e6;
    }

    #date-picker-btn i {
        font-size: 14px;
        color: #495057;
    }

    #attendance-date-picker {
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
        width: 28px;
        height: 28px;
        cursor: pointer;
        z-index: 2;
    }
</style>

<script>
let registrationChart = null;

document.addEventListener('DOMContentLoaded', function() {
    const monthDropdown = document.getElementById('registration-month-filter');
    const yearDropdown = document.getElementById('registration-year-filter');
    const financialYearDisplay = document.getElementById('financial-year-display');

    // Helper function to calculate financial year
    function calculateFinancialYear(month, year) {
        const monthIndex = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ].indexOf(month) + 1;

        if (monthIndex >= 4) {
            return `${year}-${year + 1}`;
        } else {
            return `${year - 1}-${year}`;
        }
    }

    // Helper function to get day suffix
    function getDaySuffix(day) {
        if (day >= 11 && day <= 13) return 'th';
        switch (day % 10) {
            case 1: return 'st';
            case 2: return 'nd';
            case 3: return 'rd';
            default: return 'th';
        }
    }

    // Helper function to get days in month
    function getDaysInMonth(month) {
        const months = {
            January: 31, February: 28, March: 31, April: 30, May: 31, June: 30,
            July: 31, August: 31, September: 30, October: 31, November: 30, December: 31
        };
        return months[month] || 30;
    }

    // Initialize the chart
    function initializeChart(month) {
        const daysInMonth = getDaysInMonth(month);
        const days = Array.from({ length: daysInMonth }, (_, i) => i + 1);
        const dayLabels = days.map(day => day + getDaySuffix(day));

        const chartOptions = {
            series: [
                {
                    name: "New Customers",
                    data: Array(daysInMonth).fill(0)
                },
                {
                    name: "New CAs",
                    data: Array(daysInMonth).fill(0)
                }
            ],
            chart: {
                type: "bar",
                height: 310,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "55%",
                    endingShape: "rounded",
                    borderRadius: 5,
                    gap: 20,
                    barPadding: 5
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 0,
                curve: "smooth"
            },
            xaxis: {
                categories: dayLabels,
                labels: {
                    style: {
                        fontSize: "12px"
                    }
                }
            },
            yaxis: {
                title: {
                    text: "Number of Registrations"
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " registrations";
                    }
                }
            },
            colors: ["#36b9cc", "#1cc88a"],
            legend: {
                position: "top",
                horizontalAlign: "right",
                floating: true,
                offsetY: -25,
                offsetX: -5
            }
        };

        if (document.getElementById('registration-stats-chart')) {
            if (registrationChart) {
                registrationChart.destroy();
            }
            
            // Delay initialization to ensure ApexCharts is loaded
            setTimeout(function() {
                if (typeof ApexCharts !== 'undefined') {
                    registrationChart = new ApexCharts(
                        document.getElementById('registration-stats-chart'),
                        chartOptions
                    );
                    registrationChart.render();
                    console.log('Chart initialized for month:', month, 'with days:', daysInMonth);
                } else {
                    console.error('ApexCharts is not defined');
                }
            }, 100);
        }
    }

    // Fetch dashboard data via AJAX
    function fetchDashboardData() {
        const month = monthDropdown.value;
        const year = parseInt(yearDropdown.value);

        console.log('Fetching data for:', { month, year, yearType: typeof year });

        $.ajax({
            type: 'POST',
            url: '{{ route("admin.dashboard-stats") }}',
            data: {
                month: month,
                year: year,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('API Response:', response);
                if (response.status === 'success') {
                    updateDashboard(response.data, month);
                } else {
                    console.error('Error:', response.message);
                    setDefaultValues();
                }
            },
            error: function(error) {
                console.error('Error fetching dashboard data:', error);
                setDefaultValues();
            }
        });
    }

    // Set default values (0) if data fetch fails
    function setDefaultValues() {
        const summaryCard = document.querySelector('.col-md-3 .card:first-of-type');
        const summaryH3s = summaryCard.querySelectorAll('.row.g-3 h3');
        
        if (summaryH3s.length >= 4) {
            summaryH3s[0].textContent = '0';
            summaryH3s[1].textContent = '0';
            summaryH3s[2].textContent = '0';
            summaryH3s[3].textContent = '0';
        }

        const verificationCard = document.querySelector('.col-md-3 .card:nth-of-type(2)');
        const verificationH3s = verificationCard.querySelectorAll('.row.g-3 h3');
        
        if (verificationH3s.length >= 2) {
            verificationH3s[0].textContent = '0';
            verificationH3s[1].textContent = '0';
        }
    }

    // Update dashboard with fetched data
    function updateDashboard(data, month) {
        // Update Monthly Summary
        const summaryCard = document.querySelector('.col-md-3 .card:first-of-type');
        const summaryH3s = summaryCard.querySelectorAll('.row.g-3 h3');
        
        if (summaryH3s.length >= 4) {
            summaryH3s[0].textContent = data.totalCustomers || 0;
            summaryH3s[1].textContent = data.totalCAs || 0;
            summaryH3s[2].textContent = data.newCustomers || 0;
            summaryH3s[3].textContent = data.newCAs || 0;
        }

        console.log('Dashboard data:', {
            totalCustomers: data.totalCustomers,
            totalCAs: data.totalCAs,
            newCustomers: data.newCustomers,
            newCAs: data.newCAs,
            dailyCustomersLength: data.dailyCustomers ? data.dailyCustomers.length : 0,
            dailyCAsLength: data.dailyCAs ? data.dailyCAs.length : 0
        });

        // Update Verification Status
        const verificationCard = document.querySelector('.col-md-3 .card:nth-of-type(2)');
        const verificationH3s = verificationCard.querySelectorAll('.row.g-3 h3');
        
        if (verificationH3s.length >= 2) {
            verificationH3s[0].textContent = data.pendingVerification || 0;
            verificationH3s[1].textContent = data.verifiedUsers || 0;
        }

        // Update chart if it exists
        if (registrationChart) {
            try {
                const daysInMonth = getDaysInMonth(month);
                
                // Ensure data arrays have correct length (pad with zeros if needed)
                let customerData = data.dailyCustomers || [];
                let caData = data.dailyCAs || [];
                
                // Pad arrays to match days in month
                while (customerData.length < daysInMonth) {
                    customerData.push(0);
                }
                while (caData.length < daysInMonth) {
                    caData.push(0);
                }
                
                // Trim if too long
                customerData = customerData.slice(0, daysInMonth);
                caData = caData.slice(0, daysInMonth);

                console.log('Updating chart with:', {
                    daysInMonth: daysInMonth,
                    customerDataLength: customerData.length,
                    caDataLength: caData.length,
                    customerData: customerData,
                    caData: caData
                });

                registrationChart.updateSeries([
                    {
                        name: "New Customers",
                        data: customerData
                    },
                    {
                        name: "New CAs",
                        data: caData
                    }
                ]);
            } catch (error) {
                console.error('Error updating chart:', error);
            }
        }
    }

    // Update financial year display
    function updateFinancialYear() {
        const month = monthDropdown.value;
        const year = parseInt(yearDropdown.value);
        const financialYear = calculateFinancialYear(month, year);
        financialYearDisplay.textContent = financialYear;
    }

    // Event listeners
    monthDropdown.addEventListener('change', function() {
        updateFinancialYear();
        initializeChart(this.value);
        fetchDashboardData();
    });

    yearDropdown.addEventListener('change', function() {
        updateFinancialYear();
        fetchDashboardData();
    });

    // Initial load
    updateFinancialYear();
    const currentMonth = monthDropdown.value;
    initializeChart(currentMonth);
    fetchDashboardData();

    // Employee attendance date picker
    const datePickerBtn = document.getElementById('date-picker-btn');
    const datePicker = document.getElementById('attendance-date-picker');

    if (datePickerBtn && datePicker) {
        const today = new Date();
        datePicker.valueAsDate = today;

        datePickerBtn.addEventListener('click', function(e) {
            e.preventDefault();
            datePicker.click();
        });

        datePicker.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            const formattedDate = selectedDate.toLocaleDateString('en-US', options);
            document.getElementById('current-date').textContent = formattedDate;

            // Update attendance data
            updateEmployeeAttendance(this.value);
        });
    }
});

// Update employee attendance
function updateEmployeeAttendance(selectedDate = null) {
    const today = selectedDate ? new Date(selectedDate) : new Date();
    const options = { day: 'numeric', month: 'short', year: 'numeric' };
    const formattedDate = today.toLocaleDateString('en-US', options);
    document.getElementById('current-date').textContent = formattedDate;

    const dayOfWeek = today.getDay();
    const totalEmployees = 45;

    let presentPercentage, latePercentage;

    if (dayOfWeek === 0 || dayOfWeek === 6) {
        presentPercentage = 0.45 + Math.random() * 0.1;
        latePercentage = 0.1 + Math.random() * 0.05;
    } else {
        const dateVariation = (today.getDate() % 10) * 0.01;
        presentPercentage = 0.75 + dateVariation + Math.random() * 0.1;
        latePercentage = 0.05 + Math.random() * 0.1;
    }

    let presentCount = Math.round(totalEmployees * presentPercentage);
    const lateCount = Math.round(totalEmployees * latePercentage);
    let absentCount = totalEmployees - presentCount - lateCount;

    if (absentCount < 0) {
        absentCount = 0;
        presentCount = totalEmployees - lateCount;
    }

    document.getElementById('total-employees').textContent = totalEmployees;
    document.getElementById('present-employees').textContent = presentCount;
    document.getElementById('late-employees').textContent = lateCount;
    document.getElementById('absent-employees').textContent = absentCount;
}

// Initialize attendance on page load
updateEmployeeAttendance();
</script>