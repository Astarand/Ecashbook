@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- Breadcrumb -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <ul class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">HR & Payroll Management</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Employee Task Management</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-task-management-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <u>How does this Page works?</u>
                        </a>
                    </div>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Task Management</h2>
                    </div>
                </div>
                 <div class="col-md-8 text-end mt-2">
                    <a href="#" class="btn btn-primary tour-assign-task" data-bs-toggle="modal" data-bs-target="#addTaskModal"><i class="ti ti-square-plus"></i> Assign New Task</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="row">
        <div class="col-xxl-12">
            <div class="card card-body table-card" id="task-table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead>
                            <tr style="background-color: #cbcbcb;">
                                <th class="text-end">#</th>
                                <th>Task Title</th>
                                <th>Assigned To</th>
                                <th>Priority</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignedTasks as $task)
                                <tr>
                                    <td class="text-end">{{ $loop->iteration }}</td>
                                    <td><strong class="text-dark">{{ $task->title }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avtar avtar-xs bg-light-primary text-primary me-2 rounded-circle">
                                                <i class="ti ti-user"></i>
                                            </div>
                                            <span class="fw-semibold">{{ $task->employee_name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($task->priority == 'High')
                                            <span class="badge bg-danger">High</span>
                                        @elseif($task->priority == 'Medium')
                                            <span class="badge bg-warning">Medium</span>
                                        @else
                                            <span class="badge bg-info">Low</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y, h:i A') }}</td>
                                    <td>
                                        @if($task->status == 'Pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($task->status == 'In Progress')
                                            <span class="badge bg-info">In Progress</span>
                                        @else
                                            <span class="badge bg-success">Completed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-light-primary tour-task-actions" data-bs-toggle="modal" data-bs-target="#viewTaskModal{{ $task->id }}" title="View Details"><i class="ti ti-eye"></i></a>
										<a href="javascript:void(0);" class="btn btn-sm btn-light-warning edit-task-btn" data-id="{{ $task->id }}" title="Edit Task"><i class="ti ti-edit"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-sm btn-light-danger delete-task-btn" data-id="{{ $task->id }}" title="Delete Task"><i class="ti ti-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form id="taskForm" class="modal-content border-0 shadow-lg" method="POST" action="{{ route('task.store') }}">
                @csrf
                <div class="modal-header bg-light py-3 border-bottom">
                    <h5 class="modal-title fw-bold text-dark" id="addTaskModalLabel">
                        <i class="ti ti-square-plus text-primary me-2 fs-4 align-middle"></i>Assign New Task
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Assignee Field -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">
                            Assign To <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="ti ti-user text-primary"></i></span>
                            <select name="employee_id" class="form-select border-start-0" required>
                                <option value="">Select Employee...</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->employee_id }}">{{ $employee->name }} (ID: {{ $employee->employee_id }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tasks List Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-semibold text-dark mb-0">
                                Task Items <span class="text-danger">*</span>
                            </label>
                            <span class="badge bg-light-primary text-primary fw-medium px-2 py-1">Add multiple tasks with priority & deadline</span>
                        </div>

                        <div id="taskTitleWrapper">
                            <div class="card border mb-3 task-row bg-light-subtle rounded-3 shadow-none">
                                <div class="card-body p-3">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-5">
                                            <label class="form-label small fw-semibold text-muted mb-1">Task Title <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   name="title[]"
                                                   class="form-control"
                                                   placeholder="e.g. Prepare Monthly GST Reports"
                                                   required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-semibold text-muted mb-1">Priority <span class="text-danger">*</span></label>
                                            <select name="priority[]" class="form-select" required>
                                                <option value="">Select Priority</option>
                                                <option value="High">🔴 High</option>
                                                <option value="Medium">🟡 Medium</option>
                                                <option value="Low">🔵 Low</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-semibold text-muted mb-1">Deadline <span class="text-danger">*</span></label>
                                            <input type="datetime-local"
                                                   name="due_date[]"
                                                   class="form-control"
                                                   required>
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <button type="button" class="btn btn-outline-primary w-100 add-task-row" title="Add Another Task">
                                                <i class="ti ti-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="mb-2">
                        <label class="form-label fw-semibold text-dark">
                            General Description / Instructions <span class="text-muted fw-normal">(Optional)</span>
                        </label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Provide any extra guidelines or instructions for this assignment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top py-2 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ti ti-check me-1"></i>Assign Task
                    </button>
                </div>
            </form>
        </div>
    </div>
	
	<!-- Single Edit Modal -->
	<div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<form class="modal-content border-0 shadow-lg" id="editTaskForm" method="POST">
				@csrf
				<div class="modal-header bg-light py-3 border-bottom">
					<h5 class="modal-title fw-bold text-dark"><i class="ti ti-edit text-primary me-2 fs-4 align-middle"></i>Edit Task</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>

				<div class="modal-body p-4">					
					<div class="row g-3">
						<div class="col-md-8">
							<label class="form-label fw-semibold text-dark">Task Title <span class="text-danger">*</span></label>
							<input type="text"
								   name="title"
								   id="edit_title"
								   class="form-control"
								   required>
						</div>

						<div class="col-md-4">
							<label class="form-label fw-semibold text-dark">Priority <span class="text-danger">*</span></label>
							<select name="priority"
									id="edit_priority"
									class="form-select"
									required>
								<option value="High">🔴 High</option>
								<option value="Medium">🟡 Medium</option>
								<option value="Low">🔵 Low</option>
							</select>
						</div>

						<div class="col-md-6">
							<label class="form-label fw-semibold text-dark">Deadline <span class="text-danger">*</span></label>
							<input type="datetime-local"
								   name="due_date"
								   id="edit_due_date"
								   class="form-control"
								   required>
						</div>

						<div class="col-md-6">
							<label class="form-label fw-semibold text-dark">Assign To <span class="text-danger">*</span></label>
							<select name="employee_id"
									id="edit_employee_id"
									class="form-select"
									required>
								@foreach($employees as $employee)
									<option value="{{ $employee->employee_id }}">
										{{ $employee->name }} (ID: {{ $employee->employee_id }})
									</option>
								@endforeach
							</select>
						</div>

						<div class="col-md-12">
							<label class="form-label fw-semibold text-dark">Description</label>
							<textarea name="description"
									  id="edit_description"
									  class="form-control" rows="3"></textarea>
						</div>

						<div class="col-md-12">
							<label class="form-label fw-semibold text-dark">Status <span class="text-danger">*</span></label>
							<select name="status"
									id="edit_status"
									class="form-select"
									required>
								<option value="Pending">🟡 Pending</option>
								<option value="In Progress">🔵 In Progress</option>
								<option value="Completed">🟢 Completed</option>
							</select>
						</div>
					</div>
				</div>

				<div class="modal-footer bg-light border-top py-2 px-4">
					<button type="button"
							class="btn btn-outline-secondary"
							data-bs-dismiss="modal">
						<i class="ti ti-x me-1"></i>Cancel
					</button>
					<button type="submit"
							class="btn btn-success px-4">
						<i class="ti ti-device-floppy me-1"></i>Update Task
					</button>
				</div>
			</form>
		</div>
	</div>

    {{-- View Task Modals --}}
    @foreach($assignedTasks as $task)
    <div class="modal fade" id="viewTaskModal{{ $task->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-light py-3 border-bottom">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="ti ti-file-description text-primary me-2 fs-4 align-middle"></i>Task Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                <div class="col-md-12">
                    <label class="small text-muted fw-bold">TASK TITLE</label>
                    <p class="fs-5 fw-bold text-dark mb-0">{{ $task->title }}</p>
                </div>
                <div class="col-md-6">
                    <label class="small text-muted fw-bold">ASSIGNED TO</label>
                    <p class="text-dark fw-semibold mb-0">{{ $task->employee_name }}</p>
                </div>
                <div class="col-md-6">
                    <label class="small text-muted fw-bold">PRIORITY</label>
                    <p class="mb-0">
                    <span class="badge
                        @if($task->priority == 'High') bg-danger
                        @elseif($task->priority == 'Medium') bg-warning
                        @else bg-info @endif">
                        {{ $task->priority }}
                    </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <label class="small text-muted fw-bold">DEADLINE</label>
                    <p class="text-dark fw-semibold mb-0">{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y, h:i A') }}</p>
                </div>
                <div class="col-md-6">
                    <label class="small text-muted fw-bold">STATUS</label>
                    <p class="mb-0">
                    <span class="badge
                        @if($task->status == 'Pending') bg-warning
                        @elseif($task->status == 'In Progress') bg-info
                        @else bg-success @endif">
                        {{ $task->status }}
                    </span>
                    </p>
                </div>
                <div class="col-md-12">
                    <label class="small text-muted fw-bold">DESCRIPTION</label>
                    <div class="p-3 bg-light rounded-3 border">
                        <p class="text-muted mb-0" style="white-space: pre-wrap;">{{ $task->description ?? 'No description provided.' }}</p>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="small text-muted fw-bold">LAST UPDATED</label>
                    <p class="text-muted small mb-0">{{ \Carbon\Carbon::parse($task->updated_at)->diffForHumans() }}</p>
                </div>
                </div>
            </div>

            <div class="modal-footer bg-light border-top py-2 px-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>

            </div>
        </div>
    </div>
    @endforeach

</div>
@endsection

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function getTaskRowTemplate(isFirst = false) {
        return `
            <div class="card border mb-3 task-row bg-light-subtle rounded-3 shadow-none">
                <div class="card-body p-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold text-muted mb-1">Task Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="title[]"
                                   class="form-control"
                                   placeholder="e.g. Prepare Monthly GST Reports"
                                   required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Priority <span class="text-danger">*</span></label>
                            <select name="priority[]" class="form-select" required>
                                <option value="">Select Priority</option>
                                <option value="High">🔴 High</option>
                                <option value="Medium">🟡 Medium</option>
                                <option value="Low">🔵 Low</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Deadline <span class="text-danger">*</span></label>
                            <input type="datetime-local"
                                   name="due_date[]"
                                   class="form-control"
                                   required>
                        </div>
                        <div class="col-md-1 text-center">
                            ${isFirst ? `
                                <button type="button" class="btn btn-outline-primary w-100 add-task-row" title="Add Another Task">
                                    <i class="ti ti-plus"></i>
                                </button>
                            ` : `
                                <button type="button" class="btn btn-outline-danger w-100 remove-task-row" title="Remove Task">
                                    <i class="ti ti-trash"></i>
                                </button>
                            `}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

	// Add More Task Row
	document.addEventListener("click", function (e) {
		// ADD ROW
		if (e.target.closest(".add-task-row")) {
			const wrapper = document.getElementById("taskTitleWrapper");
			wrapper.insertAdjacentHTML("beforeend", getTaskRowTemplate(false));
		}

		// REMOVE ROW
		if (e.target.closest(".remove-task-row")) {
			e.target.closest(".task-row").remove();
		}
	});

	// Reset Add Task Modal on show
	document.addEventListener("DOMContentLoaded", function () {
		const addTaskModal = document.getElementById('addTaskModal');
        if (addTaskModal) {
            addTaskModal.addEventListener('show.bs.modal', function () {
                document.getElementById('taskForm').reset();
                document.getElementById("taskTitleWrapper").innerHTML = getTaskRowTemplate(true);
            });
        }
	});

	document.addEventListener("DOMContentLoaded", function () {
		// Submit Task
		document.getElementById("taskForm")?.addEventListener("submit", function (e) {
			e.preventDefault();
			let formData = new FormData(this);
			fetch(this.action, {
				method: "POST",
				body: formData,
				headers: {"X-Requested-With":"XMLHttpRequest","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').content}
			})
			.then(res => res.json())
			.then(res => {
				if(res.status){
					showToast(res.message,"success");
					// Reset form
					this.reset();
					document.getElementById("taskTitleWrapper").innerHTML = getTaskRowTemplate(true);

					// Close modal
					bootstrap.Modal.getInstance(this.closest(".modal")).hide();

					// Reload
					setTimeout(() => location.reload(), 1000);
				} else {
					showToast("Error saving task!","error");
				}
			});
		});

					// Close modal
					bootstrap.Modal.getInstance(this.closest(".modal")).hide();

					// Reload
					location.reload();

				} else {

					showToast("Error!","error");

				}

			});
		});

		// Edit Task
		/*document.querySelectorAll(".edit-task-form").forEach(f=>{
			f.addEventListener("submit",function(e){
				e.preventDefault();
				let fd=new FormData(f);
				fetch(f.action,{method:"POST",body:fd,headers:{"X-Requested-With":"XMLHttpRequest","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').content}})
				.then(res=>res.json())
				.then(res=>{ if(res.status){ showToast(res.message,"success"); bootstrap.Modal.getInstance(f.closest(".modal")).hide(); location.reload(); } else { showToast("Validation failed","error"); }});
			})
		});*/
		
		// Open Edit Modal
		document.querySelectorAll(".edit-task-btn").forEach(btn => {

			btn.addEventListener("click", function () {

				let taskId = this.dataset.id;

				fetch("{{ url('/task-management/get-task') }}/" + taskId)
				.then(res => res.json())
				.then(task => {

					// Set form action
					document.getElementById("editTaskForm")
						.action = "{{ url('/task-management/update') }}/" + task.id;

					// Fill data
					document.getElementById("edit_title").value = task.title;
					document.getElementById("edit_employee_id").value = task.employee_id;
					document.getElementById("edit_priority").value = task.priority;
					document.getElementById("edit_due_date").value = task.due_date.replace(' ', 'T');
					document.getElementById("edit_description").value = task.description ?? '';
					document.getElementById("edit_status").value = task.status;

					// Show modal
					let modal = new bootstrap.Modal(document.getElementById('editTaskModal'));
					modal.show();

				});

			});

		});



		document.querySelectorAll('.delete-task-btn').forEach(function(btn){
			btn.addEventListener('click', function(){
				let taskId = this.getAttribute('data-id');
				Swal.fire({
					title: 'Are you sure?',
					text: "You won't be able to revert this!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#d33',
					cancelButtonColor: '#3085d6',
					confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
					if (result.isConfirmed) {
						fetch("{{ url('/task-management/delete') }}/" + taskId, {
							method: "DELETE",
							headers: {
								"X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
								"X-Requested-With": "XMLHttpRequest"
							}
						})
						.then(res => res.json())
						.then(res => {
							if (res.status) {
								showToast(res.message, "success");
								setTimeout(() => location.reload(), 1500); // reload after showing success
							} else {
								showToast(res.message, "error");
							}
						})
					}
				});
			});
		});
	});

    // --- Interactive Tour ---
    function startTaskManagementTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Task Management Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-list-check" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Delegate, assign, and monitor tasks assigned to your employees.</p></div>'
                },
                {
                    element: '.tour-assign-task',
                    title: 'Assign New Task',
                    intro: 'Click here to assign a new task to your employees, set deadlines, and configure priority levels.'
                },
                {
                    element: '#pc-dt-simple',
                    title: 'Tasks List',
                    intro: 'View and track all assigned tasks, including priorities, deadlines, and current completion status.'
                },
                {
                    element: '.tour-task-actions',
                    title: 'Task Actions',
                    intro: 'Use these controls to view complete details, edit task status, or delete task assignments.',
                    position: 'left'
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
        $('#start-task-management-tour').on('click', function(e) {
            e.preventDefault();
            startTaskManagementTour();
        });
    });
</script>
@endsection
