/**
 * Ticket Management Charts
 * This file contains all chart initialization and configuration for the ticket management dashboard
 */

function initTicketCharts() {
    console.log("Initializing ticket charts...");

    try {
        //initSupportRequestsChart();
		//initAgentResponseChart();
		//initSupportResolvedChart();
		loadCustomerTicketStats();
		loadCaTicketStats();
		loadSupportTicketStats();
        
        initCustomerSatisfactionChart();
        console.log("All charts initialized successfully");
    } catch (error) {
        console.error("Error initializing charts:", error);
    }
}

function loadCustomerTicketStats() {
    $.ajax({
        url: "/customer-ticket-stats",
        type: "GET",
        dataType: "json",
        success: function (data) {

            console.log("Ticket Stats:", data);

            // example: [open, running, resolved]
            initSupportRequestsChart([
                data.open ?? 0,
                data.running ?? 0,
                data.resolved ?? 0,
                data.closed ?? 0
            ]);
        },
        error: function (xhr, status, error) {
            console.error("Error loading ticket stats:", error);
        }
    });
}

function loadCaTicketStats() {
    $.ajax({
        url: "/ca-ticket-stats",
        type: "GET",
        dataType: "json",
        success: function (data) {

            console.log("Ticket Stats:", data);

            // example: [open, running, resolved]
            initAgentResponseChart([
                data.open ?? 0,
                data.running ?? 0,
                data.resolved ?? 0,
                data.closed ?? 0
            ]);
        },
        error: function (xhr, status, error) {
            console.error("Error loading ticket stats:", error);
        }
    });
}

function loadSupportTicketStats() {
    $.ajax({
        url: "/support-ticket-stats",
        type: "GET",
        dataType: "json",
        success: function (data) {

            console.log("Ticket Stats:", data);

            // example: [open, running, resolved]
            initSupportResolvedChart([
                data.open ?? 0,
                data.running ?? 0,
                data.resolved ?? 0,
                data.closed ?? 0
            ]);
        },
        error: function (xhr, status, error) {
            console.error("Error loading ticket stats:", error);
        }
    });
}


// Customer Ticket Chart (formerly Support Requests)
function initSupportRequestsChart(ticketData) {
    const canvas = document.getElementById("supportRequestsChart");
    if (!canvas) {
        console.error("supportRequestsChart canvas not found");
        return;
    }

    try {
        const ctx = canvas.getContext("2d");
        if (!ctx) {
            console.error("Failed to get 2D context for supportRequestsChart");
            return;
        }

        // Create gradient for the area
        const gradient = ctx.createLinearGradient(0, 0, 0, 150);
        gradient.addColorStop(0, "rgba(38, 198, 249, 0.3)");
        gradient.addColorStop(1, "rgba(38, 198, 249, 0.0)");

        // Destroy existing chart if it exists
        if (window.supportRequestsChartInstance) {
            window.supportRequestsChartInstance.destroy();
        }

        window.supportRequestsChartInstance = new Chart(ctx, {
            type: "line",
            data: {
                //labels: Array.from({ length: 20 }, (_, i) => i.toString()),
				labels: ["Open", "Running", "Resolved", "Closed"],
                datasets: [
                    {
                        /*data: [
                            20, 40, 30, 70, 50, 60, 50, 90, 40, 80, 65, 75, 50,
                            70, 65, 55, 40, 50, 80, 30,
                        ],*/
						data: ticketData,
                        borderColor: "#26c6f9",
                        backgroundColor: gradient,
                        borderWidth: 3,
                        tension: 0.5,
                        fill: true,
                        pointBackgroundColor: "transparent",
                        pointBorderColor: "transparent",
                        pointHoverBackgroundColor: "#26c6f9",
                        pointHoverBorderColor: "#fff",
                        pointHoverRadius: 6,
                        pointHoverBorderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        enabled: false,
                    },
                },
                scales: {
                    x: {
                        display: false,
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        display: false,
                        beginAtZero: true,
                        grid: {
                            display: false,
                        },
                    },
                },
                elements: {
                    point: {
                        radius: 0,
                    },
                },
            },
        });

        console.log("Customer Ticket chart initialized");
    } catch (error) {
        console.error("Error initializing Customer Ticket chart:", error);
    }
}

// CA Tickets Chart (formerly Agent Response)
function initAgentResponseChart(ticketData) {
    const canvas = document.getElementById("agentResponseChart");
    if (!canvas) {
        console.error("agentResponseChart canvas not found");
        return;
    }

    try {
        const ctx = canvas.getContext("2d");
        if (!ctx) {
            console.error("Failed to get 2D context for agentResponseChart");
            return;
        }

        // Create gradient for the area
        const gradient = ctx.createLinearGradient(0, 0, 0, 150);
        gradient.addColorStop(0, "rgba(38, 198, 249, 0.3)");
        gradient.addColorStop(1, "rgba(38, 198, 249, 0.0)");

        // Destroy existing chart if it exists
        if (window.agentResponseChartInstance) {
            window.agentResponseChartInstance.destroy();
        }

        window.agentResponseChartInstance = new Chart(ctx, {
            type: "line",
            data: {
                //labels: Array.from({ length: 20 }, (_, i) => i.toString()),
				labels: ["Open", "Running", "Resolved", "Closed"],
                datasets: [
                    {
                        data: ticketData,
                        borderColor: "#26c6f9",
                        backgroundColor: gradient,
                        borderWidth: 3,
                        tension: 0.5,
                        fill: true,
                        pointBackgroundColor: "transparent",
                        pointBorderColor: "transparent",
                        pointHoverBackgroundColor: "#26c6f9",
                        pointHoverBorderColor: "#fff",
                        pointHoverRadius: 6,
                        pointHoverBorderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        enabled: false,
                    },
                },
                scales: {
                    x: {
                        display: false,
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        display: false,
                        beginAtZero: true,
                        grid: {
                            display: false,
                        },
                    },
                },
                elements: {
                    point: {
                        radius: 0,
                    },
                },
            },
        });

        console.log("CA Tickets chart initialized");
    } catch (error) {
        console.error("Error initializing CA Tickets chart:", error);
    }
}

// Total Support Resolved Chart (formerly Support Resolved)
function initSupportResolvedChart(ticketData) {
    const canvas = document.getElementById("supportResolvedChart");
    if (!canvas) {
        console.error("supportResolvedChart canvas not found");
        return;
    }

    try {
        const ctx = canvas.getContext("2d");
        if (!ctx) {
            console.error("Failed to get 2D context for supportResolvedChart");
            return;
        }

        // Create gradient for the area
        const gradient = ctx.createLinearGradient(0, 0, 0, 150);
        gradient.addColorStop(0, "rgba(46, 213, 115, 0.3)");
        gradient.addColorStop(1, "rgba(46, 213, 115, 0.0)");

        // Destroy existing chart if it exists
        if (window.supportResolvedChartInstance) {
            window.supportResolvedChartInstance.destroy();
        }

        window.supportResolvedChartInstance = new Chart(ctx, {
            type: "line",
            data: {
                //labels: Array.from({ length: 20 }, (_, i) => i.toString()),
				labels: ["Open", "Running", "Resolved", "Closed"],
                datasets: [
                    {
                        data: ticketData,
                        borderColor: "#2ed573",
                        backgroundColor: gradient,
                        borderWidth: 3,
                        tension: 0.5,
                        fill: true,
                        pointBackgroundColor: "transparent",
                        pointBorderColor: "transparent",
                        pointHoverBackgroundColor: "#2ed573",
                        pointHoverBorderColor: "#fff",
                        pointHoverRadius: 6,
                        pointHoverBorderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        enabled: false,
                    },
                },
                scales: {
                    x: {
                        display: false,
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        display: false,
                        beginAtZero: true,
                        grid: {
                            display: false,
                        },
                    },
                },
                elements: {
                    point: {
                        radius: 0,
                    },
                },
            },
        });

        console.log("Total Support Resolved chart initialized");
    } catch (error) {
        console.error(
            "Error initializing Total Support Resolved chart:",
            error
        );
    }
}

// Customer Review & Rating Chart (formerly Customer Satisfaction)
function initCustomerSatisfactionChart() {
    const canvas = document.getElementById("customerSatisfactionChart");
    if (!canvas) {
        console.error("customerSatisfactionChart canvas not found");
        return;
    }

    // Increase height of the chart
    const chartHeight = 315;

    // Set the parent container to a specific height
    if (canvas.parentElement) {
        canvas.parentElement.style.height = chartHeight + "px";
    }

    // Set canvas dimensions explicitly
    canvas.height = chartHeight;
    canvas.width = canvas.parentElement
        ? canvas.parentElement.offsetWidth
        : 300;

    try {
        const ctx = canvas.getContext("2d");
        if (!ctx) {
            console.error(
                "Failed to get 2D context for customerSatisfactionChart"
            );
            return;
        }

        // Destroy existing chart if it exists
        if (window.customerSatisfactionChartInstance) {
            window.customerSatisfactionChartInstance.destroy();
        }

        window.customerSatisfactionChartInstance = new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: ["Very Satisfied", "Satisfied", "Neutral", "Poor"],
                datasets: [
                    {
                        data: [35.5, 26.9, 21.5, 16.1],
                        backgroundColor: [
                            "#0abde3", // Bright blue for Very Satisfied
                            "#48dbfb", // Lighter blue for Satisfied
                            "#54a0ff", // Medium blue for Neutral
                            "#c8d6e5", // Light grey for Poor
                        ],
                        borderWidth: 0,
                        hoverOffset: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: "60%",
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return (
                                    context.label + ": " + context.parsed + "%"
                                );
                            },
                        },
                    },
                },
            },
        });

        console.log("Customer Review & Rating chart initialized");
    } catch (error) {
        console.error(
            "Error initializing Customer Review & Rating chart:",
            error
        );
    }
}

// Export the initialization function
window.initTicketCharts = initTicketCharts;
// Initialize charts when the script loads if document is ready
if (
    document.readyState === "complete" ||
    document.readyState === "interactive"
) {
    setTimeout(initTicketCharts, 500);
} else {
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(initTicketCharts, 500);
    });
}
