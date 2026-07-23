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
                        <li class="breadcrumb-item"><a href="{{ route('user.VendorList') }}">Vendors & Payables</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Vendors & Payables List</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-vendor-list-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Vendors & Payables List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ route('user.AddVendor') }}" class="btn btn-primary" id="add-vendor-btn"><i class="ti ti-square-plus"></i> Add New Vendor / Seller</a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card" id="vendor-table-card">
                <div class="table-responsive">
                    <table class="table tbl-product" id="pc-dt-simple">
                        <thead>
                            <tr style="background-color: #cdcdcd;">
                                <th class="text-end">#</th>
                                <th>Vendor ID</th>
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
                            @foreach ($vendors as $vendor)
                            <tr>
                                <!--<td class="text-end"><?php echo $i++; ?></td>-->
                                <td class="text-end">
                                    {{ $loop->iteration }}
                                </td>
                                <td><span class="text-muted text-hover-primary">{{ $vendor->vendor_id }}</span></td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="mb-1">{{ $vendor->vendor_name }}</h6>
                                            <a class="text-muted f-12 text-hover-primary" href="mailto:{{ $vendor->vendor_email }}">{{ $vendor->vendor_email }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td><a class="text-muted text-hover-primary" href="#">{{ $vendor->vendor_phone }}</a></td>
                                <td><span class="text-muted text-hover-primary">1</span></td>
                                <td><span class="text-muted text-hover-primary">{{ date('d-m-Y', strtotime($vendor->created_at)) }}</span></td>
                                <td>
                                    @if ($vendor->status==1)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-danger">Deactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a
                                                    href="{{ url('/view_vendor/'.base64_encode($vendor->id)) }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                <a href="{{ url('/edit-vendor/'.base64_encode($vendor->id)) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
                                            @if ($vendor->status==1)
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Deactivate">
                                                <a href="#" data-id="{{ $vendor->id }}" data-status="0" class="status-change avtar avtar-xs btn-link-danger btn-pc-default status_update" data-bs-toggle="modal"
                                                    data-bs-target="#vendor_status_update">
                                                    <i class="ti ti-bell-off f-18"></i>
                                                </a>
                                            </li>
                                            @else
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Activate">
                                                <a href="#" data-id="{{ $vendor->id }}" data-status="1" class="status-change avtar avtar-xs btn-link-danger btn-pc-default status_update" data-bs-toggle="modal"
                                                    data-bs-target="#vendor_status_update">
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

<div class="modal vendorom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Vendor</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-danger">
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

<div class="modal custom-modal fade" id="vendor_status_update" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3 id="modalTitle">Status Update vendor</h3>
                    <p id="modalText">Are you sure you want to update status?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="status_update_vendor" class="w-100 btn btn-danger">
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
    //------------- Status Update ---------
    let statusId = null;
    let statusData = null;

    $('.status_update').on('click', function() {
        statusId = $(this).data('id');
        statusData = $(this).data('status');
    });

    $('#status_update_vendor').on('click', function() {
        if (statusId !== null && statusData !== null) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                url: '/vendor_status_update/' + statusId,
                type: 'POST',
                data: {
                    id: statusId,
                    status: statusData
                },
                success: function(response) {
                    // alert(response.message); 
                    // location.reload(); 
                    if (response.class == "succ") {
                        // showToast("Profile picture updated successfully!", "success");
                        showToast(response.message, "success");
                        setTimeout(() => location.reload(), 2000); // Reload after 2s
                    } else {
                        showToast("Business Details Update: " + response.message, "error");
                    }
                },
                error: function(xhr) {
                    alert("Error updating vendor status!");
                }
            });
        } else {
            alert("Invalid vendor status update request!");
        }
    });

    //--------------------
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.status-change').forEach(button => {
            button.addEventListener('click', function() {
                let status = this.getAttribute('data-status');
                let modalTitle = document.getElementById('modalTitle');
                let modalText = document.getElementById('modalText');

                if (status == 1) {
                    modalTitle.textContent = "Activate vandor";
                    modalText.textContent = "Are you sure you want to activate this vandor?";
                } else {
                    modalTitle.textContent = "Deactivate vandor";
                    modalText.textContent = "Are you sure you want to deactivate this vandor?";
                }
            });
        });
    });
    
    function startVendorListTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Vendors Directory Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-users" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Manage company vendor directory, track outstanding bills and transaction invoices, and check status.</p></div>'
                },
                {
                    element: '#add-vendor-btn',
                    title: 'Add New Vendor',
                    intro: 'Click here to record a new business vendor profile, including basic contact, billing address, and bank accounts.'
                },
                {
                    element: '#vendor-table-card',
                    title: 'Vendor Directory Records',
                    intro: 'View information about your vendors, including their ID, name, email, contact number, total invoices, and active/inactive status.'
                },
                {
                    element: '.prod-action-links',
                    title: 'Vendor Action controls',
                    intro: 'Select these controls to view a vendor\'s ledger, edit profiles, or toggle active/inactive status.'
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
        $('#start-vendor-list-tour').on('click', function(e) {
            e.preventDefault();
            startVendorListTour();
        });
    });
</script>
@endsection