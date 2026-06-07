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
                        <li class="breadcrumb-item"><a href="{{ url('/tds-tax-slab-list') }}">TDS TAX SLAB</a></li>
                        <li class="breadcrumb-item" aria-current="page">Add TDS TAX SLAB</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row mb-4">
        <h3>Add New TDS TAX SLAB</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="tdsRuleFrm">
                @csrf

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Module *</label>
                        <select name="module" id="module" class="form-control" required>
                            <option value="">Select</option>
                            <option value="Expenses">Expenses</option>
                            <option value="Purchase">Purchase</option>
                            <option value="Assets">Assets</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Category of Transaction *</label>
                        <select name="category" id="category" class="form-control" required>
                            <option value="">Select</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">TDS Section *</label>
                        <input type="text" name="tds_section" class="form-control" placeholder="192 / 194J" required>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">TDS Rate(%) *</label>
                        <input type="text" name="tds_rate" id="tds_rate" class="form-control" placeholder="10% / As per slab" required>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Entity *</label>
                        <select name="entity" class="form-control">
                            <option value="All">All Entities</option>
                            <option value="Proprietorship">Proprietorship</option>
                            <option value="Firm">Firm / LLP</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Threshold Limit</label>
                        <input type="text" name="threshold" id="threshold" class="form-control" placeholder="₹50,000 per FY">
                    </div>

                    <div class="col-md-9 mb-3">
                        <label class="form-label">Special Notes (ERP)</label>
                        <input type="text" name="notes" class="form-control">
                    </div>
                </div>

                <!-- Salary Slab Section -->
                <div id="salarySlabBox" class="mt-4 d-none">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5>Salary Tax Slabs (Section 192)</h5>
                        <button type="button" class="btn btn-sm btn-success" id="addSlabRow">
                            + Add Slab
                        </button>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr class="table-light">
                                <th>From Amount (₹)</th>
                                <th>To Amount (₹)</th>
                                <th>Tax Rate (%)</th>
                                <th width="60">Action</th>
                            </tr>
                        </thead>
                        <tbody id="slabRows">
                            <!-- Default rows -->
                            <tr>
                                <td><input type="number" class="form-control" value="0"></td>
                                <td><input type="text" class="form-control" value="400000"></td>
                                <td><input type="number" class="form-control" value="0"></td>
                                <td class="text-center">—</td>
                            </tr>
                            <tr>
                                <td><input type="number" class="form-control" value="400001"></td>
                                <td><input type="text" class="form-control" value="800000"></td>
                                <td><input type="number" class="form-control" value="5"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger removeRow">×</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <div class="text-end">
                    <a href="{{ url('/tds-tax-slab-list') }}" class="btn btn-secondary">Cancel</a>
                    <button class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>

	$('#tds_rate,#threshold').on('input', function () {
		this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
	});
	
    document.addEventListener("DOMContentLoaded", function () {

    $("#tdsRuleFrm").on("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        if (!$('#salarySlabBox').hasClass('d-none')) {

            let slabs = [];

            $('#slabRows tr').each(function () {
                let from = $(this).find('input:eq(0)').val();
                let to   = $(this).find('input:eq(1)').val();
                let rate = $(this).find('input:eq(2)').val();

                if (
                    from === '' ||
                    rate === '' ||
                    isNaN(from) ||
                    isNaN(rate)
                ) return;

                slabs.push({
                    from_amount: parseFloat(from),
                    to_amount: to ? to : 'Above',
                    tax_rate: parseFloat(rate)
                });
            });

            if (slabs.length > 0) {
                formData.append('salary_slabs', JSON.stringify(slabs));
            }
        }

        $.ajax({
            url: "/save_tds_tax_slab",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.class === "succ") {

                    showToast(response.message, "success"); 
                    setTimeout(() => { window.location.href = response.redirect; }, 1500);
                    
                } else {
                    // alert("Failed");
                    showToast(response.message, "error");
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                showToast("An error occurred while processing the request.", "error");
            }
        });
    });

});

    $('#category').on('change', function () {
        if ($(this).val() === 'Salary & Wages') {
            $('#salarySlabBox').removeClass('d-none');
            $('#tds_rate').val('As per slab').prop('readonly', true);
        } else {
            $('#salarySlabBox').addClass('d-none');
            $('#tds_rate').val('').prop('readonly', false);
        }
    });

    const expenseCategories = [
        // "Professional Fees (CA, Lawyer, Consultant, Marketing, HR, Training, Director Sitting Fees, Royalty/License)",
        // "Technical Fees (IT, Software, AMC, Call Centre)",
        // "Contracts – Manpower (Security, Maintenance, Repairs, Event Mgmt)",
        // "Job Work / Advertising / Transport / Freight",
        // "Rent – Building / Office / Shop",
        // "Rent – Machinery / Plant / Equipment",
        // "Salary & Wages",
        // "Commission / Brokerage (Sales, Referral)",
        // "Business Promotion / Benefits / Perquisites (In-kind)",

        // Added from directExpensesType select options
        "Raw Material Costs",
        "Direct Labour",
        "Manufacturing Expenses",
        "Factory Utilities",
        "Freight / Carriage Inward",
        "Job Work / Outsourcing",
        "Packing Material",
        "Other Direct Expenses",
        "Employee Expenses (Salary, Benefits)",
        "Rent Expense",
        "Electricity Expense",
        "Internet & Communication",
        "Office Expenses",
        "Printing & Stationery",
        "Travel & Conveyance",
        "Repair & Maintenance",
        "Professional Fees",
        "Audit Fees",
        "Legal Charges",
        "Bank Charges",
        "Interest Expense",
        "Depreciation",
        "Insurance Expense",
        "Marketing & Advertisement",
        "Freight & Transport",
        "Miscellaneous Expenses"
    ];

    const purchaseCategories = [
        "Goods Purchase (Raw material, Trading goods, Machinery as goods)"
    ];

    const assetCategories = [
        "Land / Building Purchase",
        "Building taken on Rent / Lease (Individual / HUF)",
        "Machinery on Rent / Hire"
    ];

    // Module → Category mapping
    $('#module').on('change', function () {
        let module = $(this).val();
        let $category = $('#category');

        $category.empty().append('<option value="">Select</option>');

        let categories = [];
        if (module === 'Expenses') categories = expenseCategories;
        if (module === 'Purchase') categories = purchaseCategories;
        if (module === 'Assets') categories = assetCategories;

        categories.forEach(cat => {
            $category.append(`<option value="${cat}">${cat}</option>`);
        });

        // Reset salary slab on module change
        $('#salarySlabBox').addClass('d-none');
        $('#tds_rate').val('').prop('readonly', false);
    });

    // Salary logic
    $('#category').on('change', function () {
        let val = $(this).val();

        if (val === 'Salary & Wages') {
            $('#salarySlabBox').removeClass('d-none');
            $('#tds_rate').val('As per slab').prop('readonly', true);
        } else {
            $('#salarySlabBox').addClass('d-none');
            $('#tds_rate').val('').prop('readonly', false);
        }
    });

    // Add slab row
    $('#addSlabRow').on('click', function () {
        let row = `
            <tr>
                <td><input type="number" class="form-control" placeholder="From"></td>
                <td><input type="text" class="form-control" placeholder="To / Above"></td>
                <td><input type="number" class="form-control" placeholder="%"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger removeRow">×</button>
                </td>
            </tr>
        `;
        $('#slabRows').append(row);
    });

    $(document).on('click', '.removeRow', function () {
        $(this).closest('tr').remove();
    });

    let slabs = [];

    $('#slabRows tr').each(function () {
        let from = $(this).find('input:eq(0)').val();
        let to   = $(this).find('input:eq(1)').val();
        let rate = $(this).find('input:eq(2)').val();

        // Skip empty rows
        if (
            from === '' ||
            rate === '' ||
            isNaN(from) ||
            isNaN(rate)
        ) {
            return;
        }

        slabs.push({
            from_amount: parseFloat(from),
            to_amount: to ? to : 'Above',
            tax_rate: parseFloat(rate)
        });
    });


    // Only append if slabs exist
    if (slabs.length > 0) {
        formData.append('salary_slabs', JSON.stringify(slabs));
    }



</script>

@endsection