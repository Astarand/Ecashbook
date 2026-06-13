<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
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
                        <li class="breadcrumb-item"><a href="{{ url('/hr-letter-list') }}">HR Letter</a></li>
                        <li class="breadcrumb-item" aria-current="page">HR Letter List</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-hr-letter-list-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">HR Letter List</h2>
                    </div>
                </div>
                
                {{--  Only show "Generate New Letter" button to company users (u_type == '3') --}}
                @if(auth()->user()->u_type == '3')
                    <div class="col-md-8 text-end">
                        <a href="{{ route('user.GenerateHRLetter') }}" class="btn btn-primary">
                            <i class="ti ti-square-plus"></i> Generate New Letter
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($letters as $index => $letter)
                            <?php $hr_content = $letter->content; ?>
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    {{ $letter->subject }}
                                    @if ($letter->is_company_letter)
                                    <span class="badge bg-info ms-2" title="Customized version">Custom</span>
                                    @else
                                    <span class="badge bg-secondary ms-2" title="Master template">Template</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($letter->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                    @elseif ($letter->status === 'deactive')
                                    <span class="badge bg-warning text-dark">Inactive</span>
                                    @else
                                    <span class="badge bg-danger">Deleted</span>
                                    @endif
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">

                                            <!-- View Button -->
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                title="View">
                                                <a href="#!"
                                                    class="avtar avtar-xs btn-link-success btn-pc-default viewLetterBtn"
                                                    data-subject="{{ $letter->subject }}"
                                                    data-content="{{ $letter->content }}">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>

                                            <!-- Edit Button -->
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                title="Edit">
                                                <a href="{{ route('hr_letter.edit', $letter->id) }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>

                                            <!-- Delete Button - Only for customized letters -->
                                            @if ($letter->is_company_letter)
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                title="Delete">
                                                <a href="#!"
                                                    class="avtar avtar-xs btn-link-danger btn-pc-default deleteLetterBtn"
                                                    data-id="{{ $letter->id }}">
                                                    <i class="ti ti-trash f-18"></i>
                                                </a>
                                            </li>

                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Send to Employee">
                                                <a href="#!" 
                                                    class="avtar avtar-xs btn-link-info btn-pc-default sendLetterBtn"
                                                    data-id="{{ $letter->id }}"
                                                    data-subject="{{ $letter->subject }}"
                                                    {{-- data-content="{{ htmlspecialchars($letter->content) }}"> --}}
                                                    data-content="{{ $letter->content }}">
                                                    <i class="ti ti-send f-18"></i>
                                                </a>
                                            </li>

                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No HR letters found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

<div class="modal fade" id="viewLetterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="ti ti-file-text me-2"></i>HR Letter Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h3 id="modalLetterSubject" class="mb-4 fw-bold text-center"></h3>
                <div id="modalLetterContent" style="line-height: 1.7; font-size: 15px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Send HR to employee --}}
<div class="modal fade" id="sendLetterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="ti ti-send me-2"></i>Send HR Letter to Employee</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="sendLetterId">
                <h5 id="sendLetterSubject" class="fw-bold mb-3 text-center"></h5>
                <p id="sendLetterContent" class="mb-4" style="line-height: 1.6;"></p>

                <div class="mb-3">
                    <label class="form-label fw-bold">Select Employees</label>
                    {{-- <select id="employeeSelect" class="form-select" multiple style="width: 100%;"> --}}
                    <select id="employeeSelect" class="form-select">
                        <option value="">Loading employees...</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="sendLetterSubmitBtn">
                    <i class="ti ti-send me-1"></i>Send Letter
                </button>
            </div>
        </div>
    </div>
</div>



@endsection

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // View Letter Modal
        document.querySelectorAll('.viewLetterBtn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const subject = this.getAttribute('data-subject');
                const content = this.getAttribute('data-content');

                document.getElementById('modalLetterSubject').innerText = subject;
                document.getElementById('modalLetterContent').innerHTML = content;

                const viewModal = new bootstrap.Modal(document.getElementById('viewLetterModal'));
                viewModal.show();
            });
        });

        // Delete Letter
        document.querySelectorAll('.deleteLetterBtn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const letterId = this.getAttribute('data-id');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to delete this HR letter? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/hr-letter/delete/${letterId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showToast('HR letter deleted successfully.', 'success');
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            } else {
                                showToast('This is a master template and cannot be deleted. You can only delete your customized versions.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('An error occurred while deleting the HR letter.', 'error');
                        });
                    }
                });
            });
        });

        // Send Letter Modal
        document.querySelectorAll('.sendLetterBtn').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                const letterId = this.getAttribute('data-id');
                const subject = this.getAttribute('data-subject');
                const content = this.getAttribute('data-content');

                document.getElementById('sendLetterId').value = letterId;
                document.getElementById('sendLetterSubject').innerText = subject;
                document.getElementById('sendLetterContent').innerHTML = content;

                const employeeSelect = document.getElementById('employeeSelect');
                employeeSelect.innerHTML = '<option>Loading...</option>';

                // Fetch employees for dropdown
                const response = await fetch(`/api/get-employees`);
                const data = await response.json();

                employeeSelect.innerHTML = '';
                data.forEach(emp => {
                    const opt = document.createElement('option');
                    opt.value = emp.id;
                    opt.textContent = emp.name;
                    employeeSelect.appendChild(opt);
                });

                new bootstrap.Modal(document.getElementById('sendLetterModal')).show();
            });
        });

        // Send letter submission
        document.getElementById('sendLetterSubmitBtn').addEventListener('click', async function() {
            const letterId = document.getElementById('sendLetterId').value;
            const employeeIds = Array.from(document.getElementById('employeeSelect').selectedOptions).map(o => o.value);

            if (employeeIds.length === 0) {
                showToast('Please select at least one employee.', 'warning');
                return;
            }

            const res = await fetch('{{ route("hr_letter.send") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ letter_id: letterId, employee_ids: employeeIds })
            });

            const data = await res.json();
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || 'Failed to send letter.', 'error');
            }
        });
    });


    function startHrLetterListTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'HR Documents Locker Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Track generated offer letters, certificates, and salary increments.</p></div>'
                },
                {
                    title: 'HR Documents Locker',
                    intro: 'Track generated offer letters, certificates, and salary increments.'
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
        $('#start-hr-letter-list-tour').on('click', function(e) {
            e.preventDefault();
            startHrLetterListTour();
        });
    });
</script>
@endsection