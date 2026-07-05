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
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">HR & Payroll Management</a></li>
                        <li class="breadcrumb-item"><a href="#">HR, Payroll & Attendance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Employment Policies</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-employee-policy-list-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Employment Policies</h2>
                    </div>
                </div>
                {{-- <div class="col-md-8 text-end">
                    <a href="{{ route('user.AddEmployeePolicy') }}" class="btn btn-primary">
                        <i class="ti ti-square-plus"></i> Generate New Policy
                    </a>
                </div> --}}
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
                    <table class="table table-hover tbl-product" id="pc-dt-simple">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Policy Issue Date</th>
                                <th>Policy Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($policies as $index => $policy)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($policy->created_at)
                                    {{ \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y') }}
                                    @else
                                    <span class="text-muted">Not Created</span>
                                    @endif
                                </td>
                                <td>{{ $policy->subject }}</td>
                                <td>
                                    @if ($policy->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                    @elseif ($policy->status === 'deactive')
                                    <span class="badge bg-warning text-dark">Inactive</span>
                                    @elseif ($policy->status === 'inactive')
                                    <span class="badge bg-secondary">Not Created</span>
                                    @else
                                    <span class="badge bg-danger">Deleted</span>
                                    @endif
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            @if($policy->id)
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                title="View">
                                                <a href="{{ url('employee-policy/view/' . $policy->id) }}"
                                                    class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
                                            @endif
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                title="Edit">
                                                @if($policy->id)
                                                <a href="{{ route('employee.policy.edit', $policy->id) }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                                @else
                                                <a href="{{ route('employee.policy.create', urlencode($policy->subject)) }}"
                                                    class="avtar avtar-xs btn-link-primary btn-pc-default">
                                                    <i class="ti ti-plus f-18"></i>
                                                </a>
                                                @endif
                                            </li>
                                            @if($policy->id)
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                title="Delete">
                                                <a href="javascript:void(0)" onclick="deletePolicy({{ $policy->id }})"
                                                    class="avtar avtar-xs btn-link-danger btn-pc-default">
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
    <!-- [ Main Content ] end -->
</div>

@endsection

@section('page-script')
<script>
    function deletePolicy(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This policy will be moved to deleted status.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/employee-policy/delete/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                showToast(data.message, data.status === 'success' ? 'success' : 'error');
                if (data.status === 'success') location.reload();
            })
            .catch(() => showToast('Something went wrong!', 'error'));
        }
    });
}

    function startEmployeePolicyListTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Employee Policies Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Review and list company handbooks, guidelines, and compliance policies.</p></div>'
                },
                {
                    title: 'Employee Policies',
                    intro: 'Review and list company handbooks, guidelines, and compliance policies.'
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
        $('#start-employee-policy-list-tour').on('click', function(e) {
            e.preventDefault();
            startEmployeePolicyListTour();
        });
    });
</script>
@endsection
