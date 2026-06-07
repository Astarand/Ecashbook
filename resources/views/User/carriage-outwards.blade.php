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
                        <li class="breadcrumb-item"><a href="{{ url('/sales-invoices') }}">Sales</a></li>
                        <li class="breadcrumb-item" aria-current="page">Carriage Outward</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Carriage Outward</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ url('/add-carriage-outward') }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Carriage Outward</a>
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
                                <th>Date</th>
                                <th>Invoice Number</th>
                                <th>Customer Name</th>
                                <th>Mode Of Payment</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($carrouts as $carrout)
                            <tr>
                                <td class="text-end"><?php echo $i++; ?></td>
                                <td><a class="text-muted text-hover-primary" href="#">{{$carrout->created_at}}</a></td>
                                <td><span class="text-muted text-hover-primary">{{$carrout->inv_num}}</span></td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="mb-1">{{$carrout->seller_name}}</h6>
                                            <a class="text-muted f-12 text-hover-primary" href="tel:919609412418">+91-{{$carrout->seller_contact}}</a>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-muted text-hover-primary">{{$carrout->other_mod_pay}}</span></td>
                                <td><span class="text-muted text-hover-primary">₹{{$carrout->other_total_amount}}</span></td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a
                                                    href="{{ url('/view-carriageout/'.base64_encode($carrout->id)) }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                <a href="{{ url('/edit-carriageout/'.base64_encode($carrout->id)) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                <a href="#" data-id="{{$carrout->id}}" class="avtar avtar-xs btn-link-danger btn-pc-default delete-btn" data-bs-toggle="modal" data-bs-target="#delete_modal">
                                                    <i class="ti ti-trash f-18"></i>
                                                </a>
                                            </li>
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

<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Carriage Outward</h3>
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

@endsection
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        let deleteId = null; // Store the ID of the Carriage to be deleted

        // Capture the Carriage ID when the delete button is clicked
        $('.delete-btn').on('click', function() {
            deleteId = $(this).data('id');
            //alert(deleteId);
        });
        // alert(deleteId);
        // Handle the delete confirmation
        $('#confirmDelete').on('click', function() {
            //alert('hello');
            if (deleteId) {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                $.ajax({
                    url: '/delcarrout/' + deleteId, // Update with your delete route
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

    });
</script>