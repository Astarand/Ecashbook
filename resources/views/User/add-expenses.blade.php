@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/expenses-list') }}">Expenses Management</a></li>
                        <li class="breadcrumb-item" aria-current="page">Add New Expenses</li>
                    </ul>
                </div>
				<div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Add New Expenses</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row mb-4">
        <h3>Add New Expenses</h3>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="javascript:void(0);" method="POST" name="addExpenseFrm" id="addExpenseFrm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="eId" value="">
                @csrf
                <div class="row">

					<div class="mb-3 col-sm-4">
						<label class="form-label">Proprietorship Company</label>
						<select name="propId" id="propId" class="form-control">
							<option value="">{{ parentCompanyName() }}</option>
							@foreach($proprietorships as $company)
								<option value="{{ $company->id }}">
									{{ $company->comp_name }}
								</option>
							@endforeach
						</select>
					</div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Expense Category<span class="text-danger">*</span></label>
                        <select id="expense_cat" name="expense_cat" class="form-control" required>
                            <option value="">Select</option>
                            <option value="direct">Direct Expenses</option>
                            <option value="indirect">Indirect Expenses</option>
                        </select>
                    </div>

                    <!-- Direct Expenses Section -->
                    <div id="directExpensesSection" class="col-sm-4 mb-3" style="display: none;">
                        <label class="form-label">Direct Expenses Type<span class="text-danger">*</span></label>
                        <select id="directExpensesType" class="form-control" name="direct_expense_type">
                            <option value="">Select</option>
                            {{-- <option value="raw_material">Raw Material Costs</option>
                            <option value="direct_labor">Direct Labour</option>
                            <option value="manufacturing_supplies">Manufacturing Expenses</option>
                            <option value="factory_utilities">Factory Utilities</option>
                            <option value="freight_inwards">Freight / Carriage Inward</option>
                            <option value="job_outsourcing">Job Work / Outsourcing</option>
							<option value="packaging_costs">Packing Material</option>
                            <option value="Other">Other Direct Expenses</option> --}}

                            <option value="Raw Material Costs">Raw Material Costs</option>
                            <option value="Direct Labour">Direct Labour</option>
                            <option value="Manufacturing Expenses">Manufacturing Expenses</option>
                            <option value="Factory Utilities">Factory Utilities</option>
                            <option value="Freight / Carriage Inward">Freight / Carriage Inward</option>
                            <option value="Job Work / Outsourcing">Job Work / Outsourcing</option>
                            <option value="Packing Material">Packing Material</option>
                            <option value="Other">Other Direct Expenses</option>
                        </select>
                    </div>

                    <!-- Indirect Expenses Section -->
                    <div id="indirectExpensesSection" class="col-sm-4 mb-3" style="display: none;">
                        <label class="form-label">Indirect Expenses Type<span class="text-danger">*</span></label>
                        <select id="indirectExpensesType" name="indirect_expense_type" class="form-control">
                            <option value="">Select</option>

                            <option value="employee_benefits">Employee Expenses (Salary, Benefits)</option>

                            <option value="rent_expense">Rent Expense</option>
                            <option value="electricity_expense">Electricity Expense</option>
                            <option value="internet_communication">Internet & Communication</option>
                            <option value="office_expenses">Office Expenses</option>
                            <option value="printing_stationery">Printing & Stationery</option>
                            <option value="travel_conveyance">Travel & Conveyance</option>
                            <option value="repair_maintenance">Repair & Maintenance</option>
                            <option value="professional_fees">Professional Fees</option>
                            <option value="audit_fees">Audit Fees</option>
                            <option value="legal_charges">Legal Charges</option>
                            <option value="bank_charges">Bank Charges</option>
                            <option value="interest_expense">Interest Expense</option>
                            <option value="depreciation">Depreciation</option>
                            <option value="insurance_expense">Insurance Expense</option>
                            <option value="marketing_advertisement">Marketing & Advertisement</option>
                            <option value="freight_transport">Freight & Transport</option>
                            <option value="miscellaneous_expenses">Miscellaneous Expenses</option>
                        </select>
                    </div>

                    <div id="normalExpenseFields" class="row">
                        <div class="col-sm-4 mb-3" id="other_text_box">
                            <label class="form-label">Other<span class="text-danger">*</span></label>
                            <input type="text" id="other_exp" name="other_exp" class="form-control" placeholder="Enter Share Holder's Capital">
                        </div>

                    
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Date<span class="text-danger">*</span></label>
                            <input type="date" id="expense_date" name="expense_date" class="form-control" placeholder="Enter Share Holder's Capital">
                        </div>
                        
                        <div class="col-sm-4 mb-3" id="employeeDropdownDiv" style="display:none;">
                            <label class="form-label">Select Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-control">
                                <option value="">Select Employee</option>
                            </select>
                        </div>
                        <div class="col-sm-4 mb-3" id="employeeIdDiv" style="display:none;">
                            <label class="form-label">Employee ID</label>
                            <input type="text" id="employee_code" name="employee_code" class="form-control" readonly>
                        </div>
                        <div class="col-sm-4 mb-3" id="invoiceDiv">
                            <label class="form-label">Invoice / Reference no.</label>
                            <input type="text" id="exp_invno" name="exp_invno" class="form-control" placeholder="Enter Invoice / Referance No">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label" id="amountLabel">
                                Expense Amount<span class="text-danger">*</span>
                            </label>
                            <input type="number" id="expense_amt" name="expense_amt" class="form-control" placeholder="Enter Amount">
                        </div>
                        <div class="col-sm-4 mb-3" id="payment_status_div">
                            <label class="form-label">Payment Status</label>
                            <select id="payment_status" class="form-control">
                                <option value="">Select Payment Status</option>
                                <option value="full">Full</option>
                                <option value="advance">Advance</option>
                            </select>
                        </div>

                        <div class="col-sm-4 mb-3" id="advance_amount_div">
                            <label class="form-label">Advance Amount</label>
                            <input type="number" id="advance_amount" class="form-control" value="0">
                        </div>

                        <div class="col-sm-4 mb-3" id="balance_amount_div">
                            <label class="form-label">Balance Payable Amount</label>
                            <input type="number" id="balance_amount" class="form-control" readonly>
                        </div>

                        <div class="col-sm-4 mb-3" id="adjusted_now_div">
                            <label class="form-label" >Adjusted Now</label>
                            <input type="number" id="adjusted_now" class="form-control" readonly>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Payment Method<span class="text-danger">*</span></label>
                            <select class="form-select" name="mode_of_expense" id="mode_of_expense">
                                <option value="">Select</option>
                                <option value="NEFT">NEFT</option>
                                <option value="IMPS">IMPS</option>
                                <option value="RTGS">RTGS</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Demand Draft">Demand Draft</option>
                                <option value="Pay Order">Pay Order</option>
                                <option value="UPI">UPI</option>
                                <option value="Cash">Cash</option>
                            </select>
                        </div>

                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Party / Vendor Name<span class="text-danger">*</span></label>
                            <select name="vendor_id" id="vendor_id" class="form-control">
                                <option value="">Select Vendor</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" data-pan="{{ $vendor->vendor_pan }}">
                                        {{ $vendor->vendor_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-4 mb-3">
                            <label class="form-label">PAN Card of Party / Vendor</label>
                            <input type="text" id="vendor_pan" name="vendor_pan" class="form-control" placeholder="Enter PAN Number" maxlength="10">
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description of Expenses </label>
                            <textarea  name="pur_of_expense" id="pur_of_expense" class="form-control" placeholder="Description of Expenses" rows="4"></textarea>
                        </div>

                        <div class="row">
                            {{-- <div class="tds-container col-md-12">
                                <div class="mb-3">
                                    <div id="tds_dropdown">
                                        <label for="tds_percentage">TDS Percentage</label>
                                        <select name="tds_percentage" id="tds_percentage" class="form-control">
                                            @foreach ($purposes_of_tds as $purpose)
                                            <option value="{{ $purpose->tds_rate . '-' . $purpose->id }}">
                                                {{ $purpose->category }} ({{ $purpose->tds_rate }}%)
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="col-md-12 mb-3">
                                <label class="form-label">TDS Applicable</label>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input type="radio" name="tds_applicable" value="yes" id="tdsYes" >
                                            <label for="tdsYes">Yes</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input type="radio" name="tds_applicable" value="no" id="tdsNo" >
                                            <label for="tdsNo">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="tds_section"  style="display:none;">
                                    <div class="col-md-4">
                                        <label>TDS Section</label>
                                        <input type="text" name="tds_section" id="tds_section_field" class="form-control" readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label>TDS Rate (%)</label>
                                        <input type="number" name="tds_rate" id="tds_rate_field" class="form-control" readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label>TDS Amount</label>
                                        <input type="number" name="tds_amount" id="tds_amount_field" class="form-control" readonly>
                                    </div>
                                    <input type="hidden" id="tds_threshold_limit" value="">
                            </div>

                            <div class="gst-container col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">GST Applicable <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="card shadow-sm border-0 p-3 m-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gst_applicable" value="yes" id="gstYes" checked>
                                                    <label class="form-check-label" for="gstYes">Yes</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="card shadow-sm border-0 p-3 m-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="gst_applicable" value="no" type="radio" id="gstNo">
                                                    <label class="form-check-label" for="gstNo">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="gst_section">
                                    <div class="row mb-3">

                                        <div class="col-md-4">
                                            <label>GST Transaction Mode</label>
                                            <select class="form-select" name="gst_trans" id="gst_trans">
                                                <option value="">Select</option>
                                                <option value="intrastate">Intra State</option>
                                                <option value="interstate">Inter State</option>
                                                <option value="union">Union Territory</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label>GST Rate (%)</label>
                                            <input type="number" id="gst_rate" name="gst_rate" class="form-control" value="18">
                                        </div>

                                        <div class="col-md-4">
                                            <label>GST Allocation</label>
                                            <input type="text" id="gst_allocation" name="gst_allocation" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-4 mt-2">
                                            <label>Total GST Amount</label>
                                            <input type="number" id="total_gst" name="total_gst" class="form-control" readonly>
                                        </div>

                                        <!-- OPTIONAL breakup display -->
                                        {{-- <div class="col-md-12 mt-2" id="gst_breakup"></div> --}}

                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        

                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Approver Name</label>
                            <input type="text" name="approved_by" id="approved_by" class="form-control" placeholder="Enter Name ">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Designation </label>
                            <input type="text" name="designation" id="designation" class="form-control" placeholder="Enter Designation ">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Approve Date</label>
                            <input type="date" name="approved_date" id="approved_date" class="form-control" placeholder="Enter Amount">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Special Notes/Command</label>
                            <input type="text" name="spec_note" id="spec_note" class="form-control" placeholder="Special Notes/Command">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="imageUpload">Upload Image</label>
                                <input type="file" id="imageUpload" name="exp_inv_doc" class="form-control" accept="image/*">
                            </div>
                            <div id="imagePreview" class="mt-3" style="display: none; text-align: center;">
                                <a id="downloadLink" href="#" download="uploaded_image.jpg">
                                    <img id="uploadedImage" src="" alt="Preview Image" style="max-width: 100%; cursor: pointer; border: 1px solid #ddd; padding: 10px;">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div id="depreciationSection" class="row" style="display:none;">
                        <div class="col-md-4 mb-3">
                            <label>Depreciation value</label>
                            <input type="number" class="form-control" id="dep_value">
                        </div>
                    </div>



                    <div class="text-end btn-page mt-4">
                        {{-- <div class="btn customer-btn-cancel">Cancel</div>
                            <div class="btn btn-primary">Save Changes</div> --}}

                        <a href="{{ url('/expenses-list') }}" class="btn btn-danger customer-btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<script>
    //------ start Pan No auto fill logic based on vendor selection ------
    document.getElementById('vendor_id').addEventListener('change', function () {
        let selectedOption = this.options[this.selectedIndex];
        let pan = selectedOption.getAttribute('data-pan') || '';
        let panField = document.getElementById('vendor_pan');

        if (pan && pan.trim() !== '') {
            // PAN available → auto fill + readonly
            panField.value = pan;
            panField.readOnly = true;
        } else {
            // PAN not available → allow input
            panField.value = '';
            panField.readOnly = false;
            panField.focus();
        }
    });
    //------ end Pan No auto fill logic based on vendor selection ------

    //------ start TDS & GST toggle logic ------
    document.addEventListener("DOMContentLoaded", function () {

        const tdsYes = document.getElementById("tdsYes");
        const tdsNo = document.getElementById("tdsNo");
        const tdsSection = document.getElementById("tds_section");

        const gstYes = document.getElementById("gstYes");
        const gstNo = document.getElementById("gstNo");
        const gstSection = document.getElementById("gst_section");

        function toggleTDS() {
            if (tdsYes.checked) {
                tdsSection.style.display = "flex";
            } else {
                tdsSection.style.display = "none";
            }
        }

        function toggleGST() {
            if (gstYes.checked) {
                gstSection.style.display = "block";
            } else {
                gstSection.style.display = "none";
            }
        }

        tdsYes.addEventListener("change", toggleTDS);
        tdsNo.addEventListener("change", toggleTDS);

        gstYes.addEventListener("change", toggleGST);
        gstNo.addEventListener("change", toggleGST);

        // Initial load
        toggleTDS();
        toggleGST();
    });
    //------ end TDS & GST toggle logic ------

    //------ start GST calculation logic ------
    document.addEventListener("DOMContentLoaded", function () {

        const amount = document.getElementById("expense_amt");
        const gstRate = document.getElementById("gst_rate");
        const gstTrans = document.getElementById("gst_trans");

        const gstAllocation = document.getElementById("gst_allocation");
        const totalGST = document.getElementById("total_gst");
        const gstBreakup = document.getElementById("gst_breakup");

        function calculateGST() {

            let amt = parseFloat(amount.value) || 0;
            let rate = parseFloat(gstRate.value) || 0;
            let mode = gstTrans.value;

            if (!amt || !rate || !mode) {
                gstAllocation.value = "";
                totalGST.value = "";
                gstBreakup.innerHTML = "";
                return;
            }

            let gstAmt = (amt * rate) / 100;
            totalGST.value = gstAmt.toFixed(2);

            let halfRate = rate / 2;
            let halfAmt = gstAmt / 2;

            // RESET
            let allocationText = "";
            let breakupHTML = "";

            if (mode === "intrastate") {
                allocationText = `CGST ${halfRate}% + SGST ${halfRate}%`;

                breakupHTML = `
                    <div class="alert alert-info p-2">
                        CGST: ₹${halfAmt.toFixed(2)} (${halfRate}%) |
                        SGST: ₹${halfAmt.toFixed(2)} (${halfRate}%)
                    </div>
                `;
            }

            else if (mode === "interstate") {
                allocationText = `IGST ${rate}%`;

                breakupHTML = `
                    <div class="alert alert-info p-2">
                        IGST: ₹${gstAmt.toFixed(2)} (${rate}%)
                    </div>
                `;
            }

            else if (mode === "union") {
                allocationText = `UTGST ${rate}%`;

                breakupHTML = `
                    <div class="alert alert-info p-2">
                        UTGST: ₹${gstAmt.toFixed(2)} (${rate}%)
                    </div>
                `;
            }

            gstAllocation.value = allocationText;
            gstBreakup.innerHTML = breakupHTML;
        }

        // Events
        amount.addEventListener("input", calculateGST);
        gstRate.addEventListener("input", calculateGST);
        gstTrans.addEventListener("change", calculateGST);

    });
    //------ end GST calculation logic ------

    //------ start TDS auto fetch & calculation logic ------
    document.addEventListener("DOMContentLoaded", function () {

        const expenseCat = document.getElementById("expense_cat");
        const directType = document.getElementById("directExpensesType");
        const indirectType = document.getElementById("indirectExpensesType");

        const amountField = document.getElementById("expense_amt");

        const tdsYes = document.getElementById("tdsYes");
        const tdsNo = document.getElementById("tdsNo");

        const tdsSection = document.getElementById("tds_section");

        const tdsSectionField = document.getElementById("tds_section_field");
        const tdsRateField = document.getElementById("tds_rate_field");
        const tdsAmountField = document.getElementById("tds_amount_field");
        const thresholdInput = document.getElementById("tds_threshold_limit");

        let currentRule = null;

        // ✅ Get selected category
        function getSelectedCategory() {
            if (expenseCat.value === "direct") {
                return directType.value;
            } else if (expenseCat.value === "indirect") {
                return indirectType.value;
            }
            return null;
        }

        // ✅ Fetch rule from DB
        function fetchTdsRule() {
            const category = getSelectedCategory();

            // If nothing selected → reset
            if (!category) {
                resetTDS();
                return;
            }

            fetch("/get-tds-rule", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ category: category })
            })
            .then(res => res.json())
            .then(data => {
                
                // ✅ If rule found
                if (data && data.tds_rate !== null) {
                    currentRule = data;

                    tdsSectionField.value = data.tds_section || "";
                    tdsRateField.value = data.tds_rate || "";
                    thresholdInput.value = data.threshold_limit || "";

                    calculateTDS();

                } else {
                    // ❌ No rule → force reset
                    resetTDS();
                }
            })
            .catch(() => {
                // ❌ API error → also reset
                resetTDS();
            });
        }

        // ✅ Calculate TDS
        function calculateTDS() {
            const amount = parseFloat(amountField.value) || 0;

            // No rule → no calculation
            if (!currentRule || !currentRule.tds_rate) {
                resetTDS();
                return;
            }

            const threshold = parseFloat(currentRule.threshold_limit) || 0;
            const rate = parseFloat(currentRule.tds_rate) || 0;

            if (amount > threshold) {
                // ✅ Applicable
                tdsYes.checked = true;
                tdsNo.checked = false;
                tdsSection.style.display = "flex";

                const tdsAmount = (amount * rate) / 100;
                tdsAmountField.value = tdsAmount.toFixed(2);

            } else {
                // ❌ Not applicable
                tdsNo.checked = true;
                tdsYes.checked = false;
                tdsSection.style.display = "none";
                tdsAmountField.value = "";
            }
        }

        // ✅ Reset everything
        function resetTDS() {
            currentRule = null;

            tdsSectionField.value = "";
            tdsRateField.value = "";
            tdsAmountField.value = "";
            thresholdInput.value = "";

            tdsNo.checked = true;
            tdsYes.checked = false;

            tdsSection.style.display = "none";
        }

        // ✅ EVENTS
        expenseCat.addEventListener("change", fetchTdsRule);
        directType.addEventListener("change", fetchTdsRule);
        indirectType.addEventListener("change", fetchTdsRule);

        amountField.addEventListener("input", calculateTDS);
    });

    //------ end TDS auto fetch & calculation logic ------


    document.getElementById("expense_cat").addEventListener("change", function() {
        const otherExpenseCategory = document.getElementById("otherExpenseCategory");
        if (this.value === "Other") {
            otherExpenseCategory.style.display = "block";
        } else {
            otherExpenseCategory.style.display = "none";
        }
    });
    document.getElementById("imageUpload").addEventListener("change", function(event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById("imagePreview");
        const uploadedImage = document.getElementById("uploadedImage");
        const downloadLink = document.getElementById("downloadLink");

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                // Set the image source to the uploaded file
                uploadedImage.src = e.target.result;

                // Set the download link href to the image data
                downloadLink.href = e.target.result;

                // Update the file name in the download link (optional)
                downloadLink.download = file.name;

                // Show the preview container
                previewContainer.style.display = "block";
            };

            reader.readAsDataURL(file);
        } else {
            // Hide the preview container if no file is selected
            previewContainer.style.display = "none";
        }
    });
    document.addEventListener("DOMContentLoaded", function() {
            // --- TDS Auto Fetch & Calculation Logic ---
            const expenseCat = document.getElementById("expense_cat");
            const directType = document.getElementById("directExpensesType");
            const tdsSectionDiv = document.getElementById("tds_section");
            const tdsYesRadio = document.getElementById("tdsYes");
            const tdsNoRadio = document.getElementById("tdsNo");
            const tdsSectionField = document.getElementById("tds_section_field");
            const tdsRateField = document.getElementById("tds_rate_field");
            const tdsAmountField = document.getElementById("tds_amount_field");
            const tdsThresholdField = document.getElementById("tds_threshold_limit");
            const expenseAmtInput = document.getElementById("expense_amt");

            function resetTdsFields() {
                tdsSectionField.value = '';
                tdsRateField.value = '';
                tdsAmountField.value = '';
                tdsThresholdField.value = '';
                tdsSectionDiv.style.display = 'none';
                tdsYesRadio.checked = false;
                tdsNoRadio.checked = true;
            }

            function fetchTdsRuleAndFill() {
                resetTdsFields();
                if (expenseCat.value === 'direct' && directType.value) {
                    // AJAX to fetch TDS rule for this category
                    $.ajax({
                        url: '/get-tds-rule',
                        type: 'POST',
                        data: {
                            module: 'Expenses',
                            category: directType.value,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            if (res && res.tds_section) {
                                tdsSectionField.value = res.tds_section;
                                tdsRateField.value = res.tds_rate;
                                tdsThresholdField.value = res.threshold_limit;
                                // If amount already entered, trigger calculation
                                if (expenseAmtInput.value) {
                                    calculateTdsAmount();
                                }
                            }
                        }
                    });
                }
            }

            function calculateTdsAmount() {
                let amt = parseFloat(expenseAmtInput.value) || 0;
                let rate = parseFloat(tdsRateField.value) || 0;
                let threshold = parseFloat(tdsThresholdField.value) || 0;
                if (amt > 0 && rate > 0 && amt > threshold) {
                    let tdsAmt = (amt * rate) / 100;
                    tdsAmountField.value = tdsAmt.toFixed(2);
                    tdsSectionDiv.style.display = 'flex';
                    tdsYesRadio.checked = true;
                    tdsNoRadio.checked = false;
                } else {
                    tdsAmountField.value = '';
                    tdsSectionDiv.style.display = 'none';
                    tdsYesRadio.checked = false;
                    tdsNoRadio.checked = true;
                }
            }

            // Listen for direct expense type change
            directType.addEventListener('change', fetchTdsRuleAndFill);
            expenseCat.addEventListener('change', function() {
                if (expenseCat.value !== 'direct') {
                    resetTdsFields();
                }
            });
            // Listen for amount input
            expenseAmtInput.addEventListener('input', function() {
                if (expenseCat.value === 'direct' && directType.value) {
                    calculateTdsAmount();
                }
            });
            // --- END TDS Auto Fetch & Calculation Logic ---
        const expenseCategory = document.getElementById("expense_cat");
        const directExpensesSection = document.getElementById("directExpensesSection");
        const indirectExpensesSection = document.getElementById("indirectExpensesSection");

        expenseCategory.addEventListener("change", function() {
            const value = this.value;

            directExpensesSection.style.display = (value === "direct") ? "block" : "none";
            indirectExpensesSection.style.display = (value === "indirect") ? "block" : "none";
        });

        $("form#addExpenseFrm").bind("submit", function() {

            // $("#expenseLoader").show();
            var eId = $("#eId").val();
            if (eId == "") {
                var surl = "/save_expenses";
            } else {
                var surl = "/update_expenses";
            }
            //var expensesData = $('form#addExpenseFrm').serialize();
			let propId = $("form#addExpenseFrm #propId option:selected").val();
            let expense_cat = $("form#addExpenseFrm #expense_cat option:selected").val();

            let expense_type = "";
            if (expense_cat === "direct") {
                expense_type = $("form#addExpenseFrm #directExpensesType option:selected").val();
            } else if (expense_cat === "indirect") {
                expense_type = $("form#addExpenseFrm #indirectExpensesType option:selected").val();
            }
			
			if (!expense_type) {
				showToast("Please select expense type", "error");
				return false;
			}

            let other = $("form#addExpenseFrm #other_exp").val();
            let expense_date = $("form#addExpenseFrm #expense_date").val();
            let exp_invno = $("form#addExpenseFrm #exp_invno").val();
            let expense_amt = parseFloat($("form#addExpenseFrm #expense_amt").val()) || 0;
            let mode_of_expense = $("form#addExpenseFrm #mode_of_expense").val();
            let pur_of_expense = $("form#addExpenseFrm #pur_of_expense").val();
            let approved_by = $("form#addExpenseFrm #approved_by").val();
            let designation = $("form#addExpenseFrm #designation").val();
            let approved_date = $("form#addExpenseFrm #approved_date").val();
            let spec_note = $("form#addExpenseFrm #spec_note").val();
			let employee_id = $("#employee_id option:selected").val();
			if (expense_cat === "indirect" && expense_type === "employee_benefits" && !employee_id) {
				showToast("Please select employee", "error");
				return false;
			}


            let exp_inv_doc = $("form#addExpenseFrm #imageUpload").prop("files")[0];
			let tds_percentage = $("form#addExpenseFrm #tds_percentage option:selected").val();

            let vendor_id = $("#vendor_id option:selected").val();
            let vendor_pan = $("#vendor_pan").val();
            let payment_status = $("#payment_status").val();

            // TDS Applicable
            let tds_applicable = $("input[name='tds_applicable']:checked").val() || "no";

            // TDS Fields
            let tds_section = $("#tds_section_field").val();
            let tds_rate = parseFloat($("#tds_rate_field").val()) || 0;
            let tds_amount = parseFloat($("#tds_amount_field").val()) || 0;
            let tds_threshold_limit = $("#tds_threshold_limit").val();

            // GST Applicable
            let gst_applicable = $("input[name='gst_applicable']:checked").val();

            // GST Fields
            let gst_trans = $("#gst_trans").val();
            let gst_rate = parseFloat($("#gst_rate").val()) || 0;
            let gst_allocation = $("#gst_allocation").val();
            let total_gst = parseFloat($("#total_gst").val()) || 0;
            
            let advance_amount = parseFloat($("#advance_amount").val()) || 0;
            let balance_amount = parseFloat($("#balance_amount").val()) || 0;
            let adjusted_now = parseFloat($("#adjusted_now").val()) || 0;
            let employee_code= $("#employee_code").val() || '';

            // Depreciation Fields
            // let dep_start_date = $("#dep_start_date").val();
            // let dep_frequency  = $("#dep_frequency").val();
            // let useful_life    = $("#useful_life").val();
            // let dep_method     = $("#dep_method").val();
            let dep_value      = $("#dep_value").val();
            // let residual_value = $("#residual_value").val();


            let expensesData = new FormData();
            expensesData.append("expense_amt", expense_amt);
			expensesData.append('tds_percentage', tds_percentage);

            expensesData.append("id", eId);
            expensesData.append("propId", propId);
            expensesData.append("expense_cat", expense_cat);
            expensesData.append("expense_type", expense_type);
            expensesData.append("expense_date", expense_date);
            expensesData.append("exp_invno", exp_invno);

            expensesData.append("mode_of_expense", mode_of_expense);
            expensesData.append("pur_of_expense", pur_of_expense);
            expensesData.append("approved_by", approved_by);
            expensesData.append("designation", designation);
            expensesData.append("approved_date", approved_date);
            expensesData.append("spec_note", spec_note);
			expensesData.append("employee_id", employee_id);
            expensesData.append("exp_inv_doc", exp_inv_doc);
            expensesData.append("other", other);

            expensesData.append("vendor_id", vendor_id);
            expensesData.append("vendor_pan", vendor_pan);
            expensesData.append("payment_status", payment_status);

            // TDS Append
            expensesData.append("tds_applicable", tds_applicable);
            expensesData.append("tds_section", tds_section);
            expensesData.append("tds_rate", tds_rate);
            expensesData.append("tds_amount", tds_amount);
            expensesData.append("tds_threshold_limit", tds_threshold_limit);

            // GST Append
            expensesData.append("gst_applicable", gst_applicable);
            expensesData.append("gst_trans", gst_trans);
            expensesData.append("gst_rate", gst_rate);
            expensesData.append("gst_allocation", gst_allocation);
            expensesData.append("total_gst", total_gst);
            
            expensesData.append("advance_amount", advance_amount);
            expensesData.append("balance_amount", balance_amount);
            expensesData.append("adjusted_now", adjusted_now);
            expensesData.append("employee_code", employee_code);

            // Depreciation Append
            // ✅ If Depreciation selected → send these
            if (expense_cat === "indirect" && expense_type === "depreciation") {

                // 🔒 Optional validation
                if ( !dep_value) {
                    showToast("Please fill all depreciation fields", "error");
                    return false;
                }

                // expensesData.append("dep_start_date", dep_start_date);
                // expensesData.append("dep_frequency", dep_frequency);
                // expensesData.append("useful_life", useful_life);
                // expensesData.append("dep_method", dep_method);
                expensesData.append("dep_value", dep_value);
                // expensesData.append("residual_value", residual_value);

            } else {

                // 🔁 Send empty if not depreciation (optional but clean)
                // expensesData.append("dep_start_date", "");
                // expensesData.append("dep_frequency", "");
                // expensesData.append("useful_life", "");
                // expensesData.append("dep_method", "");
                expensesData.append("dep_value", "");
                // expensesData.append("residual_value", "");
            }


            $("#loader").show();
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: surl,
                type: "POST",
                data: expensesData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $("#loader").hide();
                    //console.log(response);

                    // $("#expenseLoader").hide();
                    if (response.class == "succ") {
                        // $("#addExpenseFrm .message-container").html(
                        //     '<div class="' +
                        //         response.class +
                        //         '">' +
                        //         response.message +
                        //         "</div>"
                        // );
                        // window.location.href = response.redirect;

                        showToast(response.message, "success");
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 2000);
                    } else {
                        $.each(response, function(idx, obj) {
                            // $("#addExpenseFrm .message-container").html(
                            //     '<div class="err">' + obj + "</div>"
                            // );
                            showToast("Error: while add expenses", "error");
                        });
                    }
                },
            });
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const directExpensesType = document.getElementById("directExpensesType");
        const indirectExpensesType = document.getElementById("indirectExpensesType");
        const otherTextBox = document.getElementById("other_text_box");

        function toggleOtherTextBox() {
            if (directExpensesType.value === "Other" || indirectExpensesType.value === "Other") {
                otherTextBox.style.display = "block";
            } else {
                otherTextBox.style.display = "none";
            }
        }

        directExpensesType.addEventListener("change", toggleOtherTextBox);
        indirectExpensesType.addEventListener("change", toggleOtherTextBox);

        // Initial check on page load
        toggleOtherTextBox();
    });

        // --- END: TDS Auto Fetch & Calculation Logic ---
	
	//Employee dropdown logic
	$(document).ready(function () {

		function loadEmployees() {
			$.ajax({
				url: "/get-employees",
				type: "GET",
				success: function (res) {
					let dropdown = $("#employee_id");
					dropdown.empty();
					dropdown.append('<option value="">Select Employee</option>');

					$.each(res, function (key, emp) {
                        dropdown.append(
                            `<option value="${emp.id}" data-code="${emp.employee_code}">
                                ${emp.name}
                            </option>`
                        );
                    });
				}
			});
		}

		function handleEmployeeDropdown() {
			let category = $("#expense_cat").val();
			let type = $("#indirectExpensesType").val();

			// ✅ Direct → always hide
			if (category === "direct") {
				$("#employeeDropdownDiv").hide();
				$("#employee_id").val('');
				return;
			}

			// ✅ Indirect + employee_benefits → show
			if (category === "indirect" && type === "employee_benefits") {
				$("#employeeDropdownDiv").show();
				loadEmployees();
			} else {
				$("#employeeDropdownDiv").hide();
				$("#employee_id").val('');
			}
		}

		// 🔥 Trigger on category change
		$("#expense_cat").change(function () {
			handleEmployeeDropdown();
		});

		// 🔥 Trigger on indirect type change
		$("#indirectExpensesType").change(function () {
			handleEmployeeDropdown();
		});

		// 🔥 Initial load (important)
		handleEmployeeDropdown();
	});

    // Employee Expense UI Logic
    function handleEmployeeExpenseUI() {

        let category = $("#expense_cat").val();
        let type = $("#indirectExpensesType").val();

        let tdsSection = $("#tds_section").closest(".row");
        let gstSection = $(".gst-container");
        let vendorField = $("#vendor_id").closest(".col-sm-4");
        let panField = $("#vendor_pan").closest(".col-sm-4");
        let paymentField = $("#payment_status_div").closest(".col-sm-4");

        let amountLabel = $("#amountLabel");

        if (category === "indirect" && type === "employee_benefits") {

            // ✅ Change label
            amountLabel.text("Advance Amount *");

            // ✅ Hide unwanted sections
            tdsSection.hide();
            gstSection.hide();
            vendorField.hide();
            panField.hide();

            // ✅ Disable payment related fields
            // $("#payment_status").prop("disabled", true);
            // $("#advance_amount").prop("disabled", true);
            // $("#balance_amount").prop("disabled", true);
            // $("#adjusted_now").prop("disabled", true);

            $("#payment_status_div").hide();
            $("#advance_amount_div").hide();
            $("#balance_amount_div").hide();
            $("#adjusted_now_div").hide();

            // ✅ Reset values
            $("#payment_status_div").val("");
            $("#advance_amount_div").val(0);    
            $("#balance_amount_div").val(0);
            $("#adjusted_now_div").val(0);

        } else {

            // 🔁 Reset back to normal
            amountLabel.text("Expense Amount *");

            tdsSection.show();
            gstSection.show();
            vendorField.show();
            panField.show();

            // $("#payment_status").prop("disabled", false);
            // $("#advance_amount").prop("disabled", false);
            // $("#balance_amount").prop("disabled", true);
            // $("#adjusted_now").prop("disabled", true);

            $("#payment_status_div").show();
            $("#advance_amount_div").show();
            $("#balance_amount_div").show();
            $("#adjusted_now_div").show();
        }
    }

    $("#expense_cat").change(function () {
    handleEmployeeExpenseUI();
    });

    $("#indirectExpensesType").change(function () {
        handleEmployeeExpenseUI();
    });

    // Run on page load
    handleEmployeeExpenseUI();

    // Payment calculation logic
    function handlePaymentCalculation() {

    let total   = parseFloat($("#expense_amt").val()) || 0;
    let status  = $("#payment_status").val();
    let advance = parseFloat($("#advance_amount").val()) || 0;

    if (status === "full") {

        // 🔹 Hide Advance & Balance
        $("#advance_amount_div").hide();
        $("#balance_amount_div").hide();

        // 🔹 Show Adjusted Now
        $("#adjusted_now_div").show();

        // 🔹 Set values
        $("#advance_amount").val(0);
        $("#balance_amount").val(0);
        $("#adjusted_now").val(total.toFixed(2));

    } else if (status === "advance") {

        // 🔹 Show Advance & Balance
        $("#advance_amount_div").show();
        $("#balance_amount_div").show();

        // 🔹 Hide Adjusted Now
        $("#adjusted_now_div").hide();

        // 🔹 Prevent advance > total
        if (advance > total) {
            advance = total;
            $("#advance_amount").val(total);
        }

        // 🔹 Calculate balance
        let balance = total - advance;

        $("#balance_amount").val(balance.toFixed(2));
        $("#adjusted_now").val("");
    }
}

    $("#payment_status").change(function () {
        handlePaymentCalculation();
    });

    $("#expense_amt").on("input", function () {
        handlePaymentCalculation();
    });

    $("#advance_amount").on("input", function () {
        handlePaymentCalculation();
    });

    // Initial load
    handlePaymentCalculation();


    function handleEmployeeInvoiceToggle() {

        let category = $("#expense_cat").val();
        let type = $("#indirectExpensesType").val();

        if (category === "indirect" && type === "employee_benefits") {

            $("#invoiceDiv").hide();          // ❌ hide invoice
            $("#employeeIdDiv").show();       // ✅ show employee id

        } else {

            $("#invoiceDiv").show();          // ✅ show invoice
            $("#employeeIdDiv").hide();       // ❌ hide employee id
            $("#employee_code").val('');
        }
    }

    $("#employee_id").change(function () {

        let code = $(this).find(':selected').data('code') || '';
        $("#employee_code").val(code);

    });
        $("#expense_cat, #indirectExpensesType").change(function () {
        handleEmployeeInvoiceToggle();
    });

    // initial load
    handleEmployeeInvoiceToggle();

    // Depreciation UI Logic
    // Depreciation UI Logic (FINAL FIX)
    function handleDepreciationUI() {

        let category = $("#expense_cat").val();
        let type = $("#indirectExpensesType").val();

        if (category === "indirect" && type === "depreciation") {

            // ✅ Hide ALL normal expense fields safely
            $("#normalExpenseFields").css("display", "none");

            // ✅ Hide "Other" textbox (extra safety)
            $("#other_text_box").hide();

            // ✅ Show depreciation section
            $("#depreciationSection").css("display", "flex");

            // ✅ Change label
            $("#amountLabel").text("Asset Value *");

        } else {

            // 🔁 Show normal fields again
            $("#normalExpenseFields").css("display", "flex");

            // 🔁 Hide depreciation section
            $("#depreciationSection").css("display", "none");

            // 🔁 Reset label
            // $("#amountLabel").text("Expense Amount *");
        }
    }

    $("#expense_cat, #indirectExpensesType").on("change", function () {

        // Run after other UI updates (important fix)
        setTimeout(function () {
            handleDepreciationUI();
        }, 50);
    });

    // Run on page load
    $(document).ready(function () {
        handleDepreciationUI();
    });

    
    document.addEventListener("DOMContentLoaded", function () {

        const expenseCat = document.getElementById("expense_cat");
        const indirectType = document.getElementById("indirectExpensesType");
        const otherBox = document.getElementById("other_text_box");

        function toggleOtherField() {

            // Condition: Indirect + Miscellaneous
            if (expenseCat.value === "indirect" && indirectType.value === "miscellaneous_expenses") {
                otherBox.style.display = "block";
            } else {
                otherBox.style.display = "none";
            }
        }

        // Run on change
        expenseCat.addEventListener("change", toggleOtherField);
        indirectType.addEventListener("change", toggleOtherField);

        // Run on page load (important for edit mode)
        toggleOtherField();
    });

</script>
@endsection