@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <ul class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Task Management</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-employee-task-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <u>How does this Page works?</u>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Assign Task List</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- Main content -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card" id="tasks-table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                          <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Task Title</th>
                                  <th>Priority</th>
                                  <th>Deadline</th>
                                  <th>Status</th>
                                  <th>Action</th>
                              </tr>
                          </thead>
                          {{-- <tbody>
                              <tr>
                                  <td>1</td>
                                  <td>Complete Project Documentation</td>
                                  <td>
                                      <span class="badge bg-danger">
                                          High
                                      </span>
                                  </td>
                                  <td>2025-10-15 14:00</td>
                                  <td>
                                      <span class="badge bg-warning">
                                          Pending
                                      </span>
                                  </td>
                                  <td><span><i class="ti ti-dots-vertical f-20"></i></span>
                                      <div class="prod-action-links">
                                          <ul class="list-inline me-auto mb-0">
                                              <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="View & Edit"><a href="#!" class="avtar avtar-xs btn-link-success btn-pc-default" data-bs-toggle="modal" data-bs-target="#editTaskModal"><i class="ti ti-edit-circle f-18"></i></a></li>
                                          </ul>
                                      </div>
                                  </td>
                              </tr>
                              <tr>
                                  <td>2</td>
                                  <td>Review Code Changes</td>
                                  <td>
                                      <span class="badge bg-warning">
                                          Medium
                                      </span>
                                  </td>
                                  <td>2025-10-12 10:00</td>
                                  <td>
                                      <span class="badge bg-info">
                                          In Progress
                                      </span>
                                  </td>
                                  <td><span><i class="ti ti-dots-vertical f-20"></i></span>
                                      <div class="prod-action-links">
                                          <ul class="list-inline me-auto mb-0">
                                              <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="View & Edit"><a href="#!" class="avtar avtar-xs btn-link-success btn-pc-default" data-bs-toggle="modal" data-bs-target="#editTaskModal"><i class="ti ti-edit-circle f-18"></i></a></li>
                                          </ul>
                                      </div>
                                  </td>
                              </tr>
                              <tr>
                                  <td>3</td>
                                  <td>Client Meeting Preparation</td>
                                  <td>
                                      <span class="badge bg-info">
                                          Low
                                      </span>
                                  </td>
                                  <td>2025-10-10 09:00</td>
                                  <td>
                                      <span class="badge bg-success">
                                          Completed
                                      </span>
                                  </td>
                                  <td><span><i class="ti ti-dots-vertical f-20"></i></span>
                                      <div class="prod-action-links">
                                          <ul class="list-inline me-auto mb-0">
                                              <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="View & Edit"><a href="#!" class="avtar avtar-xs btn-link-success btn-pc-default" data-bs-toggle="modal" data-bs-target="#editTaskModal"><i class="ti ti-edit-circle f-18"></i></a></li>
                                          </ul>
                                      </div>
                                  </td>
                              </tr>
                          </tbody> --}}
                      </table>
                </div>
            </div>
        </div>

        <!-- Edit Task Modal -->
        <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="modal-title fw-bold" id="editTaskModalLabel">
                <i class="ti ti-edit-circle me-2"></i> Update Task Status
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="updateTaskForm">
                <input type="hidden" id="task_id">

                <div class="mb-3">
                    <label class="form-label fw-bold">Task Title</label>
                    <input type="text" id="task_title" class="form-control" readonly>
                </div>

                

                <div class="mb-3">
                    <label class="form-label fw-bold">Deadline</label>
                    <input type="text" id="task_deadline" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Priority</label>
                    <input type="text" id="task_priority" class="form-control" readonly>
                </div>
                <div class="mb-3"><label class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" readonly></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Current Status</label>
                    <select id="task_status" class="form-select">
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                    </select>
                </div>
                <button type="submit" id="updateBtn" class="btn btn-primary w-100 fw-bold">
                    <i class="ti ti-refresh me-1"></i> Update Status
                </button>
                </form>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')

<script>
    //****** Get Employee Tasks *******//
    $(document).ready(function() {
        
        loadEmployeeTasks();
    });

    function loadEmployeeTasks() {
        const tbody = $("#pc-dt-simple tbody");
        tbody.html(`
            <tr>
                <td colspan="6" class="text-center py-4">
                    <div class="spinner-border text-primary me-2" role="status"></div>
                    <span class="text-primary fw-semibold">Loading tasks...</span>
                </td>
            </tr>
        `);
        $.ajax({
            url: "{{ route('employee.tasks') }}",
            method: "GET",
            success: function(response) {
                if (response.status === 'success') {
                    const tasks = response.data;
                    const tbody = $("#pc-dt-simple tbody");
                    tbody.empty(); // Clear old data

                    if (tasks.length === 0) {
                        tbody.append(`
                            <tr>
                                <td colspan="6" class="text-center text-muted">No tasks found</td>
                            </tr>
                        `);
                        return;
                    }

                    tasks.forEach((task, index) => {
                        let priorityBadge = getPriorityBadge(task.priority);
                        let statusBadge = getStatusBadge(task.status);

                        tbody.append(`
                            <tr class="text-start">
                                <td>${index + 1}</td>
                                <td>${task.title}</td>
                                <td>${priorityBadge}</td>
                                <td>${formatDate(task.due_date)}</td>
                                <td>${statusBadge}</td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" 
                                                data-bs-toggle="tooltip" 
                                                aria-label="Edit" 
                                                data-bs-original-title="View & Edit">
                                                <a href="#!" 
                                                class="avtar avtar-xs btn-link-success btn-pc-default" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editTaskModal" 
                                                onclick="viewTaskDetails(${task.id})">
                                                <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    alert(response.message || "Failed to load tasks");
                }
            },
            error: function() {
                alert("Unable to fetch task data.");
            }
        });
    }

    // Helper functions
    function formatDate(dateStr) {
        if (!dateStr) return '--';
        const date = new Date(dateStr);
        return date.toLocaleString('en-IN', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function getPriorityBadge(priority) {
        switch (priority.toLowerCase()) {
            case 'high':
                return `<span class="badge bg-danger">High</span>`;
            case 'medium':
                return `<span class="badge bg-warning">Medium</span>`;
            default:
                return `<span class="badge bg-info">Low</span>`;
        }
    }

    function getStatusBadge(status) {
        switch (status.toLowerCase()) {
            case 'pending':
                return `<span class="badge bg-warning">Pending</span>`;
            case 'in progress':
                return `<span class="badge bg-info">In Progress</span>`;
            case 'completed':
                return `<span class="badge bg-success">Completed</span>`;
            default:
                return `<span class="badge bg-secondary">${status}</span>`;
        }
    }


    //--------- Upadte task staus ---------//
    function viewTaskDetails(id) {
    // Fetch the task details from current loaded table list (already in DOM)
    $.ajax({
        url: "{{ route('employee.tasks') }}",
        method: "GET",
        success: function(response) {
            if (response.status === 'success') {
                const task = response.data.find(t => t.id === id);
                if (!task) return showToast("Task not found!", 'error');

                $('#task_id').val(task.id);
                $('#task_title').val(task.title);
                $('#task_priority').val(task.priority);
                $('#description').val(task.description);
                $('#task_deadline').val(formatDate(task.due_date));
                $('#task_status').val(task.status);

                // Disable update button if already completed
                if (task.status.toLowerCase() === 'completed') {
                    $('#task_status').prop('disabled', true);
                    $('#updateBtn').prop('disabled', true).text('Already Completed');
                } else {
                    $('#task_status').prop('disabled', false);
                    $('#updateBtn').prop('disabled', false).text('Update Status');
                }

                $('#editTaskModal').modal('show');
            }
        }
    });
    }

$('#updateTaskForm').on('submit', function(e) {
    e.preventDefault();
    const id = $('#task_id').val();
    const status = $('#task_status').val();

    $.ajax({
        url: "{{ route('employee.task.update') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            id: id,
            status: status
        },
        success: function(response) {
            if (response.status === 'success') {
                $('#editTaskModal').modal('hide');
                loadEmployeeTasks(); // reload table
                showToast(response.message, 'success');
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Something went wrong while updating task.', 'error');
        }
    });
});

function startEmployeeTaskTour() {
    if (typeof introJs !== 'function') return;

    introJs().setOptions({
        steps: [
            {
                title: 'Task Management Guide',
                intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-clipboard" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Track and manage all official tasks assigned to you by company administrators or department managers.</p></div>'
            },
            {
                element: '#tasks-table-card',
                title: 'Assigned Tasks Table',
                intro: 'List of your tasks showing titles, priorities (High, Medium, Low), deadlines, and current progress status.'
            },
            {
                element: '.avtar.btn-link-success',
                title: 'Update Task Progress',
                intro: 'Click the edit icon on any task to open the status updater modal. You can toggle the status between Pending, In Progress, and Completed.',
                skipIfNoElement: true
            }
        ],
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

$(document).ready(function() {
    $('#start-employee-task-tour').on('click', function(e) {
        e.preventDefault();
        startEmployeeTaskTour();
    });
});
</script>

@endsection
