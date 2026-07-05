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
                        <li class="breadcrumb-item"><a href="#">Payroll Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Policy</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Edit Policy Document</h2>
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
                    <h5><i class="ti ti-edit me-2"></i>Update Policy</h5>
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
                                required>
                            <small class="text-muted">Update the policy subject if needed.</small>
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
                                        {!! $policy->content !!}
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
                                <option value="active" {{ $policy->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="deactive" {{ $policy->status == 'deactive' ? 'selected' : '' }}>Deactive</option>
                                <option value="delete" {{ $policy->status == 'delete' ? 'selected' : '' }}>Delete</option>
                            </select>
                            <small class="text-muted">Set the current visibility or state of this policy.</small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                <i class="ti ti-arrow-left me-1"></i>Cancel
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

    
</script>
@endsection
