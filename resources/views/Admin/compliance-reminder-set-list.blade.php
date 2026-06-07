@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/compliance-reminder-list') }}">Compliance Reminder List</a></li>
                        <li class="breadcrumb-item" aria-current="page">Compliance Reminder List</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Compliance Reminder List</h2>
                    </div>
                </div>
				@if (Auth::user()->u_type == 3 || Auth::user()->u_type == 6)
                <div class="col-md-8 text-end">
                    <a href="{{ route('admin.add-compliance-reminder') }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Compliance Reminder</a>
                </div>
				@endif
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
                    <table class="table tbl-product" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>                                
                                <th>Compliance List</th>
                                <th>Form/Return</th>
                                <th>Frequency</th> 
                                <th>Standard Due Date</th>
                                <th>Reminder Date</th>
								@if (Auth::user()->u_type == 3 || Auth::user()->u_type == 6)								
                                <th>Status</th>                                
                                <th>Action</th>
								@endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($complianceReminderSetList as $key => $crs)
                                <tr>
                                    <td class="text-end">{{ $key + 1 }}</td>
                                    <td>{{ $crs->compliance }}</td>
                                    <td>{{ $crs->form_name }}</td>
                                    <td>{{ $crs->frequency }}</td>
                                    <td>{{ $crs->due_day.'-'.$crs->due_month.'-'.$crs->due_year_type }}</td>
                                    <td>{{ $crs->reminder_day.'-'.$crs->reminder_month.'-'.$crs->reminder_year_type }}</td>  
									@if (Auth::user()->u_type == 3 || Auth::user()->u_type == 6)									
									<td>
										@if($crs->reminderStatus == 1)
											<span class="badge bg-success">Active</span>
										@else
											<span class="badge bg-danger">Inactive</span>
										@endif
									</td>									
                                    <td>
                                        <a href="{{ url('/compliance-reminder-details/' . $crs->id) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="Edit"><i class="ti ti-pencil f-16"></i></a>
                                        <a href="javascript:void(0);" data-id="{{ $crs->id }}" class="btn btn-sm btn-danger expenses" data-bs-toggle="modal" data-bs-target="#delete_modal" data-bs-toggle="tooltip" aria-label="Delete" data-bs-original-title="Delete"><i class="ti ti-trash f-16"></i></a>
                                    </td>
									@endif
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
                    <h3>Delete Compliance Reminder</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" id="del_rcs" class="w-100 btn btn-primary">
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
    $(document).on("click", ".expenses", function() {
        var itemId = $(this).data("id");
        //alert(itemId);

        $("#del_rcs").off("click").on("click", function() {
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: "DELETE",
                url: "/delete_compliance_reminder/" + itemId, // Pass ID in the URL
                dataType: "json",
                success: function(data) {
                    if (data.status === "success") {
                        // window.location.href = data.redirect;
                        showToast("Delete Successfully", "success");
                        setTimeout(() => {
                            setTimeout(() => location.reload(), 2000);
                        }, 2000);
                    } else {
                        // alert(data.message); // Show error message if delete fails
                        showToast(data.message, "error");
                    }
                },
                error: function() {
                    // alert("Something went wrong. Please try again.");
                    showToast("Something went wrong. Please try again.", "error");
                }
            });
        });
    });
</script>

@endsection