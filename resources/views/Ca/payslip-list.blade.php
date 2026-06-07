@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Payslip List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="#" class="btn btn-success me-2" data-bs-toggle="tooltip" title="Whatsapp"><i class="ti ti-brand-whatsapp"></i></a>
                    <a href="#" class="btn btn-secondary me-2" data-bs-toggle="tooltip" title="Download Now"><i class="ti ti-download"></i></a>
                    
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
								<th>#</th>
								<th>Employee</th>
								<th>Payslip No.</th>								
								<th>Year</th>
								<th>Month</th>
								<th>Download</th>
							</tr>
						</thead>
						<tbody>
							@foreach($payslips as $key => $row)
							<tr>
								<td>{{ $key+1 }}</td>
								<td>{{ $row->name }}</td>
								<td>{{ $row->payslip_no }}</td>
								<td>{{ $row->financial_year }}</td>
								<td>{{ date('F', mktime(0, 0, 0, $row->month, 1)) }}</td>
								<td>
									<a href="{{ url('download-payslip/'.$row->id) }}" target="_blank" class="btn btn-sm btn-primary">Download</a>
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