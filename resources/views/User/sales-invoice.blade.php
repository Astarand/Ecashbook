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
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="">Sales & Revenue</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sales Invoices</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Sales Invoice List</h2>
                    </div>
                </div>
				@if ($invoice_create_status == "true")
                <div class="col-md-8 text-end">
                    <a href="{{ route('user.CreateSalesInvoices') }}" class="btn btn-primary"><i
                            class="ti ti-square-plus"></i> Add New Sales Invoice</a>
                </div>
				@endif

            </div>
            <br>
            @if ($invoice_create_status == "false")
            <div class="alert alert-danger" role="alert">
                Maximum 2 invoices can remain pending. Please clear previous invoices to create a new one.
            </div>
            @endif
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card">
				<!--<div class="row mb-3">
					<div class="col-md-12 d-flex justify-content-end">
						<div class="input-group" style="max-width: 650px;">

							<input type="text"
								   name="search"
								   id="searchInvoice"
								   class="form-control"
								   placeholder="Search Invoice No / Customer"
								   value="{{ request('search') }}">

							<input type="date"
								   name="search_date"
								   id="searchDate"
								   class="form-control"
								   value="{{ request('search_date') }}">

							<button type="button"
									id="searchBtn"
									class="btn btn-primary">
								Search
							</button>

							@if(request('search') || request('search_date'))
								<a href="{{ url()->current() }}"
								   class="btn btn-light-danger">
									Reset
								</a>
							@endif

						</div>
					</div>
				</div>-->
				
				<div class="table-responsive">
					<table class="table tbl-product"  id="pc-dt-simple">
						<thead>
							<tr>
								<th class="text-end">#</th>
								@if($hasProprietorship)
								<th>Proprietorship Company</th>
								@endif
								<th>Customer Name</th>
								<th>Invoice Number</th>
								<th>Invoice Date</th>
								<th>Transation Type</th>
								<th>Grand Total</th>
								<th>Payment Status</th>
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
								<td><span class="text-muted text-hover-primary">{{ $sale->mode_of_pay }}
										@if ($sale->mode_of_pay == 'OTHER')
										[{{ $sale->other_payment }}]
										@endif
									</span>
								</td>
								<td><span class="text-muted text-hover-primary">₹&nbsp; {{ $sale->total_amount }}</span>
								</td>
								<td>
									@if ($sale->pay_status == 'Full')
									<span class="badge bg-success">Full Payment</span>
									@elseif ($sale->pay_status == 'Partial')
									<span class="badge bg-warning text-dark">Advance</span>                                    
									@else
									<span class="badge bg-secondary">Incomplete</span>
									@endif
								</td>
								<td>
									@if ($sale->status == '0')
									<span class="badge bg-warning">Pending</span>
									@elseif ($sale->status == '1')
									<span class="badge bg-success">Active</span>    
									@elseif ($sale->status == '2')
									<span class="badge bg-danger">Cancelled</span>    
									@endif
								</td>
								<td>
									<span><i class="ti ti-dots-vertical f-20"></i></span>
									<div class="prod-action-links">
										<ul class="list-inline me-auto mb-0">
											<li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
												title="View Invoice">
												<a target="_blank" href="{{ url('/sales-invoice-pdf/'.base64_encode($sale->id).'/invoice') }}"
													class="avtar avtar-xs btn-link-warning btn-pc-default">
													<i class="ti ti-file f-18"></i>
												</a>
											</li>
											<li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
												title="View">
												<a href="{{ url('/view-sales-invoice/'.base64_encode($sale->id)) }}"
													class="avtar avtar-xs btn-link-warning btn-pc-default">
													<i class="ti ti-eye f-18"></i>
												</a>
											</li>
											@if ($sale->status != '2' && (Auth::user()->u_type == 2 || Auth::user()->u_type == 5))
											<li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
												title="Edit">
												<a href="{{ url('/edit-sales-invoice/'.base64_encode($sale->id)) }}"
													class="avtar avtar-xs btn-link-success btn-pc-default">
													<i class="ti ti-edit-circle f-18"></i>
												</a>
											</li>
											@endif
											@if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
											<li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
												title="Delete">
												<a href="#" data-id="{{$sale->id}}"
													class="avtar avtar-xs btn-link-danger btn-pc-default delete-btn"
													data-bs-toggle="modal" data-bs-target="#delete_modal">
													<i class="ti ti-trash f-18"></i>
												</a>
											</li>
											@endif
										  {{--  <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
												title="Payment History">
												<a href="{{ url('/payment_history/'.base64_encode($sale->id)) }}" class="avtar avtar-xs btn-link-primary btn-pc-default">
													<i class="ti ti-currency-rupee f-18"></i>
												</a>
											</li>--}}
										</ul>
									</div>

								</td>
							</tr>
							@endforeach
						</tbody>
					</table>					
					<div class="mt-3 d-flex justify-content-end">
						
					</div>
				</div>
			</div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>



<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Invoice</h3>
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

<script>

	$(document).ready(function () {

		function filterInvoice() {

			let search = $('#searchInvoice').val();
			let searchDate = $('#searchDate').val();

			let url = "{{ url('/sale-invoices') }}?search=" + encodeURIComponent(search)
						+ "&search_date=" + encodeURIComponent(searchDate);

			window.location.href = url;
		}

		// Search button click
		$('#searchBtn').on('click', function () {
			filterInvoice();
		});

		// Enter key press
		$('#searchInvoice').on('keypress', function (e) {

			if (e.which == 13) {
				filterInvoice();
			}

		});

	});
	
	
    let deleteId = null; 
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

            $.ajax({
                url: '/delInvoice/' + deleteId, // Update with your delete route
                type: 'DELETE',
                success: function(response) {
                    alert(response.message); // Show success message
                    location.reload(); // Reload the page
                },
                error: function(xhr) {
                    alert("Error deleting invoice!");
                }
            });
        }
    });



</script>





@endsection