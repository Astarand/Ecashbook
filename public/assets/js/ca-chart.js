"use strict";
//Main initialization when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    /******************************************
     * CHART 1: TASK WISE CLIENTS CHART
     * Horizontal bar chart showing distribution of clients by task category
     ******************************************/
    // Task wise Clients chart configuration
	const options = {
		series: [{
			name: "Clients",
			data: []
		}],
		chart: {
			type: "bar",
			height: 425,
			toolbar: { show: false },
			fontFamily: 'Inter, sans-serif'
		},
		plotOptions: {
			bar: {
				horizontal: true,
				barHeight: "45%",
				borderRadius: 6,
				distributed: true
			}
		},
		colors: [
			"#008FFB", "#00E396", "#FEB019", "#FF4560",
			"#775DD0", "#3F51B5", "#546E7A"
		],
		dataLabels: {
			enabled: true,
			style: {
				fontSize: "12px",
				fontWeight: 600
			},
			formatter: val => val + " Clients"
		},
		grid: {
			borderColor: "#f1f1f1",
			padding: {
				left: 10,
				right: 30
			}
		},
		xaxis: {
			categories: [],
			title: {
				text: "Platform Usages"
			},
			min: 0,
			tickAmount: 5
		},
		yaxis: {
			title: {
				text: "Task Categories"
			}
		},
		tooltip: {
			y: {
				formatter: val => val + " Clients"
			}
		},
		title: {
			text: "Task Wise Clients",
			align: "left",
			style: {
				fontSize: "16px",
				fontWeight: 600
			}
		},
		subtitle: {
			text: "Based on completed tasks",
			align: "left",
			style: {
				fontSize: "12px"
			}
		},
		noData: {
			text: "Loading..."
		}
	};

	const chart = new ApexCharts(
		document.querySelector("#task-wise-clients-chart"),
		options
	);
	chart.render();


	// Fetch real data
	fetch('/dashboard/task-wise-clients')
		.then(res => res.json())
		.then(data => {

			let categories = [];
			let totals = [];

			data.forEach(row => {
				categories.push(row.task_category_name);
				totals.push(parseInt(row.total));
			});

			chart.updateOptions({
				xaxis: { categories: categories },
				series: [{
					data: totals
				}]
			});
		});

    /******************************************
     * CHART 2: CUSTOMER PAYMENT STATUS CHART
     * Donut chart showing payment distribution with Total Earning in center
     ******************************************/
    // Customer Payment Status donut chart configuration
    const donutOptions = {
        series: [0, 0, 0],
        labels: ["Received", "Pending", "Overdue"],
        chart: {
            type: "donut",
            height: 250,
        },
        plotOptions: {
            pie: {
                donut: {
                    size: "80%",
                    background: "transparent",
                    labels: {
                        show: true,
                        name: {
                            show: false,
                        },
                        value: {
                            show: false,
                        },
                        total: {
                            show: true,
                            label: "Total Earning",
                            color: "#422f90",
                            fontSize: "14px",
                            fontFamily: "Inter, sans-serif",
                            fontWeight: 600,
                            formatter: function (w) {
                                return (
                                    "₹" +
                                    w.globals.seriesTotals
                                        .reduce((a, b) => a + b, 0)
                                        .toFixed(2)
                                );
                            },
                        },
                    },
                },
                offsetX: 0,
                offsetY: 0,
                customScale: 1,
                hollow: {
                    size: "40%",
                    background: "transparent",
                },
            },
        },
        stroke: {
            width: 6,
            colors: ["#fff"],
        },
        dataLabels: {
            enabled: false,
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "₹" + val.toFixed(2);
                },
            },
        },
        legend: {
            show: false,
        },
        colors: ["#10b981", "#f59e0b", "#f43f5e"],
        responsive: [
            {
                breakpoint: 480,
                options: {
                    chart: {
                        height: 200,
                    },
                },
            },
        ],
    };

    // Initialize Customer Payment Status chart
    setTimeout(function () {
		const paymentChart = new ApexCharts(
			document.querySelector("#customer-payment-chart"),
			donutOptions
		);
		paymentChart.render();

		// Initial load
		simulatePaymentData(paymentChart, {
			total: 0,
			received: 0,
			pending: 0,
			overdue: 0
		});

		document
			.querySelector("#payment-month-filter")
			.addEventListener("change", function () {
				const month = this.value;
				fetch(`/api/customer-payment-stats?month=${month}`)
					.then(res => res.json())
					.then(data => {
						simulatePaymentData(paymentChart, data);
					});
			});

		function simulatePaymentData(chart, data) {

			const total = Number(data.total);
			const received = Number(data.received);
			const pending = Number(data.pending);
			const overdue = Number(data.overdue);

			// Update top boxes
			document.getElementById("total-earning").textContent  = "₹" + total.toFixed(2);
			document.getElementById("received-amount").textContent = "₹" + received.toFixed(2);
			document.getElementById("pending-amount").textContent  = "₹" + pending.toFixed(2);
			document.getElementById("overdue-amount").textContent  = "₹" + overdue.toFixed(2);

			// Update donut
			chart.updateSeries([received, pending, overdue]);
		}

	}, 300);



    // Add CSS for chart legends
    const style = document.createElement("style");
    style.innerHTML = `
        .chart-legend {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }
    `;
    document.head.appendChild(style);

    /******************************************
     * CHART 3: MONTHWISE ONBOARD CLIENTS DETAILS CHART
     * Daily stacked bar chart showing client assignments by day
     ******************************************/
    // Monthwise Onboard Clients Details Chart configuration
    const onboardChartOptions = {
        series: [
            {
                name: "Total Assign",
                data: [],
            },
            {
                name: "Request Assign",
                data: [],
            },
            {
                name: "Own Assign",
                data: [],
            },
        ],
        chart: {
            type: "bar",
            height: 236,
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
                borderRadius: 4,
            },
        },
        grid: {
            borderColor: "#f1f5f9",
            strokeDashArray: 4,
            padding: {
                left: 10,
                right: 10
            }
        },
        xaxis: {
            categories: [],
            labels: {
                rotate: -45,
                style: {
                    fontSize: "10px",
                },
            },
        },
        legend: {
            show: false,
        },
        fill: {
            opacity: 1,
        },
        colors: ["#4f46e5", "#0d9488", "#f97316"],
        dataLabels: {
            enabled: false,
        },
    };

    // Initialize Monthwise Onboard Clients chart
    setTimeout(function () {
        const onboardChart = new ApexCharts(
            document.querySelector("#monthwise-onboard-chart"),
            onboardChartOptions
        );
        onboardChart.render();

        // Initial data load
        updateDailyOnboardChart(onboardChart, "January");

        // Month filter change event
        document
            .querySelector("#onboard-month-filter")
            .addEventListener("change", function () {
                const selectedMonth = this.value;
                updateDailyOnboardChart(onboardChart, selectedMonth);
            });

        function updateDailyOnboardChart(chart, month) {
            // Generate days array for the selected month
           fetch(`/dashboard/monthwise-onboard?month=${month}`)
			.then(res => res.json())
			.then(data => {

				let dayLabels = [];
				let totalAssign = [];
				let requestAssign = [];
				let ownAssign = [];

				data.forEach(row => {
					dayLabels.push(row.day + getDaySuffix(row.day));
					totalAssign.push(row.totalAssign);
					requestAssign.push(row.requestAssign);
					ownAssign.push(row.ownAssign);
				});

				chart.updateOptions({
					xaxis: {
						categories: dayLabels
					}
				});

				chart.updateSeries([
					{ name: "Total Assign", data: totalAssign },
					{ name: "Request Assign", data: requestAssign },
					{ name: "Own Assign", data: ownAssign }
				]);

				// Monthly totals
				const totalMonthAssign = totalAssign.reduce((a, b) => a + b, 0);
				const totalMonthRequest = requestAssign.reduce((a, b) => a + b, 0);
				const totalMonthOwn = ownAssign.reduce((a, b) => a + b, 0);

				const legendCounts = document.querySelectorAll(
					".d-flex.justify-content-center .mx-3 h5"
				);
				legendCounts[0].textContent = totalMonthAssign;
				legendCounts[1].textContent = totalMonthRequest;
				legendCounts[2].textContent = totalMonthOwn;
			});
        }

        /******************************************
         * HELPER FUNCTIONS FOR DATE HANDLING
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
    }, 300);

    /******************************************
     * TASK STATUS TABLE
     * Table showing task status with filtering by month
     ******************************************/
    // Initialize the Task Status table
    setTimeout(function () {

		updateTaskStatusData("January");

		document
			.querySelector("#task-month-filter")
			.addEventListener("change", function () {
				const selectedMonth = this.value;
				updateTaskStatusData(selectedMonth);
			});

		function updateTaskStatusData(month) {

			fetch(`/dashboard/task-stats?month=${month}`)
				.then(res => res.json())
				.then(data => {

					document.getElementById("task-total-count").textContent = data.total;
					document.getElementById("task-completed-count").textContent = data.completed;
					document.getElementById("task-pending-count").textContent = data.pending;
					document.getElementById("task-overdue-count").textContent = data.overdue;
					
					// ---- Update table ----
					let html = '';
					data.tasks.forEach(task => {

						let statusBadge = '';
						if (task.project_status == 1) {
							statusBadge = '<span class="badge" style="background-color: rgba(244, 63, 94, 0.1); color: #f43f5e; border: 1px solid rgba(244, 63, 94, 0.15); font-weight: 500; font-size: 11px; padding: 6px 12px; border-radius: 6px;">Pending</span>';
						} else if (task.project_status == 2) {
							statusBadge = '<span class="badge" style="background-color: rgba(245, 158, 11, 0.1); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.15); font-weight: 500; font-size: 11px; padding: 6px 12px; border-radius: 6px;">Ongoing</span>';
						} else {
							statusBadge = '<span class="badge" style="background-color: rgba(16, 185, 129, 0.1); color: #059669; border: 1px solid rgba(16, 185, 129, 0.15); font-weight: 500; font-size: 11px; padding: 6px 12px; border-radius: 6px;">Completed</span>';
						}

						html += `
							<tr>
								<td>${task.name}</td>
								<td>${task.task_category_name}</td>
								<td>${task.due_date ?? '-'}</td>
								<td>${statusBadge}</td>
							</tr>
						`;
					});

					document.getElementById('caTasks').innerHTML = html;
				});
		}

	}, 300);
	
	
	//----------------------------------------
    // Attendance Data Display
    // Location: Updates text content of attendance elements
    // Purpose: Shows employee attendance statistics
    // Data: Total, On-time, Late, and Absent employee counts
    //----------------------------------------
    document.addEventListener("DOMContentLoaded", function () {
        const dateInput = document.getElementById("attendence_count");

        // Don't initialize flatpickr here - it's done directly in the page

        // Set today's date as default and fetch initial data
        const today = new Date().toISOString().split("T")[0];
        dateInput.value = today;
        fetchAttendance(today);
    });
});


function fetchAttendance(selectedDate) {
    // Show loading icons while fetching data
    document.getElementById("totalEmployee").innerHTML =
        '<i class="ti ti-loader ti-spin"></i>';
    document.getElementById("ontimeEmployee").innerHTML =
        '<i class="ti ti-loader ti-spin"></i>';
    document.getElementById("lateEmployee").innerHTML =
        '<i class="ti ti-loader ti-spin"></i>';
    document.getElementById("absentEmployee").innerHTML =
        '<i class="ti ti-loader ti-spin"></i>';

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: "/get_attendance_details",
        type: "GET",
        data: {
            selectedDate: selectedDate,
        },
        success: function (response) {
            // Update the UI with real data
            document.getElementById("totalEmployee").textContent =
                response.total_employee || "0";
            document.getElementById("ontimeEmployee").textContent =
                response.on_time_present || "0";
            document.getElementById("lateEmployee").textContent =
                response.late_present || "0";
            document.getElementById("absentEmployee").textContent =
                response.total_absent || "0";
        },
        error: function (xhr, status, error) {
            console.error("Failed to fetch attendance data:", error);
            // Show zeros on error
            document.getElementById("totalEmployee").textContent = "0";
            document.getElementById("ontimeEmployee").textContent = "0";
            document.getElementById("lateEmployee").textContent = "0";
            document.getElementById("absentEmployee").textContent = "0";
        },
    });
}
