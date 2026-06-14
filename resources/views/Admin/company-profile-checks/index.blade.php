@extends('App.Layout')

@section('container')

<div class="pc-content">
  <!-- [ breadcrumb ] start -->
  <div class="page-header">
    <div class="page-block">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="d-flex justify-content-between align-items-center w-100">
            <ul class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('admin.company.checks') }}"> Business Health Check-up</a></li>
            </ul>
            <a href="javascript:void(0);" id="start-company-checks-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                <u>How does this Page works?</u>
            </a>
          </div>
        </div>
        <div class="col-md-4 mt-3">
          <div class="page-header-title">
            <h3 class="mb-0">Business Health Check-up</h3>
          </div>
        </div>
        @if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
        <div class="col-md-8 text-end mt-3">				
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
      <div class="card card-body table-card">
        <div class="table-responsive">
          <table class="table tbl-product table-hover mb-0" id="pc-dt-simple">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th>User/Company Name</th>
                <th>Status</th>
                <th>Approved On</th>
                <th>Health Report</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($list as $row)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                  <div class="row align-items-center">
                    <div class="col-auto pe-0">
                      <div class="avtar avtar-s btn-light-primary">
                        <i class="ti ti-building f-20"></i>
                      </div>
                    </div>
                    <div class="col">
                      <h6 class="mb-0">{{ $row->comp_name ?? '-' }}</h6>
                    </div>
                  </div>
                </td>
                <td>
                  @if($row->admin_status == '1')
                    <span class="badge bg-light-success text-success">Approved</span>
                  @else
                    <span class="badge bg-light-warning text-warning">Pending</span>
                  @endif
                </td>
                <td>
                  <span class="text-muted"><i class="ti ti-calendar me-1"></i>{{ $row->approved_on ? date('d-m-Y', strtotime($row->approved_on)) : '-' }}</span>
                </td>
                <td>
                  @if(!empty($row->admin_certificate))
                    <a href="{{ asset($row->admin_certificate) }}" 
                       target="_blank" 
                       class="avtar avtar-xs btn-link-secondary btn-pc-default tour-report-btn"
                       data-bs-toggle="tooltip"
                       title="View Report">
                      <i class="ti ti-file-text f-18 text-primary"></i>
                    </a>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td class="text-center">
                  <div class="d-flex align-items-center justify-content-center gap-2">
                    <a href="{{ route('admin.company.checks.view',$row->id) }}"
                       class="avtar avtar-xs btn-link-success btn-pc-default tour-view-btn"
                       data-bs-toggle="tooltip"
                       title="View Check-up Details">
                       <i class="ti ti-eye f-18"></i>
                    </a>
                    @if(Auth::user()->u_type == 3 || Auth::user()->u_type == 6)
                      <a href="{{ route('user.BusinessHealthCheckup') }}?uid={{ encrypt($row->userId) }}"
                         class="avtar avtar-xs btn-link-warning btn-pc-default"
                         data-bs-toggle="tooltip"
                         title="Update Status">
                         <i class="ti ti-edit-circle f-18"></i>
                      </a>
                    @else
                      @if($canApply)
                      <a href="{{ route('user.BusinessHealthCheckup') }}?uid={{ $userId }}"
                         class="avtar avtar-xs btn-link-info btn-pc-default"
                         data-bs-toggle="tooltip"
                         title="Repeat Check-up">
                         <i class="ti ti-rotate f-18"></i>
                      </a>
                      @endif
                    @endif
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

<script>
    function startCompanyChecksTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Business Health Check-up',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-activity" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Welcome to the Business Health Check-up management. Here you can monitor, review, and track audit/compliance requests.</p></div>'
                },
                {
                    element: '.table-responsive',
                    title: 'Health Check-ups List',
                    intro: 'This table displays all filed check-ups including the user/company, status, approval dates, and generated health reports.'
                },
                {
                    element: '.tour-report-btn',
                    title: 'Health Report',
                    intro: 'Once approved, click here to view or download the comprehensive Business Health Report certificate.',
                    position: 'top'
                },
                {
                    element: '.tour-view-btn',
                    title: 'View Check-up Details',
                    intro: 'Click this view icon to inspect the details submitted for the check-up compliance reviews.',
                    position: 'left'
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

    $(document).ready(function() {
        $('#start-company-checks-tour').on('click', function(e) {
            e.preventDefault();
            startCompanyChecksTour();
        });
    });
</script>
@endsection