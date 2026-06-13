@extends('App.Layout')

@section('container')

<style>
    .spinner {
        border: 4px solid #f3f3f3; /* Light grey */
        border-top: 4px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="">Sales & Revenue</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/sales-quotation') }}">Quotation</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sales Quotation List</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-sales-quotation-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-5">
                    <div class="page-header-title">
                        <h2 class="mb-0">Sales Quotation List</h2>
                    </div>
                </div>
                <div class="col-md-7 text-end">
                    <a href="{{ route('user.CreateQuotationInvoices') }}" id="add-quotation-btn" class="btn btn-primary"><i
                            class="ti ti-square-plus"></i> Add New Quotation Invoice</a>
                </div>

            </div>
            <br>
            @if ($quotation_create_status == "false")
            <div class="alert alert-danger" role="alert">
                Unable to create quotation! Please complete your previous invoices first.
            </div>
            @endif
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
								@if($hasProprietorship)
								<th>Proprietorship Company</th>
								@endif
                                <th>Customer Name</th>
                                <th>Quotation ID</th>
                                <th>Quotation Date</th>
                                <th>Quantity</th>
                                <th>Grand Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($sales as $sale)
                            <tr>
                                <td class="text-end">
                                    <?php echo $i++; ?>
                                </td>
								@if($hasProprietorship)
								<td><span class="text-muted text-hover-primary">{{ !empty($sale->prop_name) ? $sale->prop_name : $sale->comp_name }}</span></td>
                                @endif
								<td>
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="mb-1">{{ $sale->cust_name }}</h6>
                                            <a class="text-muted f-12 text-hover-primary"
                                                href="tel:+91{{ $sale->cust_phone }}">{{ $sale->cust_phone }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-muted text-hover-primary">{{ $sale->inv_num }}</span></td>
                                <td><a class="text-muted text-hover-primary" href="#">{{ date("d-m-Y",
                                        strtotime($sale->inv_date)) }}</a></td>
                                <td><span class="text-muted text-hover-primary">{{ $sale->total_qty ?? 0  }}</span></td>
                                <td><span class="text-muted text-hover-primary">₹&nbsp; {{ $sale->total_amount ?? 0  }}</span></td>
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
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                title="View Quotation">
                                                <a target="_blank" href="{{ url('/quotation-invoice-pdf/'.base64_encode($sale->id).'/quotation') }}"
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

											
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                title="View">
                                                <a href="{{ url('/view-quotation-invoice/'.base64_encode($sale->id)) }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
											@if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                title="Edit">
                                                <a href="{{ url('/edit-quotation-invoice/'.base64_encode($sale->id)) }}"
                                                    class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                title="Delete">
                                                <a href="javascript:void(0);" data-id="{{$sale->id}}"
                                                    class="avtar avtar-xs btn-link-danger btn-pc-default delete-btn"
                                                    data-bs-toggle="modal" data-bs-target="#delete_modal">
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
                <input type="hidden" id="quotation_id">

                <label>Status</label>
                <select id="quotation_status" class="form-control">
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
                    <h3>Delete Quotation Invoice</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="confirmDelete" data-bs-dismiss="modal"
                                class="w-100 btn btn-primary">
                                Delete
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal"
                                class="w-100 btn btn-secondary paid-cancel-btn">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    function startSalesQuotationTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Sales Quotation Registry',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-file" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Review, create, and manage your sales quotations and customer inquiries.</p></div>'
                },
                {
                    element: '#add-quotation-btn',
                    title: 'New Quotation',
                    intro: 'Click here to create a new quotation for a customer with item details, rates, and tax terms.'
                },
                {
                    element: '.table-responsive',
                    title: 'Quotations Directory',
                    intro: 'Browse all quotation history, customer identifiers, issue dates, quantities, and totals.'
                },
                {
                    element: '.prod-action-links',
                    title: 'Actions',
                    intro: 'Use these options to view/download PDFs, edit draft records, delete invoices, or change status parameters.'
                }
            ],
            showBullets: true,
            showProgress: true,
            helperElementPadding: 5,
            exitOnOverlayClick: false,
            skipIfNoElement: true,
            doneLabel: 'Done',
            nextLabel: 'Next',
            prevLabel: 'Prev',
            skipLabel: 'Skip'
        }).start();
    }

    $(document).ready(function() {
        $('#start-sales-quotation-tour').on('click', function(e) {
            e.preventDefault();
            startSalesQuotationTour();
        });
    });


	$(document).on('click', '.status-btn', function () {
		let id = $(this).data('id');
		let status = $(this).data('status');

		$('#quotation_id').val(id);
		$('#quotation_status').val(status);

		$('#statusModal').modal('show');
	});

	// UPDATE STATUS
	$('#updateStatus').on('click', function () {

		let id = $('#quotation_id').val();
		let status = $('#quotation_status').val();

		$.ajax({
			url: "{{ route('quotation.update-quotation-status') }}",
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

	
    let deleteId = null; 
    //delete button is clicked
    $(document).on('click', '.delete-btn', function () {
		deleteId = $(this).data('id');
	});
    // Handle the delete confirmation
    $('#confirmDelete').on('click', function() {
        if (deleteId) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });
			$("#loader").show();
            $.ajax({
                url: '/delQuotationInvoice/' + deleteId, 
                type: 'DELETE',
                success: function(response) {
					$("#loader").hide();
                    alert(response.message); 
                    location.reload(); 
                },
                error: function(xhr) {
                    alert("Error deleting quotation!");
                }
            });
        }
    });



</script>





@endsection