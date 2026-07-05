@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">View Task</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div id="employeeEnroll" class="form-wizard row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="javascript:void(0);" method="post" name="addTaskFrm" id="addTaskFrm" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="taskId" value="{{$task->id}}">
                        @csrf
                        <div class="row mt-4">
                            <div class="col">
                                <div class="row">
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Task Date<span class="text-danger">*</span></label>
                                        <input type="date" name="task_date" id="task_date" value="{{$task->task_date}}" readonly class="form-control" placeholder="Enter Email">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Task Time <span class="text-danger">*</span></label>
                                        <input type="time" name="task_time" id="task_time" value="{{$task->task_time}}" readonly class="form-control" placeholder="Enter Time">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Compnay Name<span class="text-danger">*</span></label>
                                        <div class="form-group me-2">
                                            <select class="select form-select" name="company_id" id="company_id">
                                                <option value="">Select Name</option>
                                                @foreach($company as $k=>$val)
                                                <option value="{{ $val->id }}" value="{{ $val->id }}" <?php echo ($task->company_id == $val->id) ? "selected" : "" ?>>{{ $val->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Task Category<span class="text-danger">*</span></label>
                                        <div class="form-group me-2">
                                            <select class="select form-select" name="task_category" id="task_category">
                                                <option value="">Select Category</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Agent Name<span class="text-danger">*</span></label>
                                        <div class="form-group me-2">
                                            <select class="select form-select" name="agent_id" id="agent_id">
                                                <option value="">Select Name</option>
                                                <option value="none" {{ ($task->agent_id == "none") ? "selected" : "" }}>None</option>
                                                @foreach($agents as $k=>$val)
                                                <option value="{{ $val->id }}" {{ ($task->agent_id == $val->id) ? "selected" : "" }}>{{ $val->agent_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Goverment Fees<span class="text-danger">*</span></label>
                                        <input type="text" readonly name="gov_fees" id="gov_fees" value="{{$task->gov_fees}}" class="form-control" placeholder="Enter Goverment Fees">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Services Charges<span class="text-danger">*</span></label>
                                        <input type="text" readonly name="services_charges" id="services_charges" value="{{$task->services_charges}}" class="form-control" placeholder="Enter Services Charges">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Total Amount<span class="text-danger">*</span></label>
                                        <input type="text" readonly name="total_amount" id="total_amount" value="{{$task->total_amount}}" class="form-control" placeholder="Enter Total Amount">
                                    </div>

                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Employee Name<span class="text-danger">*</span></label>
                                        <div class="form-group me-2">
                                            <select class="select form-select" name="emp_id" id="emp_id">
                                                <option value="">Select Name</option>
                                                <option value="none" <?php echo ('none' == $task->emp_id) ? "selected" : "" ?>>None</option>
                                                @foreach($employees as $k=>$val)
                                                <option value="{{ $val->id }}" <?php echo ($val->id == $task->emp_id) ? "selected" : "" ?>>{{ $val->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Due Date<span class="text-danger">*</span></label>
                                        <input type="date" name="due_date" id="due_date" value="{{$task->due_date}}" class="form-control" placeholder="Enter Email">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Project Priority<span class="text-danger">*</span></label>
                                        <div class="form-group me-2">
                                            <select class="select form-select" name="project_priority" id="project_priority">
                                                <option value="">Select</option>
                                                <option value="High" <?php echo ($task->project_priority == 'High') ? "selected" : "" ?>>High</option>
                                                <option value="Average" <?php echo ($task->project_priority == 'Average') ? "selected" : "" ?>>Average</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Project Status<span class="text-danger">*</span></label>
                                        <div class="form-group me-2">
                                            <select class="select form-select" name="project_status" id="project_status">
                                                <option value="">Select</option>
                                                <option value="1" <?php echo ($task->project_status == 1) ? "selected" : "" ?>>Pending</option>
                                                <option value="2" <?php echo ($task->project_status == 2) ? "selected" : "" ?>>Ongoing</option>
                                                <option value="3" <?php echo ($task->project_status == 3) ? "selected" : "" ?>>Done</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label" for="inputEmail4">Special Message</label>
                                        <textarea name="message" id="message" class="form-control" placeholder="Enter Special Message" rows="4">{{$task->message}}</textarea>
                                    </div>
                                </div>
                                <div class="d-flex wizard justify-content-end mt-3">
                                    
                                    <div class="last">
                                        <a href="{{ url('/task-list') }}" id="cancel_attaBtn" class="btn btn-primary">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $.ajax({
            url: '/get-task-categories',
            method: 'GET',
            success: function (response) {
            const select = $('#task_category');
            const selectedCatId = `{{$task->task_category}}`; // Get the current selected category ID from Blade

            select.empty();
            select.append('<option value="">Select Category</option>');

            response.forEach(function (category) {
                const isSelected = (category.id == selectedCatId) ? 'selected' : '';
                select.append(
                    `<option value="${category.id}" ${isSelected}>${category.task_category_name}</option>`
                );
            });
        },
            error: function () {
                alert('Failed to load task categories.');
            }
        });
    });
</script>

@endsection