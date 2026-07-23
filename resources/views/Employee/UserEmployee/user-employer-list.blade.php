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
                            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Employer Policy</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-employee-policy-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <u>How does this Page works?</u>
                        </a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Employer Policy List</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-body table-card" id="policies-table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Policy Title</th>
                                <th>Created On</th>
                                <th>Status</th>
                                <th>Acceptance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($policies as $index => $policy)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $policy->subject }}</td>
                                    <td>{{ \Carbon\Carbon::parse($policy->created_at)->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if($policy->status == 'active')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($policy->status == 'deactive')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-secondary">Unknown</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($policy->policy_status == 'read')
                                            <span class="badge bg-success">Read</span>

                                        @elseif($policy->policy_status == 'unread')
                                            <span class="badge bg-warning text-dark">Unread</span>

                                        @else
                                            <span class="badge bg-secondary">Not Created</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#!" class="avtar avtar-xs btn-link-primary btn-pc-default"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewPolicyModal"
                                            data-policy-id="{{ $policy->id }}"
                                            data-policy-title="{{ $policy->subject }}"
                                            data-policy-content="{{ $policy->content }}"
                                            data-policy-status="{{ $policy->policy_status }}">
                                            <i class="ti ti-eye f-18"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        No policy available.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Policy View Modal -->
        <div class="modal fade" id="viewPolicyModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Policy Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Policy Name</label>
                            <input type="text" id="policyName" class="form-control" readonly>
                        </div>

                        <div id="policyContent" class="border p-4 bg-light rounded"
                            style="max-height: 500px; overflow-y: auto; line-height: 1.7;">
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="termsCheckbox" disabled>
                            <label class="form-check-label fw-semibold" for="termsCheckbox">
                                I have read all terms & conditions
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="acceptBtn" disabled>
                            <i class="ti ti-check me-1"></i>Accept
                        </button>
                        <button type="button" class="btn btn-secondary d-none" id="alreadyAcceptedBtn" disabled>
                            <i class="ti ti-shield-check me-1"></i>Already Accepted
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="ti ti-x me-1"></i>Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById('viewPolicyModal');
        let currentPolicyId = null;

        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const title = button.getAttribute('data-policy-title');
            const content = button.getAttribute('data-policy-content');
            const isAccepted = button.getAttribute('data-policy-accepted') === '1';
            currentPolicyId = button.getAttribute('data-policy-id');

            document.getElementById('policyName').value = title;
            document.getElementById('policyContent').innerHTML = content;

            const checkbox = document.getElementById('termsCheckbox');
            const acceptBtn = document.getElementById('acceptBtn');
            const alreadyAcceptedBtn = document.getElementById('alreadyAcceptedBtn');

            if (isAccepted) {
                checkbox.checked = true;
                checkbox.disabled = true;
                acceptBtn.classList.add('d-none');
                alreadyAcceptedBtn.classList.remove('d-none');
            } else {
                checkbox.disabled = false;
                checkbox.checked = false;
                acceptBtn.classList.remove('d-none');
                alreadyAcceptedBtn.classList.add('d-none');
                acceptBtn.disabled = true;

                checkbox.addEventListener('change', function() {
                    acceptBtn.disabled = !checkbox.checked;
                }, { once: true });
            }
        });

        // ✅ Accept policy via AJAX
        document.getElementById('acceptBtn').addEventListener('click', function() {
            if (!currentPolicyId) return;

            fetch('{{ url("/employee/policy/accept") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ policy_id: currentPolicyId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast(data.message, 'success');
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    modalInstance.hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || 'Something went wrong!', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Error occurred while saving acceptance.', 'error');
            });
        });
    });

    function startEmployeePolicyTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Employer Policies Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-shield" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Access and accept employment policy documents issued by your company.</p></div>'
                },
                {
                    element: '#policies-table-card',
                    title: 'Employment Policies Table',
                    intro: 'View policy titles, publication dates, approvals, and acceptance status (Read or Unread).'
                },
                {
                    element: '.avtar.btn-link-primary',
                    title: 'View & Accept Policy',
                    intro: 'Click the view eye icon on any policy to read the policy document and sign your acceptance of terms & conditions.',
                    skipIfNoElement: true
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
        $('#start-employee-policy-tour').on('click', function(e) {
            e.preventDefault();
            startEmployeePolicyTour();
        });
    });
</script>
@endsection
