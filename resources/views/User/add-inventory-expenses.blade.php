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
                        <li class="breadcrumb-item"><a href="{{ url('/expenses_inventorylist') }}">Inventory Expenses</a></li>
                        <li class="breadcrumb-item" aria-current="page">Add Inventory Expenses</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row mb-4">
        <h3>Add New Inventory Expenses</h3>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="javascript:void(0);" method="POST" name="addExpenseFrm" id="addExpenseFrm">
                <input type="hidden" name="id" id="eId" value="">
                @csrf
                <div class="row">

					<div class="col-sm-4 mb-3">
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
                        <label class="form-label">Expense Types<span class="text-danger">*</span></label>
                        <select id="expense_types" name="expense_types" class="form-control" required>
                            <option value="">Select</option>
                            <option value="Freight inward / transportation">Freight inward / transportation</option>
                            <option value="Customs duty & clearing charges">Customs duty & clearing charges</option>
                            <option value="Loading & unloading">Loading & unloading</option>
                            <option value="Insurance during transit">Insurance during transit</option>
                            <option value="Packing & Preparation Costs">Packing & Preparation Costs</option>
                            <option value="Direct Labour (Manufacturing/Processing)">Direct Labour (Manufacturing/Processing)</option>
                            <option value="Other Directly Attributable Costs">Other Directly Attributable Costs</option>
                            <option value="Taxes & Levies">Taxes & Levies</option>
                        </select>
                    </div>

                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Expense Voucher No</label>
                        <input type="text" id="expense_voucher_no" name="expense_voucher_no"  class="form-control" placeholder="Enter Expense Voucher No">
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Expense Date<span class="text-danger">*</span></label>
                        <input type="date" id="expense_date" name="expense_date" required class="form-control" placeholder="Enter Expense Date">
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Purchase Invoice Reference No.</label>
                        <input type="text" id="purchase_invoice_ref_no" name="purchase_invoice_ref_no"  class="form-control" placeholder="Enter Purchase Invoice Reference No">
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Supplier / Service Provider Name</label>
                        <input type="text" id="supplier_name" name="supplier_name"  class="form-control" placeholder="Enter Supplier / Service Provider Name">
                    </div>
                    
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Expense Amount<span class="text-danger">*</span></label>
                        <input type="text" id="expense_amount" name="expense_amount" required class="form-control" placeholder="Enter Expense Amount">
                    </div>
                   
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Stock Location / Warehouse</label>
                        <input type="text" id="stock_location" name="stock_location" class="form-control" placeholder="Enter Stock Location / Warehouse">
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Allocation Basis (Unit / Value)</label>
                        <input type="text" id="allocation_basis" name="allocation_basis"  class="form-control" placeholder="Enter Allocation Basis (Unit / Value)">
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Allocated Units / Lot No</label>
                        <input type="text" id="allocated_units" name="allocated_units" class="form-control" placeholder="Enter Allocated Units / Lot No">
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Cost Allocation Amount</label>
                        <input type="text" id="cost_allocation_amount" name="cost_allocation_amount" class="form-control" placeholder="Enter Cost Allocation Amount">
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea id="remarks" name="remarks" class="form-control" placeholder="Enter Remarks" rows="3"></textarea>
                    </div>
					
					<div class="card">
						<div class="card-header">
							<h5>GST & TDS</h5>
						</div>
						<div class="card-body">
							<div class="row currAsset">
								<div class="col-md-12">
									<label class="form-label">TDS Applicable</label>
									<div class="row">
										<div class="col-6">
											<div class="card shadow-sm border-0 p-3 m-2">
												<div class="form-check">
													<input class="form-check-input" type="radio" name="tds_applicable" value="yes" id="tdsYes">
													<label class="form-check-label" for="tdsYes">Yes</label>
												</div>
											</div>
										</div>
										<div class="col-6">
											<div class="card shadow-sm border-0 p-3 m-2">
												<div class="form-check">
													<input class="form-check-input" type="radio" name="tds_applicable" value="no" id="tdsNo" checked>
													<label class="form-check-label" for="tdsNo">No</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="tds-container col-md-12" id="tdsContainer">
									<div class="row">
										<div class="col-md-6">
											<div id="tds_dropdown_universal">
												<label for="tds_percent" class="form-label">TDS Percentage</label>
												<select name="tds_percent" id="tds_percent" class="form-control">
													@foreach ($purposes_of_tds as $purpose)
													<option value="{{ $purpose->tds_rate . '-' . $purpose->id }}">
														{{ $purpose->category }} ({{ $purpose->tds_rate }}%)
													</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<label for="tds_amount">TDS Amount</label>
											<input type="text" id="tds_amount"  class="form-control" readonly>
										</div>
									</div>
								</div>

								<div class="gst-container col-md-12">
									<div class="mb-3">
										<label class="form-label">GST Applicable</label>
										<div class="row">
											<div class="col-6">
												<div class="card shadow-sm border-0 p-3 m-2">
													<div class="form-check">
														<input class="form-check-input" type="radio" name="gst_applicable" value="yes" id="gstYes_ca" >
														<label class="form-check-label" for="gstYes_ca">Yes</label>
													</div>
												</div>
											</div>
											<div class="col-6">
												<div class="card shadow-sm border-0 p-3 m-2">
													<div class="form-check">
														<input class="form-check-input" name="gst_applicable" value="no" type="radio" id="gstNo_ca" checked>
														<label class="form-check-label" for="gstNo_ca">No</label>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<div class="col-md-4">
											<label for="gst_trans">GST Transaction Mode</label>
											<select class="form-select" name="gst_trans" id="gst_trans">
												<option value="">Select</option>
												<option value="intrastate">Intra State</option>
												<option value="interstate">Inter State</option>
												<option value="union">Union Territory</option>
											</select>
										</div>
										<div class="col-md-4">
											<label for="gst_rate">GST Rate (%)</label>
											<input type="number" name="gst_rate" id="gst_rate" class="form-control" min="0" step="0.01">
										</div>
										<div class="col-md-4">
											<label for="gst_rate">GST Allocation</label>
											<input type="text" name="gst_allocation" id="gst_allocation" class="form-control" readonly>
										</div>
										<div class="col-md-4">
											<label for="gst_amt">Total GST Amount</label>
											<input type="text" name="gst_amt" id="gst_amt" class="form-control" readonly>
										</div>
										<div class="col-md-4">
											<label for="itc_applicable">ITC Applicable</label>
											<select class="form-select" name="itc_applicable" id="itc_applicable">
												<option value="no">NO</option>
												<option value="yes">Yes</option>
											</select>
										</div>										
									</div>
								</div>
							</div>
						</div>
					</div>
					
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Supporting Document Upload</label>
                        <input type="file" id="supporting_document" name="supporting_document" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                    </div>
                    <div class="col-12 text-end">
                        <a href="{{ url('/expenses_inventorylist') }}" class="btn customer-btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<script>


	//TDS applicable
	$(document).ready(function () {

		function toggleTDS() {
			let val = $('input[name="tds_applicable"]:checked').val();

			if (val === 'yes') {
				$('#tdsContainer').show();
				calculateTDS(); 
			} else {
				$('#tdsContainer').hide();
				$('#tds_amount').val(0); 
			}
		}

		function calculateTDS() {

			let expense_amount = parseFloat($('#expense_amount').val()) || 0;

			$.ajax({
				url: '/calculate-tds-invexp',
				type: "POST",
				data: {
					_token: "{{ csrf_token() }}",
					expense_amount: expense_amount
				},
				success: function (res) {
					if (res) {
						$('#tds_amount').val(res.tds_amount);
					}
				}
			});
		}

		$('input[name="tds_applicable"]').on('change', function () {
			toggleTDS();
		});

		$('#expense_amount').on('input change', function () {
			if ($('#tdsYes').is(':checked')) {
				calculateTDS();
			}
		});

		toggleTDS();

	});
	
	//GST applicable
	document.addEventListener("DOMContentLoaded", function () {

		document.querySelectorAll(".gst-container").forEach((container) => {

			const gstYes = container.querySelector('#gstYes_ca');
			const gstNo  = container.querySelector('#gstNo_ca');

			if (gstYes && gstNo) {
				toggleGST(container, 'ca');
				gstYes.addEventListener('change', () => toggleGST(container, 'ca'));
				gstNo.addEventListener('change', () => toggleGST(container, 'ca'));
			}

		});

		function toggleGST(container, type) {

			let isYes = false;

			if (type === 'ca') {
				isYes = container.querySelector('#gstYes_ca')?.checked;
			} else {
				isYes = container.querySelector('#gstYes_nca')?.checked;
			}

			let fields = [];

			if (type === 'ca') {
				fields = container.querySelectorAll('#gst_trans, #gst_rate, #gst_allocation,#gst_amt,#itc_applicable');
			} else {
				
			}

			fields.forEach(field => {
				field.closest(".col-md-4").style.display = isYes ? "block" : "none";
			});
		}

	});
	
	//GST calculation
	function calculateGST() 
	{

		let invoiceValue = parseFloat($('#expense_amount').val()) || 0;

		let gstRate  = parseFloat($('#gst_rate').val()) || 0;
		let gstTrans = $('#gst_trans').val();

		// ================= GST CALC =================
		let gstAmount = (invoiceValue * gstRate) / 100;

		// ================= GST SPLIT =================
		let cgst = 0, sgst = 0, igst = 0;

		if (gstTrans === 'intrastate' || gstTrans === 'union') {
			cgst = gstAmount / 2;
			sgst = gstAmount / 2;
		} else if (gstTrans === 'interstate') {
			igst = gstAmount;
		}

		// ================= SET VALUES =================
		$('#gst_amt').val(gstAmount.toFixed(2));

		if (igst > 0) {
			$('#gst_allocation').val(`IGST: ${igst.toFixed(2)}`);
		} else {
			$('#gst_allocation').val(`CGST: ${cgst.toFixed(2)} | SGST: ${sgst.toFixed(2)}`);
		}
	}
	
	$('#expense_amount, #gst_rate, #gst_trans').on('change keyup', function () {
		calculateGST();
	});
	
	
    document.addEventListener("DOMContentLoaded", function () {
		
		$("form#addExpenseFrm").on("submit", function (e) {
			e.preventDefault();

			let eId = $("#eId").val();
			let surl = eId === "" ? "/save_inventory_expenses" : "/update_inventory_expenses";

			let expensesData = new FormData(this);
			if (!expensesData.has("id")) {
				expensesData.append("id", eId);
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

				success: function (response) {
					$("#loader").hide();

					if (response.class === "succ") {
						showToast(response.message, "success");
						setTimeout(() => {
							window.location.href = response.redirect;
						}, 1500);
					} else {
						showToast("Error while saving expense", "error");
					}
				},

				error: function () {
					$("#loader").hide();
					showToast("Validation error", "error");
				}
			});
		});

    /*$("form#addExpenseFrm").on("submit", function (e) {
        e.preventDefault();

        let eId = $("#eId").val();
        let surl = eId === "" 
            ? "/save_inventory_expenses" 
            : "/update_inventory_expenses";

        let expensesData = new FormData();

        expensesData.append("id", eId);
        expensesData.append("propId", $("#propId option:selected").val());
        expensesData.append("expense_types", $("#expense_types").val());
        expensesData.append("expense_voucher_no", $("#expense_voucher_no").val());
        expensesData.append("expense_date", $("#expense_date").val());
        expensesData.append("purchase_invoice_ref_no", $("#purchase_invoice_ref_no").val());
        expensesData.append("supplier_name", $("#supplier_name").val());
        expensesData.append("supplier_gstin", $("#supplier_gstin").val());
        expensesData.append("expense_amount", $("#expense_amount").val());
        expensesData.append("gst_amount", $("#gst_amount").val());
        expensesData.append("gst_rate", $("#gst_rate").val());
        expensesData.append("stock_location", $("#stock_location").val());
        expensesData.append("allocation_basis", $("#allocation_basis").val());
        expensesData.append("allocated_units", $("#allocated_units").val());
        expensesData.append("cost_allocation_amount", $("#cost_allocation_amount").val());
        expensesData.append("remarks", $("#remarks").val());

        let docFile = $("#supporting_document").prop("files")[0];
        if (docFile) {
            expensesData.append("supporting_document", docFile);
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
            success: function (response) {
				$("#loader").hide();
                if (response.class === "succ") {
                    showToast(response.message, "success");
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 1500);
                } else {
                    showToast("Error while saving expense", "error");
                }
            },
            error: function (xhr) {
				$("#loader").hide();
                showToast("Validation error", "error");
            }
        });
    });*/

});

    
</script>
@endsection