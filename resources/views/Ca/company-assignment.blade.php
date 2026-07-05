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
                        <li class="breadcrumb-item" aria-current="page">Company Assignment</li>
                    </ul>
                    <a href="javascript:void(0);" onclick="startCAAssignmentTour();" id="start-ca-assignment-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Company Assignment</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <h5 class="mb-1">Company Assignment List</h5>
                        </div>
                        <select class="form-select rounded-3 form-select-sm w-auto" id="compAssignList" onChange="getAssignRequestChart();">
                        <option value="daily">Daily</option>
                        <option  value="monthly" selected>Monthly</option>
						<option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <!--<div id="company-assignment-chart"></div>-->
					<div id="assign_chart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card table-card" id="pending-requests-card">
              <div class="card-header d-flex align-items-center justify-content-between py-3">
                <h5>Request Company/User List</h5>
              </div>
              <div class="card-body py-4 px-0">
                <div class="table-responsive" style="max-height: 295px; overflow-y: auto; display:block">
                  <table class="table table-hover table-borderless table-sm mb-0">
                    <tbody>
						@foreach ($customerLists as $customer)
                        <tr>
                            <td>
                                <div class="d-inline-block align-middle">
                                    <img src="../assets/images/user/avatar-1.jpg" alt="user image" class="img-radius align-top m-r-15" style="width:40px;">
                                    <div class="d-inline-block">
                                        <h6 class="m-b-0">
                                            <a href="javascript:void(0);" data-id="{{$customer->id}}" class="viewCustomerDet" data-bs-toggle="modal" data-bs-target="#userDetailsModal">{{ ($customer->comp_name!="")?$customer->comp_name:$customer->name }}</a>
                                        </h6>
                                        <p class="m-b-0"><strong>Request For:</strong> {{ ($customer->request_for!="")?substr($customer->request_for, 0, 20):"" }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="mb-0"><i class="ph-duotone ph-circle text-warning f-12"></i> {{ date("d M Y",strtotime($customer->created_at)) }}</p>
                            </td>
                            <td class="text-end">
                                <button  data-id="{{$customer->ca_assign_id}}" class="btn avtar avtar-xs btn-light-danger requestDelete"  title="Reject">
                                    <i class="ti ti-x"></i>
                                </button>
                                <button  data-id="{{$customer->ca_assign_id}}" class="btn avtar avtar-xs btn-light-success requestAccept"  title="Accept">
                                    <i class="ti ti-check"></i>
                                </button>
                            </td>
                        </tr>
						@endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="card table-card" id="assigned-companies-card">
                <div class="card-body table-card">
                    <table class="table tbl-product" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Contact Number</th>
                                <th>Company Extra Nature</th>
                                {{-- <th>Company Entity</th> --}}
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
							<?php $i = 1; ?>
							@foreach ($customers as $customer)
                            <tr>
                                <td class="text-end"><?php echo $i++; ?></td>
                                <td>{{ date("d M Y",strtotime($customer->created_at)) }}</td>
                                <td>
                                    <div class="row">
                                        {{-- <div class="col-auto pe-0">
                                            <div class="avtar avtar-s btn-light-primary">
                                                <img src="{{ asset('storage/profile/' . $customer->comp_logo) }}" alt="" class="wid-40 rounded">
                                            </div>
                                        </div> --}}
                                        <div class="col-auto pe-0">
                                            <div class="avtar avtar-s btn-light-primary">
                                                <img 
                                                    src="{{ !empty($customer->comp_logo) ? asset('storage/profile/' . $customer->comp_logo) : asset('assets/images/user/ecashbook.png') }}"
                                                    alt="Company Logo"
                                                    class="wid-40 rounded"
                                                    onerror="this.onerror=null;this.src='{{ asset('assets/images/user/ecashbook.png') }}';">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h6 class="mb-1">{{ ($customer->comp_name!="")?$customer->comp_name:$customer->name }}</h6>
                                            <a class="text-muted f-12 text-hover-primary" href="mailto:{{ ($customer->comp_email!="")?$customer->comp_email:$customer->email }}">{{ ($customer->comp_email!="")?$customer->comp_email:$customer->email }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td><a class="text-muted text-hover-primary" href="#">{{ ($customer->comp_phone!="")?$customer->comp_phone:$customer->phone }}</a></td>
                                <td><a class="text-muted text-hover-primary" href="#">{{ $customer->exact_comp_nature }}</a></td>
                                <td>
                                    
									@if ($customer->ca_current_status==0)
									<span class="badge bg-primary">Requested</span>
									@elseif ($customer->ca_current_status==1)
									<span class="badge bg-success">Active</span></td>
									@elseif ($customer->ca_current_status==2)
									<span class="badge bg-danger">Deactive</span></td>
									@elseif ($customer->ca_current_status==3)
									<span class="badge bg-danger">Rejected</span></td>
									@endif
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a
                                                    href="{{ url('/client-view/'.base64_encode($customer->id)) }}"
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
                                            {{-- <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                <a href="{{ url('/edit-company-assignment/'.base64_encode($customer->id)) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li> --}}
											@if ($customer->ca_current_status==0)
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                <a href="javascript:void(0);" data-id="{{$customer->id}}" data-stat="3" class="avtar avtar-xs btn-link-danger btn-pc-default custCAactive" data-bs-toggle="modal" data-bs-target="#delete_modal">
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

    </div>
</div>

<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="card user-card" style="margin-bottom:0px;">
                <div class="card-body">
                    <div class="chat-avtar d-inline-flex mx-auto">
                        <img id="cLogo" class="rounded-circle img-fluid wid-90 img-thumbnail" src="../assets/images/user/avatar-1.jpg" alt="User image">
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <h6 class="mb-1" id="cName"></h6>
                        </div>
                        <div class="flex-shrink-0">
                            <!--<button id="requestAccept" class="btn btn-success btn-md me-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Accept</button>
                            <button id="requestDelete" class="btn btn-danger btn-md" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Reject</button>-->
							
							<button  class="btn btn-success btn-md me-2 requestAccept"  >Accept</button>
                            <button  class="btn btn-danger btn-md requestDelete" >Reject</button>
                        </div>
                    </div>
                    <div class="row g-3 my-3 text-center">
                        <div class="col-6">
                            <h5 class="mb-0">Company Name</h5>
                            <small class="text-muted" id="cCompName"></small>
                        </div>
                        <div class="col-6 border border-top-0 border-bottom-0 border-right-0">
                            <h5 class="mb-0">Requested On</h5>
                            <small class="text-muted" id="requestedAt"></small>
                        </div>
                    </div>
                    <div class="saprator my-3">
                        <span>Request For</span>
                    </div>
                    <div class="text-center">
                        <span id="cReqFor" class="badge bg-light-secondary border rounded-pill border-secondary bg-transparent f-14 me-1 mt-1">GST</span>
                        <!--<span class="badge bg-light-secondary border rounded-pill border-secondary bg-transparent f-14 me-1 mt-1">Company Incorporation</span>
                        <span class="badge bg-light-secondary border rounded-pill border-secondary bg-transparent f-14 me-1 mt-1">ROC</span>-->
                    </div>
                    <div class="saprator my-3">
                        <span>Address</span>
                    </div>
                    <h6 class="text-center" id="cAddr"> </h6>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    function startCAAssignmentTour() {
        function launch() {
            introJs().setOptions({
                steps: [
                    {
                        title: 'Company Assignment',
                        intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-link" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Manage incoming customer service requests, accept assignments, and view assignment metrics.</p></div>'
                    },
                    {
                        element: '#assign_chart',
                        title: 'Assignment Metrics Chart',
                        intro: 'Provides a daily, monthly, or yearly breakdown of your assignments and requests.'
                    },
                    {
                        element: '#pending-requests-card',
                        title: 'Assignment Requests',
                        intro: 'List of companies requesting your CA services. View their specific service needs (GST, ROC, Audit) and click Accept (check icon) or Reject (cross icon).'
                    },
                    {
                        element: '#assigned-companies-card',
                        title: 'Active Mapped Clients',
                        intro: 'Registry of all currently active company assignments showing their profile parameters, extra business nature, status, and communication links.'
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
        $('#start-ca-assignment-tour').on('click', function(e) {
            e.preventDefault();
            startCAAssignmentTour();
        });
    });
</script>
@endsection
