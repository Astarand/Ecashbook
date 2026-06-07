@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Task List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="#" class="btn btn-success me-2" data-bs-toggle="tooltip" title="Whatsapp"><i class="ti ti-brand-whatsapp"></i></a>
                    <a href="#" class="btn btn-secondary me-2" data-bs-toggle="tooltip" title="Download Now"><i class="ti ti-download"></i></a>
                    @if (Auth::user()->u_type == 1)
					<a href="{{ route('ca.AddTask') }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Task</a>
					@endif
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-body table-card">
                    <table class="table tbl-product" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
                                <th>Task ID</th>
                                <th>Date  & Time</th>
                                <th>Company Name</th>
                                <th>Task Category</th>
                                <th>Assign Employee</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; ?>
                        @foreach ($tasks as $val)
                            <tr>
                                <td class="text-end">{{$i++}}</td>
                                <td><a class="text-muted text-hover-primary" href="#">{{$val->task_id}}</a></td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="mb-1">{{$val->task_date}}</h6>
                                            <a class="text-muted f-12 text-hover-primary">{{$val->task_time}}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="mb-1">{{$val->name}}</h6>
                                            <a class="text-muted f-12 text-hover-primary" href="mailto:demo@mail.com">{{$val->email}}</a>
                                        </div>
                                    </div>
                                </td>
                                <td><a class="text-muted text-hover-primary" href="#">{{$val->task_category_name}}</a></td>
                                <td><a class="text-muted text-hover-primary" href="#">{{$val->empname ?: 'None'}}</a></td>
                                <td><a class="text-muted text-hover-primary" href="#">{{$val->due_date}}</a></td>
                                @if ($val->project_status==1)
                                <td>
                                    <span class="badge bg-danger">Pending</span>
                                </td>
                                @elseif($val->project_status==2)
                                <td>
                                    <span class="badge bg-warning">Ongoing</span>
                                </td>
                                @else
                                <td>
                                    <span class="badge bg-success">Completed</span>
                                </td>
                                @endif
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a
                                                    href="{{ url('/view-task/'.base64_encode($val->id)) }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default"
                                                    >
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
											
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                <a href="{{ url('/edit-task/'.base64_encode($val->id)) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
                                            @if (Auth::user()->u_type == 1)
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                <a href="#" data-id="{{ $val->id }}" class="delete-task-btn avtar avtar-xs btn-link-danger btn-pc-default" data-bs-toggle="modal" data-bs-target="#delete_modal">
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

<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Task</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <input type="hidden" id="delete_task_id" value="">
                            <button type="button" id="confirmDeleteTask" class="w-100 btn btn-danger">
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
    $(document).on('click', '.delete-task-btn', function () {
    var taskId = $(this).data('id');
    $('#delete_task_id').val(taskId);
});

$('#confirmDeleteTask').on('click', function () {
    var taskId = $('#delete_task_id').val();

    $.ajax({
        url: '/task/delete/' + taskId, // Adjust your route accordingly
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
        success: function (response) {
            $('#delete_modal').modal('hide');
            if (response.status === 'success') {
                showToast(response.message, 'success');
                setTimeout(function () {
                    location.reload();
                }, 1500);
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function () {
            $('#delete_modal').modal('hide');
            showToast('An error occurred while deleting the task.', 'error');
        }
    });
});

</script>

@endsection