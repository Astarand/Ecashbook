@extends('App.Layout')

@section('container')




<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="#">Business Operations</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/customer-list') }}">Customer & Receivables</a></li>
                        <li class="breadcrumb-item" aria-current="page">Customer & Receivables List</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-customer-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-5 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Customer & Receivables List</h2>
                    </div>
                </div>
                <div class="col-md-7 text-end mt-2">
                    <a href="{{ route('user.AddCustomer') }}" class="btn btn-primary" id="add-customer-btn"><i class="ti ti-square-plus"></i> Add New Customer / Buyer</a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card" id="customer-table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead>
                            <tr style="background-color: #cbcbcb;">
                                <th class="text-end">#</th>
                                <th>Customer ID</th>
                                <th>Name</th>
                                <th>Contact Number</th>
                                <th>Total Invoice</th>
                                <th>Created On</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($customers as $customer)
                            <tr>
                                <td class="text-end"><?php echo $i++; ?></td>
                                <td><span class="text-muted text-hover-primary">{{ $customer->customer_id }}</span></td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="mb-1">{{ $customer->cust_name }}</h6>
                                            <a class="text-muted f-12 text-hover-primary" href="mailto:{{ $customer->cust_email }}">{{ $customer->cust_email }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td><a class="text-muted text-hover-primary" href="#">{{ $customer->cust_phone }}</a></td>
                                <td><span class="text-muted text-hover-primary">{{ $customer->total_invoices }}</span></td>
                                <td><span class="text-muted text-hover-primary">{{ date('d-m-Y', strtotime($customer->created_at)) }}</span></td>
                                <td>
                                    @if ($customer->status==1)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-danger">Deactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            {{-- <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Generate Invoice">
                                                    <a
                                                        href="#"
                                                        class="avtar avtar-xs btn-link-warning btn-pc-default"
                                                        data-bs-toggle="offcanvas"
                                                        data-bs-target="#productOffcanvas">
                                                        <i class="ti ti-file f-18"></i>
                                                    </a>
                                                </li> --}}
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a
                                                    href="{{ url('/view-customer/'.base64_encode($customer->id)) }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                <a href="{{ url('/edit-customer/'.base64_encode($customer->id)) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
                                            {{-- <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                    <a href="#" 
                                                        data-id="{{ $customer->id }}"
                                            class="avtar avtar-xs btn-link-danger btn-pc-default delete-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#delete_modal">
                                            <i class="ti ti-trash f-18"></i>
                                            </a>
                                            </li> --}}
                                            @if ($customer->status==1)
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Deactivate">
                                                <a href="#" data-id="{{ $customer->id }}" data-status="0" class="status-change avtar avtar-xs btn-link-danger btn-pc-default status_update" data-bs-toggle="modal"
                                                    data-bs-target="#customer_status_update">
                                                    <i class="ti ti-bell-off f-18"></i>
                                                </a>
                                            </li>
                                            @else
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Activate">
                                                <a href="#" data-id="{{ $customer->id }}" data-status="1" class="status-change avtar avtar-xs btn-link-danger btn-pc-default status_update" data-bs-toggle="modal"
                                                    data-bs-target="#customer_status_update">
                                                    <i class="ti ti-bell-off f-18"></i>
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

<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Customer</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="confirmDelete" class="w-100 btn btn-danger">
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

<div class="modal custom-modal fade" id="customer_status_update" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3 id="modalTitle">Status Update Customer</h3>
                    <p id="modalText">Are you sure you want to update status?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="status_update_cust" class="w-100 btn btn-danger">
                                Yes
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-secondary paid-cancel-btn">
                                No
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
    let deleteId = null; // Store the ID of the customer to be deleted

    // Capture the customer ID when the delete button is clicked
    $('.delete-btn').on('click', function() {
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
                url: '/customer-delete/' + deleteId, // Update with your delete route
                type: 'DELETE',
                success: function(response) {
                    // alert(response.message); // Show success message
                    // location.reload(); // Reload the page

                    if (response.status == true) {
                        // showToast("Profile picture updated successfully!", "success");
                        showToast(response.message, "success");

                        setTimeout(() => location.reload(), 2000); // Reload after 2s
                    } else {
                        showToast("Error: " + response.message, "error");
                    }
                },
                error: function(xhr) {
                    alert("Error deleting customer!");
                }
            });
        }
    });

    //------------- Status Update ---------
    let statusId = null;
    let statusData = null;

    $('.status_update').on('click', function() {
        statusId = $(this).data('id');
        statusData = $(this).data('status');
    });

    $('#status_update_cust').on('click', function() {
        if (statusId !== null && statusData !== null) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                url: '/customer_status_update/' + statusId,
                type: 'POST',
                data: {
                    id: statusId,
                    status: statusData
                }, // Pass statusData
                success: function(response) {
                    // alert(response.message); // Show success message
                    // location.reload(); // Reload the page
                    if (response.status == true) {
                        // showToast("Profile picture updated successfully!", "success");
                        showToast(response.message, "success");
                        setTimeout(() => location.reload(), 2000); // Reload after 2s
                    } else {
                        showToast("Error: " + response.message, "error");
                    }
                },
                error: function(xhr) {
                    alert("Error updating customer status!");
                }
            });
        } else {
            alert("Invalid customer status update request!");
        }
    });


    //---------------------------------------------------------------

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.status-change').forEach(button => {
            button.addEventListener('click', function() {
                let status = this.getAttribute('data-status');
                let modalTitle = document.getElementById('modalTitle');
                let modalText = document.getElementById('modalText');

                if (status == 1) {
                    modalTitle.textContent = "Activate Customer";
                    modalText.textContent = "Are you sure you want to activate this customer?";
                } else {
                    modalTitle.textContent = "Deactivate Customer";
                    modalText.textContent = "Are you sure you want to deactivate this customer?";
                }
            });
        });
    });

    function startCustomerTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Customer Directory Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-users" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Manage your active customer directory, track their total invoices, and control account status.</p></div>'
                },
                {
                    element: '#add-customer-btn',
                    title: 'Add New Customer',
                    intro: 'Click here to create a new customer profile, complete with GST status, billing/shipping address, and bank accounts.'
                },
                {
                    element: '#customer-table-card',
                    title: 'Customer Database',
                    intro: 'Review your registered customer details, including unique Customer IDs, contact info, total invoices generated, and current status (Active/Deactive).'
                },
                {
                    element: '.prod-action-links',
                    title: 'Action Controls',
                    intro: 'Perform quick actions on each customer, such as View details, Edit profile, or toggle active/inactive status.'
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
        $('#start-customer-tour').on('click', function(e) {
            e.preventDefault();
            startCustomerTour();
        });
    });
</script>
@endsection