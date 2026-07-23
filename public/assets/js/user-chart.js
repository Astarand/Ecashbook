"use strict";

let turnoverChart; // Chart for monthly turnover data
let columnChart; // Chart for income and expenses comparison
let cashflowChart; // Chart for cashflow summary

document.addEventListener("DOMContentLoaded", function () {
    //----------------------------------------
    // Chart 1: Monthly Turnover Chart
    // Location: #mothwise-turnover
    // Purpose: Shows company's monthly turnover in an area chart
    // Data: Single line showing turnover amounts for each month
    //----------------------------------------
    if ($("#mothwise-turnover").length > 0) {
        var options = {
            chart: {
                type: "area",
                height: 220,
                toolbar: {
                    show: false,
                },
            },
            colors: ["#008CAD"], // Brand purple accent color
            fill: {
                type: "solid",
                opacity: 0, // No area shading under the line, clean and minimal
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                width: 2,
                curve: "smooth",
            },
            plotOptions: {
                bar: {
                    columnWidth: "45%",
                    borderRadius: 4,
                },
            },
            grid: {
                strokeDashArray: 4,
            },
            series: [
                {
                    name: "Turnover",
                    data: new Array(12).fill(0), // Initially zeros
                },
            ],
            xaxis: {
                categories: [
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec",
                    "Jan",
                    "Feb",
                    "Mar",
                ],
                labels: {
                    hideOverlappingLabels: true,
                },
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
            },
            yaxis: {
                logarithmic: false,
                labels: {
                    formatter: function (value) {
                        return value >= 1000 ? `${value / 1000}K` : value;
                    },
                },
            },
        };

        turnoverChart = new ApexCharts(
            document.querySelector("#mothwise-turnover"),
            options
        );
        turnoverChart.render();
    }

    //----------------------------------------
    // Chart 2: Income & Expenses Comparison Chart
    // Location: #user-income-expense
    // Purpose: Compares monthly income vs expenses in bar chart
    // Data: Two bar series - Income (blue) and Expenses (red)
    //----------------------------------------
    if ($("#user-income-expense").length > 0) {
        var options = {
            chart: {
                height: 297,
                type: "bar",
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "75%",
                    borderRadius: 2,
                    borderRadiusApplication: "end",
                },
            },
            legend: {
                show: true,
                position: "bottom",
            },
            dataLabels: {
                enabled: false,
            },
            colors: ["#10b981", "#ef4444"], // Vibrant Green for Profit, Vibrant Red for Expenses
            stroke: {
                show: true,
                width: 1,
                colors: ["transparent"],
            },
            fill: {
                type: "solid",
            },
            grid: {
                strokeDashArray: 4,
            },
            series: [
                {
                    name: "Income",
                    data: [],
                },
                {
                    name: "Expenses",
                    data: [],
                },
            ],
            xaxis: {
                categories: [
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec",
                    "Jan",
                    "Feb",
                    "Mar",
                ],
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return "₹ " + val;
                    },
                },
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "₹ " + val;
                    },
                },
            },
        };

        columnChart = new ApexCharts(
            document.querySelector("#user-income-expense"),
            options
        );
        columnChart.render();
    }

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

    //---------- Dashboard auto month selected ------
    var monthNames = [
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
    ];

    var currentMonth = new Date().getMonth();
    var selectElement = document.getElementById("total_receivales");
    var selectElementPayables = document.getElementById("total_payables");

    selectElement.value = monthNames[currentMonth];
    selectElementPayables.value = monthNames[currentMonth];

    //----------- Dashboard Get Financial -------
    function populateFinancialYearSelect() {
        var selectElement = $("#slet_financial_year");

        // Get the current year and month
        var currentYear = new Date().getFullYear();
        var currentMonth = new Date().getMonth() + 1;

        // Determine the current financial year
        var currentFinancialYear;
        if (currentMonth >= 4) {
            currentFinancialYear = currentYear + "-" + (currentYear + 1);
        } else {
            currentFinancialYear = currentYear - 1 + "-" + currentYear;
        }

        // Add financial years starting from 2024 to the current financial year
        for (var year = 2024; year <= currentYear; year++) {
            var financialYear = year + "-" + (year + 1);
            selectElement.append(
                '<option value="' +
                    financialYear +
                    '">FY ' +
                    financialYear +
                    "</option>"
            );
        }
        selectElement.val(currentFinancialYear);
    }

    // Call the function when the document is ready
    $(document).ready(function () {
        populateFinancialYearSelect();
    });

    //----------------------------------------
    // Monthly Financial Data Functions
    // Purpose: Updates both charts and financial summaries
    // Data: Income, Expenses, Transactions, and Profit
    //----------------------------------------
    function fetchMonthlyData(financial_year, viewType = 'monthly') {
        // alert('fetchMonthlyData called with financial_year: ' + financial_year + ' and viewType: ' + viewType);
        $("#total_rec, #total_exp, #total_transaction, #total_profit").html(
            '<i class="fa-solid fa-indian-rupee-sign me-1"></i>0'
        );

        if (columnChart) {
            columnChart.destroy();
        }

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "/get-monthly-data",
            type: "GET",
            data: {
                financial_year: financial_year,
                view_type: viewType
            },
            success: function (response) {
                var incomeData = response.income;
                var expensesData = response.expenses;

                // Mapping as requested:
                // total_transaction -> Total Income
                // total_rec -> Total Expenses
                // total_exp -> Total Profit
                $("#total_transaction").html(
                    '<i class="fa-solid fa-indian-rupee-sign me-1"></i>' +
                        response.total_income
                );
                $("#total_rec").html(
                    '<i class="fa-solid fa-indian-rupee-sign me-1"></i>' +
                        response.total_expenses
                );
                $("#total_exp").html(
                    '<i class="fa-solid fa-indian-rupee-sign me-1"></i>' +
                        response.profit.replace('-', '') // Remove negative sign if exists
                );

                if ($("#user-income-expense").length > 0) {
                    var labels = [];
                    if (viewType === 'quarterly') {
                        labels = ["1st Qtr (Apr-Jun)", "2nd Qtr (Jul-Sep)", "3rd Qtr (Oct-Dec)", "4th Qtr (Jan-Mar)"];
                    } else {
                        labels = ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar"];
                    }

                    var options = {
                        chart: {
                            height: 297,
                            type: "bar",
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: "55%",
                                endingShape: "rounded",
                                borderRadius: 4,
                            },
                        },
                        dataLabels: {
                            enabled: false,
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ["transparent"],
                        },
                        series: [
                            {
                                name: "Profit", // Changed from Income to Profit
                                data: incomeData,
                            },
                            {
                                name: "Expenses",
                                data: expensesData,
                            },
                        ],
                        xaxis: {
                            categories: labels,
                        },
                        yaxis: {
                            title: {
                                text: "Amount (₹)",
                            },
                        },
                        fill: {
                            type: "solid",
                        },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return "₹ " + val;
                                },
                            },
                        },
                        legend: {
                            show: true,
                            position: "top",
                            horizontalAlign: "right",
                        },
                        colors: ["#10b981", "#ef4444"],
                    };

                    columnChart = new ApexCharts(
                        document.querySelector("#user-income-expense"),
                        options
                    );
                    columnChart.render();
                }
            },
            error: function (error) {
                console.error("Failed to fetch monthly data:", error);
            },
        });
    }

    // Event listener for financial year changes
    $("#slet_financial_year").change(function () {
        var financial_year = $(this).val();
        fetchMonthlyData(financial_year);
    });

    // Fetch monthly data on page load
    $(document).ready(function () {
        var financial_year = $("#slet_financial_year").val();
        fetchMonthlyData(financial_year, 'monthly');
    });

    $('#income_expense_view_type').on('change', function() {
        var financial_year = $("#slet_financial_year").val();
        fetchMonthlyData(financial_year, $(this).val());
    });

    //----------------------------------------
    // Receivables Data Display
    // Purpose: Shows accounts receivable information
    // Data: Unpaid invoices, current dues, and overdues
    //----------------------------------------
    function fetch_total_receivables(Month, financial_year) {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "/get-receivables-data",
            type: "GET",
            data: {
                Month: Month,
                financial_year: financial_year,
            },
            success: function (response) {
                if (response.total_unpaid !== undefined) {
                    $("#total_unpaid").text(response.total_unpaid);
                    $("#receivables_current").text(response.current_due);
                    $("#receivables_overdue").text(response.over_due);
                }
            },
            error: function (error) {
                console.error("Failed to fetch receivables data:", error);
            },
        });
    }

    // Event listener for receivables month changes
    $("#total_receivales").change(function () {
        var month_name = $(this).val();
        var financial_year = $("#slet_financial_year").val();
        fetch_total_receivables(month_name, financial_year);
    });

    // Fetch receivables data on page load
    $(document).ready(function () {
        var DefaultMonth = $("#total_receivales").val();
        var financial_year = $("#slet_financial_year").val();
        fetch_total_receivables(DefaultMonth, financial_year);
    });

    //----------------------------------------
    // Payables Data Display
    // Purpose: Shows accounts payable information
    // Data: Unpaid bills, current payments, and overdues
    //----------------------------------------
    function fetch_total_payables(Month, financial_year) {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "/get-payables-data",
            type: "GET",
            data: {
                Month: Month,
                financial_year: financial_year,
            },
            success: function (response) {
                $("#total_unpaid_Payables").text(
                    response.total_unpaid_Payables
                );
                // $("#Payables_current").text(response.total_payment_Payables);
                $("#Payables_current").text(response.current_due_Payables);
                $("#Payables_overdue").text(response.over_due_Payables);
                // $("#Payables_overdue").text(response.total_over_due_Payables);
            },
            error: function (error) {
                console.error("Failed to fetch payables data:", error);
            },
        });
    }

    // Event listener for payables month changes
    $("#total_payables").change(function () {
        var month_name = $(this).val();
        var financial_year = $("#slet_financial_year").val();
        fetch_total_payables(month_name, financial_year);
    });

    // Fetch payables data on page load
    $(document).ready(function () {
        var DefaultMonth = $("#total_payables").val();
        var financial_year = $("#slet_financial_year").val();
        fetch_total_payables(DefaultMonth, financial_year);
        GetMonthWishTurnover(financial_year, 'monthly');
    });

    // Event listeners for Turnover Detail filters
    $('#turnover_view_type').on('change', function() {
        const viewType = $(this).val();
        const fy = $('#slet_financial_year').val();
        GetMonthWishTurnover(fy, viewType);
    });

    // Function to get monthly turnover data
    function GetMonthWishTurnover(financial_year, viewType = 'monthly') {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "/get-monthlyturnover-data",
            type: "GET",
            data: { 
                financial_year: financial_year,
                view_type: viewType
            },
            success: function (response) {
                let labels = [];
                let turnoverData = [];

                if (viewType === 'monthly') {
                    $('#turnover_label').text('Month wise Turnover');
                    labels = ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar"];
                    turnoverData = new Array(12).fill(0);
                    response.forEach(function (item) {
                        let monthDate = new Date(item.label + "-01");
                        let monthIndex = (monthDate.getMonth() + 9) % 12; // April=0 ... March=11
                        turnoverData[monthIndex] = parseFloat(item.total_amount);
                    });
                } else if (viewType === 'quarterly') {
                    $('#turnover_label').text('Quarter wise Turnover');
                    labels = ["1st Qtr (Apr-Jun)", "2nd Qtr (Jul-Sep)", "3rd Qtr (Oct-Dec)", "4th Qtr (Jan-Mar)"];
                    turnoverData = new Array(4).fill(0);
                    response.forEach(function (item) {
                        // MySQL QUARTER: 1=Jan-Mar, 2=Apr-Jun, 3=Jul-Sep, 4=Oct-Dec
                        // We want: 0=Apr-Jun(2), 1=Jul-Sep(3), 2=Oct-Dec(4), 3=Jan-Mar(1)
                        let qIdxMap = { 2: 0, 3: 1, 4: 2, 1: 3 };
                        let mappedIdx = qIdxMap[item.q_index];
                        turnoverData[mappedIdx] = parseFloat(item.total_amount);
                    });
                }

                let totalSum = response.reduce((sum, item) => sum + parseFloat(item.total_amount), 0);

                // Update chart series and labels
                turnoverChart.updateOptions({
                    xaxis: {
                        categories: labels
                    }
                });
                turnoverChart.updateSeries([
                    {
                        name: "Turnover",
                        data: turnoverData,
                    },
                ]);

                document.getElementById("turn_over_total_amount").innerHTML = `₹${totalSum.toLocaleString("en-IN", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                })}`;
            },
            error: function (er) {
                console.error("Error fetching turnover data:", er);
            },
        });
    }

    // Call GetMonthWishTurnover when financial year changes
    $("#slet_financial_year").change(function () {
        var financial_year = $(this).val();
        var viewType = $('#turnover_view_type').val();
        GetMonthWishTurnover(financial_year, viewType);
    });

    // Initial call to load turnover data
    // var DefaultFyear = $("#slet_financial_year").val();
    // GetMonthWishTurnover(DefaultFyear);
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
