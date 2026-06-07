@extends('App.Layout')

@section('container')

<div class="pc-content">
    <div class="row">

        <div class="col-md-12 col-xl-4">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <p class="text-muted mb-1">Total Company Attached</p>
                            <div class="d-flex align-items-center mb-1">
                                <h4 class="mb-0">{{ $totalCompanies }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <img src="../assets/images/widget/img-visitor.png" alt="img" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-xl-4">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <p class="text-muted mb-1">Total Trial User</p>
                            <div class="d-flex align-items-center mb-1">
                                <h4 class="mb-0">{{ $totalTrial }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <img src="../assets/images/widget/img-visitor.png" alt="img" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-xl-4">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <p class="text-muted mb-1">Total Active Platform Accounts</p>
                            <div class="d-flex align-items-center mb-1">
                                <h4 class="mb-0">{{ $totalSubscribers }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <img src="../assets/images/widget/img-visitor.png" alt="img" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <p class="mb-0">Total Platform Usage Credit</p>
                    </div>
                    <div class="d-flex align-items-center mb-1">
                        <h5 class="mb-0">₹ {{ number_format($totalEarning, 2) }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <p class="mb-0">Balance Left</p>
                    </div>
                    <div class="d-flex align-items-center mb-1">
                        <h5 class="mb-0">₹ 0</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <p class="mb-0">Total Platform Fee Settlement</p>
                        <a href="{{ route('ca.EarningTransaction') }}"><i class="ph-duotone ph-arrow-square-in f-20 ms-1 text-danger" data-bs-toggle="tooltip" data-bs-title="View Details"></i></a>
                    </div>
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0">₹ 0</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 mt-5">
            <div class="card table-card">
                <div class="card-body table-card">
                    <table class="table tbl-product" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
                                <th>Company Name</th>
                                <th>Package Name</th>
                                <th>Subscription Type</th>
                                <th>Package Amount</th>
                                <th>Platform Usage Credit</th>
                                <th>End</th>
                                <th>Days Left</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
							@foreach($data as $row)
                            <tr>
                                <td class="text-end">1</td>
                                <td>
                                    <div class="row">                                        
                                        <div class="col">
                                            <h6 class="mb-1">{{ $row->name }}</h6>
                                            <a class="text-muted f-12 text-hover-primary" href="mailto:{{ $row->email }}">{{ $row->email }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ ucfirst($row->package_name) }}</td>
                                <td>{{ ucfirst($row->plan_type) }}</td>
                                <td>{{ $row->paid_amount }}</td>
                                <td>{{ $row->ca_amt }}</td>
								<td>{{ $row->end_at_fmt }}</td>
								<td>
								@if($row->is_expired)
									<span class="badge bg-danger">Expired</span>
								@else
									<span class="badge bg-success">
										Active ({{ $row->days_left }} days left)
									</span>
								@endif
								</td>
                                <td>
                                    <span class="badge bg-success">{{ $row->payment_status }}</span>
                                </td>
                            </tr>
							@endforeach
                        </tbody>
                    </table>
					{{ $data->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    </div>
</div>

@endsection