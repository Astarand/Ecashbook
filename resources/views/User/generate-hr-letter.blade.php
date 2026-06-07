@extends('App.Layout')

@section('container')
<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">HR & Payroll Management</a></li>
                        <li class="breadcrumb-item"><a href="#">HR, Payroll & Attendance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Generate New HR Letter</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Generate New HR Letter</h2>
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
                <div class="card-body">
                    <form id="hr_letter">
                        <!-- Subject Field -->
                        <div class="mb-4">
                            <label for="hr_letterSubject" class="form-label fw-semibold">
                                Hr Letter Subject <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control form-control-lg"
                                id="hr_letterSubject"
                                name="subject"
                                placeholder="Enter Hr Letter subject"
                                required>
                            <small class="text-muted">Enter a clear and concise subject for the Hr Letter</small>
                        </div>

                        <!-- hr_letter Content - CKEditor Document Editor -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-3">
                                Hr Letter Content <span class="text-danger">*</span>
                            </label>
                            <div class="document-editor border rounded">
                                <div class="document-editor__toolbar border-bottom"></div>
                                <div class="document-editor__editable-container">
                                    <div class="document-editor__editable">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                <i class="ti ti-arrow-left me-1"></i>Cancel
                            </button>
                            <div>
                                <button type="button" class="btn btn-outline-primary me-2" id="previewBtn">
                                    <i class="ti ti-eye me-1"></i>Preview
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="ti ti-check me-1"></i>Generate HR Letter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="ti ti-file-text me-2"></i>Hr Letter Preview
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="card mb-3 border-0">
                        <div class="card-body" style="background: white; padding: 40px;">
                            <h3 class="mb-4 text-center fw-bold" id="previewSubject"></h3>
                            <hr>
                            <div id="previewContent" style="line-height: 1.8; font-size: 15px;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Close
                    </button>
                    <button type="button" class="btn btn-primary" id="confirmGenerateBtn">
                        <i class="ti ti-check me-1"></i>Confirm & Generate
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-script')
<!-- CKEditor Document Editor JS -->
<script src="{{ asset('assets/js/plugins/ckeditor/document/ckeditor.js') }}"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let editorInstance;

    // Initialize CKEditor Document Editor (Decoupled Editor)
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

    // Preview Button
    document.getElementById('previewBtn').addEventListener('click', function() {
        const subject = document.getElementById('hr_letterSubject').value;
        const content = editorInstance.getData();

        if (!subject || content.trim() === '' || content === '<p>&nbsp;</p>') {
            showToast('Please fill in both Subject and Content fields!', 'error');
            return;
        }

        document.getElementById('previewSubject').textContent = subject;
        document.getElementById('previewContent').innerHTML = content;

        const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
        previewModal.show();
    });

    // Confirm Generate from Modal
    document.getElementById('confirmGenerateBtn').addEventListener('click', function() {
        document.getElementById('hr_letter').requestSubmit();
    });


    // Handle hr_letter Form Submission
    document.getElementById('hr_letter').addEventListener('submit', function(e) {
        e.preventDefault();

        const subject = document.getElementById('hr_letterSubject').value.trim();
        const content = editorInstance.getData().trim();

        if (!subject || content === '' || content === '<p>&nbsp;</p>') {
            showToast('Please fill in both Subject and Content fields!', 'error');
            return;
        }

        fetch('{{ route("hr_letter.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ subject, content })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showToast(data.message, 'success');
                setTimeout(() => window.location.href = '{{ route("user.HRLetterList") }}', 1500);
            } else {
                showToast(data.message || 'Something went wrong!', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('An error occurred while saving HR Letter.', 'error');
        });
    });

</script>
@endsection
