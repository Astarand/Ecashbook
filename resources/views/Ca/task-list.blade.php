@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/ca-dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Task List</li>
                    </ul>
                    <a href="javascript:void(0);" onclick="startCATasksTour();" id="start-ca-tasks-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Task List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end mt-2">
                    <a href="#" class="btn btn-success me-2" data-bs-toggle="tooltip" title="Whatsapp"><i class="ti ti-brand-whatsapp"></i></a>
                    <a href="#" class="btn btn-secondary me-2" data-bs-toggle="tooltip" title="Download Now"><i class="ti ti-download"></i></a>
                    @if (Auth::user()->u_type == 1)
					<a href="{{ route('ca.AddTask') }}" class="btn btn-primary" id="add-task-btn"><i class="ti ti-square-plus"></i> Add New Task</a>
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
            <div class="card table-card" id="tasks-table-card">
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
@endsection

@section('page-script')
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

<script>
    function startCATasksTour() {
        function launch() {
            const steps = [
                {
                    title: 'Task Management Portal',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-list-check" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Oversee and manage client tasks, update progress status, and monitor deadlines.</p></div>'
                },
                {
                    element: '#tasks-table-card',
                    title: 'Tasks Database Table',
                    intro: 'View a detailed list of tasks including Task ID, Date/Time, Client Name, Category, Assigned Employee, Due Date, and Project Status.'
                }
            ];

            // If add task button exists (for CA admin u_type 1)
            if (document.getElementById('add-task-btn')) {
                steps.splice(1, 0, {
                    element: '#add-task-btn',
                    title: 'Create New Task',
                    intro: 'Click here to initialize and delegate a new task for a client and assign it to an employee.'
                });
            }

            introJs().setOptions({
                steps: steps,
                showBullets: true,
                showProgress: true,
                helperElementPadding: 5,
                exitOnOverlayClick: false,
                doneLabel: 'Done',
                nextLabel: 'Next',
                prevLabel: 'Prev',
                skipLabel: 'Skip'
            }).start();
        }

        if (typeof introJs === 'function') {
            launch();
        } else {
            if (!document.getElementById('introjs-cdn-css')) {
                let css = document.createElement('link');
                css.id = 'introjs-cdn-css';
                css.rel = 'stylesheet';
                css.href = 'https://cdn.jsdelivr.net/npm/intro.js@7.2.0/introjs.min.css';
                document.head.appendChild(css);
            }
            let js = document.createElement('script');
            js.src = 'https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js';
            js.onload = function() {
                launch();
            };
            document.body.appendChild(js);
        }
    }

    $(document).ready(function() {
        $('#start-ca-tasks-tour').on('click', function(e) {
            e.preventDefault();
            startCATasksTour();
        });
    });
</script>
@endsection