//Main initialization when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    /******************************************
     * CHART 1: REGISTRATION STATS CHART
     * Type: Regular Bar Chart (non-stacked)
     * Purpose: Shows daily new customer and CA registrations
     * Data: Daily registration counts for the selected month
     * Features:
     * - Two data series: "New Customers" (blue) and "New CAs" (green)
     * - X-axis shows days of month (1st, 2nd, 3rd...)
     * - Y-axis shows number of registrations
     * - 20px gap between bars for clear separation
     * - Rounded bar corners for modern look
     ******************************************/

    // Registration Stats Chart configuration
    const registrationChartOptions = {
        series: [
            {
                name: "New Customers",
                data: [],
            },
            {
                name: "New CAs",
                data: [],
            },
        ],
        chart: {
            type: "bar",
            height: 310,
            toolbar: {
                show: false,
            },
            zoom: {
                enabled: false,
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "55%",
                endingShape: "rounded",
                borderRadius: 5,
                distributed: false,
                dataLabels: {
                    position: "top",
                },
                barHeight: "80%",
                rangeBarOverlap: false,
                rangeBarGroupRows: false,
                isRange: false,
                isDumbbell: false,
                colors: {
                    ranges: [],
                },
                borderRadius: 5,
                borderRadiusApplication: "end",
                borderRadiusWhenStacked: "last",
                columnWidth: "55%",
                gap: 20,
                barPadding: 5,
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            width: 0,
            curve: "smooth",
        },
        xaxis: {
            categories: [],
            labels: {
                style: {
                    fontSize: "12px",
                },
            },
        },
        yaxis: {
            title: {
                text: "Number of Registrations",
            },
        },
        fill: {
            opacity: 1,
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " registrations";
                },
            },
        },
        colors: ["#36b9cc", "#1cc88a"],
        legend: {
            position: "top",
            horizontalAlign: "right",
            floating: true,
            offsetY: -25,
            offsetX: -5,
        },
    };

    // Initialize Registration Stats Chart
    setTimeout(function () {
        const registrationChart = new ApexCharts(
            document.querySelector("#registration-stats-chart"),
            registrationChartOptions
        );
        registrationChart.render();

        /******************************************
         * CHART 2: SUBSCRIPTION STACKED CHART
         * Type: Stacked Bar Chart
         * Purpose: Shows package sales analytics by week
         * Data: Weekly subscription sales for each package type
         * Features:
         * - Eight data series for different subscription types
         * - X-axis shows weeks (Week 1-4)
         * - Stacked bars show distribution across package types
         * - Height: 350px for better visibility of multiple segments
         * - Legend at bottom shows all package types
         * - Eight different colors to distinguish package types
         ******************************************/

        // Initialize Subscription Stacked Chart
        const stackedChartOptions = {
            series: [
                {
                    name: "Basic Monthly",
                    data: [],
                },
                {
                    name: "Basic Yearly",
                    data: [],
                },
                {
                    name: "Standard Monthly",
                    data: [],
                },
                {
                    name: "Standard Yearly",
                    data: [],
                },
                {
                    name: "Premium Monthly",
                    data: [],
                },
                {
                    name: "Premium Yearly",
                    data: [],
                },
                {
                    name: "Enterprise Monthly",
                    data: [],
                },
                {
                    name: "Enterprise Yearly",
                    data: [],
                },
            ],
            chart: {
                type: "bar",
                height: 280,
                stacked: true,
                toolbar: {
                    show: false,
                },
                zoom: {
                    enabled: false,
                },
            },
            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        legend: {
                            position: "bottom",
                            offsetX: -10,
                            offsetY: 0,
                        },
                    },
                },
            ],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "55%",
                },
            },
            xaxis: {
                categories: ["Week 1", "Week 2", "Week 3", "Week 4"],
            },
            legend: {
                position: "bottom",
            },
            fill: {
                opacity: 1,
            },
            colors: [
                "#36b9cc",
                "#1cc88a",
                "#4e73df",
                "#f6c23e",
                "#e74a3b",
                "#6f42c1",
                "#fd7e14",
                "#20c9a6",
            ],
        };

        const stackedChart = new ApexCharts(
            document.querySelector("#subscription-stacked-chart"),
            stackedChartOptions
        );
        stackedChart.render();

        /******************************************
         * CHART 3: REVENUE DONUT CHART
         * Type: Donut Chart
         * Purpose: Shows breakdown of total earnings
         * Data: Fixed values showing revenue distribution
         * Features:
         * - Three segments showing revenue sources:
         *   1. CA Commission (₹1,700 - Blue)
         *   2. Direct Earning (₹1,944 - Green)
         *   3. Package Renewal (₹1,210 - Yellow)
         * - Total value (₹4,854.00) displayed in center
         * - 70% donut size (large hollow center)
         * - No external legend for cleaner look
         * - Custom font sizes for better mobile display
         ******************************************/

        // Initialize Revenue Donut Chart
        const radialChartOptions = {
            series: [1700, 1944, 1210], // CA Commission, Direct Earning, Package Renewal values
            chart: {
                type: "donut",
                height: 250,
            },
            labels: ["CA Commission", "Direct Earning", "Package Renewal"],
            colors: ["#4e73df", "#1cc88a", "#f6c23e"],
            legend: {
                show: false,
            },
            dataLabels: {
                enabled: false,
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: "70%",
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: "12px",
                                fontFamily: "Helvetica, Arial, sans-serif",
                                fontWeight: 300,
                                color: "#373d3f",
                                offsetY: -10,
                            },
                            value: {
                                show: true,
                                fontSize: "16px",
                                fontFamily: "Helvetica, Arial, sans-serif",
                                fontWeight: 500,
                                color: "#373d3f",
                                offsetY: 5,
                                formatter: function () {
                                    return "₹4854.00";
                                },
                            },
                            total: {
                                show: true,
                                showAlways: true,
                                label: "Total Earning",
                                fontSize: "16px",
                                fontWeight: 700,
                                color: "#373d3f",
                                formatter: function () {
                                    return "₹4854.00";
                                },
                            },
                        },
                    },
                },
            },
            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200,
                        },
                        legend: {
                            position: "bottom",
                        },
                    },
                },
            ],
        };

        const radialChart = new ApexCharts(
            document.querySelector("#revenue-radial-chart"),
            radialChartOptions
        );
        radialChart.render();

        // Declare variable to track current revenue for radial chart
        let totalRevenueCurrent = 0;

        // Get current month
        const currentDate = new Date();
        const currentMonth = currentDate.toLocaleString("default", {
            month: "long",
        });

        // Set initial month in dropdown
        const monthDropdown = document.querySelector(
            "#registration-month-filter"
        );
        monthDropdown.value = currentMonth;

        // Get year dropdown
        const yearDropdown = document.querySelector(
            "#registration-year-filter"
        );
        const currentYear = currentDate.getFullYear();

        // Function to calculate financial year
        function calculateFinancialYear(month, year) {
            // Convert month name to number (1-12)
            const monthIndex = [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December",
            ].indexOf(month) + 1;

            // Financial year runs from April to March
            if (monthIndex >= 4) {
                // April to December - FY starts this year
                return `${year}-${year + 1}`;
            } else {
                // January to March - FY started in previous year
                return `${year - 1}-${year}`;
            }
        }

        // Function to update financial year display
        function updateFinancialYearDisplay() {
            const selectedMonth = monthDropdown.value;
            const selectedYear = parseInt(yearDropdown.value);
            const financialYear = calculateFinancialYear(
                selectedMonth,
                selectedYear
            );
            document.getElementById("financial-year-display").textContent =
                financialYear;
        }

        // Initialize financial year display
        updateFinancialYearDisplay();

        // Initialize dashboard title with current month
        document.querySelector(
            ".card h4.mb-0"
        ).textContent = `Dashboard Overview - ${currentMonth}`;

        // Initial data load from API
        fetchAndUpdateDashboard();

        // Month filter change event
        monthDropdown.addEventListener("change", function () {
            const selectedMonth = this.value;

            // Update dashboard title with selected month
            document.querySelector(
                ".card h4.mb-0"
            ).textContent = `Dashboard Overview - ${selectedMonth}`;

            // Update financial year display
            updateFinancialYearDisplay();

            // Fetch updated data when month changes
            fetchAndUpdateDashboard();
        });

        // Year filter change event
        yearDropdown.addEventListener("change", function () {
            // Update financial year display
            updateFinancialYearDisplay();
            
            // Fetch updated data when year changes
            fetchAndUpdateDashboard();
        });

        // Function to fetch dashboard data from API
        function fetchAndUpdateDashboard() {
            const selectedMonth = monthDropdown.value;
            const selectedYear = parseInt(yearDropdown.value);

            // Make API call to fetch dashboard statistics
            fetch(`/api/admin/dashboard-stats?month=${selectedMonth}&year=${selectedYear}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        updateChartsWithData(data.data, selectedMonth);
                    } else {
                        console.error('Failed to fetch dashboard data:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching dashboard data:', error);
                });
        }

        // Function to update charts with fetched data
        function updateChartsWithData(data, month) {
            // Generate day labels
            const daysInMonth = getDaysInMonth(month);
            const days = Array.from({ length: daysInMonth }, (_, i) => i + 1);
            const dayLabels = days.map((day) => {
                const suffix = getDaySuffix(day);
                return day + suffix;
            });

            // Update registration chart with fetched data
            registrationChart.updateOptions({
                xaxis: {
                    categories: dayLabels,
                },
            });

            registrationChart.updateSeries([
                {
                    name: "New Customers",
                    data: data.dailyCustomers,
                },
                {
                    name: "New CAs",
                    data: data.dailyCAs,
                },
            ]);

            // Update Monthly Summary - get all h3 elements in the summary card
            const summaryCard = document.querySelector('.col-md-3 .card:first-of-type');
            const summaryH3s = summaryCard.querySelectorAll('.row.g-3 h3');
            
            if (summaryH3s.length >= 4) {
                summaryH3s[0].textContent = data.totalCustomers;      // Total Customers
                summaryH3s[1].textContent = data.totalCAs;            // Total CAs
                summaryH3s[2].textContent = data.newCustomers;        // New Customers
                summaryH3s[3].textContent = data.newCAs;              // New CAs
            }

            // Update Verification Status - get h3 in verification card
            const verificationCard = document.querySelector('.col-md-3 .card:nth-of-type(2)');
            const verificationH3s = verificationCard.querySelectorAll('.row.g-3 h3');
            
            if (verificationH3s.length >= 2) {
                verificationH3s[0].textContent = data.pendingVerification;  // Pending
                verificationH3s[1].textContent = data.verifiedUsers;        // Verified
            }
        }

        // Function to update the registration chart
        function updateRegistrationChart(chart, month) {
            // Generate days array for the selected month
            const daysInMonth = getDaysInMonth(month);
            const days = Array.from({ length: daysInMonth }, (_, i) => i + 1);

            // Create day labels (1st, 2nd, 3rd, etc.)
            const dayLabels = days.map((day) => {
                const suffix = getDaySuffix(day);
                return day + suffix;
            });

            /******************************************
             * DATA GENERATION FOR ALL CHARTS
             * This section generates data for all three charts based on the selected month
             *
             * For Registration Chart:
             * - Generates daily data based on weekly patterns
             * - Higher values in weeks 2 and 4
             *
             * For Stacked Chart:
             * - Creates weekly data for 8 subscription types
             * - Will update the stackedChart in a later section
             *
             * For Donut Chart:
             * - Static values are used, but would normally be calculated
             *   based on the monthly data
             ******************************************/

            // Use month to create different yet consistent data patterns
            const monthIndex = [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December",
            ].indexOf(month);

            // Monthly multiplier to create different patterns for different months
            const monthMultiplier = (monthIndex + 1) / 6;

            // Generate weekly data for registration chart
            const customerWeekly = [
                Math.floor(45 * monthMultiplier), // Week 1
                Math.floor(65 * monthMultiplier), // Week 2
                Math.floor(38 * monthMultiplier), // Week 3
                Math.floor(52 * monthMultiplier), // Week 4
            ];

            const caWeekly = [
                Math.floor(20 * monthMultiplier), // Week 1
                Math.floor(28 * monthMultiplier), // Week 2
                Math.floor(15 * monthMultiplier), // Week 3
                Math.floor(22 * monthMultiplier), // Week 4
            ];

            // Distribute weekly totals across days for the chart
            // For simplicity, we'll distribute them evenly within each week
            const customerData = [];
            const caData = [];

            // Split the month into 4 weeks
            const daysPerWeek = Math.ceil(daysInMonth / 4);

            for (let week = 0; week < 4; week++) {
                const weekCustomers = customerWeekly[week];
                const weekCAs = caWeekly[week];

                // Get the number of days in this week (handle last week being shorter)
                const daysThisWeek = Math.min(
                    daysPerWeek,
                    daysInMonth - week * daysPerWeek
                );

                // Calculate daily averages for this week
                const dailyCustomers = Math.floor(weekCustomers / daysThisWeek);
                const dailyCAs = Math.floor(weekCAs / daysThisWeek);

                // Distribute with some random variation
                for (let day = 0; day < daysThisWeek; day++) {
                    // Add some randomness to daily values
                    const randomMultiplier = 0.7 + Math.random() * 0.6; // 0.7 to 1.3

                    customerData.push(
                        Math.max(
                            1,
                            Math.floor(dailyCustomers * randomMultiplier)
                        )
                    );
                    caData.push(
                        Math.max(0, Math.floor(dailyCAs * randomMultiplier))
                    );
                }
            }

            // Fill any remaining days (in case of rounding)
            while (customerData.length < daysInMonth) {
                customerData.push(1);
                caData.push(0);
            }

            // Update the chart with new x-axis categories and data
            chart.updateOptions({
                xaxis: {
                    categories: dayLabels,
                },
            });

            // Update the chart series data
            chart.updateSeries([
                {
                    name: "New Customers",
                    data: customerData,
                },
                {
                    name: "New CAs",
                    data: caData,
                },
            ]);

            // Calculate weekly totals for package sales chart
            const packageSalesData = {
                "Basic Monthly": [15, 22, 12, 18],
                "Basic Yearly": [6, 9, 5, 8],
                "Standard Monthly": [25, 35, 20, 30],
                "Standard Yearly": [12, 18, 10, 15],
                "Premium Monthly": [8, 12, 6, 10],
                "Premium Yearly": [4, 6, 3, 5],
                "Enterprise Monthly": [3, 5, 2, 4],
                "Enterprise Yearly": [2, 3, 1, 2],
            };

            // Apply month multiplier to all values
            Object.keys(packageSalesData).forEach((key) => {
                packageSalesData[key] = packageSalesData[key].map((val) =>
                    Math.max(0, Math.floor(val * monthMultiplier))
                );
            });

            // Update stacked chart
            stackedChart.updateSeries([
                {
                    name: "Basic Monthly",
                    data: packageSalesData["Basic Monthly"],
                },
                {
                    name: "Basic Yearly",
                    data: packageSalesData["Basic Yearly"],
                },
                {
                    name: "Standard Monthly",
                    data: packageSalesData["Standard Monthly"],
                },
                {
                    name: "Standard Yearly",
                    data: packageSalesData["Standard Yearly"],
                },
                {
                    name: "Premium Monthly",
                    data: packageSalesData["Premium Monthly"],
                },
                {
                    name: "Premium Yearly",
                    data: packageSalesData["Premium Yearly"],
                },
                {
                    name: "Enterprise Monthly",
                    data: packageSalesData["Enterprise Monthly"],
                },
                {
                    name: "Enterprise Yearly",
                    data: packageSalesData["Enterprise Yearly"],
                },
            ]);

            // Calculate monthly totals
            const totalCustomers = customerData.reduce((a, b) => a + b, 0);
            const totalCAs = caData.reduce((a, b) => a + b, 0);

            // Update the summary card with monthly totals
            const summaryValues = getElementsContainingText(
                ".card-header",
                "Monthly Summary"
            ).flatMap((header) => {
                const cardBody = header.nextElementSibling;
                if (cardBody && cardBody.classList.contains("card-body")) {
                    return Array.from(cardBody.querySelectorAll(".row.g-3 h3"));
                }
                return [];
            });

            if (summaryValues.length >= 4) {
                // Update total values (these would typically come from the backend)
                summaryValues[0].textContent = Math.floor(
                    1500 + monthIndex * 150
                ); // Total Customers
                summaryValues[1].textContent = Math.floor(
                    450 + monthIndex * 40
                ); // Total CAs

                // Update new registrations for the month
                summaryValues[2].textContent = totalCustomers;
                summaryValues[3].textContent = totalCAs;
            }

            // Calculate total package sales
            let totalPackageSales = 0;
            Object.keys(packageSalesData).forEach((key) => {
                totalPackageSales += packageSalesData[key].reduce(
                    (a, b) => a + b,
                    0
                );
            });

            // Calculate revenue - using rupees pricing
            // Prices in rupees
            const prices = {
                "Basic Monthly": 799,
                "Basic Yearly": 7999,
                "Standard Monthly": 1599,
                "Standard Yearly": 15999,
                "Premium Monthly": 2499,
                "Standard Yearly": 24999,
                "Enterprise Monthly": 3999,
                "Enterprise Yearly": 39999,
            };

            // Calculate revenue from package sales
            let packageRevenue = 0;
            Object.keys(packageSalesData).forEach((key) => {
                const price = prices[key] || 1000; // Default price if not found
                const sales = packageSalesData[key].reduce((a, b) => a + b, 0);
                packageRevenue += price * sales;
            });

            // Update current revenue for radial chart
            totalRevenueCurrent = packageRevenue;

            // Update revenue status with breakdowns of Total Earning
            const totalEarning = 4854; // ₹4,854
            const caCommission = 1700; // ₹1,700 (35% of total)
            const directEarning = 1944; // ₹1,944 (40% of total)
            const packageRenewal = 1210; // ₹1,210 (25% of total)

            // Format as Indian number format
            function formatIndianRupees(amount) {
                return "₹" + amount.toLocaleString("en-IN");
            }

            document.getElementById("revenue-ca-commission").textContent =
                formatIndianRupees(caCommission);
            document.getElementById("revenue-direct-earning").textContent =
                formatIndianRupees(directEarning);
            document.getElementById("revenue-package-renewal").textContent =
                formatIndianRupees(packageRenewal);

            // No need to update radial chart as it's now configured with static series

            // Update verification status counts with more realistic data
            const totalSubscriptions = totalCustomers + totalCAs;
            const pendingCount = Math.floor(totalSubscriptions * 0.15); // 15% of subscriptions pending verification
            const verifiedCount = totalSubscriptions - pendingCount; // 85% verified

            const verificationValues = getElementsContainingText(
                ".card-header",
                "Verification Status"
            ).flatMap((header) => {
                const cardBody = header.nextElementSibling;
                if (cardBody && cardBody.classList.contains("card-body")) {
                    return Array.from(cardBody.querySelectorAll(".row.g-3 h3"));
                }
                return [];
            });

            if (verificationValues.length >= 2) {
                verificationValues[0].textContent = pendingCount; // Pending
                verificationValues[1].textContent = verifiedCount; // Verified
            }

            // Update employee attendance with daily data
            updateEmployeeAttendance();
        }

        // Function to update employee attendance daily
        function updateEmployeeAttendance() {
            // Set current date
            const today = new Date();
            const formattedDate = today.toLocaleDateString("en-IN", {
                day: "numeric",
                month: "short",
                year: "numeric",
            });

            // Update the date display
            document.getElementById("current-date").textContent = formattedDate;

            // Create random but realistic attendance data that changes daily
            const totalEmployees = 45; // Total staff

            // Use the current date as a seed for consistent day-to-day values
            const day = today.getDate();
            const dayOfWeek = today.getDay(); // 0 (Sunday) to 6 (Saturday)

            // Lower attendance on weekends (if working weekends)
            const attendanceFactor =
                dayOfWeek === 0 || dayOfWeek === 6 ? 0.7 : 0.9;

            // Generate values based on day of month
            const seed = day % 10; // 0-9 range for variation

            // Calculate attendance numbers
            let presentCount =
                Math.floor(totalEmployees * attendanceFactor) - seed;
            let lateCount = Math.floor((seed + 1) * 0.8) + 2; // 2-10 employees late
            let absentCount = totalEmployees - presentCount - lateCount;

            // Ensure values make sense
            if (absentCount < 0) {
                presentCount += absentCount;
                absentCount = 0;
            }

            // Update the UI
            document.getElementById("total-employees").textContent =
                totalEmployees;
            document.getElementById("present-employees").textContent =
                presentCount;
            document.getElementById("late-employees").textContent = lateCount;
            document.getElementById("absent-employees").textContent =
                absentCount;
        }

        /******************************************
         * HELPER FUNCTIONS
         ******************************************/
        // Helper function to get the number of days in a month
        function getDaysInMonth(monthName) {
            const currentYear = new Date().getFullYear();
            const isLeapYear = (year) => {
                return (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0;
            };

            // February has 29 days in leap years, 28 days in non-leap years
            const februaryDays = isLeapYear(currentYear) ? 29 : 28;

            const months = {
                January: 31,
                February: februaryDays,
                March: 31,
                April: 30,
                May: 31,
                June: 30,
                July: 31,
                August: 31,
                September: 30,
                October: 31,
                November: 30,
                December: 31,
            };

            return months[monthName] || 30;
        }

        // Helper function to get the day suffix (st, nd, rd, th)
        function getDaySuffix(day) {
            if (day >= 11 && day <= 13) {
                return "th";
            }

            switch (day % 10) {
                case 1:
                    return "st";
                case 2:
                    return "nd";
                case 3:
                    return "rd";
                default:
                    return "th";
            }
        }

        // Helper function to get elements containing specific text
        function getElementsContainingText(selector, text) {
            const elements = document.querySelectorAll(selector);
            return Array.from(elements).filter(function (element) {
                return element.textContent.includes(text);
            });
        }
    }, 300);
});

// Update CA verification status counts
function updateCAVerificationStatusCounts() {
    // Get random counts
    const totalCAs = Math.floor(Math.random() * 100) + 100; // Between 100-199
    const pendingCAs = Math.floor(Math.random() * 20) + 10; // Between 10-29
    const verifiedCAs = Math.floor(Math.random() * 40) + 50; // Between 50-89
    const rejectedCAs = totalCAs - pendingCAs - verifiedCAs;

    // Update the DOM
    document.getElementById("total-cas").textContent = totalCAs;
    document.getElementById("pending-verification").textContent = pendingCAs;
    document.getElementById("verified-cas").textContent = verifiedCAs;
    document.getElementById("rejected-cas").textContent = rejectedCAs;
}

// Update employee attendance
function updateEmployeeAttendance(selectedDate = null) {
    // Set current date in the UI
    const today = selectedDate ? new Date(selectedDate) : new Date();
    const options = { day: "numeric", month: "short", year: "numeric" };
    const formattedDate = today.toLocaleDateString("en-US", options);
    document.getElementById("current-date").textContent = formattedDate;

    // Calculate attendance based on day of week
    const dayOfWeek = today.getDay(); // 0 = Sunday, 6 = Saturday
    const totalEmployees = 45;

    let presentPercentage, latePercentage;

    // Weekends have lower attendance
    if (dayOfWeek === 0 || dayOfWeek === 6) {
        presentPercentage = 0.45 + Math.random() * 0.1; // 45-55%
        latePercentage = 0.1 + Math.random() * 0.05; // 10-15%
    } else {
        // Use the date as a seed for some variation
        const dateVariation = (today.getDate() % 10) * 0.01; // 0-9% variation
        presentPercentage = 0.75 + dateVariation + Math.random() * 0.1; // 75-94%
        latePercentage = 0.05 + Math.random() * 0.1; // 5-15%
    }

    // Calculate actual counts
    let presentCount = Math.round(totalEmployees * presentPercentage);
    const lateCount = Math.round(totalEmployees * latePercentage);
    let absentCount = totalEmployees - presentCount - lateCount;

    // Ensure numbers make sense
    if (absentCount < 0) {
        absentCount = 0;
        presentCount = totalEmployees - lateCount;
    }

    // Update the UI
    document.getElementById("total-employees").textContent = totalEmployees;
    document.getElementById("present-employees").textContent = presentCount;
    document.getElementById("late-employees").textContent = lateCount;
    document.getElementById("absent-employees").textContent = absentCount;
}

// Call the functions
updateCAVerificationStatusCounts();
updateEmployeeAttendance();

// Date picker initialization
document.addEventListener("DOMContentLoaded", function () {
    const datePickerBtn = document.getElementById("date-picker-btn");
    const datePicker = document.getElementById("attendance-date-picker");

    if (datePickerBtn && datePicker) {
        // Set the current date as default value
        const today = new Date();
        datePicker.valueAsDate = today;

        // With our overlay approach, we don't need to do anything special on button click
        // The input is positioned directly over the button, so clicking the button
        // will automatically trigger the date picker

        // When date is changed, update the attendance
        datePicker.addEventListener("change", function () {
            updateEmployeeAttendance(this.value);

            // Update the title based on the selected date
            const today = new Date();
            const todayStr = today.toISOString().split("T")[0];

            const attendanceTitle = document.querySelector(".card-title");
            if (
                attendanceTitle &&
                attendanceTitle.textContent.includes("Employee Attendance")
            ) {
                if (this.value === todayStr) {
                    attendanceTitle.textContent = "Today's Employee Attendance";
                } else {
                    const selectedDate = new Date(this.value);
                    const formattedDate = selectedDate.toLocaleDateString(
                        "en-US",
                        {
                            day: "numeric",
                            month: "short",
                            year: "numeric",
                        }
                    );
                    attendanceTitle.textContent = `Employee Attendance (${formattedDate})`;
                }
            }
        });
    }
});
