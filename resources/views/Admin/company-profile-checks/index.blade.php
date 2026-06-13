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
				<li class="breadcrumb-item"><a href="{{ route('admin.company.checks') }}"> Business Health Check-up</a></li>
			  </ul>
			</div>
			@if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
			<div class="col-md-4">
				<div class="page-header-title">
					<h3 class="mb-0">Business Health Check-up</h3>
				</div>
			</div>
			<div class="col-md-8 text-end">				
				@php
					$authUser = Auth::user();
					$userId = ($authUser->u_type == 2) ? $authUser->id : $authUser->user_add_by;
				@endphp
				
				@if($canApply)
				<a href="{{ route('user.BusinessHealthCheckup') }}?uid={{ $userId }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Apply Health Check-up</a>
				@else
					<button class="btn btn-secondary" disabled> Apply After {{ $nextApplyDate->format('d-m-Y') }}</button>
				@endif
			</div>
			@endif
		  </div>
    </div>
  </div>
  <!-- [ breadcrumb ] end -->

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5>Business Health Check-up</h5>
        </div>
        <div class="card-body">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>User</th>
						<th>Status</th>
						<th>Approved On</th>
						<th>Health Report</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($list as $row)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td>{{ $row->comp_name ?? '-' }}</td>
						<td>
							<span class="badge 
								{{ $row->admin_status == '1' ? 'bg-success' : 
								   ($row->admin_status == '0' ? 'bg-danger' : 'bg-warning') }}">
								
								{{ $row->admin_status == '1' ? 'Approved' : 
								   ($row->admin_status == '0' ? 'Pending' : 'Pending') }}
							</span>
						</td>
						<td>{{ $row->approved_on ? date('d-m-Y', strtotime($row->approved_on)) : '-' }}</td>
						<td>
						@if(!empty($row->admin_certificate))
							<a href="{{ asset($row->admin_certificate) }}" 
							   target="_blank" 
							   class="btn btn-sm btn-primary">
								<i class="ti ti-eye"></i>
							</a>
						@else
							-
						@endif
						</td>
						<td>
							<a href="{{ route('admin.company.checks.view',$row->id) }}"
							   class="btn btn-sm btn-primary">
							   View
							</a>
							@if(Auth::user()->u_type == 3 || Auth::user()->u_type == 6)
								<a href="{{ route('user.BusinessHealthCheckup') }}?uid={{ encrypt($row->userId) }}"
								   class="btn btn-sm btn-primary">
								   Update Status
								</a>
							@else
								@if($canApply)
								<a href="{{ route('user.BusinessHealthCheckup') }}?uid={{ $userId }}"
								   class="btn btn-sm btn-primary">
								   Repeat
								</a>
								@endif
							@endif
							
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
 
</script>
@endsection