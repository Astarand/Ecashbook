@extends('App.Layout')

@section('container')
<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">CA List</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead>
                            <tr style="background-color: #cbcbcb;">
                                <th class="text-end">#</th>
                                <th>CA ID</th>
                                <th>Name</th>
                                <th>Contact Number</th>
								<th>Subscription %</th>
                                <th>Joined Through</th>
                                <th>Join Date</th>
                                <th>Total Customer</th>
                                <th>Verify Status</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $company)
                                <tr>
                                    <td class="text-end">{{ $loop->iteration }}</td>
                                    <td><span class="text-muted text-hover-primary">CA00{{ $company->customerNo }}</span></td>
                                    <td>
                                        <div class="row">
                                            <div class="col">
                                                <h6 class="mb-1">{{ $company->comp_name }}</h6>
                                                <a class="text-muted f-12 text-hover-primary" href="mailto:{{ $company->comp_email }}">{{ $company->comp_email }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td><a class="text-muted text-hover-primary" href="#">{{ $company->comp_phone }}</a></td>
									<td>
										<span class="badge bg-info">
											{{ $company->subs_percentage }}%
										</span>
									</td>
                                    <td><span class="text-muted text-hover-primary">Own Assigned</span></td>
                                    <td><span class="text-muted text-hover-primary">{{ $company->created_at ? date('d-m-Y', strtotime($company->created_at)) : '' }}</span></td>
                                    <td><span class="text-muted text-hover-primary">{{ $company->customerNo }}</span></td>
                                    <td><span class="badge bg-success">{{ $company->isCaActive ? 'Verified' : 'Unverified' }}</span></td>
                                    <td><span class="badge {{ $company->status ? 'bg-success' : 'bg-danger' }}">{{ $company->status ? 'Active' : 'Inactive' }}</span></td>
                                    <td>
                                        <span><i class="ti ti-dots-vertical f-20"></i></span>
                                        <div class="prod-action-links">
                                            <ul class="list-inline me-auto mb-0">
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                    <a href="{{ route('admin.ca-details', ['id' => $company->id]) }}" class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                        <i class="ti ti-eye f-18"></i>
                                                    </a>
                                                </li>
												<li class="list-inline-item align-bottom" title="Update Subscription">
													<a href="#" 
													   class="avtar avtar-xs btn-link-primary btn-pc-default update-subscription-btn"
													   data-id="{{ $company->id }}"
													   data-percent="{{ $company->subs_percentage }}">
														<i class="ti ti-percentage f-18"></i>
													</a>
												</li>
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Message">
                                                    <a href="#" class="avtar avtar-xs btn-link-warning btn-pc-default message-ca-btn" data-id="{{ $company->id }}">
                                                        <i class="ph-duotone ph-chat-circle-text f-18"></i>
                                                    </a>
                                                </li>
												@if($company->status == 1)
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Deactivate">
                                                    <a href="#" data-id="{{ $company->id }}" data-status="0" class="status-change avtar avtar-xs btn-link-danger btn-pc-default status_update" data-bs-toggle="modal" data-bs-target="#customer_status_update">
                                                        <i class="ti ti-bell-off f-18"></i>
                                                    </a>
                                                </li>
												@else
												<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Activate">
                                                    <a href="#" data-id="{{ $company->id }}" data-status="1" class="status-change avtar avtar-xs btn-link-danger btn-pc-default status_update" data-bs-toggle="modal" data-bs-target="#customer_status_update">
                                                        <i class="ti ti-bell-off f-18"></i>
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

<!-- Subscription Modal -->
<div class="modal fade" id="subscriptionModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Update Subscription %</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="ca_id">
        <input type="number" id="subs_percentage" 
               class="form-control" min="0" max="100">
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancel
        </button>
        <button type="button" class="btn btn-primary" id="saveSubscription">
            Save
        </button>
      </div>

    </div>
  </div>
</div>


<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
		<form id="caMessageForm" method="post" action="{{ route('admin.ca.message') }}" enctype="multipart/form-data">
			<input type="hidden" name="ca_id" id="ca_id" value="">
			@csrf
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="messageModalLabel">Send Message to CA</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">                    
						<div class="mb-3">
							<label for="subject" class="form-label">Subject</label>
							<input type="text" class="form-control" id="subject" name="subject" required>
						</div>
						<div class="mb-3">
							<label for="message" class="form-label">Message</label>
							<textarea class="form-control" id="message" name="message" rows="4" required></textarea>
						</div>
						<div class="mb-3">
							<label for="attachment" class="form-label">Attachment</label>
							<input type="file" class="form-control" id="attachment" name="attachment">
						</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="sendMessageBtn">Send Message</button>
				</div>
			</div>
		</form>
    </div>
</div>

<div class="modal fade" id="customer_status_update">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Update CA Status</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="status_ca_id">
                <input type="hidden" id="status_value">
                Are you sure you want to update CA status?
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" id="confirmStatusBtn">Yes</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
	
	$(document).on('click', '.update-subscription-btn', function () {
		$('#ca_id').val($(this).data('id'));
		$('#subs_percentage').val($(this).data('percent'));
		$('#subscriptionModal').modal('show');
	});

	$('#saveSubscription').click(function () {
		$.post('/admin/update-ca-subscription', {
			_token: '{{ csrf_token() }}',
			ca_id: $('#ca_id').val(),
			percentage: $('#subs_percentage').val()
		}, function () {
			location.reload();
		});
	});

	$(document).on('click', '.status_update', function () {
		let caId = $(this).data('id');
		let status = $(this).data('status');

		$("#status_ca_id").val(caId);
		$("#status_value").val(status);
	});

	$("#confirmStatusBtn").click(function(){
		$.ajax({
			url: "{{ route('admin.ca.status') }}",
			type: "POST",
			data: {
				_token: "{{ csrf_token() }}",
				ca_id: $("#status_ca_id").val(),
				status: $("#status_value").val()
			},
			success: function(res){
				location.reload();
			}
		});
	});

    document.addEventListener('DOMContentLoaded', function () {

		const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));

		// Open modal
		document.querySelectorAll('.message-ca-btn').forEach(btn => {
			btn.addEventListener('click', function (e) {
				e.preventDefault();
				document.getElementById('ca_id').value = this.getAttribute('data-id');
				messageModal.show();
			});
		});

		// AJAX submit
		document.getElementById('sendMessageBtn').addEventListener('click', function () {

			let form = document.getElementById('caMessageForm');
			let formData = new FormData(form);

			// Clear old errors
			document.querySelectorAll('.text-danger').forEach(e => e.remove());

			fetch("{{ route('admin.ca.message') }}", {
				method: "POST",
				headers: {
					'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
				},
				body: formData
			})
			.then(async response => {
				let data = await response.json();
				if (!response.ok) {
					throw data;
				}
				return data;
			})
			.then(res => {
				alert(res.message);
				form.reset();
				messageModal.hide();
			})
			.catch(err => {
				if (err.errors) {
					// Show validation errors below fields
					Object.keys(err.errors).forEach(field => {
						let input = document.querySelector(`[name="${field}"]`);
						let error = document.createElement('div');
						error.classList.add('text-danger');
						error.innerText = err.errors[field][0];
						input.parentNode.appendChild(error);
					});
				} else {
					alert('Something went wrong');
				}
			});
		});


	});
</script>
@endsection