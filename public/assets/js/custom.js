"use strict";
// Password Show
document.addEventListener("DOMContentLoaded", function () {
    const togglePasswordElements = document.querySelectorAll("#togglePassword");

    togglePasswordElements.forEach((toggle) => {
        toggle.addEventListener("click", function () {
            // Find the sibling input field relative to the clicked toggle
            const passwordInput = this.previousElementSibling;

            // Toggle the password visibility
            const type =
                passwordInput.getAttribute("type") === "password"
                    ? "text"
                    : "password";
            passwordInput.setAttribute("type", type);

            // Toggle the eye icon class
            const icon = this.querySelector("i");
            icon.classList.toggle("ti-eye");
            icon.classList.toggle("ti-eye-off");
        });
    });
});

// Toast Notification Function
function showToast(message, type) {
    Toastify({
        text: message,
        duration: 3000, // Show for 3 seconds
        close: true,
        gravity: "top", // Position: Top
        position: "right", // Align: Right
        backgroundColor: type === "success" ? "#28a745" : "#dc3545", // Green for success, Red for error
        stopOnFocus: true, // Stop on hover
        style: {
            fontSize: "18px", // Larger Font
            padding: "16px 24px", // More Padding
            borderRadius: "8px", // Smooth Edges
            background: type === "success" ? "#28a745" : "#dc3545", // Green for success, Red for error
            color: "#fff", // White text
            boxShadow: "0px 5px 15px rgba(0, 0, 0, 0.2)", // Nice Shadow
        },
    }).showToast();
}
// Fetch Finincial Year
function populateFinancialYearSelect() {
    var selectElement = $("#select_financial_year");

    // Get the current year and month
    var currentYear = new Date().getFullYear();
    var currentMonth = new Date().getMonth() + 1;

    // Determine the current financial year
    var currentFinancialYear;
    if (currentMonth >= 4) {
        // Financial year starts in April
        currentFinancialYear = currentYear + "-" + (currentYear + 1);
    } else {
        currentFinancialYear = currentYear - 1 + "-" + currentYear;
    }

    // Add financial years starting from 2022 to the current financial year
    for (var year = 2022; year <= currentYear; year++) {
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
populateFinancialYearSelect();
// User Module assign CA other toggle
document.addEventListener("DOMContentLoaded", function () {
    const toggleSwitch = document.getElementById("toggle-switch");
    const toggleDiv = document.getElementById("toggle-div");
    toggleSwitch.addEventListener("change", function () {
        if (toggleSwitch.checked) {
            toggleDiv.style.display = "block";
        } else {
            toggleDiv.style.display = "none";
        }
    });
});

//Customer & Vendor Billing & Shipping Address
// function copyBillingAddress() {
//     const billingFields = [
//         { id: "cust_bill_gstno", copyTo: "cust_ship_gstno" },
//         { id: "cust_bill_contact", copyTo: "cust_ship_contact" },
//         { id: "cust_bill_designa", copyTo: "cust_ship_designa" },
//         { id: "cust_bill_mobilno", copyTo: "cust_ship_mobilno" },
//         { id: "cust_bill_addone", copyTo: "cust_ship_addone" },
//         { id: "cust_bill_addtwo", copyTo: "cust_ship_addtwo" },
//         { id: "comp_bill_state", copyTo: "cust_ship_state" },
//         { id: "cust_bill_city", copyTo: "cust_ship_city" }, // City dropdown
//         { id: "cust_bill_pin", copyTo: "cust_ship_pin" },
//     ];

//     billingFields.forEach((field) => {
//         const billingInput = document.getElementById(field.id);
//         const shippingInput = document.getElementById(field.copyTo);

//         if (billingInput && shippingInput) {
//             shippingInput.value = billingInput.value;

//             // Trigger change event for dropdowns
//             if (shippingInput.tagName === "SELECT") {
//                 shippingInput.dispatchEvent(new Event("change"));
//             }
//         }
//     });
// }

function copyBillingAddress() {
    const billingFields = [
        { id: "cust_bill_gstno", copyTo: "cust_ship_gstno" },
        { id: "cust_bill_contact", copyTo: "cust_ship_contact" },
        { id: "cust_bill_designa", copyTo: "cust_ship_designa" },
        { id: "cust_bill_mobilno", copyTo: "cust_ship_mobilno" },
        { id: "cust_bill_addone", copyTo: "cust_ship_addone" },
        { id: "cust_bill_addtwo", copyTo: "cust_ship_addtwo" },
        { id: "cust_bill_pin", copyTo: "cust_ship_pin" }
    ];

    // Copy simple input fields
    billingFields.forEach((field) => {
        const billingInput = document.getElementById(field.id);
        const shippingInput = document.getElementById(field.copyTo);
        if (billingInput && shippingInput) {
            shippingInput.value = billingInput.value;
        }
    });

    // Handle State and City copy with AJAX call
    const billingState = document.getElementById("comp_bill_state").value;
    const billingCity = document.getElementById("cust_bill_city").value;

    const shippingState = document.getElementById("cust_ship_state");
    const shippingCity = document.getElementById("cust_ship_city");

    if (billingState && shippingState) {
        shippingState.value = billingState;

        // Trigger change to fetch cities for the selected state
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: "/getCity?" + billingState,
            dataType: "json",
            data: { id: billingState },
            success: function (data) {
                let str = '<option value="">Select City</option>';
                $.each(data, function (idx, item) {
                    str += '<option value="' + item.id + '">' + item.name + "</option>";
                });
                shippingCity.innerHTML = str;

                // Set city after cities are loaded
                shippingCity.value = billingCity;
            },
        });
    }
}



//Add Liabilities Radio
function toggleLiabilities(type) {
    const currentDiv = document.getElementById("currentLiabilitiesDiv");
    const nonCurrentDiv = document.getElementById("nonCurrentLiabilitiesDiv");

    if (type === "current") {
        currentDiv.style.display = "block";
        nonCurrentDiv.style.display = "none";
    } else if (type === "nonCurrent") {
        currentDiv.style.display = "none";
        nonCurrentDiv.style.display = "block";
    }
}
//Company Type Dropdown With CIN & Incorporation date Field

// document.addEventListener("DOMContentLoaded", function () {
//     document.querySelectorAll(".company-type-dropdown").forEach((dropdown) => {

//         const parentRow = dropdown.closest(".row");

//         const otherField = parentRow.querySelector("#comp_type_other");
//         const otherInput = otherField ? otherField.querySelector("input") : null;

//         const regField = parentRow.querySelector("#company_reg_div");
//         const regInput = regField ? regField.querySelector("input") : null;
//         const regLabel = parentRow.querySelector("#company_reg_label");

//         const incField = parentRow.querySelector("#inc_date_div");
//         const incInput = incField ? incField.querySelector("input") : null;
//         const incLabel = parentRow.querySelector("#inc_date_label");

//         function resetFields() {
//             if (otherField) otherField.style.display = "none";
//             if (regField) regField.style.display = "none";
//             if (incField) incField.style.display = "none";

//             if (otherInput) {
//                 otherInput.required = false;
//                 otherInput.value = "";
//             }

//             if (regInput) {
//                 regInput.required = false;
//                 // regInput.value = ""; // Removed to preserve pre-filled values in edit mode
//             }

//             if (incInput) {
//                 incInput.required = false;
//                 // incInput.value = "";
//             }
//         }

//         function toggleFields(type) {
//             resetFields();

//             const cinTypes = [
//                 "One person Company (OPC)",
//                 "PVT Ltd Company",
//                 "LTD Company",
//                 "Section-8 Company",
//             ];

//             if (type === "Other") {
//                 otherField.style.display = "block";
//                 if (otherInput) otherInput.required = true;
//             }

//             else if (cinTypes.includes(type)) {
//                 regField.style.display = "block";
//                 incField.style.display = "block";

//                 regLabel.innerHTML = 'CIN <span class="text-danger">*</span>';
//                 regInput.name = "cin";
//                 regInput.id = "cin";
//                 regInput.placeholder = "Enter CIN Number";
//                 regInput.required = true;

//                 incLabel.innerHTML = 'Incorporation Date <span class="text-danger">*</span>';
//                 incInput.required = true;
//             }

//             else if (type === "LLP Company") {
//                 regField.style.display = "block";
//                 incField.style.display = "block";

//                 regLabel.innerHTML = 'LLPIN <span class="text-danger">*</span>';
//                 regInput.name = "llpin";
//                 regInput.id = "llpin";
//                 regInput.placeholder = "Enter LLPIN";
//                 regInput.required = true;

//                 incLabel.innerHTML = 'Incorporation Date <span class="text-danger">*</span>';
//                 incInput.required = true;
//             }

//             else if (type === "Society/Trust") {
//                 regField.style.display = "block";
//                 incField.style.display = "block";

//                 regLabel.innerHTML = 'Registration No <span class="text-danger">*</span>';
//                 regInput.name = "reg_no";
//                 regInput.id = "reg_no";
//                 regInput.placeholder = "Enter Registration No";
//                 regInput.required = true;

//                 incLabel.innerHTML = 'Registration Date <span class="text-danger">*</span>';
//                 incInput.required = true;
//             }
//         }

//         // Run on page load
//         toggleFields(dropdown.value);

//         // On change
//         dropdown.addEventListener("change", function () {
//             toggleFields(this.value);
//         });
//     });
// });

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".company-type-dropdown").forEach((dropdown) => {

        const parentRow = dropdown.closest(".row");

        const otherField = parentRow.querySelector("#comp_type_other");
        const otherInput = otherField ? otherField.querySelector("input") : null;

        const regField = parentRow.querySelector("#company_reg_div");
        const regInput = regField ? regField.querySelector("input") : null;
        const regLabel = parentRow.querySelector("#company_reg_label");

        const incField = parentRow.querySelector("#inc_date_div");
        const incInput = incField ? incField.querySelector("input") : null;
        const incLabel = parentRow.querySelector("#inc_date_label");

        function resetFields() {
            if (otherField) otherField.style.display = "none";
            if (regField) regField.style.display = "none";
            if (incField) incField.style.display = "none";

            if (otherInput) {
                otherInput.required = false;
                otherInput.value = "";
            }

            if (regInput) {
                regInput.required = false;
                // regInput.value = ""; // Removed to preserve pre-filled values in edit mode
            }

            if (incInput) {
                incInput.required = false;
                // incInput.value = "";
            }
        }

        function toggleFields(type) {
            resetFields();

            const cinTypes = [
                "One person Company (OPC)",
                "PVT Ltd Company",
                "LTD Company",
                "Section-8 Company",
            ];

            if (type === "Other") {
                otherField.style.display = "block";
                if (otherInput) otherInput.required = true;
            }

            else if (cinTypes.includes(type)) {
                regField.style.display = "block";
                incField.style.display = "block";

                regLabel.innerHTML = 'CIN';
                regInput.name = "cin";
                regInput.id = "cin";
                regInput.placeholder = "Enter CIN Number";
                regInput.required = false;

                incLabel.innerHTML = 'Incorporation Date';
                incInput.required = false;
            }

            else if (type === "LLP Company") {
                regField.style.display = "block";
                incField.style.display = "block";

                regLabel.innerHTML = 'LLPIN';
                regInput.name = "llpin";
                regInput.id = "llpin";
                regInput.placeholder = "Enter LLPIN";
                regInput.required = false;

                incLabel.innerHTML = 'Incorporation Date';
                incInput.required = false;
            }

            else if (type === "Society/Trust") {
                regField.style.display = "block";
                incField.style.display = "block";

                regLabel.innerHTML = 'Registration No';
                regInput.name = "reg_no";
                regInput.id = "reg_no";
                regInput.placeholder = "Enter Registration No";
                regInput.required = false;

                incLabel.innerHTML = 'Registration Date';
                incInput.required = false;
            }
        }

        // Run on page load
        toggleFields(dropdown.value);

        // On change
        dropdown.addEventListener("change", function () {
            toggleFields(this.value);
        });
    });
});



// Universal TDS Dropdown Handler
// document.addEventListener("DOMContentLoaded", function () {
//     document.querySelectorAll(".tds-container").forEach((container) => {
//         const tdsYes = container.querySelector('input[id="tdsYes"]');
//         const tdsNo = container.querySelector('input[id="tdsNo"]');
//         const tdsDropdown = container.querySelector("#tds_dropdown");
//         if (tdsYes && tdsDropdown) {
//             tdsYes.checked = true;
//             tdsDropdown.style.display = "block";
//         }
//         if (tdsYes) {
//             tdsYes.addEventListener("click", () => {
//                 if (tdsDropdown) tdsDropdown.style.display = "block";
//             });
//         }
//         if (tdsNo) {
//             tdsNo.addEventListener("click", () => {
//                 if (tdsDropdown) tdsDropdown.style.display = "none";
//             });
//         }
//     });
// });

document.addEventListener("DOMContentLoaded", function () {
    const tdsYes = document.getElementById("tdsYes");
    const tdsNo = document.getElementById("tdsNo");
    const tdsDropdown = document.getElementById("tds_dropdown");

    if (tdsYes && tdsDropdown) {
        // Show the dropdown if "Yes" is already selected
        if (tdsYes.checked) {
            tdsDropdown.style.display = "block";
        } else {
            tdsDropdown.style.display = "none";
        }

        // Event listener for "Yes"
        tdsYes.addEventListener("click", () => {
            tdsDropdown.style.display = "block";
        });

        // Event listener for "No"
        tdsNo.addEventListener("click", () => {
            tdsDropdown.style.display = "none";
        });
    }
});

// Universal GST Handler
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".gst-container").forEach((container) => {
        const gstYes = container.querySelector('input[id="gstYes"]');
        const gstNo = container.querySelector('input[id="gstNo"]');
        const gstFields = container.querySelectorAll(
            "#hsn_sac_code, #gst_rate, #gst_trans"
        );
        function toggleGSTFields(show) {
            gstFields.forEach((field) => {
                field.closest(".col-md-4").style.display = show
                    ? "block"
                    : "none";
            });
        }
        if (gstYes) {
            gstYes.checked = true; // Preselect Yes
            toggleGSTFields(true); // Show GST fields by default
        }
        if (gstYes) {
            gstYes.addEventListener("click", () => toggleGSTFields(true));
        }
        if (gstNo) {
            gstNo.addEventListener("click", () => toggleGSTFields(false));
        }
    });
});
//Universal Partial Payment Option
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".payment-container").forEach((container) => {
        const paymentStatus = container.querySelector("#pay_status");
        const partialFields = container.querySelector("#partial");

        function togglePartialFields(value) {
            if (value === "Partial") {
                partialFields.style.display = "flex";
            } else {
                partialFields.style.display = "none";
                container.querySelector("#total_amount").value = "0";
                container.querySelector("#advance_amount").value = "0";
                container.querySelector("#due_amount").value = "0";
            }
        }
        if (paymentStatus) {
            togglePartialFields(paymentStatus.value);
            paymentStatus.addEventListener("change", function () {
                togglePartialFields(this.value);
            });
        }
    });
});

//-------------- img js ----------

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".fileInput").forEach((input) => {
        input.addEventListener("change", function () {
            const file = this.files[0];
            if (!file) return;

            const uploadArea = this.closest(".upload-area");
            const previewContainer = uploadArea.querySelector(
                ".file-preview-container"
            );
            const uploadText = uploadArea.querySelector(".upload-text");
            previewContainer.innerHTML = ""; // Clear previous preview

            const fileURL = URL.createObjectURL(file);
            const fileName = file.name;
            const fileSize = (file.size / 1024).toFixed(2) + " KB"; // Convert size to KB
            const fileType = file.type;

            // Hide the "Click to Upload" text
            uploadText.classList.add("hidden");

            // Create preview box
            let preview = document.createElement("div");
            preview.classList.add("file-preview");

            // Image Preview
            if (fileType.startsWith("image/")) {
                let img = document.createElement("img");
                img.src = fileURL;
                img.classList.add("preview-image");
                img.style.maxWidth = "100px";
                img.style.borderRadius = "5px";
                img.style.marginBottom = "10px";
                preview.appendChild(img);
            }

            // File Info
            let fileInfo = document.createElement("div");
            fileInfo.classList.add("file-info");
            fileInfo.innerHTML = `<div class="file-name">${fileName}</div>
                                  <div class="file-size">${fileSize}</div>`;

            // Download Button
            let downloadBtn = document.createElement("a");
            downloadBtn.textContent = "Download";
            downloadBtn.href = fileURL;
            downloadBtn.download = fileName;
            downloadBtn.classList.add("btn", "btn-success", "btn-sm");

            // Append File Info & Download Button
            preview.appendChild(fileInfo);
            preview.appendChild(downloadBtn);
            previewContainer.appendChild(preview);

            // Hide input field after upload
            this.style.display = "none";
        });
    });
});

function copy_contact_person_details() {
    // Get values from the first set of input fields
    let custName = document.getElementById("cust_name").value;
    let custPhone = document.getElementById("cust_phone").value;
    let custEmail = document.getElementById("cust_email").value;

    // Set the values to the second set of input fields
    document.getElementById("cont_name").value = custName;
    document.getElementById("cont_no").value = custPhone;
    document.getElementById("cont_email").value = custEmail;
}

function copy_contact_person_details_vendor() {
    // Get values from the first set of input fields
    let custName = document.getElementById("vendor_name").value;
    let custPhone = document.getElementById("vendor_phone").value;
    let custEmail = document.getElementById("vendor_email").value;

    // Set the values to the second set of input fields
    document.getElementById("cont_name").value = custName;
    document.getElementById("cont_no").value = custPhone;
    document.getElementById("cont_email").value = custEmail;
}

//Start Add Sales
changeCustomer();
changeCustomer_purchase();
var addSalesFrm = $("#addSalesFrm").validate({
    rules: {
        seller_name: {
            required: true,
        },
        seller_contact: {
            required: true,
            minlength: 10,
            maxlength: 10,
            number: true,
        },
        seller_email: {
            required: true,
            email: true,
        },
        seller_pan: {
            required: true,
        },
        seller_addone: {
            required: true,
        },
        seller_country: {
            required: true,
        },
        seller_state: {
            required: true,
        },
        seller_city: {
            required: true,
        },
        seller_pin: {
            required: true,
            number: true,
        },
    },

    messages: {
        seller_name: {
            required: "Seller name is required",
        },
        seller_contact: {
            required: "Contact is required",
        },
        seller_email: {
            required: "Email is required",
        },
        seller_pan: {
            required: "Email is required",
        },
        seller_addone: {
            required: "Address is required",
        },
        seller_country: {
            required: "Country is required",
        },
        seller_state: {
            required: "State is required",
        },
        seller_city: {
            required: "City is required",
        },
        seller_pin: {
            required: "Pincode is required",
        },
    },
    errorElement: "em",
    errorPlacement: function (error, element) {
        // Add the `help-block` class to the error element
        error.addClass("help-block");
        error.insertAfter(element);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("has-error").removeClass("has-success");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).addClass("has-success").removeClass("has-error");
    },
});

$("form#addSalesFrm").bind("submit", function () {
    if (addSalesFrm.form()) {
        $("#addSalesLoader").show();
        var sId = $("#sId").val();
        if (sId == "") {
            var surl = base_url + "/save_sales_invoice";
        } else {
            var surl = base_url + "/update_sales_invoice";
        }
        var salesData =
            $("form#addSalesFrmTop").serialize() +
            "&" +
            $("form#addSalesFrm").serialize();
        $.ajax({
            url: surl,
            type: "POST",
            data: salesData,
            success: function (response) {
                $("#addSalesLoader").hide();
                if (response.class == "succ") {
                    //$("#addSalesFrm .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
                    if (sId == "") {
                        window.location.href = response.redirect;
                    }
                    $("#tab-A").removeClass("active");
                    $("#tab-B").addClass("active");

                    $("#basic").hide();
                    $("#customer").show();
                    $("#customer").addClass("show");
                    $("#customer").addClass("active");
                } else {
                    $.each(response, function (idx, obj) {
                        //alert(obj);
                        $("#addSalesFrm .message-container").html(
                            '<div class="err">' + obj + "</div>"
                        );
                    });
                }
            },
        });
    }
});
$("form#addSalesFrm #nextBtnSeller").on("click", function () {
    $("#tab-A").removeClass("active");
    $("#tab-B").addClass("active");
    $("#basic").hide();
    $("#customer").show();
    $("#customer").addClass("show");
    $("#customer").addClass("active");
});

$("form#addSalesFrmTwo #nextBtnCust").on("click", function () {
    $("#tab-B").removeClass("active");
    $("#tab-C").addClass("active");
    $("#customer").hide();
    $("#product").show();
    $("#product").addClass("show");
    $("#product").addClass("active");
});

$("form#addSalesFrmThree #nextBtnProd").on("click", function () {
    $("#tab-C").removeClass("active");
    $("#tab-D").addClass("active");
    $("#product").hide();
    $("#other").show();
    $("#other").addClass("show");
    $("#other").addClass("active");
});

$("form#addSalesFrmTwo #prevBtnCust").on("click", function () {
    $("#tab-B").removeClass("active");
    $("#tab-A").addClass("active");

    $("#customer").hide();
    $("#basic").show();
    $("#basic").addClass("show");
    $("#basic").addClass("active");
});

$("form#addSalesFrmThree #prevBtnProd").on("click", function () {
    $("#tab-C").removeClass("active");
    $("#tab-B").addClass("active");

    $("#product").hide();
    $("#customer").show();
    $("#customer").addClass("show");
    $("#customer").addClass("active");
});

$("form#addSalesFrmFour #prevBtnOther").on("click", function () {
    $("#tab-D").removeClass("active");
    $("#tab-C").addClass("active");

    $("#other").hide();
    $("#product").show();
    $("#product").addClass("show");
    $("#product").addClass("active");
});

var addSalesFrmTwo = $("#addSalesFrmTwo").validate({
    rules: {
        inv_name: {
            required: true,
        },
        add_type: {
            required: true,
        },
        cont_person: {
            required: true,
        },
        cont_person_no: {
            required: true,
            //minlength: "10 characters at least"
        },
    },
    messages: {
        inv_name: {
            required: "Customer is required",
        },
        add_type: {
            required: "Address type is required",
        },
        cont_person: {
            required: "contact person name is required",
        },
        cont_person_no: {
            required: "contact person_no type is required",
        },
    },
    errorElement: "em",
    errorPlacement: function (error, element) {
        error.addClass("help-block");
        error.insertAfter(element);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("has-error").removeClass("has-success");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).addClass("has-success").removeClass("has-error");
    },
});

$("form#addSalesFrmTwo").bind("submit", function () {
    //e.preventDefault();
    if (addSalesFrmTwo.form()) {
        $("#editSalesLoader").show();
        var base_url = $("#base_url").val();
        var itemurl = base_url + "/update_sales_customer";
        var custData = $("form#addSalesFrmTwo").serialize();

        $.ajax({
            url: itemurl,
            type: "POST",
            data: custData,
            success: function (result) {
                $("#editSalesLoader").hide();
                $("#tab-B").removeClass("active");
                $("#tab-C").addClass("active");

                $("#customer").hide();
                $("#product").show();
                $("#product").addClass("show");
                $("#product").addClass("active");
            },
        });
    }
});

var signature = $("form#addSalesFrmThree #sign").val();
if (signature == "") {
    var addSalesFrmThree = $("#addSalesFrmThree").validate({
        rules: {
            signature: {
                required: true,
            },
            signature_name: {
                required: true,
            },
        },
        messages: {
            signature: {
                required: "Signature image is required",
            },
            signature_name: {
                required: "Signature name is required",
            },
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            error.addClass("help-block");
            error.insertAfter(element);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("has-error").removeClass("has-success");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).addClass("has-success").removeClass("has-error");
        },
    });
} else {
    var addSalesFrmThree = $("#addSalesFrmThree").validate({
        rules: {
            signature_name: {
                required: true,
            },
        },
        messages: {
            signature_name: {
                required: "Signature name is required",
            },
        },
    });
}

$("form#addSalesFrmThree").bind("submit", function () {
    if (addSalesFrmThree.form()) {
        $("#editSalesLoader").show();
        var base_url = $("#base_url").val();
        let signature = $("#addSalesFrmThree #signature").prop("files")[0];
        let signature_name = $("#addSalesFrmThree #signature_name").val();
        let id = $("#sId").val();
        let sales_data = new FormData();

        let tdsApplicable = $("input[name='tds_applicable']:checked").val();

        let tdsPercentage = "";
        let taxableAmt = "";
        let tds_amount = "";
        let tdsPercentageWithId = "";
        let tds_id = "";

        if (tdsApplicable === "yes") {
            tdsPercentageWithId = $("#tds_percentage").val();

            // Split the 'tds_percentage' value by the hyphen ('-') to get the percentage part
            tdsPercentage = tdsPercentageWithId.split("-")[0];
            tds_id = tdsPercentageWithId.split("-")[1];

            taxableAmt = $("#taxableAmt").val();
            tds_amount =
                parseFloat(taxableAmt) +
                parseFloat(taxableAmt) * (parseFloat(tdsPercentage) / 100);
        }

        sales_data.append("signature", signature);
        sales_data.append("signature_name", signature_name);
        sales_data.append("id", id);
        sales_data.append("tdsApplicable", tdsApplicable);
        sales_data.append("tdsPercentage", tdsPercentage);
        sales_data.append("tds_id", tds_id);
        sales_data.append("tds_amount", tds_amount);

        $.ajax({
            url: base_url + "/update_sales_invoice_final",
            type: "POST",
            data: sales_data,
            contentType: false,
            processData: false,
            success: function (response) {
                $("#editSalesLoader").hide();
                if (response.class == "succ") {
                    $("#addSalesFrmThree .message-container").html(
                        '<div class="' +
                            response.class +
                            '">' +
                            response.message +
                            "</div>"
                    );
                    //window.location.href=response.redirect;
                    $("#tab-C").removeClass("active");
                    $("#tab-D").addClass("active");

                    $("#product").hide();
                    $("#other").show();
                    $("#other").addClass("show");
                    $("#other").addClass("active");
                } else {
                    $.each(response, function (idx, obj) {
                        $("#addSalesFrmThree .message-container").html(
                            '<div class="err">' + obj + "</div>"
                        );
                    });
                }
            },
        });
    }
});

var addSalesFrmFour = $("#addSalesFrmFour").validate({
    rules: {
        mode_of_pay: {
            required: true,
        },
        pay_status: {
            required: true,
        },
        other_payment: {
            required: function () {
                return $("#mode_of_pay").val() === "OTHER";
            },
        },
    },
    messages: {
        mode_of_pay: {
            required: "Payment mode is required",
        },
        pay_status: {
            required: "Payment status is required",
        },
        other_payment: {
            required: "Specify Other Payment Method is required",
        },
    },
    errorElement: "em",
    errorPlacement: function (error, element) {
        error.addClass("help-block");
        error.insertAfter(element);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("has-error").removeClass("has-success");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).addClass("has-success").removeClass("has-error");
    },
});

$("form#addSalesFrmFour").bind("submit", function () {
    //e.preventDefault();
    if (addSalesFrmFour.form()) {
        $("#editSalesLoader").show();
        var base_url = $("#base_url").val();
        var itemurl = base_url + "/update_sales_other";
        var custData = $("form#addSalesFrmFour").serialize();
        $.ajax({
            url: itemurl,
            type: "POST",
            data: custData,
            success: function (response) {
                $("#editSalesLoader").hide();
                if (response.class == "succ") {
                    $("#addSalesFrmFour .message-container").html(
                        '<div class="' +
                            response.class +
                            '">' +
                            response.message +
                            "</div>"
                    );
                    window.location.href = response.redirect;
                } else {
                    $.each(response, function (idx, obj) {
                        $("#addSalesFrmFour .message-container").html(
                            '<div class="err">' + obj + "</div>"
                        );
                    });
                }
            },
        });
    }
});

var addSalesFrmFive = $("#addSalesFrmFive").validate({
    rules: {
        rate: {
            required: true,
            number: true,
        },
        disc_amt: {
            required: true,
            number: true,
        },
        tax_type: {
            required: true,
        },
    },
    messages: {
        rate: {
            required: "Rate is required",
        },
        disc_amt: {
            required: "Discount is required",
        },
        tax_type: {
            required: "Tax type is required",
        },
    },
    errorElement: "em",
    errorPlacement: function (error, element) {
        error.addClass("help-block");
        error.insertAfter(element);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("has-error").removeClass("has-success");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).addClass("has-success").removeClass("has-error");
    },
});

$("form#addSalesFrmFive").bind("submit", function () {
    //e.preventDefault();
    if (addSalesFrmFive.form()) {
        var base_url = $("#base_url").val();
        var itemurl = base_url + "/update_sales_item";
        var editData = $("form#addSalesFrmFive").serialize();

        $("form#addSalesFrmFive #invoiceData").html("");
        $.ajax({
            url: itemurl,
            type: "POST",
            data: editData,
            success: function (result) {
                $("form#addSalesFrmFive #invoiceData").html(result);
            },
        });
    }
});

$("#prod_id").change(function () {
    var prod_id = $("#prod_id option:selected").val();
    var sId = $("#sId").val();
    var base_url = $("#base_url").val();
    if (prod_id != "") {
        $.ajax({
            method: "POST",
            url: base_url + "/getProduct",
            data: { sId: sId, prod_id: prod_id },
            datatype: "json",
            success: function (result) {
                var res = JSON.parse(result);
                //console.log(res[0].hsn_sac_code)
                $("#hsn_sac_code").val(res[0].hsn_sac_code);
                $("#gst_rate").val(res[0].gst_rate);
                $("#disc_sell").val(res[0].disc_sell);
                $("#disc_sell_type").val(res[0].disc_sell_type);
            },
        });
    } else {
        $("#hsn_sac_code").val("");
		$("#gst_rate").val("");
        $("#disc_sell").val(0);
        $("#prod_gov_fee").val(0);
        $("#billing_type").prop("selectedIndex", 0);
        $("#gst_trans").prop("selectedIndex", 0);
        $("#disc_sell_type").prop("selectedIndex", 0);
    }
});

$(".invoicedelete").click(function () {
    var sId = $(this).data("id");
    var base_url = $("#base_url").val();
    $("#del_invoice").click(function () {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: base_url + "/delInvoice",
            data: { id: sId },
            success: function (data) {
                //console.log(data.success)
                window.location.href = data.redirect;
            },
        });
    });
});

function changeRate(el) {
    var id = $(el).data("id");
    var sid = $(el).data("sid");
    var rate = $("#rate_" + id).val();
    var base_url = $("#base_url").val();

    $("form#addSalesFrmThree #invoiceData").html("");
    $.ajax({
        method: "POST",
        //dataType: "json",
        url: base_url + "/update_sales_item_rate",
        data: { id: id, sid: sid, rate: rate },
        success: function (result) {
            console.log(result);
            $("form#addSalesFrmThree #invoiceData").html(result);
        },
    });
}

$(".inv_active").click(function () {
    var status = $(this).data("stat");
    var base_url = $("#base_url").val();
    var id = $(this).data("id");
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/activateStatus",
        data: { status: status, id: id },
        success: function (data) {
            //console.log(data.success)
            window.location.href = data.redirect;
        },
    });
});

var transType = $("#transport_type option:selected").val();
if (transType == "Other") {
    $("#transportTypeOther").show();
} else {
    $("#transportTypeOther").hide();
}
$("#transport_type").on("change", function (e) {
    var optionSelected = $("option:selected", this);
    var transportType = this.value;
    if (transportType == "Other") {
        $("#transportTypeOther").show();
        $("#transport_type_other").val("");
    } else {
        $("#transportTypeOther").hide();
        $("#transport_type_other").val("");
    }
});

//End Add Sales
// select sales invoice custmor part
function changeCustomer() {
    var base_url = $("#base_url").val();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    var invcustId = $("#invNameCustomer option:selected").val();
    var salesTableID = $("#sId").val();
    //alert(salesTableID);

    if (invcustId != "") {
        $.ajax({
            url: base_url + "/getinvcust?" + invcustId,
            dataType: "json",
            //type: "post",
            data: { id: invcustId, salesTableID: salesTableID },
            success: function (data) {
                // console.log(data);
                // alert(data);

                $("#contact_no").val(data.cust_phone);
                $("#cust_email").val(data.cust_email);
                $("#gst_reg").val(data.gst_reg);
                $("#cust_gst_no").val(data.cust_gst_no);
                $("#cust_pan").val(data.cust_pan);
                $("#comp_type").val(data.comp_type);
                $("#cust_gst_type").val(data.cust_gst_type);
                // $("#bill_name").val(data.cust_bill_name);
                $("#bill_addone").val(data.cust_bill_addone);
                $("#bill_addtwo").val(data.cust_bill_addtwo);
                $("#cust_bill_pin").val(data.cust_bill_pin);

                $("#cust_bill_country")
                    .val(data.cust_bill_country)
                    .attr("selected", "selected");
                $("#cust_bill_state").empty();
                var stateBillOpt = '<option value="">Select State</option>';
                $.each(data.stateBill, function (idx, item) {
                    if (item.id == item.sid) {
                        stateBillOpt +=
                            '<option value="' +
                            item.id +
                            '" selected="">' +
                            item.name +
                            "</option>";
                    } else {
                        stateBillOpt +=
                            '<option value="' +
                            item.id +
                            '" >' +
                            item.name +
                            "</option>";
                    }
                });
                $("#cust_bill_state").html(stateBillOpt);
                $("#cust_bill_city").empty();
                var cityBillOpt = '<option value="">Select City</option>';
                $.each(data.cityBill, function (idx, item) {
                    if (item.id == item.sid) {
                        cityBillOpt +=
                            '<option value="' +
                            item.id +
                            '" selected="">' +
                            item.name +
                            "</option>";
                    } else {
                        cityBillOpt +=
                            '<option value="' +
                            item.id +
                            '" >' +
                            item.name +
                            "</option>";
                    }
                });
                $("#cust_bill_city").html(cityBillOpt);
                //$("#cust_bill_pin").val(data.cust_bill_pin);

                //Ship section
                //$("#ship_name").val(data.cust_ship_name);
                $("#cust_ship_addone").val(data.cust_ship_addone);
                $("#cust_ship_addtwo").val(data.cust_ship_addtwo);
                $("#cust_ship_country")
                    .val(data.cust_ship_country)
                    .attr("selected", "selected");
                $("#cust_ship_state").empty();
                var stateShipOpt = '<option value="">Select State</option>';
                $.each(data.stateShip, function (idx, item) {
                    if (item.id == item.selid) {
                        stateShipOpt +=
                            '<option value="' +
                            item.id +
                            '" selected="">' +
                            item.name +
                            "</option>";
                    } else {
                        stateShipOpt +=
                            '<option value="' +
                            item.id +
                            '" >' +
                            item.name +
                            "</option>";
                    }
                });
                $("#cust_ship_state").html(stateShipOpt);
                $("#cust_ship_city").empty();
                var cityShipOpt = '<option value="">Select City</option>';
                $.each(data.cityShip, function (idx, item) {
                    if (item.id == item.selid) {
                        cityShipOpt +=
                            '<option value="' +
                            item.id +
                            '" selected="">' +
                            item.name +
                            "</option>";
                    } else {
                        cityShipOpt +=
                            '<option value="' +
                            item.id +
                            '" >' +
                            item.name +
                            "</option>";
                    }
                });
                $("#cust_ship_city").html(cityShipOpt);
                $("#cust_ship_pin").val(data.cust_ship_pin);

                $("#cust_bill_gstno").val(data.cust_bill_gstno);
                $("#cont_person").val(data.cust_bill_contact);
                $("#contact_name").val(data.cust_bill_contact);

                $("#cont_person_no").val(data.cust_bill_mobilno);
                $("#cust_bill_designa").val(data.cust_bill_designa);
                // $("#cust_bill_name").val(data.cust_bill_name);
                $("#cust_bill_addtwo").val(data.cust_bill_addtwo);

                $("#cust_ship_gstno").val(data.cust_ship_gstno);
                $("#cust_ship_contact").val(data.cust_ship_contact);
                $("#cust_ship_mobilno").val(data.cust_ship_mobilno);
                $("#cust_ship_designa").val(data.cust_ship_designa);
                // $("#cust_ship_name").val(data.cust_ship_name);

                // $("#cust_ship_addone").val(data.cust_ship_addone);
                // $("#cust_ship_addtwo").val(data.cust_ship_addtwo);
            },
        });
    } else {
        $("#contact_no").val("");
        $("#cust_email").val("");
        $("#cust_pan").val("");
        $("#cust_gst_no").val("");
        $("#cust_name").val("");

        $("#cust_bill_addone").val("");
        $("#cust_bill_addtwo").val("");
        $("#cust_bill_state").empty();
        $("#cust_bill_city").empty();
        $("#cust_bill_pin").val("");

        $("#cust_bill_gstno").val("");
        $("#cont_person").val("");
        $("#cont_person_no").val("");
        $("#cust_bill_designa").val("");
        // $("#cust_bill_name").val("");
        $("#bill_addone").val("");
        $("#bill_addtwo").val("");

        $("#cust_ship_addone").val("");
        $("#cust_ship_addtwo").val("");
        $("#cust_ship_state").empty();
        $("#cust_ship_city").empty();
        $("#cust_ship_pin").val("");

        $("#cust_ship_gstno").val("");
        $("#cust_ship_contact").val("");
        $("#cust_ship_mobilno").val("");
        $("#cust_ship_designa").val("");
        // $("#cust_ship_name").val("");
    }
}

function changeCustomer_purchase() {
    var base_url = $("#base_url").val();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    var invcustId = $("#invNameCustomer option:selected").val();
    var salesTableID = $("#sId").val();
    //alert(salesTableID);

    if (invcustId != "") {
        $.ajax({
            url: base_url + "/getinvcust_purchase?" + invcustId,
            dataType: "json",
            //type: "post",
            data: { id: invcustId, salesTableID: salesTableID },
            success: function (data) {
                //console.log(data);
                //alert(data);

                $("#contact_no").val(data.vendor_phone);
                $("#cust_email").val(data.vendor_email);
                $("#gst_reg").val(data.gst_reg);
                $("#cust_gst_no").val(data.cust_gst_no);
                $("#cust_pan").val(data.cust_pan);
                $("#comp_type").val(data.comp_type);
                $("#cust_gst_type").val(data.cust_gst_type);

                $("#bill_addone").val(data.cust_bill_addone);
                $("#bill_addtwo").val(data.cust_bill_addtwo);
                $("#cust_bill_country")
                    .val(data.cust_bill_country)
                    .attr("selected", "selected");
                $("#cust_bill_state").empty();
                var stateBillOpt = '<option value="">Select State</option>';
                $.each(data.stateBill, function (idx, item) {
                    if (item.id == item.sid) {
                        stateBillOpt +=
                            '<option value="' +
                            item.id +
                            '" selected="">' +
                            item.name +
                            "</option>";
                    } else {
                        stateBillOpt +=
                            '<option value="' +
                            item.id +
                            '" >' +
                            item.name +
                            "</option>";
                    }
                });
                $("#cust_bill_state").html(stateBillOpt);

                console.log(stateBillOpt);

                $("#cust_bill_city").empty();
                var cityBillOpt = '<option value="">Select City</option>';
                $.each(data.cityBill, function (idx, item) {
                    if (item.id == item.sid) {
                        cityBillOpt +=
                            '<option value="' +
                            item.id +
                            '" selected="">' +
                            item.name +
                            "</option>";
                    } else {
                        cityBillOpt +=
                            '<option value="' +
                            item.id +
                            '" >' +
                            item.name +
                            "</option>";
                    }
                });
                $("#cust_bill_city").html(cityBillOpt);
                $("#cust_bill_pin").val(data.cust_bill_pin);

                //Ship section
                //$("#ship_name").val(data.cust_ship_name);
                $("#cust_ship_addone").val(data.cust_ship_addone);
                $("#cust_ship_addtwo").val(data.cust_ship_addtwo);
                $("#cust_ship_country")
                    .val(data.cust_ship_country)
                    .attr("selected", "selected");
                $("#cust_ship_state").empty();
                var stateShipOpt = '<option value="">Select State</option>';
                $.each(data.stateShip, function (idx, item) {
                    if (item.id == item.selid) {
                        stateShipOpt +=
                            '<option value="' +
                            item.id +
                            '" selected="">' +
                            item.name +
                            "</option>";
                    } else {
                        stateShipOpt +=
                            '<option value="' +
                            item.id +
                            '" >' +
                            item.name +
                            "</option>";
                    }
                });
                $("#cust_ship_state").html(stateShipOpt);
                $("#cust_ship_city").empty();
                var cityShipOpt = '<option value="">Select City</option>';
                $.each(data.cityShip, function (idx, item) {
                    if (item.id == item.selid) {
                        cityShipOpt +=
                            '<option value="' +
                            item.id +
                            '" selected="">' +
                            item.name +
                            "</option>";
                    } else {
                        cityShipOpt +=
                            '<option value="' +
                            item.id +
                            '" >' +
                            item.name +
                            "</option>";
                    }
                });
                $("#cust_ship_city").html(cityShipOpt);
                $("#cust_ship_pin").val(data.cust_ship_pin);

                $("#cust_bill_gstno").val(data.cust_bill_gstno);
                $("#cust_bill_contact").val(data.cust_bill_contact);
                //$("#contact_name").val(data.cust_bill_contact);

                $("#cust_bill_mobilno").val(data.cust_bill_mobilno);
                $("#cust_bill_designa").val(data.cust_bill_designa);
                $("#cust_bill_name").val(data.cust_bill_name);
                $("#cust_bill_addtwo").val(data.cust_bill_addtwo);

                $("#cust_ship_gstno").val(data.cust_ship_gstno);
                $("#cust_ship_contact").val(data.cust_ship_contact);
                $("#cust_ship_mobilno").val(data.cust_ship_mobilno);
                $("#cust_ship_designa").val(data.cust_ship_designa);
                // $("#cust_ship_name").val(data.cust_ship_name);

                // $("#cust_ship_addone").val(data.cust_ship_addone);
                // $("#cust_ship_addtwo").val(data.cust_ship_addtwo);
            },
        });
    } else {
        $("#contact_no").val("");
        $("#cust_email").val("");
        $("#cust_pan").val("");
        $("#cust_gst_no").val("");
        $("#cust_name").val("");

        $("#cust_bill_addone").val("");
        $("#cust_bill_addtwo").val("");
        $("#cust_bill_state").empty();
        $("#cust_bill_city").empty();
        $("#cust_bill_pin").val("");

        $("#cust_bill_gstno").val("");
        $("#cont_person").val("");
        $("#cont_person_no").val("");
        $("#cust_bill_designa").val("");
        $("#cust_bill_name").val("");
        $("#bill_addone").val("");
        $("#bill_addtwo").val("");

        $("#cust_ship_addone").val("");
        $("#cust_ship_addtwo").val("");
        $("#cust_ship_state").empty();
        $("#cust_ship_city").empty();
        $("#cust_ship_pin").val("");

        $("#cust_ship_gstno").val("");
        $("#cust_ship_contact").val("");
        $("#cust_ship_mobilno").val("");
        $("#cust_ship_designa").val("");
        $("#cust_ship_name").val("");
    }
}
// purchase product change
// function changeProductType() {
//     var base_url = $("#base_url").val();
//     var id = $("#prod_type option:selected").val();
//     $.ajaxSetup({
//         headers: {
//             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//         },
//     });
//     if (id != "") {
//         $.ajax({
//             url: base_url + "/getProductType",
//             dataType: "json",
//             type: "post",
//             data: { id: id },
//             success: function (data) {
//                 // console.log(data);

//                 $("#prod_id").empty();
//                 var str = '<option value="">Select</option>';
//                 $.each(data, function (idx, item) {
//                     //$("#state").append('<option value="' + item.id + '">' + item.name + '</option>');
//                     str +=
//                         '<option value="' +
//                         item.id +
//                         '">' +
//                         item.name +
//                         "</option>";
//                 });
//                 $("#prod_id").html(str);
//             },
//         });
//     } else {
//         $("#prod_id").html("");
//         $("#hsn_sac_code").val("");
//         $("#disc_sell").val(0);
//         $("#prod_gov_fee").val(0);
//         $("#billing_type").prop("selectedIndex", 0);
//         $("#gst_trans").prop("selectedIndex", 0);
//         $("#disc_sell_type").prop("selectedIndex", 0);
//     }
// }

//sels invoice product / Add These Item
function changeQuantity(el) {
    //alert('hell');
    var id = $(el).data("id");
    var sid = $(el).data("sid");
    var prod_id = $(el).data("prod_id");
    var quantity = $("#quantity_" + id).val();
    var base_url = $("#base_url").val();

    $("form#addSalesFrmThree #invoiceData").html("");
    $.ajax({
        method: "POST",
        //dataType: "json",
        url: base_url + "/update_sales_item_quantity",
        data: { id: id, sid: sid, prod_id: prod_id, quantity: quantity },
        success: function (result) {
            $("form#addSalesFrmThree #invoiceData").html(result);
            console.log(result);
            
        },
    });
}

// Product / Service Details GST Transaction mode
/*function updateGSTAllocation() {
    var selectedOption = $("select[name='gst_trans']").val();
    alert(selectedOption);
    var allocationInput = $("#gst_allocation input");
    //alert(allocationInput);
    switch (selectedOption) {
        case "intrastate":
            allocationInput.val("CGST(9%) & SGST(9%)");
            break;
        case "interstate":
            allocationInput.val("IGST(18%)");
            break;
        case "union":
            allocationInput.val("UGST(18%)");
            break;
        default:
            allocationInput.val("");
            break;
    }
}

updateGSTAllocation();
$("select[name='gst_trans']").change(function () {
    updateGSTAllocation();
}); */



//  Function to auto-update GST Allocation
function updateGSTAllocation() {
    var selectedOption = $("#gst_trans").val();
   // var gstRate = parseFloat($("#gst_rate").val()) || 0;
    var gstRate = $("#gst_rate").val();
    var allocationInput = $("#gst_allocation_text");
    //alert(allocationInput);

    if (!selectedOption || gstRate === 0) {
        allocationInput.val("");
        return;
    }

    let allocationText = "";

    switch (selectedOption) {
        case "intrastate":
            allocationText = `CGST(${gstRate / 2}%) & SGST(${gstRate / 2}%)`;
            
            break;
        case "interstate":
            allocationText = `IGST(${gstRate}%)`;
            break;

        case "union":
            allocationText = `UGST(${gstRate}%)`;
            break;

        default:
            allocationText = "";
            break;
    }

    allocationInput.val(allocationText);
}

//  Auto-update allocation when mode or rate changes
$(document).ready(function () {
    $("#gst_trans").change(updateGSTAllocation);
    $("#gst_rate").on("input", updateGSTAllocation);
});






// rest all product/service details in sales invoice
function addAnotherProduct() {
    $("#prod_type").prop("selectedIndex", 0);
    $("#prod_id").prop("selectedIndex", 0);
    $("#hsn_sac_code").val("");
    $("#disc_sell").val(0);
    $("#prod_gov_fee").val(0);
    $("#gst_rate").val(0);
    $("#billing_type").prop("selectedIndex", 0);
    $("#gst_trans").prop("selectedIndex", 0);
    $("#disc_sell_type").prop("selectedIndex", 0);
}

// Edit selected item in sales invoice
function editItem(el) {
    var id = $(el).data("id");
    var base_url = $("#base_url").val();
    $("#editSalesLoader").show();
    $.ajax({
        method: "POST",
        url: base_url + "/fetchSalesItem",
        data: { id: id },
        datatype: "json",
        success: function (result) {
            $("#editSalesLoader").hide();
            var res = JSON.parse(result);
            res = res[id];
            //console.log(res.prod_id);
            $("#prod_type").val(res.item_type);
            changeProductType();
			setTimeout(function () {
				$('#prod_id').val(res.prod_id).trigger('change');
			}, 300);
            $("#billing_type").val(res.billing_type);
            $("#prod_gov_fee").val(res.prod_gov_fee);
            if (res.hsn_code != "") {
                $("#hsn_sac_code").val(res.hsn_code);
            } else {
                $("#hsn_sac_code").val(res.sac_code);
            }
            $("#gst_rate").val(res.gst_rate);
            $("#gst_trans").val(res.gst_trans);
            //$("#disc_sell").val(res.disc_sell);
            $("#disc_sell").val(res.disc);
            $("#disc_sell_type").val(res.disc_type);
        },
    });
}

//Start sales items delet
function delItem(el) {
    var salesItemId = "";
    var sid = "";
    salesItemId = $(el).data("id");
    //alert(salesItemId);
    sid = $(el).data("sid");
    var base_url = $("#base_url").val();
    $("#delItemSales").click(function () {
        $("form#addSalesFrmThree #invoiceData").html("");
        $.ajax({
            method: "POST",
            //dataType: "json",
            url: base_url + "/delSalesItem",
            data: { id: salesItemId, sid: sid },
            success: function (result) {
                $("form#addSalesFrmThree #invoiceData").html(result);
            },
        });
    });
}

function cust_ship_changeState_ship(el) {
    var base_url = $("#base_url").val();
    var id = el.value;

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: base_url + "/getCity?" + id,
        dataType: "json",
        //type: "post",
        data: { id: id },
        success: function (data) {
            $("#cust_ship_city").empty();
            var str = '<option value="">Select City</option>';
            $.each(data, function (idx, item) {
                str +=
                    '<option value="' +
                    item.id +
                    '">' +
                    item.name +
                    "</option>";
            });
            $("#cust_ship_city").html(str);
        },
    });
}

//Start Add Purchase Invoice
var addPurchaseFrm = $("#addPurchaseFrm").validate({
    rules: {
        inv_name: {
            required: true,
        },
		inv_date: {
            required: true,
        },
    },

    messages: {
        inv_name: {
            required: "Number is required",
        },inv_date: {
            required: "Date is required",
        },
    },
    errorElement: "em",
    errorPlacement: function (error, element) {
        // Add the `help-block` class to the error element
        error.addClass("help-block");
        error.insertAfter(element);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("has-error").removeClass("has-success");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).addClass("has-success").removeClass("has-error");
    },
});

$("form#addPurchaseFrm").bind("submit", function () {
    if (addPurchaseFrm.form()) {
        var base_url = $("#base_url").val();
        $("#loader").show();
        var sId = $("#sId").val();
        if (sId == "") {
            var surl = base_url + "/save_purchase_invoice";
        } else {
            var surl = base_url + "/update_purchase_invoice";
        }
        var salesData =
            $("form#addPurchaseFrmTop").serialize() +
            "&" +
            $("form#addPurchaseFrm").serialize();
        $.ajax({
            url: surl,
            type: "POST",
            data: salesData,
            success: function (response) {
                $("#loader").hide();
                if (response.class == "succ") {
                    //$("#addPurchaseFrm .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
                    if (sId == "") {
                        //window.location.href = response.redirect;
						showToast("Invoice Create Successfully", "success");
						setTimeout(() => {
							window.location.href = response.redirect;
						}, 2000);
                    }
                    $("#tab-A").removeClass("active");
                    $("#tab-B").addClass("active");

                    $("#buyer").hide();
                    $("#seller").show();
                    $("#seller").addClass("show");
                    $("#seller").addClass("active");
                } else {
                    var errorHtml = "";
					$.each(response, function(idx, obj) {
						// errorHtml += '<div class="err">' + obj + "</div>";
						showToast("Error: " + obj, "error");
					});
                }
            },
        });
    }
});

$("form#addPurchaseFrm #nextBtnBuyer").on("click", function () {
    $("#tab-A").removeClass("active");
    $("#tab-B").addClass("active");
    $("#buyer").hide();
    $("#seller").show();
    $("#seller").addClass("show");
    $("#seller").addClass("active");
});

$("form#addPurchaseFrmTwo #nextBtnSeller").on("click", function () {
    $("#tab-B").removeClass("active");
    $("#tab-C").addClass("active");
    $("#seller").hide();
    $("#product").show();
    $("#product").addClass("show");
    $("#product").addClass("active");
});

$("form#addPurchaseFrmThree #nextBtnProd").on("click", function () {
    $("#tab-C").removeClass("active");
    $("#tab-D").addClass("active");
    $("#product").hide();
    $("#other").show();
    $("#other").addClass("show");
    $("#other").addClass("active");
});

$("form#addPurchaseFrmTwo #prevBtnBuyer").on("click", function () {
    $("#tab-B").removeClass("active");
    $("#tab-A").addClass("active");

    $("#seller").hide();
    $("#buyer").show();
    $("#buyer").addClass("show");
    $("#buyer").addClass("active");
});

$("form#addPurchaseFrmThree #prevBtnProd").on("click", function () {
    $("#tab-C").removeClass("active");
    $("#tab-B").addClass("active");

    $("#product").hide();
    $("#seller").show();
    $("#seller").addClass("show");
    $("#seller").addClass("active");
});

$("form#addPurchaseFrmFour #prevBtnOther").on("click", function () {
    $("#tab-D").removeClass("active");
    $("#tab-c").addClass("active");

    $("#other").hide();
    $("#product").show();
    $("#product").addClass("show");
    $("#product").addClass("active");
});

var addPurchaseFrmTwo = $("#addPurchaseFrmTwo").validate({
    rules: {
        seller_name: {
            required: true,
        },
        seller_contact: {
            required: true,
            minlength: 10,
            maxlength: 10,
            number: true,
        },
        seller_email: {
            required: true,
            email: true,
        },
        seller_pan: {
            required: true,
        },
        seller_addone: {
            required: true,
        },
        seller_country: {
            required: true,
        },
        seller_state: {
            required: true,
        },
        /*seller_city: {
                            required: true
                        },
                        seller_pin: {
                            required: true,
                            number:true
                        },*/
    },

    messages: {
        seller_name: {
            required: "Seller name is required",
        },
        seller_contact: {
            required: "Contact is required",
        },
        seller_email: {
            required: "Email is required",
        },
        seller_pan: {
            required: "Email is required",
        },
        seller_addone: {
            required: "Address is required",
        },
        seller_country: {
            required: "Country is required",
        },
        seller_state: {
            required: "State is required",
        },
        /*seller_city: {
                                required: "City is required"
                            },
                            seller_pin: {
                                required: "Pincode is required"
                            },*/
    },
    errorElement: "em",
    errorPlacement: function (error, element) {
        // Add the `help-block` class to the error element
        error.addClass("help-block");
        error.insertAfter(element);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("has-error").removeClass("has-success");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).addClass("has-success").removeClass("has-error");
    },
});
////--------------------- work---------------
$("form#addPurchaseFrmTwo").bind("submit", function () {
    if (addPurchaseFrmTwo.form()) {
        var base_url = $("#base_url").val();
        $("#loader").show();
        var sId = $("#sId").val();
        var surl = base_url + "/update_seller_details";
        var sellerData = $("form#addPurchaseFrmTwo").serialize();
        $.ajax({
            url: surl,
            type: "POST",
            data: sellerData,
            success: function (response) {
                //console.log(response);
                $("#loader").hide();
                if (response.class == "succ") {
                    //$("#addPurchaseFrmTwo .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');

                    $("#tab-B").removeClass("active");
                    $("#tab-C").addClass("active");

                    $("#seller").hide();
                    $("#product").show();
                    $("#product").addClass("show");
                    $("#product").addClass("active");
                } else {
                    $.each(response, function (idx, obj) {
                        //alert(obj);
                        $("#addPurchaseFrmTwo .message-container").html(
                            '<div class="err">' + obj + "</div>"
                        );
                    });
                }
            },
        });
    }
});

var addPurchaseFrmThree = $("#addPurchaseFrmThree").validate({
    rules: {
        signature_name: {
            //required: true
        },
    },
    messages: {
        signature_name: {
            //required: "Signature name is required"
        },
    },
    errorElement: "em",
    errorPlacement: function (error, element) {
        error.addClass("help-block");
        error.insertAfter(element);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("has-error").removeClass("has-success");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).addClass("has-success").removeClass("has-error");
    },
});

$("form#addPurchaseFrmThree").bind("submit", function () {
    if (addPurchaseFrmThree.form()) {
        $("#loader").show();
        let signature_name = $("#addPurchaseFrmThree #signature_name").val();
		let discount_amount = $("#addPurchaseFrmThree #discount_amount").val();
        var base_url = $("#base_url").val();
        let id = $("#sId").val();
        let sales_data = new FormData();

        sales_data.append("signature_name", signature_name);
        sales_data.append("discount_amount", discount_amount);
        sales_data.append("id", id);

        $.ajax({
            url: base_url + "/update_purchase_invoice_final",
            type: "POST",
            data: sales_data,
            contentType: false,
            processData: false,
            success: function (response) {
                $("#loader").hide();
                if (response.class == "succ") {
                    $("#addPurchaseFrmThree .message-container").html(
                        '<div class="' +
                            response.class +
                            '">' +
                            response.message +
                            "</div>"
                    );
                    //window.location.href=response.redirect;
                    $("#tab-C").removeClass("active");
                    $("#tab-D").addClass("active");

                    $("#product").hide();
                    $("#other").show();
                    $("#other").addClass("show");
                    $("#other").addClass("active");
                } else {
                    $.each(response, function (idx, obj) {
                        $("#addPurchaseFrmThree .message-container").html(
                            '<div class="err">' + obj + "</div>"
                        );
                    });
                }
            },
        });
    }
});

var addPurchaseFrmFour = $("#addPurchaseFrmFour").validate({
    rules: {
        mode_of_pay: {
            required: true,
        },
        pay_status: {
            required: true,
        },
        /*order_date: {
            required: true,
        },
        disp_through: {
            required: true,
        },*/
        other_payment: {
            required: function () {
                return $("#mode_of_pay").val() === "OTHER";
            },
        },
    },
    messages: {
        mode_of_pay: {
            required: "Payment mode is required",
        },
        pay_status: {
            required: "Payment status is required",
        },
        /*order_date: {
            required: "Date is required",
        },
        disp_through: {
            required: "Dispatch through is required",
        },*/
        other_payment: {
            required: "Other Payment Name is required",
        },
    },
    errorElement: "em",
    errorPlacement: function (error, element) {
        error.addClass("help-block");
        error.insertAfter(element);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("has-error").removeClass("has-success");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).addClass("has-success").removeClass("has-error");
    },
});

$("form#addPurchaseFrmFour").bind("submit", function () {
    //e.preventDefault();
    if (addPurchaseFrmFour.form()) {
        $("#loader").show();
        var base_url = $("#base_url").val();
        var itemurl = base_url + "/update_purchase_other";
        let id = $("#sId").val();
        let tdsApplicable = $("input[name='tds_option']:checked").val();
        //alert(tdsApplicable);
		let tds_nature = $("#addPurchaseFrmFour #tds_nature option:selected").val();
		let tds_section = $("#addPurchaseFrmFour #tds_section option:selected").val();
        let tds_rate = $("#addPurchaseFrmFour #tds_rate").val();
        let invoice_date = $("#addPurchaseFrmFour #invoice_date").val();
        let payment_date = $("#addPurchaseFrmFour #payment_date").val();
        let invoice_amount = $("#addPurchaseFrmFour #invoice_amount").val();
        let tds_amount = $("#addPurchaseFrmFour #tds_amount").val();
        let tds_deduamount = $("#addPurchaseFrmFour #tds_deduamount").val();
        let tds_threamount = $("#addPurchaseFrmFour #tds_threamount").val();
        let tds_limitsection = $("#addPurchaseFrmFour #tds_limitsection").val();

        let mode_of_pay = $("#addPurchaseFrmFour #mode_of_pay option:selected").val();
        let pay_status = $("#addPurchaseFrmFour #pay_status option:selected").val();
        let other_payment = $("#addPurchaseFrmFour #other_payment").val();
        let total_amount = $("#addPurchaseFrmFour #total_amount").val();
        let advance_amount = $("#addPurchaseFrmFour #advance_amount").val();
        let due_amount = $("#addPurchaseFrmFour #due_amount").val();
        let seller_orderno = $("#addPurchaseFrmFour #seller_orderno").val();
        let order_date = $("#addPurchaseFrmFour #order_date").val();
        let buyer_refno = $("#addPurchaseFrmFour #buyer_refno").val();
        let other_refno = $("#addPurchaseFrmFour #other_refno").val();
        let dispa_docno_one = $("#addPurchaseFrmFour #dispa_docno_one").val();
        let disp_through = $("#addPurchaseFrmFour #disp_through option:selected").val();
        let other_dispa_det = $("#addPurchaseFrmFour #other_dispa_det").val();
        let terms_delivery = $("#addPurchaseFrmFour #terms_delivery").val();

        const totalImages = $("#image_sign")[0].files.length;
        let image_sign = $("#image_sign")[0];
        let frmData = new FormData();
        frmData.append("id", id);
        frmData.append("tds_applicable", tdsApplicable);
        frmData.append("tds_nature", tds_nature);
        frmData.append("tds_section", tds_section);
		frmData.append("tds_rate", tds_rate);
        frmData.append("invoice_date", invoice_date);
        frmData.append("payment_date", payment_date);
        frmData.append("invoice_amount", invoice_amount);
        frmData.append("tds_amount", tds_amount);
        frmData.append("tds_deduamount", tds_deduamount);
        frmData.append("tds_threamount", tds_threamount);
        frmData.append("tds_limitsection", tds_limitsection);
		
		
        frmData.append("mode_of_pay", mode_of_pay);
        frmData.append("other_payment", other_payment);
        frmData.append("pay_status", pay_status);
        frmData.append("total_amount", total_amount);
        frmData.append("advance_amount", advance_amount);
        frmData.append("due_amount", due_amount);
        frmData.append("seller_orderno", seller_orderno);
        frmData.append("order_date", order_date);
        frmData.append("buyer_refno", buyer_refno);
        frmData.append("other_refno", other_refno);
        frmData.append("dispa_docno_one", dispa_docno_one);
        frmData.append("disp_through", disp_through);
        frmData.append("other_dispa_det", other_dispa_det);
        frmData.append("terms_delivery", terms_delivery);
        for (let i = 0; i < totalImages; i++) {
            frmData.append("image_sign" + i, image_sign.files[i]);
        }
        frmData.append("totalImages", totalImages);
        $.ajax({
            url: itemurl,
            type: "POST",
            data: frmData,
            contentType: false,
            processData: false,
            success: function (response) {
                $("#loader").hide();
                if (response.class == "succ") {
                    $("#addPurchaseFrmFour .message-container").html(
                        '<div class="' +
                            response.class +
                            '">' +
                            response.message +
                            "</div>"
                    );
                    window.location.href = response.redirect;
                } else {
                    $.each(response, function (idx, obj) {
                        $("#addPurchaseFrmFour .message-container").html(
                            '<div class="err">' + obj + "</div>"
                        );
                    });
                }
            },
        });
    }
});

var addPurchaseFrmFive = $("#addPurchaseFrmFive").validate({
    rules: {
        rate: {
            required: true,
            number: true,
        },
        disc_amt: {
            required: true,
            number: true,
        },
        tax_type: {
            required: true,
        },
    },
    messages: {
        rate: {
            required: "Rate is required",
        },
        disc_amt: {
            required: "Discount is required",
        },
        tax_type: {
            required: "Tax type is required",
        },
    },
    errorElement: "em",
    errorPlacement: function (error, element) {
        error.addClass("help-block");
        error.insertAfter(element);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("has-error").removeClass("has-success");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).addClass("has-success").removeClass("has-error");
    },
});

$("form#addPurchaseFrmFive").bind("submit", function () {
    //e.preventDefault();
    if (addPurchaseFrmFive.form()) {
        var itemurl = base_url + "/update_purchase_item";
        var editData = $("form#addPurchaseFrmFive").serialize();

        $("form#addPurchaseFrmFive #invoiceData").html("");
        $.ajax({
            url: itemurl,
            type: "POST",
            data: editData,
            success: function (result) {
                $("form#addPurchaseFrmFive #invoiceData").html(result);
            },
        });
    }
});

$("#purchase_prod_id").change(function () {
    var prod_id = $("#purchase_prod_id option:selected").val();
    var sId = $("#sId").val();
    if (prod_id != "") {
        $("#invoiceData").html("");
        $.ajax({
            method: "POST",
            url: base_url + "/purchase_items_display",
            data: { sId: sId, prod_id: prod_id },
            datatype: "json",
            success: function (result) {
                //console.log(result)
                $("#invoiceData").html(result);
            },
        });
    }
});

$(".invoicePurdelete").click(function () {
    var sId = $(this).data("id");
    $("#del_purchase_invoice").click(function () {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: base_url + "/delInvoicePurchase",
            data: { id: sId },
            success: function (data) {
                //console.log(data.success)
                window.location.href = data.redirect;
            },
        });
    });
});

$(".inv_pur_active").click(function () {
    var status = $(this).data("stat");
    var id = $(this).data("id");
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/activateStatusPurchase",
        data: { status: status, id: id },
        success: function (data) {
            //console.log(data.success)
            window.location.href = data.redirect;
        },
    });
});

function addProductItems() {
    var base_url = $("#base_url").val();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    var prod_id = $("#prod_id option:selected").val();
    var billing_type = $("#billing_type option:selected").val();
    var prod_gov_fee = $("#prod_gov_fee").val();
    var gst_trans = $("#gst_trans option:selected").val();
    var disc_sell = $("#disc_sell").val();
    var disc_sell_type = $("#disc_sell_type option:selected").val();
    var gst_allocation_text = $("#gst_allocation_text").val();
    var sId = $("#sId").val();
    if (prod_id == "") {
        alert("Please select product");
    } else if (prod_id == undefined) {
        alert("Please select product");
    } else if (billing_type == "") {
        alert("Please select billing type");
    } else if (billing_type == "gov" && prod_gov_fee == "") {
        alert("Please enter fees");
    } else {
        /*else if(gst_trans == ""){
                        alert("Please select GST transaction mode");
                    }*/
        $("#invoiceData").html("");
        $.ajax({
            method: "POST",
            url: base_url + "/sales_items_display",
            data: {
                sId: sId,
                prod_id: prod_id,
                billing_type: billing_type,
                prod_gov_fee: prod_gov_fee,
                gst_trans: gst_trans,
                disc_sell: disc_sell,
                disc_sell_type: disc_sell_type,
                gst_allocation_text: gst_allocation_text,
            },
            datatype: "json",
            success: function (result) {
                //console.log(result)
                $("#invoiceData").html(result);
				updateTotalAmount();
            },
        });
    }
}

function addProductItems_purchase() {
    var base_url = $("#base_url").val();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    var prod_id = $("#prod_id option:selected").val();
    var billing_type = $("#billing_type option:selected").val();
    var gst_rate = $("#gst_rate").val();
    var gst_trans = $("#gst_trans option:selected").val();
    var disc_sell = $("#disc_sell").val();
    var disc_sell_type = $("#disc_sell_type option:selected").val();
    var sId = $("#sId").val();
    if (prod_id == "") {
        alert("Please select product");
    } else if (prod_id == undefined) {
        alert("Please select product");
    } else if (billing_type == "") {
        alert("Please select billing type");
    } else if (billing_type == "with gst" && gst_trans == "") {
        alert("Please select GST transaction mode");
    } else {
        $("#invoiceData").html("");
        $.ajax({
            method: "POST",
            url: base_url + "/purchase_items_display",
            data: {
                sId: sId,
                prod_id: prod_id,
                billing_type: billing_type,
                gst_rate: gst_rate,
                gst_trans: gst_trans,
                disc_sell: disc_sell,
                disc_sell_type: disc_sell_type,
            },
            datatype: "json",
            success: function (result) {
                //console.log(result)
                $("#invoiceData").html(result);
				updateTotalAmount();
            },
        });
    }
}

//Start purchase items
function delItemPurchase(el) {
    var salesItemId = "";
    var sid = "";
    salesItemId = $(el).data("id");
    sid = $(el).data("sid");
    var base_url = $("#base_url").val();
    $("#delItemPurchase").click(function () {
        $("form#addPurchaseFrmThree #invoiceData").html("");
        $.ajax({
            method: "POST",
            //dataType: "json",
            url: base_url + "/delPurchaseItem",
            data: { id: salesItemId, sid: sid },
            success: function (result) {
                $("form#addPurchaseFrmThree #invoiceData").html(result);
				updateTotalAmount();
            },
        });
    });
}

function editItemPurchase(el) {
    var id = $(el).data("id");
    var base_url = $("#base_url").val();
    $("#editSalesLoader").show();
    $.ajax({
        method: "POST",
        url: base_url + "/fetchPurchaseItem",
        data: { id: id },
        datatype: "json",
        success: function (result) {
            $("#editSalesLoader").hide();
            var res = JSON.parse(result);
            res = res[id];
            //console.log(res.disc_sell);
            $("#prod_type").val(res.item_type);
            $("#prod_id").val(res.prod_id);
            $("#billing_type").val(res.billing_type);
            if (res.hsn_code != "") {
                $("#hsn_sac_code").val(res.hsn_code);
            } else {
                $("#hsn_sac_code").val(res.sac_code);
            }
            $("#gst_rate").val(res.gst_rate);
            $("#gst_trans").val(res.gst_trans);
            $("#disc_sell").val(res.disc_sell);
            $("#disc_sell_type").val(res.disc_type);

            // Billing Type
            var selectedOption = $("#billing_type option:selected").val();
            const billingInputRow = document.getElementById(
                "purchase_billing_input"
            );
            if (selectedOption === "with gst") {
                billingInputRow.style.display = "flex";
            } else {
                billingInputRow.style.display = "none";
            }
        },
    });
}

function changeQuantityPurchase(el) {
    var id = $(el).data("id");
    var sid = $(el).data("sid");
    var prod_id = $(el).data("prod_id");
    var quantity = $("#quantity_" + id).val();
    var base_url = $("#base_url").val();

    $("form#addPurchaseFrmThree #invoiceData").html("");
    $.ajax({
        method: "POST",
        //dataType: "json",
        url: base_url + "/update_purchase_item_quantity",
        data: { id: id, sid: sid, prod_id: prod_id, quantity: quantity },
        success: function (result) {
            $("form#addPurchaseFrmThree #invoiceData").html(result);
			updateTotalAmount();
        },
    });
}
function changeRatePurchase(el) {
    var id = $(el).data("id");
    var sid = $(el).data("sid");
    var rate = $("#rate_" + id).val();
    var base_url = $("#base_url").val();

    $("form#addPurchaseFrmThree #invoiceData").html("");
    $.ajax({
        method: "POST",
        //dataType: "json",
        url: base_url + "/update_purchase_item_rate",
        data: { id: id, sid: sid, rate: rate },
        success: function (result) {
            $("form#addPurchaseFrmThree #invoiceData").html(result);
			updateTotalAmount();
        },
    });
}
//End Add Purchase Invoice



//Start Add Purchase Order
var addPoFrm = $("#addPoFrm").validate({
    rules: {
        inv_name: {
            required: true,
        },
		inv_date: {
            required: true,
        },
    },

    messages: {
        inv_name: {
            required: "Number is required",
        },inv_date: {
            required: "Date is required",
        },
    },
    errorElement: "em",
    errorPlacement: function (error, element) {
        // Add the `help-block` class to the error element
        error.addClass("help-block");
        error.insertAfter(element);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("has-error").removeClass("has-success");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).addClass("has-success").removeClass("has-error");
    },
});

$("form#addPoFrm").bind("submit", function () {
    if (addPoFrm.form()) {
        var base_url = $("#base_url").val();
        $("#loader").show();
        var sId = $("#sId").val();
        if (sId == "") {
            var surl = base_url + "/save_po_invoice";
        } else {
            var surl = base_url + "/update_po_invoice";
        }
        var salesData =
            $("form#addPoFrmTop").serialize() +
            "&" +
            $("form#addPoFrm").serialize();
        $.ajax({
            url: surl,
            type: "POST",
            data: salesData,
            success: function (response) {
                $("#loader").hide();
                if (response.class == "succ") {
                    //$("#addPoFrm .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
                    if (sId == "") {
                        //window.location.href = response.redirect;
						showToast("Invoice Create Successfully", "success");
						setTimeout(() => {
							window.location.href = response.redirect;
						}, 2000);
                    }
                    $("#tab-A").removeClass("active");
                    $("#tab-B").addClass("active");

                    $("#buyer").hide();
                    $("#seller").show();
                    $("#seller").addClass("show");
                    $("#seller").addClass("active");
                } else {
                    var errorHtml = "";
					$.each(response, function(idx, obj) {
						// errorHtml += '<div class="err">' + obj + "</div>";
						showToast("Error: " + obj, "error");
					});
                }
            },
        });
    }
});

$("form#addPoFrm #nextBtnBuyer").on("click", function () {
    $("#tab-A").removeClass("active");
    $("#tab-B").addClass("active");
    $("#buyer").hide();
    $("#seller").show();
    $("#seller").addClass("show");
    $("#seller").addClass("active");
});

$("form#addPoFrmTwo #nextBtnSeller").on("click", function () {
    $("#tab-B").removeClass("active");
    $("#tab-C").addClass("active");
    $("#seller").hide();
    $("#product").show();
    $("#product").addClass("show");
    $("#product").addClass("active");
});

$("form#addPoFrmThree #nextBtnProd").on("click", function () {
    $("#tab-C").removeClass("active");
    $("#tab-D").addClass("active");
    $("#product").hide();
    $("#other").show();
    $("#other").addClass("show");
    $("#other").addClass("active");
});

$("form#addPoFrmTwo #prevBtnBuyer").on("click", function () {
    $("#tab-B").removeClass("active");
    $("#tab-A").addClass("active");

    $("#seller").hide();
    $("#buyer").show();
    $("#buyer").addClass("show");
    $("#buyer").addClass("active");
});

$("form#addPoFrmThree #prevBtnProd").on("click", function () {
    $("#tab-C").removeClass("active");
    $("#tab-B").addClass("active");

    $("#product").hide();
    $("#seller").show();
    $("#seller").addClass("show");
    $("#seller").addClass("active");
});

$("form#addPoFrmFour #prevBtnOther").on("click", function () {
    $("#tab-D").removeClass("active");
    $("#tab-C").addClass("active");

    $("#other").hide();
    $("#product").show();
    $("#product").addClass("show");
    $("#product").addClass("active");
});

var addPoFrmTwo = $("#addPoFrmTwo").validate({
    rules: {
        seller_name: {
            required: true,
        },
        seller_contact: {
            required: true,
            minlength: 10,
            maxlength: 10,
            number: true,
        },
        seller_email: {
            required: true,
            email: true,
        },
        seller_pan: {
            required: true,
        },
        seller_addone: {
            required: true,
        },
        seller_country: {
            required: true,
        },
        seller_state: {
            required: true,
        },
        /*seller_city: {
                            required: true
                        },
                        seller_pin: {
                            required: true,
                            number:true
                        },*/
    },

    messages: {
        seller_name: {
            required: "Seller name is required",
        },
        seller_contact: {
            required: "Contact is required",
        },
        seller_email: {
            required: "Email is required",
        },
        seller_pan: {
            required: "Email is required",
        },
        seller_addone: {
            required: "Address is required",
        },
        seller_country: {
            required: "Country is required",
        },
        seller_state: {
            required: "State is required",
        },
        /*seller_city: {
                                required: "City is required"
                            },
                            seller_pin: {
                                required: "Pincode is required"
                            },*/
    },
    errorElement: "em",
    errorPlacement: function (error, element) {
        // Add the `help-block` class to the error element
        error.addClass("help-block");
        error.insertAfter(element);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("has-error").removeClass("has-success");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).addClass("has-success").removeClass("has-error");
    },
});
////--------------------- work---------------
$("form#addPoFrmTwo").bind("submit", function () {
    if (addPoFrmTwo.form()) {
        var base_url = $("#base_url").val();
        $("#loader").show();
        var sId = $("#sId").val();
        var surl = base_url + "/update_po_seller_details";
        var sellerData = $("form#addPoFrmTwo").serialize();
        $.ajax({
            url: surl,
            type: "POST",
            data: sellerData,
            success: function (response) {
                //console.log(response);
                $("#loader").hide();
                if (response.class == "succ") {
                    //$("#addPoFrmTwo .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');

                    $("#tab-B").removeClass("active");
                    $("#tab-C").addClass("active");

                    $("#seller").hide();
                    $("#product").show();
                    $("#product").addClass("show");
                    $("#product").addClass("active");
                } else {
                    $.each(response, function (idx, obj) {
                        //alert(obj);
                        $("#addPoFrmTwo .message-container").html(
                            '<div class="err">' + obj + "</div>"
                        );
                    });
                }
            },
        });
    }
});

var addPoFrmThree = $("#addPoFrmThree").validate({
    rules: {
        signature_name: {
            //required: true
        },
    },
    messages: {
        signature_name: {
            //required: "Signature name is required"
        },
    },
    errorElement: "em",
    errorPlacement: function (error, element) {
        error.addClass("help-block");
        error.insertAfter(element);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("has-error").removeClass("has-success");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).addClass("has-success").removeClass("has-error");
    },
});

$("form#addPoFrmThree").bind("submit", function () {
    if (addPoFrmThree.form()) {
        $("#loader").show();
        let signature_name = $("#addPoFrmThree #signature_name").val();
        var base_url = $("#base_url").val();
        let id = $("#sId").val();
        let sales_data = new FormData();

        sales_data.append("signature_name", signature_name);
        sales_data.append("id", id);

        $.ajax({
            url: base_url + "/update_po_invoice_final",
            type: "POST",
            data: sales_data,
            contentType: false,
            processData: false,
            success: function (response) {
                $("#loader").hide();
                if (response.class == "succ") {
                    $("#addPoFrmThree .message-container").html(
                        '<div class="' +
                            response.class +
                            '">' +
                            response.message +
                            "</div>"
                    );
                    //window.location.href=response.redirect;
                    $("#tab-C").removeClass("active");
                    $("#tab-D").addClass("active");

                    $("#product").hide();
                    $("#other").show();
                    $("#other").addClass("show");
                    $("#other").addClass("active");
                } else {
                    $.each(response, function (idx, obj) {
                        $("#addPoFrmThree .message-container").html(
                            '<div class="err">' + obj + "</div>"
                        );
                    });
                }
            },
        });
    }
});

var addPoFrmFour = $("#addPoFrmFour").validate({
    rules: {
        mode_of_pay: {
            required: true,
        },
        pay_status: {
            required: true,
        },
        order_date: {
            required: true,
        },
        disp_through: {
            required: true,
        },
        other_payment: {
            required: function () {
                return $("#mode_of_pay").val() === "OTHER";
            },
        },
    },
    messages: {
        mode_of_pay: {
            required: "Payment mode is required",
        },
        pay_status: {
            required: "Payment status is required",
        },
        order_date: {
            required: "Date is required",
        },
        disp_through: {
            required: "Dispatch through is required",
        },
        other_payment: {
            required: "Other Payment Name is required",
        },
    },
    errorElement: "em",
    errorPlacement: function (error, element) {
        error.addClass("help-block");
        error.insertAfter(element);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("has-error").removeClass("has-success");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).addClass("has-success").removeClass("has-error");
    },
});

$("form#addPoFrmFour").bind("submit", function () {
    //e.preventDefault();
    if (addPoFrmFour.form()) {
        $("#loader").show();
        var base_url = $("#base_url").val();
        var itemurl = base_url + "/update_po_other";
        let id = $("#sId").val();
        let tdsApplicable = $("input[name='tds_option']:checked").val();
        //alert(tdsApplicable);
		let tds_nature = $("#addPoFrmFour #tds_nature option:selected").val();
		let tds_section = $("#addPoFrmFour #tds_section option:selected").val();
        let tds_rate = $("#addPoFrmFour #tds_rate").val();
        let invoice_date = $("#addPoFrmFour #invoice_date").val();
        let payment_date = $("#addPoFrmFour #payment_date").val();
        let invoice_amount = $("#addPoFrmFour #invoice_amount").val();
        let tds_amount = $("#addPoFrmFour #tds_amount").val();
        let tds_deduamount = $("#addPoFrmFour #tds_deduamount").val();
        let tds_threamount = $("#addPoFrmFour #tds_threamount").val();
        let tds_limitsection = $("#addPoFrmFour #tds_limitsection").val();

        let mode_of_pay = $("#addPoFrmFour #mode_of_pay option:selected").val();
        let pay_status = $("#addPoFrmFour #pay_status option:selected").val();
        let other_payment = $("#addPoFrmFour #other_payment").val();
        let total_amount = $("#addPoFrmFour #total_amount").val();
        let advance_amount = $("#addPoFrmFour #advance_amount").val();
        let due_amount = $("#addPoFrmFour #due_amount").val();
        let seller_orderno = $("#addPoFrmFour #seller_orderno").val();
        let order_date = $("#addPoFrmFour #order_date").val();
        let buyer_refno = $("#addPoFrmFour #buyer_refno").val();
        let other_refno = $("#addPoFrmFour #other_refno").val();
        let dispa_docno_one = $("#addPoFrmFour #dispa_docno_one").val();
        let disp_through = $("#addPoFrmFour #disp_through option:selected").val();
        let other_dispa_det = $("#addPoFrmFour #other_dispa_det").val();
        let terms_delivery = $("#addPoFrmFour #terms_delivery").val();

        const totalImages = $("#image_sign")[0].files.length;
        let image_sign = $("#image_sign")[0];
        let frmData = new FormData();
        frmData.append("id", id);
        frmData.append("tds_applicable", tdsApplicable);
        frmData.append("tds_nature", tds_nature);
        frmData.append("tds_section", tds_section);
		frmData.append("tds_rate", tds_rate);
        frmData.append("invoice_date", invoice_date);
        frmData.append("payment_date", payment_date);
        frmData.append("invoice_amount", invoice_amount);
        frmData.append("tds_amount", tds_amount);
        frmData.append("tds_deduamount", tds_deduamount);
        frmData.append("tds_threamount", tds_threamount);
        frmData.append("tds_limitsection", tds_limitsection);
		
		
        frmData.append("mode_of_pay", mode_of_pay);
        frmData.append("other_payment", other_payment);
        frmData.append("pay_status", pay_status);
        frmData.append("total_amount", total_amount);
        frmData.append("advance_amount", advance_amount);
        frmData.append("due_amount", due_amount);
        frmData.append("seller_orderno", seller_orderno);
        frmData.append("order_date", order_date);
        frmData.append("buyer_refno", buyer_refno);
        frmData.append("other_refno", other_refno);
        frmData.append("dispa_docno_one", dispa_docno_one);
        frmData.append("disp_through", disp_through);
        frmData.append("other_dispa_det", other_dispa_det);
        frmData.append("terms_delivery", terms_delivery);
        for (let i = 0; i < totalImages; i++) {
            frmData.append("image_sign" + i, image_sign.files[i]);
        }
        frmData.append("totalImages", totalImages);
        $.ajax({
            url: itemurl,
            type: "POST",
            data: frmData,
            contentType: false,
            processData: false,
            success: function (response) {
                $("#loader").hide();
                if (response.class == "succ") {
                    $("#addPoFrmFour .message-container").html(
                        '<div class="' +
                            response.class +
                            '">' +
                            response.message +
                            "</div>"
                    );
                    window.location.href = response.redirect;
                } else {
                    $.each(response, function (idx, obj) {
                        $("#addPoFrmFour .message-container").html(
                            '<div class="err">' + obj + "</div>"
                        );
                    });
                }
            },
        });
    }
});

var addPoFrmFive = $("#addPoFrmFive").validate({
    rules: {
        rate: {
            required: true,
            number: true,
        },
        disc_amt: {
            required: true,
            number: true,
        },
        tax_type: {
            required: true,
        },
    },
    messages: {
        rate: {
            required: "Rate is required",
        },
        disc_amt: {
            required: "Discount is required",
        },
        tax_type: {
            required: "Tax type is required",
        },
    },
    errorElement: "em",
    errorPlacement: function (error, element) {
        error.addClass("help-block");
        error.insertAfter(element);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("has-error").removeClass("has-success");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).addClass("has-success").removeClass("has-error");
    },
});

$("form#addPoFrmFive").bind("submit", function () {
    //e.preventDefault();
    if (addPoFrmFive.form()) {
        var itemurl = base_url + "/update_po_item";
        var editData = $("form#addPoFrmFive").serialize();

        $("form#addPoFrmFive #invoiceData").html("");
        $.ajax({
            url: itemurl,
            type: "POST",
            data: editData,
            success: function (result) {
                $("form#addPoFrmFive #invoiceData").html(result);
            },
        });
    }
});

function changeCustomer_po() {
    var base_url = $("#base_url").val();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    var invcustId = $("#invNameCustomer option:selected").val();
    var salesTableID = $("#sId").val();
    //alert(salesTableID);

    if (invcustId != "") {
        $.ajax({
            url: base_url + "/getinvcust_po?" + invcustId,
            dataType: "json",
            //type: "post",
            data: { id: invcustId, salesTableID: salesTableID },
            success: function (data) {
                //console.log(data);
                //alert(data);

                $("#contact_no").val(data.vendor_phone);
                $("#cust_email").val(data.vendor_email);
                $("#gst_reg").val(data.gst_reg);
                $("#cust_gst_no").val(data.cust_gst_no);
                $("#cust_pan").val(data.cust_pan);
                $("#comp_type").val(data.comp_type);
                $("#cust_gst_type").val(data.cust_gst_type);

                $("#bill_addone").val(data.cust_bill_addone);
                $("#bill_addtwo").val(data.cust_bill_addtwo);
                $("#cust_bill_country")
                    .val(data.cust_bill_country)
                    .attr("selected", "selected");
                $("#cust_bill_state").empty();
                var stateBillOpt = '<option value="">Select State</option>';
                $.each(data.stateBill, function (idx, item) {
                    if (item.id == item.sid) {
                        stateBillOpt +=
                            '<option value="' +
                            item.id +
                            '" selected="">' +
                            item.name +
                            "</option>";
                    } else {
                        stateBillOpt +=
                            '<option value="' +
                            item.id +
                            '" >' +
                            item.name +
                            "</option>";
                    }
                });
                $("#cust_bill_state").html(stateBillOpt);

                console.log(stateBillOpt);

                $("#cust_bill_city").empty();
                var cityBillOpt = '<option value="">Select City</option>';
                $.each(data.cityBill, function (idx, item) {
                    if (item.id == item.sid) {
                        cityBillOpt +=
                            '<option value="' +
                            item.id +
                            '" selected="">' +
                            item.name +
                            "</option>";
                    } else {
                        cityBillOpt +=
                            '<option value="' +
                            item.id +
                            '" >' +
                            item.name +
                            "</option>";
                    }
                });
                $("#cust_bill_city").html(cityBillOpt);
                $("#cust_bill_pin").val(data.cust_bill_pin);

                //Ship section
                //$("#ship_name").val(data.cust_ship_name);
                $("#cust_ship_addone").val(data.cust_ship_addone);
                $("#cust_ship_addtwo").val(data.cust_ship_addtwo);
                $("#cust_ship_country")
                    .val(data.cust_ship_country)
                    .attr("selected", "selected");
                $("#cust_ship_state").empty();
                var stateShipOpt = '<option value="">Select State</option>';
                $.each(data.stateShip, function (idx, item) {
                    if (item.id == item.selid) {
                        stateShipOpt +=
                            '<option value="' +
                            item.id +
                            '" selected="">' +
                            item.name +
                            "</option>";
                    } else {
                        stateShipOpt +=
                            '<option value="' +
                            item.id +
                            '" >' +
                            item.name +
                            "</option>";
                    }
                });
                $("#cust_ship_state").html(stateShipOpt);
                $("#cust_ship_city").empty();
                var cityShipOpt = '<option value="">Select City</option>';
                $.each(data.cityShip, function (idx, item) {
                    if (item.id == item.selid) {
                        cityShipOpt +=
                            '<option value="' +
                            item.id +
                            '" selected="">' +
                            item.name +
                            "</option>";
                    } else {
                        cityShipOpt +=
                            '<option value="' +
                            item.id +
                            '" >' +
                            item.name +
                            "</option>";
                    }
                });
                $("#cust_ship_city").html(cityShipOpt);
                $("#cust_ship_pin").val(data.cust_ship_pin);

                $("#cust_bill_gstno").val(data.cust_bill_gstno);
                $("#cust_bill_contact").val(data.cust_bill_contact);
                //$("#contact_name").val(data.cust_bill_contact);

                $("#cust_bill_mobilno").val(data.cust_bill_mobilno);
                $("#cust_bill_designa").val(data.cust_bill_designa);
                $("#cust_bill_name").val(data.cust_bill_name);
                $("#cust_bill_addtwo").val(data.cust_bill_addtwo);

                $("#cust_ship_gstno").val(data.cust_ship_gstno);
                $("#cust_ship_contact").val(data.cust_ship_contact);
                $("#cust_ship_mobilno").val(data.cust_ship_mobilno);
                $("#cust_ship_designa").val(data.cust_ship_designa);
                // $("#cust_ship_name").val(data.cust_ship_name);

                // $("#cust_ship_addone").val(data.cust_ship_addone);
                // $("#cust_ship_addtwo").val(data.cust_ship_addtwo);
            },
        });
    } else {
        $("#contact_no").val("");
        $("#cust_email").val("");
        $("#cust_pan").val("");
        $("#cust_gst_no").val("");
        $("#cust_name").val("");

        $("#cust_bill_addone").val("");
        $("#cust_bill_addtwo").val("");
        $("#cust_bill_state").empty();
        $("#cust_bill_city").empty();
        $("#cust_bill_pin").val("");

        $("#cust_bill_gstno").val("");
        $("#cont_person").val("");
        $("#cont_person_no").val("");
        $("#cust_bill_designa").val("");
        $("#cust_bill_name").val("");
        $("#bill_addone").val("");
        $("#bill_addtwo").val("");

        $("#cust_ship_addone").val("");
        $("#cust_ship_addtwo").val("");
        $("#cust_ship_state").empty();
        $("#cust_ship_city").empty();
        $("#cust_ship_pin").val("");

        $("#cust_ship_gstno").val("");
        $("#cust_ship_contact").val("");
        $("#cust_ship_mobilno").val("");
        $("#cust_ship_designa").val("");
        $("#cust_ship_name").val("");
    }
}

$("#po_prod_id").change(function () {
    var prod_id = $("#po_prod_id option:selected").val();
    var sId = $("#sId").val();
    if (prod_id != "") {
        $("#invoiceData").html("");
        $.ajax({
            method: "POST",
            url: base_url + "/po_items_display",
            data: { sId: sId, prod_id: prod_id },
            datatype: "json",
            success: function (result) {
                //console.log(result)
                $("#invoiceData").html(result);
            },
        });
    }
});

$(".invoicePodelete").click(function () {
    var sId = $(this).data("id");
    $("#del_po_invoice").click(function () {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: base_url + "/delInvoicePo",
            data: { id: sId },
            success: function (data) {
                //console.log(data.success)
                window.location.href = data.redirect;
            },
        });
    });
});

//update total amount after ajax
function updateTotalAmount() {
	const grandTotalElement = document.getElementById("grand_total_amount");
	const totalAmount = document.getElementById("total_amount");

	if (grandTotalElement && totalAmount) {
		let amt = grandTotalElement.textContent.replace(/[₹,]/g, '').trim();
		totalAmount.value = parseFloat(amt || 0).toFixed(2);
	}
}

function addProductItems_po() {
    var base_url = $("#base_url").val();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    var prod_id = $("#prod_id option:selected").val();
    var billing_type = $("#billing_type option:selected").val();
    var gst_rate = $("#gst_rate").val();
    var gst_trans = $("#gst_trans option:selected").val();
    var disc_sell = $("#disc_sell").val();
    var disc_sell_type = $("#disc_sell_type option:selected").val();
    var sId = $("#sId").val();
    if (prod_id == "") {
        alert("Please select product");
    } else if (prod_id == undefined) {
        alert("Please select product");
    } else if (billing_type == "") {
        alert("Please select billing type");
    } else if (billing_type == "with gst" && gst_trans == "") {
        alert("Please select GST transaction mode");
    } else {
        $("#invoiceData").html("");
        $.ajax({
            method: "POST",
            url: base_url + "/po_items_display",
            data: {
                sId: sId,
                prod_id: prod_id,
                billing_type: billing_type,
                gst_rate: gst_rate,
                gst_trans: gst_trans,
                disc_sell: disc_sell,
                disc_sell_type: disc_sell_type,
            },
            datatype: "json",
            success: function (result) {
                //console.log(result)
                $("#invoiceData").html(result);
				updateTotalAmount();
            },
        });
    }
}

function delItemPo(el) {
    var salesItemId = "";
    var sid = "";
    salesItemId = $(el).data("id");
    sid = $(el).data("sid");
    var base_url = $("#base_url").val();
    //$("#delItemPo").click(function () {
        $("form#addPoFrmThree #invoiceData").html("");
        $.ajax({
            method: "POST",
            //dataType: "json",
            url: base_url + "/delPoItem",
            data: { id: salesItemId, sid: sid },
            success: function (result) {
                $("form#addPoFrmThree #invoiceData").html(result);
				updateTotalAmount();
            },
        });
    //});
}

function editItemPo(el) {
    var id = $(el).data("id");
    var base_url = $("#base_url").val();
    $("#loader").show();
    $.ajax({
        method: "POST",
        url: base_url + "/fetchPoItem",
        data: { id: id },
        datatype: "json",
        success: function (result) {
            $("#loader").hide();
            var res = JSON.parse(result);
            res = res[id];
            //console.log(res.disc_sell);
            $("#prod_type").val(res.item_type);
            $("#prod_id").val(res.prod_id);
            $("#billing_type").val(res.billing_type);
            if (res.hsn_code != "") {
                $("#hsn_sac_code").val(res.hsn_code);
            } else {
                $("#hsn_sac_code").val(res.sac_code);
            }
            $("#gst_rate").val(res.gst_rate);
            $("#gst_trans").val(res.gst_trans);
            $("#disc_sell").val(res.disc_sell);
            $("#disc_sell_type").val(res.disc_type);

            // Billing Type
            var selectedOption = $("#billing_type option:selected").val();
            const billingInputRow = document.getElementById(
                "purchase_billing_input"
            );
            if (selectedOption === "with gst") {
                billingInputRow.style.display = "flex";
            } else {
                billingInputRow.style.display = "none";
            }
        },
    });
}

function changeQuantityPo(el) {
    var id = $(el).data("id");
    var sid = $(el).data("sid");
    var prod_id = $(el).data("prod_id");
    var quantity = $("#quantity_" + id).val();
    var base_url = $("#base_url").val();

    $("form#addPoFrmThree #invoiceData").html("");
    $.ajax({
        method: "POST",
        //dataType: "json",
        url: base_url + "/update_po_item_quantity",
        data: { id: id, sid: sid, prod_id: prod_id, quantity: quantity },
        success: function (result) {
            $("form#addPoFrmThree #invoiceData").html(result);
			updateTotalAmount();
        },
    });
}
function changeRatePo(el) {
    var id = $(el).data("id");
    var sid = $(el).data("sid");
    var rate = $("#rate_" + id).val();
    var base_url = $("#base_url").val();

    $("form#addPoFrmThree #invoiceData").html("");
    $.ajax({
        method: "POST",
        //dataType: "json",
        url: base_url + "/update_po_item_rate",
        data: { id: id, sid: sid, rate: rate },
        success: function (result) {
            $("form#addPoFrmThree #invoiceData").html(result);
			updateTotalAmount();
        },
    });
}

//End Add Purchase Order


	//TDS calculation

	function calculateAnnualTdsFromSlabs(annualIncome, slabs) {
		let tax = 0;
		let previousLimit = 0;

		slabs.forEach(slab => {
			const to = slab.to_amount ? Number(slab.to_amount) : Infinity;
			const rate = Number(slab.tax_rate);

			if (annualIncome > previousLimit) {
				const taxable = Math.min(annualIncome, to) - previousLimit;
				tax += taxable * (rate / 100);
			}

			previousLimit = to;
		});

		return tax;
	}


	function calculateMonthlyTdsFromSlabs(monthlyGross) {
		if (!monthlyGross || !TDS_SLABS.length) return 0;

		const annualIncome = monthlyGross * 12;
		const annualTax = calculateAnnualTdsFromSlabs(annualIncome, TDS_SLABS);

		return annualTax / 12;
	}

	//start sales and purchase payment
	let isViewPage = $("#isViewPage").val() == "1";

	$(document).on('click', '.paymentModalBtn', function () {

		let id = $(this).data('id');
		let type = $(this).data('type');

		$("#f_id").val(id);
		$("#voucher_type").val(type);

		loadPaymentVouchers(id, type);

		$("#paymentVoucherModal").modal('show');
	});

	function loadPaymentVouchers(id, type)
	{
		$.get('/payment-invoice/' + type + '/' + id, function (res) {

			$("#invoice_total").val(res.invoice_total);
			$("#total_paid").val(res.total_paid);
			$("#balance_due").val(res.balance_due);

			let html = '';

			$.each(res.payments, function (i, row) {

				let actionBtn = '';

				if (!isViewPage) {
					actionBtn = `
					<td>
						<button
							class="btn btn-danger deleteVoucher"
							data-id="${row.id}">
							X
						</button>
					</td>`;
				}

				html += `
				<tr>
					<td>
						<input type="date"
							class="form-control pay_date"
							value="${row.date}"
							${isViewPage ? 'readonly' : ''}>
					</td>

					<td>
						<input type="number"
							class="form-control pay_amount"
							value="${row.amount}"
							${isViewPage ? 'readonly' : ''}>
					</td>

					<td>
						<select class="form-select payment_mode"
							${isViewPage ? 'disabled' : ''}>
							<option value="">Select</option>
							<option value="Cash" ${row.payment_mode == 'Cash' ? 'selected' : ''}>Cash</option>
							<option value="Bank" ${row.payment_mode == 'Bank' ? 'selected' : ''}>Bank</option>
							<option value="UPI" ${row.payment_mode == 'UPI' ? 'selected' : ''}>UPI</option>
						</select>
					</td>

					${actionBtn}
				</tr>`;
			});

			$("#voucherRows").html(html);

			if (isViewPage) {
				$("#actionHeader").hide();
				$("#addVoucherRow").hide();
				$("#saveVoucherPayments").hide();
			} else {
				$("#actionHeader").show();
				$("#addVoucherRow").show();
				$("#saveVoucherPayments").show();
			}
		});
	}

	$("#addVoucherRow").click(function () {

		let balance = parseFloat($("#balance_due").val()) || 0;

		if (balance <= 0) {
			alert('Invoice already fully paid');
			return;
		}

		let row = `
		<tr>
			<td>
				<input type="date"
					class="form-control pay_date">
			</td>

			<td>
				<input type="number"
					class="form-control pay_amount">
			</td>

			<td>
				<select class="form-select payment_mode">
					<option value="">Select</option>
					<option value="Cash">Cash</option>
					<option value="Bank">Bank</option>
					<option value="UPI">UPI</option>
				</select>
			</td>

			<td>
				<button
					class="btn btn-danger removeRow">
					X
				</button>
			</td>
		</tr>`;

		$("#voucherRows").append(row);
	});

	$(document).on('click', '.removeRow', function () {
		$(this).closest('tr').remove();

		$(".pay_amount:first").trigger('input');
	});

	$(document).on('input', '.pay_amount', function () {

		let invoiceTotal = parseFloat($("#invoice_total").val()) || 0;

		let total = 0;

		$(".pay_amount").each(function () {
			total += parseFloat($(this).val()) || 0;
		});

		if (total > invoiceTotal) {

			alert('Amount exceeds invoice total');

			$(this).val('');

			total = 0;

			$(".pay_amount").each(function () {
				total += parseFloat($(this).val()) || 0;
			});
		}

		$("#total_paid").val(total.toFixed(2));

		$("#balance_due").val(
			(invoiceTotal - total).toFixed(2)
		);
	});

	$("#saveVoucherPayments").click(function () {

		let rows = [];
		let hasError = false;

		$("#voucherRows tr").each(function () {

			let paymentMode = $(this).find('.payment_mode').val();

			if (!paymentMode) {
				alert('Please select payment mode for all rows.');
				hasError = true;
				return false;
			}

			rows.push({
				date: $(this).find('.pay_date').val(),
				amount: $(this).find('.pay_amount').val(),
				payment_mode: paymentMode
			});
		});

		if (hasError) {
			return;
		}

		$.ajax({

			url: '/payment-invoice/store',

			type: 'POST',

			data: {
				_token: $('meta[name="csrf-token"]').attr('content'),
				f_id: $("#f_id").val(),
				voucher_type: $("#voucher_type").val(),
				rows: rows
			},

			success: function (res) {

				alert(res.message);

				loadPaymentVouchers(
					$("#f_id").val(),
					$("#voucher_type").val()
				);
			}
		});
	});
	
	$(document).on('click','.deleteVoucher',function(){

		if(!confirm('Delete payment?'))
		{
			return;
		}

		let id=$(this).data('id');

		$.ajax({

			url:'/payment-invoice/'+id,

			type:'DELETE',

			data:{
				_token:$('meta[name="csrf-token"]').attr('content')
			},

			success:function(res){

				loadPaymentVouchers(
					$("#f_id").val(),
					$("#voucher_type").val()
				);
			}
		});
	});
	
	//end sales and purchase payment
	
	//start upload pdf
    $(document).on('click','.upload-pdf-btn',function () {
         $("#pdf_id").val($(this).data('id'));
         $("#pdf_type").val($(this).data('type'));
    });

	$('#uploadPdfModal').on('hidden.bs.modal', function () {
		$("#uploadPdfForm")[0].reset();
		$("#pdf_id").val('');
		$("#pdf_type").val('');
		$("#uploadError").html('');
	});

	$("#uploadPdfForm").submit(function (e) {
      e.preventDefault();
	  var baseUrl = $("#base_url").val();
      let file = $("#pdf_file")[0].files[0];
      if (!file) {
		 showToast("Select PDF file", "error");
         return;
      }

      if (file.type !=="application/pdf") {
		 showToast("Only PDF allowed", "error");
         return;
      }

      if (file.size > 5 * 1024 * 1024) {
		 showToast("Maximum 5MB allowed", "error");
         return;
      }

      let formData = new FormData(this);
	  $("#loader").show();
      $.ajax({
         url: baseUrl + "/upload-signed-pdf",
         type: "POST",
         data: formData,
         processData: false,
         contentType: false,
		 headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
         success: function (res) {
			$("#loader").hide();
            if (res.status) {
               setTimeout(function () {
					$('#uploadPdfModal').modal('hide');
					showToast(
						res.message,
						"success"
					);
					setTimeout(function(){
						location.reload();
					},1000);
				},2000);
            }
         },
         error: function (xhr) {
			 $("#loader").hide();
            $("#uploadError").html(xhr.responseJSON.message);
         }

      });


	});
	//end upload pdf
	
	