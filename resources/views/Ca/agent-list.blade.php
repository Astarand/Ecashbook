@extends('App.Layout')

@section('container')

<div class="pc-content">
	<!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="#">Business Operations</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/agent-list') }}">Agent & Channel Partner</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Agent Record</li>
                    </ul>
                </div>
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h2 class="mb-0">Agent & Channel Partner</h2>
                    </div>
                </div>
				<div class="col-md-4 text-end">
                    <a href="{{ route('ca.AddAgent') }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Agent</a>
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
                        <?php $i = 1; ?>
                        @foreach ($agents as $agent)
                            <tr>
                                <td class="text-end"><?php echo $i++; ?></td>
                                <td><a class="text-muted text-hover-primary" href="#">{{ $agent->agent_id }}</a></td>
                                <td>
                                    <div class="row">

                                        <div class="col-auto pe-0">
                                            <div class="avtar avtar-s btn-light-primary">
                                                <img src="{{ $agent->agent_image ? asset('storage/business_agent/' . $agent->agent_image) : asset('assets/images/user/avatar-2.jpg') }}" alt="{{ $agent->agent_name }}" class="wid-40 rounded">
                                            </div>
                                        </div>
                                        
                                        <div class="col">
                                            <h6 class="mb-1">{{ $agent->agent_name }}</h6>
                                            <a class="text-muted f-12 text-hover-primary" href="mailto:{{ $agent->agent_email }}">{{ $agent->agent_email }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td><a class="text-muted text-hover-primary" href="#">+91{{ $agent->agent_phone }}</a></td>
                                <td>
                                @if ($agent->status==0) 
                                    <span class="badge bg-danger">De-Active</span>
                                    @else
                                    <span class="badge bg-success">Active</span>
                                </td>
                                @endif
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a
                                                    href="{{ url('/agent-view/'.base64_encode($agent->id)) }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                <a href="{{ url('/edit-agent/'.base64_encode($agent->id)) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
                                            {{-- <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                <a href="#" class="avtar avtar-xs btn-link-danger btn-pc-default" data-bs-toggle="modal" data-bs-target="#delete_modal">
                                                    <i class="ti ti-trash f-18"></i>
                                                </a>
                                            </li> --}}
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="{{ $agent->status == 1 ? 'Deactivate' : 'Activate' }}">
                                                <a href="javascript:void(0);"
                                                   class="avtar avtar-xs btn-link-warning btn-pc-default"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#status_modal"
                                                   data-id="{{ $agent->id }}"
                                                   data-status="{{ $agent->status }}">
                                                    <i class="ti {{ $agent->status == 1 ? 'ti-bell-off' : 'ti-bell' }} f-18"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal custom-modal fade" id="status_modal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-md">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <form id="statusChangeForm">
                                                @csrf
                                                <input type="hidden" name="agent_id" id="modal_agent_id">
                                                <input type="hidden" name="status" id="modal_status">
                            
                                                <div class="form-header text-center">
                                                    <h3 id="status_modal_title">Change Status</h3>
                                                    <p id="status_modal_text">Are you sure you want to update the status?</p>
                                                </div>
                                                <div class="modal-btn delete-action">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <button type="submit" class="w-100 btn btn-primary">Yes</button>
                                                        </div>
                                                        <div class="col-6">
                                                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-secondary">Cancel</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

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

    $('#statusChangeForm').on('submit', function (e) {
    e.preventDefault();

    var formData = $(this).serialize();
    var suburl = "/agent-status-change"; // fixed route to match backend
    

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST', 
        url: suburl,
        data: formData,
        success: function (response) {
            $('#status_modal').modal('hide');

            if (response.status === 'success') {
                showToast(response.message, 'success');
            } else {
                showToast(response.message, 'error');
            }

            setTimeout(function () {
                location.reload();
            }, 2000);
        },
        error: function (xhr) {
            $('#status_modal').modal('hide');
            showToast("An error occurred while updating status", 'error');

            // Optional: console log for debugging
            console.error(xhr.responseText);
        }
    });
});

</script>

@endsection