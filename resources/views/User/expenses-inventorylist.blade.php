@extends('App.Layout')

@section('container')

<div class="pc-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/expenses_inventorylist') }}">Expenses</a></li>
                        <li class="breadcrumb-item" aria-current="page">Inventory Expenses List</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h2 class="mb-0">Inventory Expenses List</h2>
                </div>
                @if($req_type != 1)
                    <div class="col-md-8 text-end">
                        <a href="{{ route('user.AddInventoryExpenses') }}" class="btn btn-primary">
                            <i class="ti ti-square-plus"></i> Add New Inventory Expenses
                        </a>
                    </div>
                @endif
                
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>#</th>
								@if($hasProprietorship)
                                <th>PROPRIETORSHIP COMPANY</th>
								@endif
                                <th>Date</th>
                                <th>Invoice / Ref</th>
                                <th>Expense Type</th>
                                <th>Voucher No</th>
                                <th>Amount</th>
                                <th>Supplier</th>
                                @if($req_type != 1)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $key => $exp)
                            <tr>
                                <td>{{ $key + 1 }}</td>
								@if($hasProprietorship)
								<td>{{ $exp->comp_name }}</td>
								@endif
                                <td>{{ date('d-m-Y', strtotime($exp->expense_date)) }}</td>
                                <td>{{ $exp->purchase_invoice_ref_no }}</td>
                                <td>{{ $exp->expense_types }}</td>
                                <td>{{ $exp->expense_voucher_no }}</td>
                                <td>{{ $exp->expense_amount }}</td>
                                <td>{{ $exp->supplier_name }}</td>
                                @if($req_type != 1)
                                <td>
                                    <button class="btn btn-sm btn-info"
                                        onclick="window.location.href='/edit_inventory_expenses/{{ $exp->id }}'">
                                        Edit
                                    </button>

                                    <button class="btn btn-sm btn-danger expenses"
                                        data-id="{{ $exp->id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#delete_modal">
                                        Delete
                                    </button>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="delete_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4>Delete Expense</h4>
                <p>Are you sure you want to delete this record?</p>
                <div class="row mt-3">
                    <div class="col-6">
                        <button type="button" id="del_exp" class="btn btn-danger w-100">
                            Delete
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary w-100">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
  
	let deleteId = null;

	$(document).on("click", ".expenses", function () {
		deleteId = $(this).data("id");
	});

	$("#del_exp").on("click", function () {
		if (!deleteId) return;

		$.ajax({
			headers: {
				"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
			},
			type: "DELETE",
			url: "/delete_inventory_expenses/" + deleteId,
			success: function (response) {
				showToast(response.message, "success");
				setTimeout(() => {
					location.reload();
				}, 1200);
			},
			error: function () {
				showToast("Something went wrong", "error");
			}
		});
	});  
	
	
</script>

@endsection