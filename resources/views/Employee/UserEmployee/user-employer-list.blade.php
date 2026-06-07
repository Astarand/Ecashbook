@extends('App.Layout')

@section('container')
<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Employer Policy</li>
                    </ul>
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
            <div class="card card-body table-card">
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
</script>
@endsection
