@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Edit Quote</h2>
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
                    <form action="javascript:void(0);" method="post" name="addTaskquoteFrm" id="addTaskquoteFrm" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="quoteId" value="{{$quote->id}}">
                        @csrf
                        <div class="row mt-4">
                            <div class="col">
                                <div class="row">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputEmail4">Task Name<span class="text-danger">*</span></label>
                                        <div class="d-flex align-items-center">
                                            <div class="form-group me-2" style="flex-grow: 1;">

                                                <select class="select form-select" name="task_category" id="task_category">
                                                    <option value="">Select Category</option>
                                                    
                                                </select>
                                            </div>
                                            <a class="btn btn-primary form-plus-btn d-flex align-items-center justify-content-center" href="#" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                                                <i class="ti ti-plus py-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputEmail4">Goverment Fees<span class="text-danger">*</span></label>
                                        <input type="text" name="govfee" id="govfee" value="{{$quote->govfee}}" class="form-control" placeholder="Enter Goverment Fees">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputEmail4">Services Charges<span class="text-danger">*</span></label>
                                        <input type="text" name="service_charge" value="{{$quote->service_charge}}" id="service_charge" class="form-control" placeholder="Enter Services Charges">
                                    </div>
                                </div>
                                 <div class="d-flex wizard justify-content-end mt-3">
                                    <div class="last">
                                        <button type="submit" id="save_attaBtn"  class="btn btn-primary">Update Quation</button>
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

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="UpdatePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addTaskForm">
                    <div class="mb-3">
                        <label for="task_name" class="form-label">Task Name</label>
                        <input type="text" class="form-control" id="task_name" name="task_name" placeholder="Enter Task Name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitTask">Add Task</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        function fetch_task_actegory(){
            $.ajax({
                url: '/get-task-categories',
                method: 'GET',
                success: function (response) {
                    const select = $('#task_category');
                    const selectedCatId = `{{$quote->task_cat}}`; // Get the current selected category ID from Blade

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
                    
                    showToast('Failed to load task categories.', 'error');
                }
            });
        }

        

        $('#submitTask').click(function () {
            const taskName = $('#task_name').val().trim();
            if (!taskName) {
                showToast('Task name is required.', 'error');
                return;
            }

            $.ajax({
                url: '{{ route("task-category.store") }}',
                method: 'POST',
                data: {
                    task_name: taskName,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    showToast(response.message, 'success');
                    $('#addTaskModal').modal('hide');
                    $('#addTaskForm')[0].reset();
                    fetch_task_actegory();
                },
                error: function (xhr) {
                    const message = xhr.responseJSON?.message || 'Something went wrong.';
                    showToast(message, 'error');
                }
            });
        });

        fetch_task_actegory();
    });


</script>

@endsection