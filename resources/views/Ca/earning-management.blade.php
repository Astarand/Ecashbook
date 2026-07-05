@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/ca-dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Earning Details</li>
                    </ul>
                    <a href="javascript:void(0);" onclick="startCAEarningTour();" id="start-ca-earning-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Earning Details</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div class="row">

        <div class="col-md-12 col-xl-4" id="card-total-companies">
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

        <div class="col-md-12 col-xl-4" id="card-total-trials">
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

        <div class="col-md-12 col-xl-4" id="card-total-active">
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

        <div class="col-sm-6 col-xl-4" id="card-usage-credit">
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
            <div class="card table-card" id="earnings-table-card">
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

@section('page-script')
<script>
    function startCAEarningTour() {
        function launch() {
            introJs().setOptions({
                steps: [
                    {
                        title: 'Earning Details Dashboard',
                        intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-wallet" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Welcome to the Earning Details dashboard. Track referred client subscriptions, platform usage commission credits, and account status.</p></div>'
                    },
                    {
                        element: '#card-total-companies',
                        title: 'Total Attached Companies',
                        intro: 'Displays the total count of client companies currently mapped under your Chartered Accountant registry.'
                    },
                    {
                        element: '#card-total-trials',
                        title: 'Free Trial Accounts',
                        intro: 'Displays the number of client businesses currently utilizing the platform free trial period.'
                    },
                    {
                        element: '#card-total-active',
                        title: 'Active Paid Accounts',
                        intro: 'Displays the count of referred clients with active paid subscription packages.'
                    },
                    {
                        element: '#card-usage-credit',
                        title: 'Usage Commission Credit',
                        intro: 'Total revenue share commission you have earned from referred client subscription payments.'
                    },
                    {
                        element: '#earnings-table-card',
                        title: 'Earning Ledger Records',
                        intro: 'Detailed tabular ledger showing client subscription package names, duration types, pricing, your revenue share credit, expiry dates, and transaction status.'
                    }
                ],
                showBullets: true,
                showProgress: true,
                helperElementPadding: 5,
                exitOnOverlayClick: false,
                doneLabel: 'Done',
                nextLabel: 'Next',
                prevLabel: 'Prev',
                skipLabel: 'Skip'
            }).start();
        }

        if (typeof introJs === 'function') {
            launch();
        } else {
            if (!document.getElementById('introjs-cdn-css')) {
                let css = document.createElement('link');
                css.id = 'introjs-cdn-css';
                css.rel = 'stylesheet';
                css.href = 'https://cdn.jsdelivr.net/npm/intro.js@7.2.0/introjs.min.css';
                document.head.appendChild(css);
            }
            let js = document.createElement('script');
            js.src = 'https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js';
            js.onload = function() {
                launch();
            };
            document.body.appendChild(js);
        }
    }

    $(document).ready(function() {
        $('#start-ca-earning-tour').on('click', function(e) {
            e.preventDefault();
            startCAEarningTour();
        });
    });
</script>
@endsection