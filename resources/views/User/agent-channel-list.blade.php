@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Agent List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ route('user.AddAgentChannelList') }}" class="btn btn-primary">
                        <i class="ti ti-square-plus"></i> Add New Agent
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
                                <th>Agent ID</th>
                                <th>Name</th>
                                <th>Contact Number</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-end">1</td>
                                <td>
                                    <a class="text-muted text-hover-primary" href="#">AGT-001</a>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-auto pe-0">
                                            <div class="avtar avtar-s btn-light-primary">
                                                <img src="../assets/images/user/avatar-2.jpg" alt="John Doe" class="wid-40 rounded">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h6 class="mb-1">John Doe</h6>
                                            <a class="text-muted f-12 text-hover-primary" href="mailto:johndoe@mail.com">
                                                johndoe@mail.com
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a class="text-muted text-hover-primary" href="#">+91 9876543210</a>
                                </td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a href="javascript:void(0);" class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                <a href="javascript:void(0);" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Deactivate">
                                                <a href="javascript:void(0);"
                                                   class="avtar avtar-xs btn-link-warning btn-pc-default"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#status_modal"
                                                   data-id="1"
                                                   data-status="1">
                                                    <i class="ti ti-bell-off f-18"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <!-- Static Row 2 -->
                            <tr>
                                <td class="text-end">2</td>
                                <td>
                                    <a class="text-muted text-hover-primary" href="#">AGT-002</a>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-auto pe-0">
                                            <div class="avtar avtar-s btn-light-primary">
                                                <img src="../assets/images/user/avatar-2.jpg" alt="Jane Smith" class="wid-40 rounded">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h6 class="mb-1">Jane Smith</h6>
                                            <a class="text-muted f-12 text-hover-primary" href="mailto:janesmith@mail.com">
                                                janesmith@mail.com
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a class="text-muted text-hover-primary" href="#">+91 9123456780</a>
                                </td>
                                <td>
                                    <span class="badge bg-danger">De-Active</span>
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a href="javascript:void(0);" class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                <a href="javascript:void(0);" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Activate">
                                                <a href="javascript:void(0);"
                                                   class="avtar avtar-xs btn-link-warning btn-pc-default"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#status_modal"
                                                   data-id="2"
                                                   data-status="0">
                                                    <i class="ti ti-bell f-18"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- Status Modal (now static / frontend-only) -->
<div class="modal custom-modal fade" id="status_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <form id="statusChangeForm">
                    <input type="hidden" id="modal_agent_id">
                    <input type="hidden" id="modal_status">

                    <div class="form-header text-center">
                        <h3 id="status_modal_title">Change Status</h3>
                        <p id="status_modal_text">Are you sure you want to update the status?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="w-100 btn btn-primary" data-bs-dismiss="modal">
                                    Yes
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-secondary">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal (unchanged, static) -->
<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Employee</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-primary">
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
    // Keep only front-end behavior for the status modal (no AJAX / backend).
    $('#status_modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var agentId = button.data('id');
        var currentStatus = button.data('status');
        var newStatus = currentStatus == 1 ? 0 : 1;

        $('#modal_agent_id').val(agentId);
        $('#modal_status').val(newStatus);

        $('#status_modal_title').text(newStatus == 1 ? 'Activate Agent' : 'Deactivate Agent');
        $('#status_modal_text').text(newStatus == 1
            ? 'Are you sure you want to activate this agent?'
            : 'Are you sure you want to deactivate this agent?');
    });

    // No form submit / AJAX – static only
</script>

@endsection
