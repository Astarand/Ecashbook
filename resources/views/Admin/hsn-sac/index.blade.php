@extends('App.Layout')

@section('container')

<div class="pc-content">

    <!-- Page Header -->
    <!--<div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">HSN & SAC Master</h4>
            <p class="text-muted mb-0">Manage HSN and SAC codes, GST rates and applicability.</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadModal"><i class="fa fa-upload"></i>Upload Excel</button>
            <button type="button" class="btn btn-primary" onclick="openAddModal()"><i class="fa fa-plus"></i>Add HSN/SAC</button>
        </div>
    </div>-->
	<!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('hsnSac.index') }}">HSN & SAC Set</a></li>
                        <li class="breadcrumb-item active" aria-current="page">HSN & SAC Lists</li>
                    </ul>
                </div>
                <div class="col-md-12">
                   <div class="d-flex justify-content-between align-items-center mb-3">
						<div>
							<h4 class="mb-1">HSN & SAC Master</h4>
							<p class="text-muted mb-0">Manage HSN and SAC codes, GST rates and applicability.</p>
						</div>
						<div class="d-flex gap-2">
							<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadModal"><i class="fa fa-upload"></i>Upload Excel</button>
							<button type="button" class="btn btn-primary" onclick="openAddModal()"><i class="fa fa-plus"></i>Add HSN/SAC</button>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->


    <!-- Filter Card -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('hsnSac.index') }}">
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label">Type</label>
                        <select name="code_type" class="form-select">
                            <option value="">All</option>
                            <option value="HSN" {{ request('code_type') == 'HSN' ? 'selected' : '' }}>HSN</option>
                            <option value="SAC" {{ request('code_type') == 'SAC' ? 'selected' : '' }}>SAC</option>
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search code, description..." value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">GST Rate</label>
                        <select name="gst_rate" class="form-select">
                            <option value="">All</option>
                            <option value="0"{{ request('gst_rate') === '0' ? 'selected' : '' }}>0%</option>
                            <option value="5"{{ request('gst_rate') === '5' ? 'selected' : '' }}>5%</option>
                            <option value="12"{{ request('gst_rate') === '12' ? 'selected' : '' }}>12%</option>
                            <option value="18"{{ request('gst_rate') === '18' ? 'selected' : '' }}>18%</option>
                            <option value="28"{{ request('gst_rate') === '28' ? 'selected' : '' }}>28%</option>
                        </select>

                    </div>


                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('hsnSac.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- List -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <strong>HSN / SAC List</strong>
				<span class="text-muted">Total: {{ $records->total() }}</span>
            </div>
        </div>


        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60">#</th>
                            <th width="90">Type</th>
                            <th width="130">Code</th>
                            <th>Description</th>
                            <th width="110">GST Rate</th>
                            <!--<th>Condition / Applicability</th>-->
                            <th width="100">Status</th>
                            <th width="130">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($records as $key => $record)
                            <tr>
                                <td>{{ $records->firstItem() + $key }}</td>
                                <td>
                                    @if($record->code_type == 'HSN')
                                        <span class="badge bg-primary">HSN</span>
                                    @else
                                        <span class="badge bg-info">SAC</span>
                                    @endif
                                </td>
                                <td><strong>{{ $record->code }}</strong></td>
                                <td>
									@php
										$description = $record->description ?? '';
									@endphp
									<span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $description }}">
										{{ \Illuminate\Support\Str::limit($description, 50, '...') }}
									</span>
								</td>
                                <td>
                                    <span class="badge bg-success">
                                        {{ number_format($record->gst_rate, 2) }}%
                                    </span>
                                </td>
                                <!--<td>{{ $record->apply_cond ?: '-' }}</td>-->
                                <td>
                                    @if($record->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-primary"
                                        onclick="editRecord({{ $record->id }})">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-danger"
                                        onclick="deleteRecord({{ $record->id }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">No HSN/SAC records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

		<div class="card-footer d-flex justify-content-between align-items-center">
			<div class="text-muted small">
				Showing
				<strong>{{ $records->firstItem() ?? 0 }}</strong>
				-
				<strong>{{ $records->lastItem() ?? 0 }}</strong>
				of
				<strong>{{ $records->total() }}</strong>
				records
			</div>
			@if($records->hasPages())
				<div>
					{{ $records->onEachSide(1)->links('pagination::bootstrap-5') }}
				</div>
			@endif
		</div>
    </div>

</div>


<!-- ========================================================= -->
<!-- ADD / EDIT MODAL -->
<!-- ========================================================= -->

<div class="modal fade" id="hsnSacModal" tabindex="-1"aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hsnSacModalTitle">
                    Add HSN/SAC
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>


            <form id="hsnSacForm">
                @csrf
                <input type="hidden" id="record_id" name="record_id">

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">
                                Type <span class="text-danger">*</span>
                            </label>

                            <select name="code_type" id="code_type" class="form-select">
                                <option value="">Select Type</option>
                                <option value="HSN">HSN</option>
                                <option value="SAC">SAC</option>
                            </select>

                            <div class="text-danger small" id="error_code_type"></div>
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">
                                Code <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="code" id="code" class="form-control" placeholder="Enter HSN/SAC code">
                            <div class="text-danger small" id="error_code"></div>
                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                GST Rate (%) <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                name="gst_rate"
                                id="gst_rate"
                                class="form-control"
                                step="0.01"
                                min="0"
                                max="100"
                                placeholder="e.g. 18">

                            <div class="text-danger small" id="error_gst_rate"></div>

                        </div>


                        <div class="col-md-12">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter description"></textarea>
                            <div class="text-danger small" id="error_description"></div>
                        </div>


                        <div class="col-md-12">

                            <label class="form-label">
                                Condition / When This Rate Applies
                            </label>

                            <textarea
                                name="apply_cond"
                                id="apply_cond"
                                class="form-control"
                                rows="4"
                                placeholder="Enter applicable condition"></textarea>

                            <div class="text-danger small" id="error_apply_cond"></div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>

</div>


<!-- ========================================================= -->
<!-- UPLOAD MODAL -->
<!-- ========================================================= -->

<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Upload HSN / SAC Excel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>


            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
				<div class="modal-body">
					<div class="alert alert-info">
						<strong>Upload HSN & SAC Master</strong>
						<p class="mb-1 mt-2">
							Upload the Excel workbook containing:
						</p>
						<ul class="mb-0">
							<li>
								<strong>HSN Codes</strong> sheet
							</li>
							<li>
								<strong>SAC Codes</strong> sheet
							</li>
						</ul>
					</div>


					<div class="mb-3">
						<label class="form-label">
							Excel File
							<span class="text-danger">*</span>
						</label>

						<input type="file" name="file" id="excel_file" class="form-control" accept=".xlsx,.xls">

						<div class="form-text">
							Maximum file size: 10 MB
						</div>
						<div class="text-danger small" id="upload_error_file"></div>
					</div>
				</div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="uploadBtn"><i class="fa fa-upload"></i>Upload</button>
                </div>
            </form>
        </div>
    </div>

</div>



<script>

	$(document).ready(function () {
		$('[data-bs-toggle="tooltip"]').tooltip();
	});
    //Clear Errors
    function clearErrors()
    {
        $(".text-danger.small").html("");
        $("#hsnSacForm .form-control, #hsnSacForm .form-select").removeClass("is-invalid");
    }


	//ADD
    function openAddModal()
    {
        clearErrors();
        $("#hsnSacForm")[0].reset();
        $("#record_id").val("");
        $("#hsnSacModalTitle").text("Add HSN/SAC");
        $("#saveBtn").text("Save");
        let modal = new bootstrap.Modal(document.getElementById("hsnSacModal"));
        modal.show();
    }


    //EDIT
    function editRecord(id)
    {
        clearErrors();
        $.ajax({
            url: "{{ url('hsn-sac/show') }}/" + id,
            type: "GET",
			headers: {
				"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
			},
            success: function(response)
            {
                if(response.success)
                {
                    let data = response.data;
                    $("#record_id").val(data.id);
                    $("#code_type").val(data.code_type);
                    $("#code").val(data.code);
                    $("#description").val(data.description);
                    $("#gst_rate").val(data.gst_rate);
                    $("#apply_cond").val(data.apply_cond);
                    $("#hsnSacModalTitle").text("Edit HSN/SAC");
                    $("#saveBtn").text("Update");
                    let modal = new bootstrap.Modal(document.getElementById("hsnSacModal"));
                    modal.show();
                }
            },

            error: function()
            {
                showToast("Unable to fetch record.","error");
            }

        });
    }


    //ADD or UPDATE SUBMIT
	$(document).ready(function () {
		$("#hsnSacForm").on("submit", function(e){
			e.preventDefault();
			clearErrors();
			let id = $("#record_id").val();
			let url = id ? "{{ url('hsn-sac/update') }}/" + id : "{{ route('hsnSac.store') }}";

			//let method = id ? "PUT" : "POST";
			let formData = new FormData(this);
			/* if(id)
			{
				formData.append("_method", "PUT");
			} */

			$("#saveBtn").prop("disabled", true).text(id ? "Updating..." : "Saving...");

			$.ajax({
				url: url,
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
				},
				success: function(response)
				{
					if(response.success)
					{
						showToast(response.message,"success");
						location.reload();
					}
				},

				error: function(xhr)
				{
					if(xhr.status === 422)
					{
						let errors = xhr.responseJSON.errors;

						$.each(errors, function(field, messages)
						{
							$("#error_" + field).html(messages.join("<br>"));
							$("#" + field).addClass("is-invalid");
						});
					}
					else
					{
						showToast("Something went wrong.","error");
					}
				},

				complete: function()
				{
					$("#saveBtn").prop("disabled", false).text(id ? "Update" : "Save");
				}

			});

		});
    });


    //DELETE
    function deleteRecord(id)
    {
        if(!confirm("Are you sure you want to delete this HSN/SAC record?"))
        {
            return;
        }

        $.ajax({
            url: "{{ url('hsn-sac/delete') }}/" + id,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                _method: "DELETE"
            },
            success: function(response)
            {
                if(response.success)
                {
                    showToast(response.message,"success");
                    location.reload();
                }
            },

            error: function()
            {
                showToast("Unable to delete record.","error");
            }

        });
    }


    //EXCEL UPLOAD
	$("#uploadForm").on("submit", function(e){
		e.preventDefault();

		$("#upload_error_file").html("");
		let formData = new FormData(this);
		$("#uploadBtn").prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Importing...');
		$.ajax({
			url: "{{ route('hsnSac.upload') }}",
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,
			success: function(response)
			{
				if(response.success)
				{
					showToast(response.message,"success");
					setTimeout(function(){
						location.reload();
					}, 1000);
				}
			},
			error: function(xhr)
			{
				if(xhr.status === 422)
				{
					let errors = xhr.responseJSON.errors;
					if(errors.file)
					{
						$("#upload_error_file").html(errors.file.join("<br>"));
					}
				}
				else
				{
					let message = xhr.responseJSON?.message || "Unable to import Excel file.";
					showToast(message,"error");
				}
			},
			complete: function()
			{
				$("#uploadBtn").prop("disabled", false).html('<i class="fa fa-upload"></i> Upload');
			}
		});
	});

</script>

@endsection