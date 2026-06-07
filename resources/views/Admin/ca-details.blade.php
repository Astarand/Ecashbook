@extends('App.Layout')

@section('container')
<div class="pc-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card customer-details-group shadow-sm">
                <div class="card-body">
                    <div class="row gy-3 align-items-center">
                        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-img">
                                        <img src="/storage/ca_profile/{{$users->comp_logo}}" alt="">
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>{{$users->comp_name}}</h6>
                                        <p>Company Representative</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-envelope-open"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Email Address</h6>
                                        <p>{{$users->comp_email}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-phone-call"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Phone Number</h6>
                                        <p>{{$users->comp_phone}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-bank"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Company Name</h6>
                                        <p>{{$users->comp_name}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-globe"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Website</h6>
                                        <p><a href="{{$users->comp_website}}" target="_blank">{{$users->comp_website}}</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-navigation-arrow"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Company Address</h6>
                                        <p>{{$users->comp_bill_addone}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 mb-5">
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-users text-primary f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">Total No of Customers</h6>
                                <h4 class="fw-bold mb-0">{{$users->customerNo}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-user-plus text-success f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">Subscribed Customers Count</h6>
                                <h4 class="fw-bold mb-0">{{$users->customerNo}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-user-circle-minus text-danger f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">No of Free Customers</h6>
                                <h4 class="fw-bold mb-0">{{$users->customerNo}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-briefcase text-primary f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">Total Task Ongoing</h6>
                                <h4 class="fw-bold mb-0">{{ $users->total_tasks }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-currency-circle-dollar text-primary f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">Total Earnings</h6>
                                <h4 class="fw-bold mb-0">₹ {{ $users->total_earning }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-currency-dollar text-success f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">Withdrawal Amount</h6>
                                <h4 class="fw-bold mb-0">₹ 0</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-wallet text-success f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">Wallet Amount</h6>
                                <h4 class="fw-bold mb-0">₹ 0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- [ sample-page ] start -->
            <div class="col-sm-12">
                <div class="card card-body table-card">
                    <div class="table-responsive">
                        <table class="table tbl-product my-3" id="pc-dt-simple">
                            <thead>
                                <tr style="background-color: #cbcbcb;">
                                    <th class="text-end">#</th>
                                    <th>Customer ID</th>
                                    <th>Name</th>
                                    <th>Contact Number</th>
                                    <th>Assign For</th>
                                    <th>Total Task</th>
                                    <th>Ongoing Task</th>
                                    <th>Due Task</th>
                                    <th>Total Earning</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as  $cust)
                                <tr>
                                    <td class="text-end">{{$loop->index + 1}}</td>
                                    <td><span class="text-muted text-hover-primary">CUST001</span></td>
                                    <td>
                                        <div class="row">
                                            <div class="col">
                                                <h6 class="mb-1">{{$cust->name}}</h6>
                                                <a class="text-muted f-12 text-hover-primary" href="mailto:john@example.com">{{$cust->email}}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td><a class="text-muted text-hover-primary" href="#">+91 {{$cust->phone}}</a></td>
                                    <td>
                                        <span class="badge bg-info rounded-pill me-1">GST</span>
                                        <span class="badge bg-primary rounded-pill">ITR</span>
                                    </td>
                                    <td><span class="text-muted text-hover-primary">50</span></td>
                                    <td><span class="text-muted text-hover-primary">20</span></td>
                                    <td><span class="text-muted text-hover-primary">10</span></td>
                                    <td><span class="text-muted text-hover-primary">₹ 1000</span></td>
                                    <td>
                                        <span class="badge {{ $cust->ca_assign_status ? 'bg-success' : 'bg-danger' }}">{{ $cust->ca_assign_status ? 'Active' : 'Inactive' }}
                                        
                                    </td>
                                    <td>
                                        <span><i class="ti ti-dots-vertical f-20"></i></span>
                                        <div class="prod-action-links">
                                            <ul class="list-inline me-auto mb-0">
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                    <a href="#" class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                        <i class="ti ti-eye f-18"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Unassign">
                                                    <a href="#" data-id="1" data-status="0" class="status-change avtar avtar-xs btn-link-danger btn-pc-default status_update" data-bs-toggle="modal" data-bs-target="#customer_status_update">
                                                        <i class="ph-duotone ph-user-circle-minus f-18"></i>
                                                    </a>
                                                </li>
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
    </div>
</div>
@endsection