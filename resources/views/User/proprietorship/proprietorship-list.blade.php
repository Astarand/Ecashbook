@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/proprietorship-list') }}">Proprietorship Companies List</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Proprietorship Companies List</h2>
                    </div>
                </div>
                <!--<div class="col-md-8 text-end">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-loan-account-modal">
                        <i class="ti ti-square-plus f-20"></i> Add New Proprietorship
                    </a>
                </div>-->
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
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead>
                            <tr style="background-color: #cbcbcb;">
                                <th class="text-end">#</th>
                                <th>Company Name</th>
                                <th>Company PAN</th>
                                <th>Company GST</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach($data as $row)
                            <tr>
                                <td class="text-end"><?php echo $i++; ?></td>
                                <td><span class="text-muted text-hover-primary">
								{{ $row->comp_name }}
								@if($row->company_type == 'parent')
									<span class="badge bg-primary">Parent</span>
								@endif
								</span></td>
                                <td><span class="text-muted text-hover-primary">{{ $row->comp_pan_no }}</span></td>
                                <td><span class="text-muted text-hover-primary">{{ $row->gst_no }}</span></td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
										
											@if($row->company_type == 'parent')
											<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                <a href="{{ route('user.CompanyProfile') }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>

											@else
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                <a href="{{ route('proprietorship.edit',Crypt::encrypt($row->id)) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                <a href="#" 
                                                    data-id="{{ $row->id }}"
                                                    class="avtar avtar-xs btn-link-danger btn-pc-default deleteBtn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#delete_modal">
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


<div class="modal fade" id="proprietorshipModal">
		<div class="modal-dialog">
			<form id="proprietorshipForm">
				@csrf
				<div class="modal-content">
					<div class="modal-header">
						<h5>Enter Proprietorship Company Name</h5>
					</div>
					<div class="modal-body">
						<input type="text" name="company_name" id="company_name" class="form-control" placeholder="Enter Company Name" required>
						<div id="companyNameError" class="text-danger mt-2"></div>
						
						<small class="mt-2 d-block" style="color:#003399; font-weight:500;">
							Note: You can enter multiple company names for <b>Proprietorship</b>. 
							Simply submit the form again to add another company.
						</small>
					</div>
					
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" id="saveProprietorship">
							Submit
						</button>
					</div>
				</div>

			</form>
		</div>
	</div>



<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Proprietorship</h3>
                    <p>Are you sure you want to delete this proprietorship record?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="confirmDelete" class="w-100 btn btn-danger">
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
    let deleteId = null;

	$(document).on("click", ".deleteBtn", function() {
        
        deleteId = $(this).data('id');
    });

    $('#confirmDelete').on('click', function() {
        if (deleteId) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                url: '/proprietorship-delete/' + deleteId,
                type: 'GET',
                success: function(response) {
                    if (response.status == true || response.success == true) {
                        showToast("Proprietorship deleted successfully!", "success");
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        //showToast("Error: " + (response.message || "Unable to delete"), "error");
						showToast("Proprietorship deleted successfully!", "success");
                        setTimeout(() => location.reload(), 2000);
                    }
                },
                error: function(xhr) {
                    showToast("Error deleting proprietorship!", "error");
                }
            });
        }
    });
	
	//Add proprietorship company
	$('#proprietorshipModal').on('shown.bs.modal', function () {
		$('#companyNameError').text('');
	});
	
	$('#saveProprietorship').click(function(){
		$("#loader").show();
		$.ajax({
			url: "{{ route('check.proprietorship.company') }}",
			type: "POST",
			data: $('#proprietorshipForm').serialize(),
			success:function(response){
				$("#loader").hide();
				if(response.status == 'exists'){
					$('#companyNameError').text(response.message);
					 return;
				}

				if(response.status == 'success'){
					$('#proprietorshipModal').modal('hide');
					$('#company_name').val('');
					location.reload();
				}

			},
			error:function(xhr){
				$("#loader").hide();
				let errors = xhr.responseJSON.errors;
				if(errors.company_name){
					$('#companyNameError').text(errors.company_name[0]);
				}
			}
		});
	});
</script>

@endsection