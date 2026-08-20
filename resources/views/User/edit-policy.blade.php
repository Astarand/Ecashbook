@extends('App.Layout')

@section('container')
<style>
    /* Premium Document Editor Styling */
    .document-editor {
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        display: flex;
        flex-direction: column;
        background: #f8fafc;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04) !important;
    }
    .document-editor__toolbar {
        z-index: 2;
        background: #ffffff !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 8px 12px !important;
        position: sticky;
        top: 0;
    }
    .document-editor__toolbar .ck-toolbar {
        border: 0 !important;
        background: transparent !important;
    }
    .document-editor__editable-container {
        padding: 30px 20px;
        background: #eef2f6;
        overflow-y: auto;
        max-height: 650px;
        display: flex;
        justify-content: center;
    }
    .document-editor__editable-container .document-editor__editable {
        width: 100%;
        max-width: 850px;
        min-height: 500px;
        padding: 40px 50px;
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        background: #ffffff !important;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06) !important;
        outline: none !important;
        font-size: 15px;
        line-height: 1.7;
        color: #1e293b;
    }
    .document-editor__editable-container .document-editor__editable:focus {
        border-color: var(--bs-primary, #04a9f5) !important;
        box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.18), 0 6px 18px rgba(0, 0, 0, 0.06) !important;
    }
    .document-editor__editable figure.table table {
        width: 100%;
        border-collapse: collapse;
        margin: 1rem 0;
    }
    .document-editor__editable figure.table table th,
    .document-editor__editable figure.table table td {
        border: 1px solid #cbd5e1;
        padding: 8px 12px;
    }
    .document-editor__editable figure.table table th {
        background-color: #f1f5f9;
        font-weight: 600;
    }
    .document-editor__editable blockquote {
        border-left: 4px solid var(--bs-primary, #04a9f5);
        padding: 10px 20px;
        margin: 1.5rem 0;
        background-color: #f8fafc;
        border-radius: 0 8px 8px 0;
        color: #475569;
    }
</style>
<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Payroll Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $policy->id ? 'Edit Policy' : 'Create Policy' }}</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-edit-policy-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">{{ $policy->id ? 'Edit Policy Document' : 'Create Policy Document' }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="ti ti-{{ $policy->id ? 'edit' : 'plus' }} me-2"></i>{{ $policy->id ? 'Update Policy' : 'Create Policy' }}</h5>
                </div>
                <div class="card-body">
                    <form id="updatePolicyForm">
                        <input type="hidden" id="policyId" value="{{ $policy->id }}">

                        <!-- Subject Field -->
                        <div class="mb-4">
                            <label for="policySubject" class="form-label fw-semibold">
                                Policy Subject <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control form-control-lg" 
                                id="policySubject" 
                                name="subject"
                                value="{{ $policy->subject }}"
                                {{ in_array($policy->subject, ['Terms and Conditions', 'Privacy Policy']) ? 'readonly' : '' }}
                                required>
                            <small class="text-muted">
                                @if(in_array($policy->subject, ['Terms and Conditions', 'Privacy Policy']))
                                    This is a required policy subject and cannot be changed.
                                @else
                                    Update the policy subject if needed.
                                @endif
                            </small>
                        </div>

                        <!-- Policy Content -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-3">
                                Policy Content <span class="text-danger">*</span>
                            </label>
                            <div class="document-editor border rounded">
                                <div class="document-editor__toolbar border-bottom"></div>
                                <div class="document-editor__editable-container">
                                    <div class="document-editor__editable">
                                        {!! $policy->content ?? '' !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 🟡 Status Field -->
                        <div class="mb-4">
                            <label for="policyStatus" class="form-label fw-semibold">
                                Policy Status <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg" id="policyStatus" name="status" required>
                                <option value="active" {{ ($policy->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="deactive" {{ ($policy->status ?? '') == 'deactive' ? 'selected' : '' }}>Deactive</option>
                                <option value="delete" {{ ($policy->status ?? '') == 'delete' ? 'selected' : '' }}>Delete</option>
                            </select>
                            <small class="text-muted">Set the current visibility or state of this policy.</small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                <i class="ti ti-arrow-left me-1"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-device-floppy me-1"></i>{{ $policy->id ? 'Update Policy' : 'Create Policy' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

@section('page-script')
<!-- CKEditor Document Editor -->
<script src="{{ asset('assets/js/plugins/ckeditor/document/ckeditor.js') }}"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let editorInstance;

    // Initialize CKEditor
    (function () {
        DecoupledEditor.create(document.querySelector('.document-editor__editable'))
            .then((editor) => {
                editorInstance = editor;
                const toolbarContainer = document.querySelector('.document-editor__toolbar');
                toolbarContainer.appendChild(editor.ui.view.toolbar.element);
            })
            .catch((error) => {
                console.error('CKEditor initialization error:', error);
            });
    })();

    // Update Policy Form Submission
    document.getElementById('updatePolicyForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const policyId = document.getElementById('policyId').value;
        const subject = document.getElementById('policySubject').value.trim();
        const content = editorInstance.getData();
        const status = document.getElementById('policyStatus').value;

        if (!subject || content.trim() === '' || content === '<p>&nbsp;</p>') {
            showToast('Please fill in all required fields!', 'error');
            return;
        }

        // Show loading
        Swal.fire({
            title: policyId ? 'Updating Policy...' : 'Creating Policy...',
            html: policyId ? 'Please wait while we update your policy document.' : 'Please wait while we create your policy document.',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        // 🟢 AJAX Request
        fetch('{{ route("employee.policy.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                subject: subject,
                content: content,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();

            if (data.status === 'success') {
                showToast(data.message, 'success');
                setTimeout(() => window.location.href = '{{ route("user.EmployeePolicyList") }}', 1500);
            } else {
                showToast(data.message || 'Update failed!', 'error');
            }
        })
        .catch(error => {
            Swal.close();
            console.error(error);
            showToast('Something went wrong! Please try again later.', 'error');
        });
    });

    function startEditPolicyTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Edit Policy Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Modify existing company rules, dates, or uploaded attachments.</p></div>'
                },
                {
                    title: 'Edit Policy',
                    intro: 'Modify existing company rules, dates, or uploaded attachments.'
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
        $('#start-edit-policy-tour').on('click', function(e) {
            e.preventDefault();
            startEditPolicyTour();
        });
    });
</script>
@endsection
