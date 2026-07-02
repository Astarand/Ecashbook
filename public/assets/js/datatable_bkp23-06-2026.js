/**
 * Custom DataTable configuration for #pc-dt-simple
 *
 * This file extends the functionality of simple-datatables.js
 * to provide custom options and settings for the #pc-dt-simple table.
 */

document.addEventListener("DOMContentLoaded", function () {
    // Check if the table exists
    const table = document.getElementById("pc-dt-simple");
    if (!table) return;

    // Initialize the DataTable with custom options
    const dataTable = new simpleDatatables.DataTable(table, {
        sortable: true,
        perPage: 10,
        perPageSelect: [5, 10, 15, 20, 25, 50],
        searchable: true,
        fixedHeight: false,
        labels: {
            placeholder: "Search...",
            perPage: "entries per page",
            noRows: "No data available",
            info: "Showing {start} to {end} of {rows} entries",
        },
        layout: {
            top: "{select}{search}",
            bottom: "{info}{pager}",
        },
        classes: {
            active: "active",
            disabled: "disabled",
            selector: "form-select",
            input: "form-control",
            paginationList: "pagination",
        },
    });

    // simple-datatables may leave the {select} token in label text depending on build/version.
    function normalizePerPageLabel(wrapperElement) {
        if (!wrapperElement) return;

        const selectorLabel = wrapperElement.querySelector(
            ".datatable-selector-wrapper label, .datatable-dropdown label"
        );

        if (selectorLabel) {
            selectorLabel.innerHTML = selectorLabel.innerHTML.replace(
                /\{select\}\s*/g,
                ""
            );
        }
    }

    // Function to rearrange datatable DOM elements
    function rearrangeDatatableLayout() {
        const wrapper = table.closest(".datatable-wrapper");
        if (!wrapper) return;

        // Get top and bottom sections
        const topSection = wrapper.querySelector(".datatable-top");
        const bottomSection = wrapper.querySelector(".datatable-bottom");
        if (!topSection || !bottomSection) return;

        // First, find the selector in the top section
        const topSelectorWrapper = topSection.querySelector(
            ".datatable-selector-wrapper"
        );

        // If selector exists in top section, move it to bottom
        if (topSelectorWrapper) {
            // Get info and pagination elements
            const infoElement = bottomSection.querySelector(".datatable-info");

            // Insert before info if it exists, otherwise prepend to bottom section
            if (infoElement) {
                bottomSection.insertBefore(topSelectorWrapper, infoElement);
            } else {
                bottomSection.prepend(topSelectorWrapper);
            }

            // Apply styling to moved selector
            topSelectorWrapper.style.marginBottom = "10px";
            topSelectorWrapper.style.display = "block";
            topSelectorWrapper.style.width = "auto";
            topSelectorWrapper.style.float = "left";
            topSelectorWrapper.style.clear = "left";
        }

        // Set styles for search in top section
        const searchElement = topSection.querySelector(".datatable-search");
        if (searchElement) {
            searchElement.style.float = "left";
            searchElement.style.marginLeft = "0";
            searchElement.style.marginRight = "auto";
        }

        // Style the bottom section elements
        bottomSection
            .querySelectorAll(".datatable-selector-wrapper, .datatable-info")
            .forEach((el) => {
                el.style.float = "left";
                el.style.clear = "left";
            });

        const infoElement = bottomSection.querySelector(".datatable-info");
        if (infoElement) {
            infoElement.style.marginTop = "5px";
            infoElement.style.marginBottom = "10px";
        }

        // Make pagination float right
        const paginationElement = bottomSection.querySelector(
            ".datatable-pagination"
        );
        if (paginationElement) {
            paginationElement.style.float = "right";
            paginationElement.style.marginTop = "-45px";
        }
    }

    // Enhance search form to match design
    function enhanceSearchForm() {
        const searchContainer = document.querySelector(".datatable-search");
        if (!searchContainer) return;

        // Get the search input
        const input = searchContainer.querySelector('input[type="search"]');
        if (!input) return;

        // Remove any existing elements first
        const existingIcon = searchContainer.querySelector(".icon-search");
        if (existingIcon) existingIcon.remove();

        // Add icon class if not using ::before
        const iconElement = document.createElement("i");
        iconElement.className = "ph-duotone ph-magnifying-glass icon-search";
        iconElement.style.left = "12px";
        iconElement.style.top = "50%";
        iconElement.style.transform = "translateY(-50%)";
        iconElement.style.position = "absolute";
        iconElement.style.color = "#6c757d";
        iconElement.style.zIndex = "1";
        searchContainer.prepend(iconElement);
    }

    // Add download and print buttons
    function addActionButtons(container) {
        // Create buttons container
        const buttonContainer = document.createElement("div");
        buttonContainer.className = "datatable-custom-buttons";

        // Download button
        const downloadBtn = document.createElement("a");
        downloadBtn.href = "#";
        downloadBtn.className = "btn btn-secondary me-2";
        downloadBtn.setAttribute("data-bs-toggle", "tooltip");
        downloadBtn.setAttribute("aria-label", "Download Now");
        downloadBtn.setAttribute("data-bs-original-title", "Download Now");
        downloadBtn.setAttribute("title", "Download Now");
        downloadBtn.innerHTML = '<i class="ti ti-download"></i>';

        // Print button - primary
        const printBtn = document.createElement("a");
        printBtn.href = "#";
        printBtn.className = "btn btn-primary"; // Changed to primary
        printBtn.setAttribute("data-bs-toggle", "tooltip");
        printBtn.setAttribute("aria-label", "Print");
        printBtn.setAttribute("data-bs-original-title", "Print");
        printBtn.setAttribute("title", "Print");
        printBtn.innerHTML = '<i class="ti ti-printer"></i>';

        // Add event listeners
        downloadBtn.addEventListener("click", function (e) {
            e.preventDefault();
            exportTableToExcel();
        });

        printBtn.addEventListener("click", function (e) {
            e.preventDefault();
            printTable();
        });

        // Append buttons to container
        buttonContainer.appendChild(downloadBtn);
        buttonContainer.appendChild(printBtn);

        // Add the buttons to the provided container
        container.appendChild(buttonContainer);

        // Initialize tooltips if Bootstrap is available
        if (typeof bootstrap !== "undefined" && bootstrap.Tooltip) {
            const tooltipTriggerList = [].slice.call(
                container.querySelectorAll('[data-bs-toggle="tooltip"]')
            );
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    }

    // Function to export table data to Excel
    function exportTableToExcel() {
        const toText = (value) => {
            if (value == null) return "";
            if (typeof value === "string" || typeof value === "number") {
                return String(value);
            }

            if (typeof value === "object") {
                if (typeof value.text === "string") return value.text;
                if (typeof value.data === "string") return value.data;
                if (typeof value.content === "string") return value.content;
                if (typeof value.nodeName === "string") {
                    const tempEl = document.createElement("div");
                    try {
                        tempEl.appendChild(value.cloneNode(true));
                        return tempEl.textContent ? tempEl.textContent.trim() : "";
                    } catch (_e) {
                        return "";
                    }
                }
            }

            return String(value);
        };

        const dtData = dataTable && dataTable.data ? dataTable.data : null;
        if (!dtData || !Array.isArray(dtData.data)) {
            // Fallback to visible table if datatable internals are unavailable.
            const worksheetFallback = XLSX.utils.table_to_sheet(table);
            const workbookFallback = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbookFallback, worksheetFallback, "Data");
            XLSX.writeFile(workbookFallback, "table-data.xlsx");
            return;
        }

        const headings = Array.isArray(dtData.headings) ? dtData.headings : [];
        const headerTexts = headings.map((h) => toText(h).trim());
        const actionColumnIndex = headerTexts.findIndex(
            (h) => h.toLowerCase() === "action"
        );

        const exportedHeader =
            actionColumnIndex === -1
                ? headerTexts
                : headerTexts.filter((_, index) => index !== actionColumnIndex);

        const exportedRows = dtData.data.map((row) => {
            const cells = Array.isArray(row && row.cells) ? row.cells : [];
            const values = cells.map((cell) => toText(cell));
            return actionColumnIndex === -1
                ? values
                : values.filter((_, index) => index !== actionColumnIndex);
        });

        const sheetRows = [exportedHeader, ...exportedRows];
        const worksheet = XLSX.utils.aoa_to_sheet(sheetRows);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Data");

        // Generate file and download
        XLSX.writeFile(workbook, "table-data.xlsx");
    }

    // Function to print table
    function printTable() {
        const printWindow = window.open("", "_blank");
        const tableClone = table.cloneNode(true);

        // Find and remove the action column (last column) from the cloned table
        const headers = tableClone.querySelectorAll("thead th");
        const actionColumnIndex = Array.from(headers).findIndex(
            (th) => th.textContent.trim().toLowerCase() === "action"
        );

        if (actionColumnIndex !== -1) {
            // Remove the header
            headers[actionColumnIndex].remove();

            // Remove the corresponding cell from each row
            tableClone.querySelectorAll("tbody tr").forEach((row) => {
                const cells = row.querySelectorAll("td");
                if (cells.length > actionColumnIndex) {
                    cells[actionColumnIndex].remove();
                }
            });
        }

        printWindow.document.write(`
            <html>
                <head>
                    <title>Print</title>
                    <style>
                        @page {
                            size: A4 landscape;
                            margin: 10mm;
                        }
                        body {
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                        }
                        .print-container {
                            width: 100%;
                            max-width: 277mm; /* A4 landscape width - margins */
                            margin: 0 auto;
                        }
                        table {
                            border-collapse: collapse;
                            width: 100%;
                            table-layout: fixed;
                            font-size: 11px;
                        }
                        th, td {
                            border: 1px solid #ddd;
                            padding: 6px;
                            text-align: left;
                            word-wrap: break-word;
                            overflow: hidden;
                            text-overflow: ellipsis;
                        }
                        th {
                            background-color: #f2f2f2;
                            font-weight: bold;
                        }
                        thead {
                            display: table-header-group;
                        }
                        tfoot {
                            display: table-footer-group;
                        }
                        tr {
                            page-break-inside: avoid;
                        }
                        @media print {
                            body {
                                width: 100%;
                                height: 100%;
                                margin: 0;
                                padding: 0;
                            }
                            .print-container {
                                width: 100%;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="print-container">
                        ${tableClone.outerHTML}
                    </div>
                    <script>
                        window.onload = function() {
                            setTimeout(function() {
                                window.print();
                                window.close();
                            }, 500);
                        }
                    </script>
                </body>
            </html>
        `);

        printWindow.document.close();
    }

    // Adjust default styling after initialization
    setTimeout(function () {
        // Create a container for search and buttons
        const wrapper = table.closest(".datatable-wrapper");
        if (wrapper) {
            const topSection = wrapper.querySelector(".datatable-top");
            if (topSection) {
                // Create a container for search and buttons
                const searchAndButtonsContainer = document.createElement("div");
                searchAndButtonsContainer.className =
                    "datatable-search-and-buttons";

                // Get the search element
                const searchElement =
                    topSection.querySelector(".datatable-search");
                if (searchElement) {
                    // Move search inside the container
                    topSection.removeChild(searchElement);
                    searchAndButtonsContainer.appendChild(searchElement);
                }

                // Add container to top section
                topSection.appendChild(searchAndButtonsContainer);

                // Add search icon
                enhanceSearchForm();

                // Add buttons to the container
                addActionButtons(searchAndButtonsContainer);
            }

            // Force move selector to bottom
            moveEntriesSelectorToBottom(wrapper);

            // Ensure entries selector always updates table page size.
            bindPerPageSelector(wrapper);

            // Ensure label text does not show raw {select} token.
            normalizePerPageLabel(wrapper);
        }
    }, 500);

    // Function to move entries selector from top to bottom
    function moveEntriesSelectorToBottom(wrapper) {
        const topSection = wrapper.querySelector(".datatable-top");
        const bottomSection = wrapper.querySelector(".datatable-bottom");

        if (!topSection || !bottomSection) return;

        // First, find the selector in the top section
        const topSelector = topSection.querySelector(
            ".datatable-selector-wrapper"
        );
        if (topSelector) {
            // Find the info element in the bottom section
            const infoElement = bottomSection.querySelector(".datatable-info");

            // Move selector to bottom
            if (infoElement) {
                bottomSection.insertBefore(topSelector, infoElement);
            } else {
                bottomSection.prepend(topSelector);
            }

            // Style the moved selector
            topSelector.style.float = "left";
            topSelector.style.clear = "left";
            topSelector.style.marginBottom = "10px";
            topSelector.style.width = "auto";

            // Make info appear below selector
            if (infoElement) {
                infoElement.style.clear = "left";
                infoElement.style.marginTop = "5px";
            }
        }
    }

    function bindPerPageSelector(wrapper) {
        if (!wrapper || wrapper.dataset.boundPerPageDelegate === "1") return;

        wrapper.addEventListener("change", function (e) {
            const target = e.target;
            if (!(target instanceof HTMLSelectElement)) return;

            // Only react to the datatable entries selector.
            if (
                !target.closest(".datatable-dropdown") &&
                !target.classList.contains("datatable-selector") &&
                !target.classList.contains("form-select")
            ) {
                return;
            }

            const nextPerPage = parseInt(target.value, 10);
            if (!Number.isFinite(nextPerPage) || nextPerPage <= 0) return;

            dataTable.options.perPage = nextPerPage;
            dataTable._currentPage = 1;
            dataTable.update();

            if (typeof dataTable._fixHeight === "function") {
                dataTable._fixHeight();
            }
        });

        wrapper.dataset.boundPerPageDelegate = "1";
    }

    // Add row hover effect
    table.addEventListener("mouseover", function (e) {
        const tr = e.target.closest("tr");
        if (tr && tr.parentElement.tagName === "TBODY") {
            tr.classList.add("row-hover");
        }
    });

    table.addEventListener("mouseout", function (e) {
        const tr = e.target.closest("tr");
        if (tr && tr.parentElement.tagName === "TBODY") {
            tr.classList.remove("row-hover");
        }
    });

    // Add custom export buttons if needed
    const addExportButtons = function () {
        const wrapper = table.closest(".datatable-wrapper");
        if (!wrapper) return;

        const topSection = wrapper.querySelector(".datatable-top");
        if (!topSection) return;

        const buttonContainer = document.createElement("div");
        buttonContainer.className = "datatable-export-buttons";

        const exportButtons = [
            { format: "csv", label: "CSV", icon: "ti ti-file-csv" },
            { format: "excel", label: "Excel", icon: "ti ti-file-spreadsheet" },
            { format: "pdf", label: "PDF", icon: "ti ti-file-pdf" },
            { format: "print", label: "Print", icon: "ti ti-printer" },
        ];

        exportButtons.forEach((btn) => {
            const button = document.createElement("button");
            button.className = "btn btn-sm btn-light-secondary";
            button.innerHTML = `<i class="${btn.icon}"></i> ${btn.label}`;
            button.dataset.exportFormat = btn.format;

            button.addEventListener("click", function () {
                // Export functionality can be implemented here
                console.log(`Export to ${btn.format} clicked`);
            });

            buttonContainer.appendChild(button);
        });

        topSection.appendChild(buttonContainer);
    };

    // Uncomment to add export buttons
    // addExportButtons();

    // Add responsive behavior
    const makeResponsive = function () {
        const tableWrapper = table.closest(".datatable-container");
        if (tableWrapper) {
            tableWrapper.style.overflowX = "auto";
        }

        // Check table width vs container width
        const checkOverflow = function () {
            if (tableWrapper) {
                if (table.offsetWidth > tableWrapper.offsetWidth) {
                    tableWrapper.classList.add("table-responsive");
                } else {
                    tableWrapper.classList.remove("table-responsive");
                }
            }
        };

        // Check on load and resize
        checkOverflow();
        window.addEventListener("resize", checkOverflow);
    };

    makeResponsive();

    // Make the datatable instance available globally if needed
    window.pcDtSimple = dataTable;
});
