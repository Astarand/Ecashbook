@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="">Purchase & Procurement</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Purchase Order (PO)</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Purchase Order (PO)</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ route('user.CreatePurchaseOrder') }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Purchase Order</a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
								@if($hasProprietorship)
								<th>Proprietorship Company</th>
								@endif
                                <th>Vendor Name</th>
                                <th>Invoice Number</th>
                                <th>Invoice Date</th>
                                <th>Transation Type</th>
                                <th>Grand Total</th>
                                <th>Pay Status</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($sales as $sale)
                            <tr>
                                <td class="text-end"><?php echo $i++; ?></td>
								@if($hasProprietorship)
								<td><span class="text-muted text-hover-primary">{{ !empty($sale->prop_name) ? $sale->prop_name : $sale->comp_name }}</span></td>
                                @endif
								<td>
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="mb-1">{{ $sale->cust_name }}</h6>
                                            <a class="text-muted f-12 text-hover-primary" href="tel:{{ $sale->cust_phone }}">{{ $sale->cust_phone }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-muted text-hover-primary">{{ $sale->inv_num }}</span></td>
                                <td><a class="text-muted text-hover-primary" href="#">{{ date("d-m-Y", strtotime($sale->inv_date)) }}</a></td>
                                <td><span class="text-muted text-hover-primary">{{ $sale->mode_of_pay }}
                                        @if ($sale->mode_of_pay == 'OTHER')
                                        [{{ $sale->other_payment }}]
                                        @endif
                                    </span>
                                </td>
                                <td><span class="text-muted text-hover-primary">₹{{ $sale->grandTotal }}</span></td>
								<td>
                                    @if ($sale->pay_status == 'Full')
                                    <span class="badge bg-success">Full</span>
                                    @elseif ($sale->pay_status == 'Partial')
                                    <span class="badge bg-warning text-dark">Advance</span>                                    
                                    @else
                                    <span class="badge bg-secondary">Incomplete</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($sale->status == '0')
										<span class="badge bg-secondary">Partial Draft</span>
									@elseif ($sale->status == '1')
										<span class="badge bg-info text-dark">Draft</span>
									@elseif ($sale->status == '2')
										<span class="badge bg-warning text-dark">Sent</span>
									@elseif ($sale->status == '3')
										<span class="badge bg-success">Accepted</span>
									@elseif ($sale->status == '4')
										<span class="badge bg-danger">Rejected</span>
									@endif
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View Invoice">
                                                <a target="_blank" href="{{ url('/po-invoice-pdf/'.base64_encode($sale->id).'/invoice') }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-file f-18"></i>
                                                </a>
                                            </li>
											<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Change Status">
												<a href="javascript:void(0)"
												   data-id="{{ $sale->id }}"
												   data-status="{{ $sale->status }}"
												   class="avtar avtar-xs btn-link-primary btn-pc-default status-btn">
													<i class="ti ti-edit f-18"></i>
												</a>
											</li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a
                                                    href="{{ url('/view-po-invoice/'.base64_encode($sale->id)) }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
											@if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                <a href="{{ url('/edit-po-invoice/'.base64_encode($sale->id)) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
											
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                <a href="javascript:void(0);" data-id="{{$sale->id}}" class="avtar avtar-xs btn-link-danger btn-pc-default delete-btn" data-bs-toggle="modal" data-bs-target="#delete_modal">
                                                    <i class="ti ti-trash f-18"></i>
                                                </a>
                                            </li>
											@endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Change Quotation Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="po_id">

                <label>Status</label>
                <select id="po_status" class="form-control">
                    <option value="1">Draft</option>
					<option value="2">Sent</option>
                    <option value="3">Accepted</option>
                    <option value="4">Rejected</option>
                </select>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="updateStatus">Update</button>
            </div>
        </div>
    </div>
</div>

<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Invoice List Items</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="confirmDelete" data-bs-dismiss="modal" class="w-100 btn btn-primary">
                                Delete
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-secondary paid-cancel-btn">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
		
		$(document).on('click', '.status-btn', function () {
			let id = $(this).data('id');
			let status = $(this).data('status');

			$('#po_id').val(id);
			$('#po_status').val(status);

			$('#statusModal').modal('show');
		});

		// UPDATE STATUS
		$('#updateStatus').on('click', function () {

			let id = $('#po_id').val();
			let status = $('#po_status').val();

			$.ajax({
				url: "{{ route('po.update-po-status') }}",
				type: "POST",
				data: {
					_token: $('meta[name="csrf-token"]').attr('content'),
					id: id,
					status: status
				},
				success: function (res) {
					if (res.success) {
						location.reload(); 
					}
				}
			});
		});
	
        let deleteId = null; // Store the ID of the customer to be deleted

        // Capture the customer ID when the delete button is clicked
        $(document).on('click', '.delete-btn', function() {
            deleteId = $(this).data('id');
            console.log("Selected ID: " + deleteId);
            // If you're showing a modal for confirmation, trigger it here
            $('#deleteModal').modal('show'); 
        });

        // Handle the delete confirmation
        $('#confirmDelete').on('click', function() {
            if (deleteId) {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                $.ajax({
                    url: '/delInvoicePo/' + deleteId, // Update with your delete route
                    type: 'DELETE',
                    success: function(response) {
                        alert(response.message); // Show success message
                        location.reload(); // Reload the page
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert("Error deleting invoice!");
                    }
                });
            }
        });
    });
</script>


@endsection