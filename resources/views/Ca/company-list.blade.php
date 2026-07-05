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
                        <li class="breadcrumb-item"><a href="{{ url('/company-list') }}">Companies</a></li>
                        <li class="breadcrumb-item" aria-current="page">Company List</li>
                    </ul>
                    <a href="javascript:void(0);" onclick="startCACompaniesTour();" id="start-ca-companies-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Company List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end mt-2">
                    <a href="#" class="btn btn-success me-2" data-bs-toggle="tooltip" title="Whatsapp"><i class="ti ti-brand-whatsapp"></i></a>
                    <a href="#" class="btn btn-secondary me-2" data-bs-toggle="tooltip" title="Download Now"><i class="ti ti-download"></i></a>
                    <a href="{{ route('CA.CompanyAdd') }}" id="add-company-btn" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Company</a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card table-card" id="companies-table-card">
                <div class="card-body table-card">
                    <div class="table-responsive">
                        <table class="table tbl-product" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th class="text-end">#</th>
                                    <!--<th>Company ID</th>-->
                                    <th>Name</th>
                                    <th>Contact Number</th>
                                    
                                    <th>Assign By</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($customers as $customer)
                                <tr>
                                    <td class="text-end"><?php echo $i++; ?></td>
                                    <!--<td><a class="text-muted text-hover-primary" href="#">{{ $customer->compId }}</a></td>-->
                                    <td>
                                        <div class="row">
                                            <div class="col-auto pe-0">
                                                <div class="avtar avtar-s btn-light-primary">
                                                    <img src="../assets/images/user/avatar-2.jpg" alt="user-image" class="wid-40 rounded">
                                                </div>
                                            </div>
                                            <div class="col">                                                
												<a href="{{ url('/client-view/'.base64_encode($customer->userId)) }}" class="text-dark text-decoration-none">
													{{ ($customer->comp_name!="")?$customer->comp_name:$customer->name }}
												</a>
                                                <a class="text-muted f-12 text-hover-primary" href="mailto:{{$customer->email}}">{{$customer->email}}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td><a class="text-muted text-hover-primary" href="#">+91{{ $customer->phone }}</a></td>
                                    <td>
                                        @if ($customer->ca_add_by==0)
                                        <span class="badge bg-success">Requested Assign</span>
                                        @else
                                        <span class="badge bg-success">Own Assign</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($customer->ca_current_status==0)
                                        <span class="badge bg-primary">Requested</span>
                                        @elseif ($customer->ca_current_status==1)
                                        <span class="badge bg-success">Active</span>
                                        @elseif ($customer->ca_current_status==2)
                                        <span class="badge bg-danger">Deactive</span>
                                        @elseif ($customer->ca_current_status==3)
                                        <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span><i class="ti ti-dots-vertical f-20"></i></span>
                                        <div class="prod-action-links">
                                            <ul class="list-inline me-auto mb-0">
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                    <a
                                                        href="{{ url('/client-view/'.base64_encode($customer->userId)) }}"
                                                        class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                        <i class="ti ti-eye f-18"></i>
                                                    </a>
                                                </li>
												<a href="javascript:void(0);"
												   class="btn btn-primary position-relative start-chat"
												   data-company="{{ $customer->userId }}"
												   data-ca="{{ $caId }}">
													Chat
													@if($customer->unread_count >= 0)
														<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
															{{ $customer->unread_count }}
														</span>
													@endif
												</a>
                                                @if ($customer->ca_current_status==0 || $customer->ca_current_status==2)
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Activate">
                                                    <a
                                                        href="javascript:void(0);"
                                                        data-stat="1"
                                                        data-id="{{$customer->userId}}"
                                                        class="avtar avtar-xs btn-link-success btn-pc-default custCAactive"
                                                        data-bs-toggle="offcanvas"
                                                        data-bs-target="#productOffcanvas">
                                                        <i class="ti ti-bell-off f-18"></i>
                                                    </a>
                                                </li>
                                                @elseif ($customer->ca_current_status==1)
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Deactivate">
                                                    <a
                                                        href="javascript:void(0);"
                                                        data-stat="2"
                                                        data-id="{{$customer->userId}}"
                                                        class="avtar avtar-xs btn-link-danger btn-pc-default custCAactive"
                                                        data-bs-toggle="offcanvas"
                                                        data-bs-target="#productOffcanvas">
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
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

@endsection

@section('page-script')
<script>
    function startCACompaniesTour() {
        function launch() {
            introJs().setOptions({
                steps: [
                    {
                        title: 'Company List',
                        intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-building" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Manage all companies and clients assigned to you or added by your firm.</p></div>'
                    },
                    {
                        element: '#add-company-btn',
                        title: 'Onboard New Company',
                        intro: 'Click here to register a new client company profile under your CA firm.'
                    },
                    {
                        element: '#companies-table-card',
                        title: 'Companies Roster',
                        intro: 'List of all clients showing company name, contact numbers, email, assignment source (own or requested), status, and actions.'
                    },
                    {
                        element: '.prod-action-links',
                        title: 'Action Panel',
                        intro: 'Quick actions to view client profile details, start a real-time chat, or activate/deactivate the company account.'
                    }
                ],
                showBullets: true,
                showProgress: true,
                helperElementPadding: 5,
                exitOnOverlayClick: false,
                doneLabel: 'Done',
                nextLabel: 'Next',
                prevLabel: 'Prev',
                skipLabel: 'Skip',
                skipIfNoElement: true
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
        $('#start-ca-companies-tour').on('click', function(e) {
            e.preventDefault();
            startCACompaniesTour();
        });
    });
</script>
@endsection