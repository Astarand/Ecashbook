@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/expenses-list') }}">Expenses Management</a></li>
                        <li class="breadcrumb-item" aria-current="page">Edit Expenses</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-edit-expenses-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
				<div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Edit Expenses</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="card">
        <div class="card-body">
            <form action="javascript:void(0);" method="POST" enctype="multipart/form-data" name="addExpenseFrm" id="addExpenseFrm">
                <input type="hidden" name="id" id="eId" value="{{ $expenses->id }}">
                @csrf
                <div class="row">

                    <div class="mb-3 col-sm-4">
                        <label class="form-label">Proprietorship Company</label>
                        <select name="propId" id="propId" class="form-control">
                            <option value="">{{ parentCompanyName() }}</option>
                            @foreach($proprietorships as $company)
                                <option value="{{ $company->id }}" {{ ($expenses->propId == $company->id) ? 'selected' : '' }}>
                                    {{ $company->comp_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Expense Category<span class="text-danger">*</span></label>
                        <select id="expense_cat" name="expense_cat" class="form-control" required>
                            <option value="">Select</option>
                            <option value="direct" {{ ($expenses->expense_cat == 'direct') ? 'selected' : '' }}>Direct Expenses</option>
                            <option value="indirect" {{ ($expenses->expense_cat == 'indirect') ? 'selected' : '' }}>Indirect Expenses</option>
                            
                        </select>
                    </div>

                    <!-- Direct Expenses Section -->
                    <div id="directExpensesSection" class="col-sm-4 mb-3" style="display: none;">
                        <label class="form-label">Direct Expenses Type<span class="text-danger">*</span></label>                        
                        <select id="directExpensesType" class="form-control" name="direct_expense_type">
                            <option value="">Select</option>

                        </select>
						<small id="directExpenseTypeInfo" class="text-primary fw-bold mt-1 d-block"></small>
                    </div>

                    <!-- Indirect Expenses Section -->
                    <div id="indirectExpensesSection" class="col-sm-4 mb-3" style="display: none;">
                        <label class="form-label">Indirect Expenses Type<span class="text-danger">*</span></label>
                        <select id="indirectExpensesType" name="indirect_expense_type" class="form-control">
                            <option value="">Select</option>
                            
                        </select>
						<small id="indirectExpenseTypeInfo" class="text-primary fw-bold mt-1 d-block"></small>
                    </div>
					
				
					
					<input type="hidden" id="selected_direct_expense" value="{{ $expenses->expense_type }}">
					<input type="hidden" id="selected_indirect_expense" value="{{ $expenses->expense_type }}">

                    <div id="normalExpenseFields" class="row">

                        <div class="col-sm-4 mb-3" id="other_text_box">
                            <label class="form-label">Other<span class="text-danger">*</span></label>
                            <input type="text" id="other_exp" name="other_exp" value="{{ $expenses->other_expenses_details }}" class="form-control" placeholder="Enter Other Expense Details">
                        </div>

                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Date<span class="text-danger">*</span></label>
                            <input type="date" id="expense_date" name="expense_date" value="{{ $expenses->expense_date }}" class="form-control" required>
                        </div>

                        <div class="col-sm-4 mb-3" id="employeeDropdownDiv" style="display:none;">
                            <label class="form-label">Select Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-control">
                                <option value="">Select Employee</option>
                            </select>
                        </div>
                        <div class="col-sm-4 mb-3" id="employeeIdDiv" style="display:none;">
                            <label class="form-label">Employee ID</label>
                            <input type="text" id="employee_code" name="employee_code" value="{{ $expenses->employee_code ?? '' }}" class="form-control" readonly>
                        </div>

                        <div class="col-sm-4 mb-3" id="invoiceDiv">
                            <label class="form-label">Invoice / Reference no.</label>
                            <input type="text" id="exp_invno" name="exp_invno" value="{{ $expenses->exp_invno }}" class="form-control" placeholder="Enter Invoice / Reference No">
                        </div>

                        <div class="col-sm-4 mb-3">
                            <label class="form-label" id="amountLabel">Expense Amount<span class="text-danger">*</span></label>
                            <input type="number" id="expense_amt" name="expense_amt" value="{{ $expenses->expense_amt }}" class="form-control" placeholder="Enter Amount" required>
                        </div>

                        <div class="col-sm-4 mb-3" id="payment_status_div">
                            <label class="form-label">Payment Status <span class="text-danger">*</span></label>
                            <select id="payment_status" name="payment_status" class="form-control">
                                <option value="">Select Payment Status</option>
                                <option value="full" {{ $expenses->payment_status == 'full' ? 'selected' : '' }}>Full</option>
                                <option value="advance" {{ $expenses->payment_status == 'advance' ? 'selected' : '' }}>Advance</option>
                                <option value="due" {{ $expenses->payment_status == 'due' ? 'selected' : '' }}>Due</option>
                            </select>
                        </div>

                        <div class="col-sm-4 mb-3" id="advance_amount_div">
                            <label class="form-label">Advance Amount</label>
                            <input type="number" id="advance_amount" name="advance_amount" class="form-control" value="{{ $expenses->advance_amount ?? 0 }}">
                        </div>

                        <div class="col-sm-4 mb-3" id="balance_amount_div">
                            <label class="form-label">Balance Payable Amount</label>
                            <input type="number" id="balance_amount" name="balance_amount" class="form-control" value="{{ $expenses->balance_amount ?? 0 }}" readonly>
                        </div>

                        <div class="col-sm-4 mb-3" id="adjusted_now_div">
                            <label class="form-label">Adjusted Now</label>
                            <input type="number" id="adjusted_now" name="adjusted_now" class="form-control" value="{{ $expenses->adjusted_now ?? 0 }}" readonly>
                        </div>

                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Payment Method</label>
                            <select class="form-select" name="mode_of_expense" id="mode_of_expense" required>
                                <option value="">Select</option>
                                <option value="NEFT" {{ ($expenses->mode_of_expense == 'NEFT') ? 'selected' : '' }}>NEFT</option>
                                <option value="IMPS" {{ ($expenses->mode_of_expense == 'IMPS') ? 'selected' : '' }}>IMPS</option>
                                <option value="RTGS" {{ ($expenses->mode_of_expense == 'RTGS') ? 'selected' : '' }}>RTGS</option>
                                <option value="Cheque" {{ ($expenses->mode_of_expense == 'Cheque') ? 'selected' : '' }}>Cheque</option>
                                <option value="Demand Draft" {{ ($expenses->mode_of_expense == 'Demand Draft') ? 'selected' : '' }}>Demand Draft</option>
                                <option value="Pay Order" {{ ($expenses->mode_of_expense == 'Pay Order') ? 'selected' : '' }}>Pay Order</option>
                                <option value="UPI" {{ ($expenses->mode_of_expense == 'UPI') ? 'selected' : '' }}>UPI</option>
                                <option value="Cash" {{ ($expenses->mode_of_expense == 'Cash') ? 'selected' : '' }}>Cash</option>
                            </select>
                        </div>
						
						<div class="col-sm-4 mb-3 d-flex flex-column">
							<label class="form-label">&nbsp;</label>
							<a href="javascript:void(0);"
								class="btn btn-primary btn-sm paymentModalBtn"
								data-id="{{ $expenses->id ?? '' }}"
								data-type="Expense"
								style="width:90px;">
								Payment
							</a>
						</div>
						
						<div class="row mt-2 tax-info-section" style="{{ !empty($expenses->tax_treatment ?? '') ? '' : 'display:none;' }}">

							<div class="col-md-4">
								<label class="form-label">Tax Treatment</label>
								<input type="text"
									   id="tax_treatment"
									   name="tax_treatment"
									   class="form-control"
									   value="{{ $expenses->tax_treatment ?? '' }}"
									   readonly>
							</div>

							<div class="col-md-4">
								<label class="form-label">Allowed Ratio (%)</label>
								<input type="number"
									   id="allowed_ratio"
									   name="allowed_ratio"
									   class="form-control"
									   value="{{ $expenses->allowed_ratio ?? '' }}">
							</div>

							<div class="col-md-4">
								<label class="form-label">Deduction Amount</label>
								<input type="text"
									   id="rebate_amt"
									   name="rebate_amt"
									   class="form-control"
									   value="{{ $expenses->rebate_amt ?? '' }}"
									   readonly>
							</div>
						</div>

						<input type="hidden" id="old_allowed_ratio"
							   value="{{ $expenses->allowed_ratio ?? '' }}">

						<input type="hidden" id="old_rebate_amt"
							   value="{{ $expenses->rebate_amt ?? '' }}">

                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Party / Vendor Name<span class="text-danger">*</span></label>
                            <select name="vendor_id" id="vendor_id" class="form-control">
                                <option value="">Select Vendor</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}"
                                        data-pan="{{ $vendor->vendor_pan }}"
                                        {{ ($expenses->vendor_id == $vendor->id) ? 'selected' : '' }}>
                                        {{ $vendor->vendor_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-4 mb-3">
                            <label class="form-label">PAN Card of Party / Vendor</label>
                            <input type="text" readonly id="vendor_pan" name="vendor_pan" value="{{ $expenses->vendor_pan }}" class="form-control" placeholder="Enter PAN Number" maxlength="10">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description of Expenses</label>
                            <textarea name="pur_of_expense" id="pur_of_expense" class="form-control" placeholder="Description of Expenses" rows="4">{{ $expenses->pur_of_expense }}</textarea>
                        </div>

                        <div class="row">

                            <div class="col-md-12 mb-3">
                                <label class="form-label">TDS Applicable</label>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-check">
                                            <input type="radio" name="tds_applicable" value="yes" id="tdsYes" {{ ($expenses->tds_applicable == 'yes') ? 'checked' : '' }}>
                                            <label for="tdsYes">Yes</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-check">
                                            <input type="radio" name="tds_applicable" value="no" id="tdsNo" {{ ($expenses->tds_applicable != 'yes') ? 'checked' : '' }}>
                                            <label for="tdsNo">No</label>
                                        </div>
                                    </div>
									<div class="col-4">
										<label class="form-label fw-bold">Threshold Type <span class="text-danger">*</span></label>
										<select id="threshold_type" name="threshold_type"  class="form-control">
											<option value="">Select Threshold Type</option>
											<option value="NA" {{ $expenses->threshold_type == 'NA' ? 'selected' : '' }}>N/A</option>
											<option value="Single" {{ $expenses->threshold_type == 'Single' ? 'selected' : '' }}>Single</option>
											<option value="Annual"{{ $expenses->threshold_type == 'Annual' ? 'selected' : '' }}>Annual</option>
										</select>
									</div>
                                </div>
                            </div>

                            <div id="tds_section" style="display:none;">
                                <div class="col-md-4">
                                    <label>TDS Section</label>
                                    <input type="text" name="tds_section" id="tds_section_field" value="{{ $expenses->tds_section }}" class="form-control" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>TDS Rate (%)</label>
                                    <input type="number" name="tds_rate" id="tds_rate_field" value="{{ $expenses->tds_rate }}" class="form-control" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>TDS Amount</label>
                                    <input type="number" name="tds_amount" id="tds_amount_field" value="{{ $expenses->tds_amount }}" class="form-control" readonly>
                                </div>
                                <input type="hidden" id="tds_threshold_limit" value="{{ $expenses->tds_threshold_limit ?? '' }}">
                            </div>

                            <div class="gst-container1 col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">GST Applicable <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="card shadow-sm border-0 p-3 m-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gst_applicable" value="yes" id="gstYes" {{ ($expenses->gst_applicable == 'yes') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="gstYes">Yes</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="card shadow-sm border-0 p-3 m-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="gst_applicable" value="no" type="radio" id="gstNo" {{ ($expenses->gst_applicable == 'no' ) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="gstNo">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="gst_section" style="display:none;">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label>GST Transaction Mode</label>
                                            <select class="form-select" name="gst_trans" id="gst_trans">
                                                <option value="">Select</option>
                                                <option value="intrastate" {{ ($expenses->gst_trans == 'intrastate') ? 'selected' : '' }}>Intra State</option>
                                                <option value="interstate" {{ ($expenses->gst_trans == 'interstate') ? 'selected' : '' }}>Inter State</option>
                                                <option value="union" {{ ($expenses->gst_trans == 'union') ? 'selected' : '' }}>Union Territory</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>GST Rate (%)</label>
                                            <input type="number" id="gst_rate" name="gst_rate" class="form-control" value="{{ $expenses->gst_rate ?? 18 }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label>GST Allocation</label>
                                            <input type="text" id="gst_allocation" name="gst_allocation" value="{{ $expenses->gst_allocation }}" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <label>Total GST Amount</label>
                                            <input type="number" id="total_gst" name="total_gst" value="{{ $expenses->total_gst }}" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Approver Name</label>
                            <input type="text" name="approved_by" id="approved_by" value="{{ $expenses->approved_by }}" class="form-control" placeholder="Enter Name">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Designation</label>
                            <input type="text" name="designation" id="designation" value="{{ $expenses->designation }}" class="form-control" placeholder="Enter Designation">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Approve Date</label>
                            <input type="date" name="approved_date" id="approved_date" value="{{ $expenses->approved_date }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Special Notes/Command</label>
                            <input type="text" name="spec_note" id="spec_note" value="{{ $expenses->spec_note }}" class="form-control" placeholder="Special Notes/Command">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="imageUpload">Upload Image</label>
                                <input type="file" id="imageUpload" name="exp_inv_doc" class="form-control" accept="image/*">
                            </div>
                            @if (!empty($expenses->exp_inv_doc))
                                @php
                                    $imagePath = asset('storage/' . $expenses->exp_inv_doc);
                                @endphp
                                <div id="imagePreview" class="mt-3" style="text-align: center;">
                                    <a id="downloadLink" href="{{ $imagePath }}" download="{{ basename($expenses->exp_inv_doc) }}">
                                        <img id="uploadedImage" src="{{ $imagePath }}" alt="Preview Image"
                                             style="max-width: 100%; cursor: pointer; border: 1px solid #ddd; padding: 10px;">
                                    </a>
                                </div>
                            @else
                                <div id="imagePreview" class="mt-3" style="display: none; text-align: center;">
                                    <a id="downloadLink" href="#" download="uploaded_image.jpg">
                                        <img id="uploadedImage" src="" alt="Preview Image"
                                             style="max-width: 100%; cursor: pointer; border: 1px solid #ddd; padding: 10px;">
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>

                    <div id="depreciationSection" class="row" style="display:none;">
                        <div class="col-md-4 mb-3">
                            <label>Depreciation Value</label>
                            <input type="number" class="form-control" id="dep_value" name="dep_value" value="{{ $expenses->dep_value ?? '' }}">
                        </div>
                    </div>

                    <div class="text-end btn-page mt-4">
                        <a href="{{ url('/expenses-list') }}" class="btn btn-danger customer-btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentVoucherModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Payment Details</h5>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="f_id">
                <input type="hidden" id="voucher_type">
				<input type="hidden" id="isViewPage" value="0">
				
				<div id="paymentNoteArea" class="alert alert-warning mt-2">
					<strong>Note:</strong>
					Please click <strong>Save</strong> to update payment vouchers,
					journal entries and payment status.
				</div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Total Invoice Amount</label>
                        <input type="text"
                            id="invoice_total"
                            class="form-control"
                            readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Paid Amount</label>
                        <input type="text"
                            id="total_paid"
                            class="form-control"
                            readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Balance Due</label>
                        <input type="text"
                            id="balance_due"
                            class="form-control"
                            readonly>
                    </div>
					
                </div>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Mode</th>
						<th>Bank</th>
                        <th width="80">Action</th>
                    </tr>
                    </thead>

                    <tbody id="voucherRows">

                    </tbody>
                </table>

                <button type="button"
                    class="btn btn-success"
                    id="addVoucherRow">
                    Add Payment
                </button>

            </div>

            <div class="modal-footer">
                <button type="button"
                    class="btn btn-primary"
                    id="saveVoucherPayments">
                    Save
                </button>
            </div>

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
            panField.value = pan;
            panField.readOnly = true;
        } else {
            panField.value = '';
            panField.readOnly = false;
            panField.focus();
        }
    });
    //------ end Pan No auto fill logic based on vendor selection ------

    //------ start TDS & GST toggle logic ------
    document.addEventListener("DOMContentLoaded", function () {

        const tdsYes = document.getElementById("tdsYes");
        const tdsNo  = document.getElementById("tdsNo");
        const tdsSection = document.getElementById("tds_section");

        const gstYes = document.getElementById("gstYes");
        const gstNo  = document.getElementById("gstNo");
        const gstSection = document.getElementById("gst_section");

        function toggleTDS() {
            tdsSection.style.display = tdsYes.checked ? "flex" : "none";
        }

        function toggleGST() {

            let selectedGST = document.querySelector('input[name="gst_applicable"]:checked');

            if (selectedGST && selectedGST.value === "yes") {
                gstSection.style.display = "block";
            } else {
                gstSection.style.display = "none";
            }
        }

        tdsYes.addEventListener("change", toggleTDS);
        tdsNo.addEventListener("change", toggleTDS);
        gstYes.addEventListener("change", toggleGST);
        gstNo.addEventListener("change", toggleGST);

        // Initial load — respect saved values
        toggleTDS();
        toggleGST();
    });
    //------ end TDS & GST toggle logic ------

    //------ start GST calculation logic ------
    document.addEventListener("DOMContentLoaded", function () {

        const amount      = document.getElementById("expense_amt");
        const gstRate     = document.getElementById("gst_rate");
        const gstTrans    = document.getElementById("gst_trans");
        const gstAllocation = document.getElementById("gst_allocation");
        const totalGST    = document.getElementById("total_gst");

        function calculateGST() {
            let amt  = parseFloat(amount.value) || 0;
            let rate = parseFloat(gstRate.value) || 0;
            let mode = gstTrans.value;

            if (!amt || !rate || !mode) {
                gstAllocation.value = "";
                totalGST.value = "";
                return;
            }

            let gstAmt   = (amt * rate) / 100;
            let halfRate = rate / 2;
            let halfAmt  = gstAmt / 2;

            totalGST.value = gstAmt.toFixed(2);

            if (mode === "intrastate") {
                gstAllocation.value = `CGST ${halfRate}% + SGST ${halfRate}%`;
            } else if (mode === "interstate") {
                gstAllocation.value = `IGST ${rate}%`;
            } else if (mode === "union") {
                gstAllocation.value = `UTGST ${rate}%`;
            }
        }

        amount.addEventListener("input", calculateGST);
        gstRate.addEventListener("input", calculateGST);
        gstTrans.addEventListener("change", calculateGST);

        // Recalculate on load in case values are pre-filled
        calculateGST();
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

		//-------------------------------------------------------
		// Selected Expense Head
		//-------------------------------------------------------
		function getSelectedCategory() {

			if (expenseCat.value === "direct")
				return directType.value;

			if (expenseCat.value === "indirect")
				return indirectType.value;

			return "";
		}

		//-------------------------------------------------------
		// Edit Page Load
		//-------------------------------------------------------
		if (
			tdsYes.checked &&
			tdsRateField.value &&
			tdsSectionField.value
		) {
			currentRule = {
				tds_section: tdsSectionField.value,
				tds_rate: parseFloat(tdsRateField.value),
				threshold_limit: parseFloat(thresholdInput.value) || 0
			};

			tdsSection.style.display = "flex";
			calculateTDS();

		} else {
			resetTDS(false);
		}

		//-------------------------------------------------------
		// YES Click
		//-------------------------------------------------------
		tdsYes.addEventListener("change", function () {

			if (!this.checked)
				return;

			fetch("/check-company-policies")
				.then(res => res.json())
				.then(res => {
					if (!res.comp_tan || res.comp_tan.trim() === "") {
						showToast("Please update Company TAN in Organization Profile.", "error");
						tdsYes.checked = false;
						tdsNo.checked = true;
						resetTDS();
						return;
					}

					fetchTdsRule();

				})
				.catch(() => {
					showToast("Unable to verify Company TAN.", "error");
					tdsYes.checked = false;
					tdsNo.checked = true;
					resetTDS();
				});
		});

		//-------------------------------------------------------
		// NO Click
		//-------------------------------------------------------
		tdsNo.addEventListener("change", function () {
			if (this.checked) {
				resetTDS();
			}
		});

		//-------------------------------------------------------
		// Fetch TDS Rule
		//-------------------------------------------------------
		function fetchTdsRule() {

			const category = getSelectedCategory();
			if (!category) {
				resetTDS(false);
				return;
			}

			fetch("/get-tds-rule", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					"X-CSRF-TOKEN": document
						.querySelector('meta[name="csrf-token"]')
						.content

				},

				body: JSON.stringify({
					category: category
				})

			})

			.then(res => res.json())
			.then(data => {
				if (!data || data.tds_rate == null) {
					showToast("No TDS rule found.", "warning");
					resetTDS(false);
					return;
				}

				currentRule = data;
				tdsSectionField.value = data.tds_section || "";
				tdsRateField.value = data.tds_rate || "";
				thresholdInput.value = data.threshold_limit || "";
				calculateTDS();

			})

			.catch(function () {
				showToast("Unable to fetch TDS Rule.", "error");
				resetTDS(false);

			});

		}

		//-------------------------------------------------------
		// Calculate TDS
		//-------------------------------------------------------
		
		function calculateTDS() {
            const amount = parseFloat(amountField.value) || 0;
            if (!currentRule || !currentRule.tds_rate) { resetTDS(); return; }

            const threshold = parseFloat(currentRule.threshold_limit) || 0;
            const rate      = parseFloat(currentRule.tds_rate) || 0;

            if (amount >= threshold) {
                tdsYes.checked = true;
                tdsNo.checked  = false;
                tdsSection.style.display = "flex";
                tdsAmountField.value = ((amount * rate) / 100).toFixed(2);
            } else {
                tdsNo.checked  = true;
                tdsYes.checked = false;
                tdsSection.style.display = "none";
                tdsAmountField.value = "";
            }
        }

		//-------------------------------------------------------
		// Reset
		//-------------------------------------------------------
		function resetTDS(resetRadio = true) {
			currentRule = null;
			tdsSectionField.value = "";
			tdsRateField.value = "";
			tdsAmountField.value = "";
			thresholdInput.value = "";
			tdsSection.style.display = "none";
			if (resetRadio) {
				tdsYes.checked = false;
				tdsNo.checked = true;
			}
		}

		//-------------------------------------------------------
		// Amount Changed
		//-------------------------------------------------------
		amountField.addEventListener("input", function () {
			if (tdsYes.checked) {
				calculateTDS();
			}
		});

		//-------------------------------------------------------
		// Expense Head Changed
		//-------------------------------------------------------
		function expenseChanged() {
			if (tdsYes.checked) {
				fetchTdsRule();
			}
		}

		expenseCat.addEventListener("change", expenseChanged);
		directType.addEventListener("change", expenseChanged);
		indirectType.addEventListener("change", expenseChanged);

	});
    //------ end TDS auto fetch & calculation logic ------

    //------ start image upload preview ------
    document.getElementById("imageUpload").addEventListener("change", function (event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById("imagePreview");
        const uploadedImage    = document.getElementById("uploadedImage");
        const downloadLink     = document.getElementById("downloadLink");

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                uploadedImage.src    = e.target.result;
                downloadLink.href    = e.target.result;
                downloadLink.download = file.name;
                previewContainer.style.display = "block";
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = "none";
        }
    });
    //------ end image upload preview ------

    document.addEventListener("DOMContentLoaded", function () {

        //------ Expense category → show/hide direct/indirect sections ------
        const expenseCat             = document.getElementById("expense_cat");
        const directExpensesSection  = document.getElementById("directExpensesSection");
        const indirectExpensesSection = document.getElementById("indirectExpensesSection");
        

        function toggleExpenseSections() {
            directExpensesSection.style.display  = (expenseCat.value === "direct")   ? "block" : "none";
            indirectExpensesSection.style.display = (expenseCat.value === "indirect") ? "block" : "none";
            
        }

        expenseCat.addEventListener("change", toggleExpenseSections);
        toggleExpenseSections(); // run on load

        //------ Other text box: show for "Other" type or Miscellaneous ------
        const directExpensesType  = document.getElementById("directExpensesType");
        const indirectExpensesType = document.getElementById("indirectExpensesType");
        const otherTextBox        = document.getElementById("other_text_box");

        function toggleOtherTextBox() {
            const isDirect      = directExpensesType.value === "Other";
            const isMisc        = expenseCat.value === "indirect" && indirectExpensesType.value === "miscellaneous_expenses";
            const isIndirectOther = indirectExpensesType.value === "Other";
            otherTextBox.style.display = (isDirect || isMisc || isIndirectOther) ? "block" : "none";
        }

        expenseCat.addEventListener("change", toggleOtherTextBox);
        directExpensesType.addEventListener("change", toggleOtherTextBox);
        indirectExpensesType.addEventListener("change", toggleOtherTextBox);
        toggleOtherTextBox(); // run on load

        //------ Form submit ------
        $("form#addExpenseFrm").bind("submit", function () {

            var eId  = $("#eId").val();
            var surl = eId ? "/update_expenses" : "/save_expenses";

            let propId       = $("#propId option:selected").val();
            let expense_cat  = $("#expense_cat option:selected").val();

            let expense_type = "";
            if (expense_cat === "direct") {
                expense_type = $("#directExpensesType option:selected").val();
            } else if (expense_cat === "indirect") {
                expense_type = $("#indirectExpensesType option:selected").val();
            }

            if (!expense_type) {
                showToast("Please select expense type", "error");
                return false;
            }

            let employee_id = $("#employee_id option:selected").val();
            if ((expense_cat === "indirect" || expense_cat === "direct") && expense_type === "employee_benefits" && !employee_id) {
                showToast("Please select employee", "error");
                return false;
            }

            let other         = $("#other_exp").val();
            let expense_date  = $("#expense_date").val();
			let threshold_type = $("#threshold_type option:selected").val();
            let exp_invno     = $("#exp_invno").val();
            let expense_amt   = parseFloat($("#expense_amt").val()) || 0;
            let mode_of_expense = $("#mode_of_expense").val();
			let tax_treatment = $("#tax_treatment").val();
			let allowed_ratio = $("#allowed_ratio").val();
			let rebate_amt 	  = $("#rebate_amt").val();
            let pur_of_expense  = $("#pur_of_expense").val();
            let approved_by   = $("#approved_by").val();
            let designation   = $("#designation").val();
            let approved_date = $("#approved_date").val();
            let spec_note     = $("#spec_note").val();
            let employee_code = $("#employee_code").val() || '';
            let exp_inv_doc   = $("#imageUpload").prop("files")[0];
            let tds_percentage = $("#tds_percentage option:selected").val();

            let vendor_id     = $("#vendor_id option:selected").val();
            let vendor_pan    = $("#vendor_pan").val();
            let payment_status = $("#payment_status").val();
			
			if (!expense_date) {
				showToast("Please select expense date", "error");
				return false;
			}
			
			if (!payment_status) {
				showToast("Please select payment status", "error");
				return false;
			}
			
			if (payment_status !== 'due' && !mode_of_expense) {
				showToast("Please select mode of expense", "error");
				return false;
			}

            // TDS
            let tds_applicable     = $("input[name='tds_applicable']:checked").val() || "no";
            let tds_section        = $("#tds_section_field").val();
            let tds_rate           = parseFloat($("#tds_rate_field").val()) || 0;
            let tds_amount         = parseFloat($("#tds_amount_field").val()) || 0;
            let tds_threshold_limit = $("#tds_threshold_limit").val();

            // GST
            let gst_applicable = $("input[name='gst_applicable']:checked").val();
            let gst_trans      = $("#gst_trans").val();
            let gst_rate       = parseFloat($("#gst_rate").val()) || 0;
            let gst_allocation = $("#gst_allocation").val();
            let total_gst      = parseFloat($("#total_gst").val()) || 0;

            let advance_amount = parseFloat($("#advance_amount").val()) || 0;
            let balance_amount = parseFloat($("#balance_amount").val()) || 0;
            let adjusted_now   = parseFloat($("#adjusted_now").val()) || 0;

            let dep_value = $("#dep_value").val();

            // Depreciation validation
            if (expense_cat === "indirect" && expense_type === "depreciation") {
                if (!dep_value) {
                    showToast("Please fill all depreciation fields", "error");
                    return false;
                }
            }

            let expensesData = new FormData();
            expensesData.append("id",           eId);
            expensesData.append("propId",        propId);
            expensesData.append("expense_cat",   expense_cat);
            expensesData.append("expense_type",  expense_type);
            expensesData.append("expense_date",  expense_date);
            expensesData.append("threshold_type",  threshold_type);
            expensesData.append("exp_invno",     exp_invno);
            expensesData.append("expense_amt",   expense_amt);
            expensesData.append("mode_of_expense", mode_of_expense);
			expensesData.append("tax_treatment", tax_treatment);
			expensesData.append("allowed_ratio", allowed_ratio);
			expensesData.append("rebate_amt", rebate_amt);
            expensesData.append("pur_of_expense",  pur_of_expense);
            expensesData.append("approved_by",   approved_by);
            expensesData.append("designation",   designation);
            expensesData.append("approved_date", approved_date);
            expensesData.append("spec_note",     spec_note);
            expensesData.append("employee_id",   employee_id);
            expensesData.append("employee_code", employee_code);
            expensesData.append("exp_inv_doc",   exp_inv_doc);
            expensesData.append("other",         other);
            expensesData.append("tds_percentage", tds_percentage);

            expensesData.append("vendor_id",     vendor_id);
            expensesData.append("vendor_pan",    vendor_pan);
            expensesData.append("payment_status", payment_status);

            // TDS
            expensesData.append("tds_applicable",      tds_applicable);
            expensesData.append("tds_section",          tds_section);
            expensesData.append("tds_rate",             tds_rate);
            expensesData.append("tds_amount",           tds_amount);
            expensesData.append("tds_threshold_limit",  tds_threshold_limit);

            // GST
            expensesData.append("gst_applicable", gst_applicable);
            expensesData.append("gst_trans",      gst_trans);
            expensesData.append("gst_rate",       gst_rate);
            expensesData.append("gst_allocation", gst_allocation);
            expensesData.append("total_gst",      total_gst);

            expensesData.append("advance_amount", advance_amount);
            expensesData.append("balance_amount", balance_amount);
            expensesData.append("adjusted_now",   adjusted_now);

            expensesData.append("dep_value", (expense_cat === "indirect" && expense_type === "depreciation") ? dep_value : "");

            $("#loader").show();
            $.ajax({
                headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                url: surl,
                type: "POST",
                data: expensesData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $("#loader").hide();
                    if (response.class == "succ") {
                        showToast(response.message, "success");
                        setTimeout(() => { window.location.href = response.redirect; }, 2000);
                    } else {
                        $.each(response, function (idx, obj) {
                            showToast("Error: while updating expenses", "error");
                        });
                    }
                }
            });
        });

    }); // end DOMContentLoaded

    //------ Employee dropdown logic ------
    let selectedEmployeeId = "{{ $expenses->employee_id ?? '' }}";

    $(document).ready(function () {
		
		function loadEmployees(selectedEmployee = '') {

			$.ajax({
				url: "/get-employees",
				type: "GET",
				success: function (res) {

					let dropdown = $("#employee_id");
					dropdown.empty();
					dropdown.append('<option value="">Select Employee</option>');

					$.each(res, function (key, emp) {

						let selected = (emp.id == selectedEmployee) ? 'selected' : '';

						dropdown.append(`
							<option value="${emp.id}"
									data-code="${emp.employee_code}"
									${selected}>
								${emp.name}
							</option>
						`);
					});

					// Fill Employee Code
					if(selectedEmployee){
						let code = dropdown.find(":selected").data("code") || '';
						$("#employee_code").val(code);
					}
				}
			});
		}
		
		function handleEmployeeDropdown() {

			let category = $("#expense_cat").val();

			let type = (category === "direct")
				? $("#directExpensesType").val()
				: $("#indirectExpensesType").val();

			// Saved employee id (Edit page)
			let selectedEmployee = "{{ $expenses->employee_id ?? '' }}";

			if ((category === "indirect" || category === "direct")
				&& type === "employee_benefits") {

				$("#employeeDropdownDiv").show();
				$("#employeeIdDiv").show();

				loadEmployees(selectedEmployee);

			} else {

				$("#employeeDropdownDiv").hide();
				$("#employeeIdDiv").hide();

				$("#employee_id").empty()
					.append('<option value="">Select Employee</option>');

				$("#employee_code").val('');
			}
		}

        $("#expense_cat").change(function () { handleEmployeeDropdown(); });
        $("#indirectExpensesType, #directExpensesType").change(function () {
            selectedEmployeeId = ""; // reset on type change
            handleEmployeeDropdown();
        });

        // Restore employee code when dropdown changes
        $("#employee_id").change(function () {
            let code = $(this).find(':selected').data('code') || '';
            $("#employee_code").val(code);
        });

        handleEmployeeDropdown(); // run on page load

        //------ Employee Expense UI (hide TDS/GST/Vendor for employee_benefits) ------
        function handleEmployeeExpenseUI() {
            let category = $("#expense_cat").val();
            let type     = (category === "direct") 
                    ? $("#directExpensesType").val() 
                    : $("#indirectExpensesType").val();

            if ((category === "indirect" || category === "direct") && type === "employee_benefits") {
                $("#amountLabel").text("Advance Amount *");
                $("#tds_section").closest(".row").hide();
                $(".gst-container").hide();
                $("#vendor_id").closest(".col-sm-4").hide();
                $("#vendor_pan").closest(".col-sm-4").hide();
                //$("#payment_status_div").hide();
                $("#advance_amount_div").hide();
                $("#balance_amount_div").hide();
                $("#adjusted_now_div").hide();
            } else {
                $("#amountLabel").text("Expense Amount *");
                $("#tds_section").closest(".row").show();
                $(".gst-container").show();
                $("#vendor_id").closest(".col-sm-4").show();
                $("#vendor_pan").closest(".col-sm-4").show();
                $("#payment_status_div").show();
                $("#advance_amount_div").show();
                $("#balance_amount_div").show();
                $("#adjusted_now_div").show();
            }
        }

        $("#expense_cat").change(function () { handleEmployeeExpenseUI(); });
        $("#indirectExpensesType, #directExpensesType").change(function () { handleEmployeeExpenseUI(); });
        handleEmployeeExpenseUI(); // run on page load

    }); // end document.ready

    //------ Invoice / Employee ID toggle ------
    function handleEmployeeInvoiceToggle() {
        let category = $("#expense_cat").val();
        let type     = (category === "direct") 
                ? $("#directExpensesType").val() 
                : $("#indirectExpensesType").val();

        if ((category === "indirect" || category === "direct") && type === "employee_benefits") {
            $("#invoiceDiv").hide();
            $("#employeeIdDiv").show();
        } else {
            $("#invoiceDiv").show();
            $("#employeeIdDiv").hide();
            $("#employee_code").val('');
        }
    }

    $("#expense_cat, #indirectExpensesType, #directExpensesType").change(function () { handleEmployeeInvoiceToggle(); });
    handleEmployeeInvoiceToggle();


    //------ Payment calculation logic ------
    function handlePaymentCalculation() {
        let total   = parseFloat($("#expense_amt").val()) || 0;
        let status  = $("#payment_status").val();
        let advance = parseFloat($("#advance_amount").val()) || 0;

        if (status === "full") {
            // Keep fields visible but disabled
            $("#advance_amount_div").show();
            $("#balance_amount_div").show();
            $("#adjusted_now_div").show();

            $("#advance_amount").val(0).prop("disabled", true);
            $("#balance_amount").val(0).prop("disabled", true);
            $("#adjusted_now").val(total.toFixed(2));

        } else if (status === "advance") {
            // Re-enable advance input, keep balance readonly
            $("#advance_amount_div").show();
            $("#balance_amount_div").show();
            $("#adjusted_now_div").hide();

            $("#advance_amount").prop("disabled", false);
            $("#balance_amount").prop("disabled", false);

            if (advance > total) {
                advance = total;
                $("#advance_amount").val(total);
            }
            $("#balance_amount").val((total - advance).toFixed(2));
            $("#adjusted_now").val("");

        } else {
            // No status selected — reset to enabled
            $("#advance_amount").prop("disabled", false);
            $("#balance_amount").prop("disabled", false);
        }
    }

    $("#payment_status").change(function () { handlePaymentCalculation(); });
    $("#expense_amt").on("input", function () { handlePaymentCalculation(); });
    $("#advance_amount").on("input", function () { handlePaymentCalculation(); });
    handlePaymentCalculation(); // run on page load to apply saved status

    //------ Depreciation UI logic ------
    function handleDepreciationUI() {
        let category = $("#expense_cat").val();
        let type     = (category === "direct") 
                ? $("#directExpensesType").val() 
                : $("#indirectExpensesType").val();

        if (category === "indirect" && type === "depreciation") {
            $("#normalExpenseFields").css("display", "none");
            $("#other_text_box").hide();
            $("#depreciationSection").css("display", "flex");
            $("#amountLabel").text("Asset Value *");
        } else {
            $("#normalExpenseFields").css("display", "flex");
            $("#depreciationSection").css("display", "none");
        }
    }

    $("#expense_cat, #indirectExpensesType, #directExpensesType").on("change", function () {
        setTimeout(function () { handleDepreciationUI(); }, 50);
    });
	
	$(document).ready(function () { handleDepreciationUI(); });
	
	$('#expense_cat').on('change', function () {
		let expenseCat = $(this).val();
		$('#directExpensesSection, #indirectExpensesSection').hide();
		$('#directExpensesType').html('<option value="">Select</option>');
		$('#indirectExpensesType').html('<option value="">Select</option>');

		if (!expenseCat) {
			return;
		}

		$.ajax({
			url: "{{ route('getDropdownTypes') }}",
			type: "POST",
			data: {
				module: 'Expense',
				dropdown_name: expenseCat,
				_token: "{{ csrf_token() }}"
			},
			success: function (res) {

				let html = '<option value="">Select</option>';

				$.each(res, function (i, item) {
					html += `
						<option value="${item.option_value}" data-type="${item.type}">
							${item.option_text}
						</option>
					`;
				});

				if (expenseCat === 'direct') {
					$('#directExpensesType').html(html).val($('#selected_direct_expense').val());
					$('#directExpensesSection').show();
					showExpenseTypeInfo();
				} else if (expenseCat === 'indirect') {
					$('#indirectExpensesType').html(html).val($('#selected_indirect_expense').val()).trigger('change');
					$('#indirectExpensesSection').show();
					showExpenseTypeInfo();
				}
				
			}
		});
	});


	$(document).ready(function () {
		$('#expense_cat').trigger('change');
	});
	
	//Start calculate Tax deduction
	function loadTaxRule(expenseType, expenseHead)
	{
		if (!expenseType || !expenseHead) {
			$('.tax-info-section').hide();
			return;
		}

		$.ajax({
			url: "{{ route('getTaxRule') }}",
			type: "POST",
			data: {
				_token: "{{ csrf_token() }}",
				expense_type: expenseType,
				expense_head: expenseHead
			},
			success: function(res){

				if(!res.status){
					$('.tax-info-section').hide();
					return;
				}

				$('.tax-info-section').show();

				$('#tax_treatment').val(res.tax_treatment);

				let treatment = (res.tax_treatment || '').trim();

				// FULLY ALLOWED
				if(treatment === 'Fully Allowed')
				{
					$('#allowed_ratio')
						.val(100)
						.prop('readonly', true)
						.attr('min', 100)
						.attr('max', 100);

					calculateRebate();
				}

				// PARTIAL ALLOWED
				else if(treatment === 'Partial Allowed')
				{
					let start = parseFloat(res.allow_start || 0);
					let end   = parseFloat(res.allow_end || 100);

					let savedRatio = $('#old_allowed_ratio').val();

					$('#allowed_ratio')
						.val(savedRatio ? savedRatio : start)
						.prop('readonly', false)
						.attr('min', start)
						.attr('max', end);

					calculateRebate();
				}

				// DISALLOWED
				else if(treatment === 'Disallowed')
				{
					$('#allowed_ratio')
						.val(0)
						.prop('readonly', true)
						.attr('min', 0)
						.attr('max', 0);

					$('#rebate_amt').val('0.00');
				}
			}
		});
	}

	function calculateRebate()
	{
		let amount = parseFloat($('#expense_amt').val()) || 0;
		let ratio  = parseFloat($('#allowed_ratio').val()) || 0;
		let rebate = (amount * ratio) / 100;
		$('#rebate_amt').val(rebate.toFixed(2));
	}

	$(document).on('input', '#allowed_ratio', function(){

		let min = parseFloat($(this).attr('min')) || 0;
		let max = parseFloat($(this).attr('max')) || 100;
		let val = parseFloat($(this).val()) || 0;

		if(val < min)
		{
			$(this).val(min);
			showToast('Minimum allowed ratio is ' + min + '%', 'error');
			val = min;
		}

		if(val > max)
		{
			$(this).val(max);
			showToast('Maximum allowed ratio is ' + max + '%', 'error');
			val = max;
		}

		calculateRebate();
	});

	$(document).on('keyup change', '#expense_amt', function(){
		calculateRebate();
	});

	$('#directExpensesType').on('change', function(){
		$('#old_allowed_ratio').val('');
		$('#old_rebate_amt').val('');
		loadTaxRule('direct', $(this).val());
	});

	$('#indirectExpensesType').on('change', function(){
		$('#old_allowed_ratio').val('');
		$('#old_rebate_amt').val('');
		loadTaxRule('indirect', $(this).val());
	});

	// Edit Page Auto Load
	$(document).ready(function(){

		let expenseType = '';
		let expenseHead = '';

		if($('#directExpensesType').length &&
		   $('#directExpensesType').val())
		{
			expenseType = 'direct';
			expenseHead = $('#directExpensesType').val();
		}
		else if($('#indirectExpensesType').length &&
				$('#indirectExpensesType').val())
		{
			expenseType = 'indirect';
			expenseHead = $('#indirectExpensesType').val();
		}

		if(expenseType && expenseHead)
		{
			loadTaxRule(expenseType, expenseHead);
		}

		calculateRebate();
	});
	//End calculate Tax deduction
	
	function showExpenseTypeInfo() {

		let directType = $("#directExpensesType").find(":selected").data("type") || "";
		$("#directExpenseTypeInfo").html(
			directType ? '<i class="ti ti-info-circle"></i> Type : ' + directType : ""
		);

		let indirectType = $("#indirectExpensesType").find(":selected").data("type") || "";
		$("#indirectExpenseTypeInfo").html(
			indirectType ? '<i class="ti ti-info-circle"></i> Type : ' + indirectType : ""
		);
	}

	// On change
	$(document).on("change", "#directExpensesType, #indirectExpensesType", function () {
		showExpenseTypeInfo();
	});


    function startEditExpensesTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Edit Expense Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Update date, category, amount, and payment details of this expense.</p></div>'
                },
                {
                    title: 'Edit Expense',
                    intro: 'Update date, category, amount, and payment details of this expense.'
                }
            ],
            showBullets: true,
            showProgress: true,
            helperElementPadding: 5,
            exitOnOverlayClick: false,
            doneLabel: 'Done',
            nextLabel: 'Next',
            prevLabel: 'Prev',
            skipLabel: 'Skip'
        }).start();
    }

    $(document).ready(function() {
        $('#start-edit-expenses-tour').on('click', function(e) {
            e.preventDefault();
            startEditExpensesTour();
        });
    });
</script>
@endsection
