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
            <div class="card">
                <div class="card-body table-card">
                    <div class="table-responsive">
                        <table class="table tbl-product my-3" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Task Title</th>
                                    <th>Assigned To</th>
                                    <th>Priority</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignedTasks as $i => $task)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $task->title }}</td>
                                        <td>{{ $task->employee_name }}</td>
                                        <td>
                                            <span class="badge
                                                @if($task->priority == 'High') bg-danger
                                                @elseif($task->priority == 'Medium') bg-warning
                                                @else bg-info @endif">
                                                {{ $task->priority }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y, h:i A') }}</td>
                                        <td>
                                            <span class="badge
                                                @if($task->status == 'Pending') bg-warning
                                                @elseif($task->status == 'In Progress') bg-info
                                                @else bg-success @endif">
                                                {{ $task->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-light-primary tour-task-actions" data-bs-toggle="modal" data-bs-target="#viewTaskModal{{ $task->id }}"><i class="ti ti-eye"></i></a>                                            
											<a href="javascript:void(0);" class="btn btn-sm btn-light-warning edit-task-btn" data-id="{{ $task->id }}"><i class="ti ti-edit"></i></a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-light-danger delete-task-btn" data-id="{{ $task->id }}"> <i class="ti ti-trash"></i> </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center">No tasks assigned yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="taskForm" class="modal-content" method="POST" action="{{ route('task.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Assign New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
					<div class="mb-3">
						<label class="form-label">Tasks<span class="text-danger">*</span></label>

						<div id="taskTitleWrapper">

							<div class="border rounded p-3 mb-3 task-row">

								<div class="row">

									<div class="col-md-5">
										<label>Task Title</label>

										<input type="text"
											   name="title[]"
											   class="form-control"
											   placeholder="Enter Task Title"
											   required>
									</div>

									<div class="col-md-2">
										<label>Priority</label>

										<select name="priority[]"
												class="form-control"
												required>

											<option value="">Select</option>
											<option value="High">High</option>
											<option value="Medium">Medium</option>
											<option value="Low">Low</option>

										</select>
									</div>

									<div class="col-md-4">
										<label>Deadline</label>

										<input type="datetime-local"
											   name="due_date[]"
											   class="form-control"
											   required>
									</div>

									<div class="col-md-1 d-flex align-items-end">
										<button type="button"
												class="btn btn-primary add-task-row">
											<i class="ti ti-plus"></i>
										</button>
									</div>

								</div>

							</div>

						</div>

						<small class="text-muted">
							You can add multiple tasks with different priority and deadline.
						</small>
					</div>
					
                    <div class="mb-3"><label class="form-label">Assign To<span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-control" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->employee_id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>                   
                    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control"></textarea></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Assign Task</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button></div>
            </form>
        </div>
    </div>
	
	<!-- Single Edit Modal -->
	<div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<form class="modal-content" id="editTaskForm" method="POST">
				@csrf

				<div class="modal-header">
					<h5 class="modal-title">Edit Task</h5>
					<button type="button"
							class="btn-close"
							data-bs-dismiss="modal"></button>
				</div>

				<div class="modal-body">					
					<div class="row">
						<div class="col-md-8 mb-3">
							<label class="form-label">Task Title<span class="text-danger">*</span></label>
							<input type="text"
								   name="title"
								   id="edit_title"
								   class="form-control"
								   required>
						</div>

						<div class="col-md-4 mb-3">
							<label class="form-label">Priority<span class="text-danger">*</span></label>
							<select name="priority"
									id="edit_priority"
									class="form-control"
									required>
								<option value="High">High</option>
								<option value="Medium">Medium</option>
								<option value="Low">Low</option>

							</select>
						</div>
					</div>
					
					<div class="mb-3">
						<label class="form-label">Deadline<span class="text-danger">*</span></label>
						<input type="datetime-local"
							   name="due_date"
							   id="edit_due_date"
							   class="form-control"
							   required>
					</div>

					<div class="mb-3">
						<label class="form-label">Assign To<span class="text-danger">*</span></label>

						<select name="employee_id"
								id="edit_employee_id"
								class="form-control"
								required>

							@foreach($employees as $employee)
								<option value="{{ $employee->employee_id }}">
									{{ $employee->name }}
								</option>
							@endforeach

						</select>
					</div>

					<div class="mb-3">
						<label class="form-label">Description</label>

						<textarea name="description"
								  id="edit_description"
								  class="form-control"></textarea>
					</div>

					<div class="mb-3">
						<label class="form-label">Status</label>
						<select name="status"
								id="edit_status"
								class="form-control"
								required>
							<option value="Pending">Pending</option>
							<option value="In Progress">In Progress</option>
							<option value="Completed">Completed</option>

						</select>
					</div>
				</div>

				<div class="modal-footer">
					<button type="submit"
							class="btn btn-success">
						Update
					</button>

					<button type="button"
							class="btn btn-secondary"
							data-bs-dismiss="modal">
						Cancel
					</button>
				</div>

			</form>
		</div>
	</div>

    {{-- View Task Modals --}}
    @foreach($assignedTasks as $task)
    <div class="modal fade" id="viewTaskModal{{ $task->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                <i class="ti ti-eye me-1"></i> Task Details - {{ $task->title }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                <div class="col-md-6">
                    <label class="fw-bold">Task Title:</label>
                    <p class="text-muted">{{ $task->title }}</p>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold">Assigned To:</label>
                    <p class="text-muted">{{ $task->employee_name }}</p>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold">Priority:</label>
                    <p>
                    <span class="badge
                        @if($task->priority == 'High') bg-danger
                        @elseif($task->priority == 'Medium') bg-warning
                        @else bg-info @endif">
                        {{ $task->priority }}
                    </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold">Deadline:</label>
                    <p class="text-muted">{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y, h:i A') }}</p>
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">Description:</label>
                    <p class="text-muted">{{ $task->description ?? 'No description provided.' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold">Status:</label>
                    <p>
                    <span class="badge
                        @if($task->status == 'Pending') bg-warning
                        @elseif($task->status == 'In Progress') bg-info
                        @else bg-success @endif">
                        {{ $task->status }}
                    </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold">Last Updated:</label>
                    <p class="text-muted">{{ \Carbon\Carbon::parse($task->updated_at)->diffForHumans() }}</p>
                </div>
                </div>
            </div>

            <div class="modal-footer">
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

	// Add More Task Title Row
	/*document.addEventListener("click", function (e) {

		// Add Row
		if (e.target.closest(".add-task-row")) {

			const wrapper = document.getElementById("taskTitleWrapper");

			const newRow = `
				<div class="input-group mb-2 task-row">
					<input type="text"
						   name="title[]"
						   class="form-control"
						   placeholder="Enter Task Title"
						   required>

					<button type="button"
							class="btn btn-danger remove-task-row">
						<i class="ti ti-minus"></i>
					</button>
				</div>
			`;

			wrapper.insertAdjacentHTML("beforeend", newRow);
		}

		// Remove Row
		if (e.target.closest(".remove-task-row")) {
			e.target.closest(".task-row").remove();
		}

	});*/
	
	// Add More Task Row
	document.addEventListener("click", function (e) {

		// ADD ROW
		if (e.target.closest(".add-task-row")) {

			const wrapper = document.getElementById("taskTitleWrapper");

			const newRow = `
				<div class="border rounded p-3 mb-3 task-row">

					<div class="row">

						<div class="col-md-5">
							<label>Task Title</label>

							<input type="text"
								   name="title[]"
								   class="form-control"
								   placeholder="Enter Task Title"
								   required>
						</div>

						<div class="col-md-2">
							<label>Priority</label>

							<select name="priority[]"
									class="form-control"
									required>

								<option value="">Select</option>
								<option value="High">High</option>
								<option value="Medium">Medium</option>
								<option value="Low">Low</option>

							</select>
						</div>

						<div class="col-md-4">
							<label>Deadline</label>

							<input type="datetime-local"
								   name="due_date[]"
								   class="form-control"
								   required>
						</div>

						<div class="col-md-1 d-flex align-items-end">
							<button type="button"
									class="btn btn-danger remove-task-row">
								<i class="ti ti-minus"></i>
							</button>
						</div>

					</div>

				</div>
			`;

			wrapper.insertAdjacentHTML("beforeend", newRow);
		}

		// REMOVE ROW
		if (e.target.closest(".remove-task-row")) {

			e.target.closest(".task-row").remove();
		}

	});

	//Add new task
	document.addEventListener("DOMContentLoaded", function () {

		/*const addTaskModal = document.getElementById('addTaskModal');

		addTaskModal.addEventListener('show.bs.modal', function () {

			// Reset form
			document.getElementById('taskForm').reset();

			// Reset task title rows
			document.getElementById("taskTitleWrapper").innerHTML = `
				<div class="input-group mb-2 task-row">
					<input type="text"
						   name="title[]"
						   class="form-control"
						   placeholder="Enter Task Title"
						   required>

					<button type="button"
							class="btn btn-primary add-task-row">
						<i class="ti ti-plus"></i>
					</button>
				</div>
			`;
		});*/
		
		const addTaskModal = document.getElementById('addTaskModal');

		addTaskModal.addEventListener('show.bs.modal', function () {

			document.getElementById('taskForm').reset();

			document.getElementById("taskTitleWrapper").innerHTML = `
				<div class="border rounded p-3 mb-3 task-row">

					<div class="row">

						<div class="col-md-5">
							<label>Task Title</label>

							<input type="text"
								   name="title[]"
								   class="form-control"
								   placeholder="Enter Task Title"
								   required>
						</div>

						<div class="col-md-2">
							<label>Priority</label>

							<select name="priority[]"
									class="form-control"
									required>

								<option value="">Select</option>
								<option value="High">High</option>
								<option value="Medium">Medium</option>
								<option value="Low">Low</option>

							</select>
						</div>

						<div class="col-md-4">
							<label>Deadline</label>

							<input type="datetime-local"
								   name="due_date[]"
								   class="form-control"
								   required>
						</div>

						<div class="col-md-1 d-flex align-items-end">
							<button type="button"
									class="btn btn-primary add-task-row">
								<i class="ti ti-plus"></i>
							</button>
						</div>

					</div>

				</div>
			`;
		});

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
					// Reset multiple task rows
					document.getElementById("taskTitleWrapper").innerHTML = `
						<div class="input-group mb-2 task-row">
							<input type="text"
								   name="title[]"
								   class="form-control"
								   placeholder="Enter Task Title"
								   required>

							<button type="button"
									class="btn btn-primary add-task-row">
								<i class="ti ti-plus"></i>
							</button>
						</div>
					`;

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
