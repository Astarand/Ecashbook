// Business Earnings Chart
document.addEventListener("DOMContentLoaded", function () {
    let currentChart = null;
    const chartContainer = document.getElementById("weeklyUsageChart");

    // Initialize with weekly view
    if (chartContainer) {
        //renderChart("weekly");
        loadChartData("weekly");
        updateStats("weekly");
    }

    // Handle dropdown change
    const timeRangeSelect = document.getElementById("timeRangeSelect");
    if (timeRangeSelect) {
        timeRangeSelect.addEventListener("change", function () {
            const selectedValue = this.value;
            //renderChart(selectedValue);
            loadChartData(selectedValue);
            updateStats(selectedValue);
        });
    }
	
	function loadChartData(range) {
		$('#loader').show();
		$.ajax({
			url: "/subscriber-stats/" + range,
			type: "GET",
			success: function (response) {
				$('#loader').hide();
				renderChart(range, response);
			}
		});
	}

    // Render appropriate chart based on selection
    function renderChart(timeRange, apiData) {
        // Destroy existing chart if it exists
        if (currentChart) {
            currentChart.destroy();
        }

        const ctx = chartContainer.getContext("2d");

        let chartData = {
            labels: [],
            datasets: [],
        };

        let chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "bottom",
                    labels: {
                        usePointStyle: true,
                        boxWidth: 6,
                    },
                },
                tooltip: {
                    mode: "index",
                    intersect: false,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                    },
                    ticks: {
                        callback: function (value) {
                            if (
                                timeRange === "monthly" ||
                                timeRange === "yearly"
                            ) {
                                return value + "k";
                            }
                            return value;
                        },
                    },
                },
                x: {
                    grid: {
                        display: false,
                    },
                },
            },
        };

        // Configure chart based on time range
        switch (timeRange) {
            case "weekly":
                chartData.labels = [
                    "Sun",
                    "Mon",
                    "Tue",
                    "Wed",
                    "Thu",
                    "Fri",
                    "Sat",
                ];
                chartData.datasets = [
                    {
                        label: "Active Platform Accounts",
                        data: apiData.subscriber,//[35, 78, 78, 78, 80, 78, 80],
                        backgroundColor: "#3498db",
                        borderWidth: 0,
                        borderRadius: 5,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7,
                    },
                    {
                        label: "Trial User",
                        data: apiData.trial_user, //[80, 95, 95, 90, 90, 90, 90],
                        backgroundColor: "#2ecc71",
                        borderWidth: 0,
                        borderRadius: 5,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7,
                    },
                ];
                chartOptions.scales.y.ticks.stepSize = 30;
                break;

            case "monthly":
                chartData.labels = [
                    "1st Week",
                    "2nd Week",
                    "3rd Week",
                    "4th Week",
                ];
                chartData.datasets = [
                    {
                        label: "Active Platform Accounts",
                        data: apiData.subscriber, //[200, 300, 400, 500],
                        backgroundColor: "#3498db",
                        borderWidth: 0,
                        borderRadius: 5,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7,
                    },
                    {
                        label: "Trial User",
                        data: apiData.trial_user,//[150, 250, 350, 450],
                        backgroundColor: "#2ecc71",
                        borderWidth: 0,
                        borderRadius: 5,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7,
                    },
                ];
                chartOptions.scales.y.ticks.stepSize = 100;
                break;

            case "yearly":
                chartData.labels = [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec",
                ];
                chartData.datasets = [
                    {
                        label: "Active Platform Accounts",
                        // data: [
                            // 1000, 1100, 1200, 1300, 1400, 1500, 1600, 1700,
                            // 1800, 1900, 2000, 2100,
                        // ],
						data: apiData.subscriber,
                        backgroundColor: "#3498db",
                        borderWidth: 0,
                        borderRadius: 5,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7,
                    },
                    {
                        label: "Trial User",
                        /*data: [
                            800, 900, 1000, 1100, 1200, 1300, 1400, 1500, 1600,
                            1700, 1800, 1900,
                        ],*/
						data:apiData.trial_user,
                        backgroundColor: "#2ecc71",
                        borderWidth: 0,
                        borderRadius: 5,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7,
                    },
                ];
                chartOptions.scales.y.ticks.stepSize = 500;
                break;
        }

        currentChart = new Chart(ctx, {
            type: "bar",
            data: chartData,
            options: chartOptions,
        });
    }

    // Update stats based on time range
    function updateStats(timeRange) {
        // Sample data for different time ranges
        /*let statsData = {
            weekly: {
                totalCompanies: 50,
                totalCAs: 20,
                directAttachments: 15,
                attachedByCA: 35,
                totalSubscribers: 80,
                totalTrialUsers: 90,
                totalEarnings: 5000,
                caEarnings: 2500,
                netProfit: 2500,
            },
            monthly: {
                totalCompanies: 200,
                totalCAs: 80,
                directAttachments: 60,
                attachedByCA: 140,
                totalSubscribers: 320,
                totalTrialUsers: 360,
                totalEarnings: 20000,
                caEarnings: 10000,
                netProfit: 10000,
            },
            yearly: {
                totalCompanies: 2400,
                totalCAs: 960,
                directAttachments: 720,
                attachedByCA: 1680,
                totalSubscribers: 3840,
                totalTrialUsers: 4320,
                totalEarnings: 240000,
                caEarnings: 120000,
                netProfit: 120000,
            },
        };

        // Get values for selected time range
        const stats = statsData[timeRange];

        // Update counter elements with animation
        animateCounter("totalCompanies", stats.totalCompanies);
        animateCounter("totalCAs", stats.totalCAs);
        animateCounter("directAttachments", stats.directAttachments);
        animateCounter("attachedByCA", stats.attachedByCA);
        animateCounter("totalSubscribers", stats.totalSubscribers);
        animateCounter("totalTrialUsers", stats.totalTrialUsers);
        animateCounter("totalEarnings", stats.totalEarnings);
        animateCounter("caEarnings", stats.caEarnings);
        animateCounter("netProfit", stats.netProfit);*/
		$('#loader').show();
		$.ajax({
        url: "/stats",
        type: "GET",
        data: { timeRange: timeRange },
        dataType: "json",
        success: function (response) {
            //console.log(response);
			$('#loader').hide();
			const stats = response[timeRange];

			animateCounter("totalCompanies", stats.totalCompanies);
			animateCounter("totalCAs", stats.totalCAs);
			animateCounter("directAttachments", stats.directAttachments);
			animateCounter("attachedByCA", stats.attachedByCA);
			animateCounter("totalSubscribers", stats.totalSubscribers);
			animateCounter("totalTrialUsers", stats.totalTrialUsers);
			animateCounter("totalEarnings", stats.totalEarnings);
			animateCounter("caEarnings", stats.caEarnings);
			animateCounter("netProfit", stats.netProfit);
        }
    });
    }

    // Helper function to animate counters
    function animateCounter(id, targetValue) {
        const element = document.getElementById(id);
        if (!element) return;

        const isCurrency = id.includes("Earnings") || id.includes("Profit");
        const currentValue =
            parseInt(element.textContent.replace(/[^\d]/g, "")) || 0;
        const duration = 1000;
        const framesPerSecond = 60;
        const totalFrames = duration / (1000 / framesPerSecond);
        const increment = (targetValue - currentValue) / totalFrames;

        let currentCount = currentValue;
        const updateCount = () => {
            currentCount += increment;
            if (
                (increment > 0 && currentCount < targetValue) ||
                (increment < 0 && currentCount > targetValue)
            ) {
                if (isCurrency) {
                    element.textContent =
                        "₹" + Math.round(currentCount).toLocaleString();
                } else {
                    element.textContent = Math.round(currentCount);
                }
                requestAnimationFrame(updateCount);
            } else {
                if (isCurrency) {
                    element.textContent = "₹" + targetValue.toLocaleString();
                } else {
                    element.textContent = targetValue;
                }
            }
        };

        updateCount();
    }
});
