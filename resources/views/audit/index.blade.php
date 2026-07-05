@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Audit & Log Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Log Lists</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Log Lists</h2>
                    </div>
                </div>
                
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
											<th>User</th>
											<th>Role</th>
											<th>Action</th>
											<th>Module</th>
											<th>IP Address</th>
											<th>Date & Time</th>
											<th>Details</th>
										</tr>
									</thead>
									<tbody>
										@forelse($logs as $log)
										<tr>
											<td class="text-end">{{ $loop->iteration }}</td>
											<td><span class="text-muted text-hover-primary fw-semibold">{{ $log->user_name }}</span></td>
											<td>
												<span class="badge bg-info">
													{{ $log->user_type_label }}
												</span>
											</td>
											<td>
												<span class="badge bg-primary">
													{{ ucfirst($log->action) }}
												</span>
											</td>
											<td><span class="text-muted text-hover-primary">{{ $log->module ?? '-' }}</span></td>
											<td><span class="text-muted text-hover-primary">{{ $log->ip }}</span></td>
											<td><span class="text-muted text-hover-primary">{{ $log->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}</span></td>
											<td>
												<span><i class="ti ti-dots-vertical f-20"></i></span>
												<div class="prod-action-links">
													<ul class="list-inline me-auto mb-0">
														<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View Details">
															<a href="#" class="avtar avtar-xs btn-link-warning btn-pc-default" onclick='viewAudit({{ json_encode($log) }})'>
																<i class="ti ti-eye f-18"></i>
															</a>
														</li>
													</ul>
												</div>
											</td>
										</tr>
										@empty
										<tr>
											<td colspan="8" class="text-center text-muted py-4">
												No logs found
											</td>
										</tr>
										@endforelse
									</tbody>
								</table>
							</div>
						</div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>
@include('audit.modal')


<script>
   function viewAudit(log) {
		document.getElementById('m_user_id').innerText = log.user_name ?? '-';
		document.getElementById('m_user_type').innerText = userTypeText(log.user_type);
		document.getElementById('m_action').innerText = log.action ?? '-';
		document.getElementById('m_module').innerText = log.module ?? '-';
		document.getElementById('m_description').innerText = log.description ?? '-';
		//document.getElementById('m_url').innerText = log.url ?? '-';
		document.getElementById('m_method').innerText = log.method ?? '-';
		document.getElementById('m_ip').innerText = log.ip ?? '-';
		document.getElementById('m_user_agent').innerText = log.user_agent ?? '-';

		document.getElementById('m_old_data').innerText =
			log.old_data ? JSON.stringify(JSON.parse(log.old_data), null, 2) : 'N/A';

		document.getElementById('m_new_data').innerText =
			log.new_data ? JSON.stringify(JSON.parse(log.new_data), null, 2) : 'N/A';

		new bootstrap.Modal(document.getElementById('auditModal')).show();
	}

	function userTypeText(type) {
		const map = {
				1 : 'CA',
				2 : 'User',
				3 : 'Admin',
				4 : 'CA Employee',
				5 : 'User Employee',
				6 : 'Admin Employee',
		};
		return map[type] ?? 'Unknown';
	}
</script>

@endsection