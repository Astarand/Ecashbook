@extends('App.Layout')
@section('page-style')
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
@endsection
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
                        <li class="breadcrumb-item"><a href="{{ route('user.HRLetterList') }}">HR Letter</a></li>
                        <li class="breadcrumb-item" aria-current="page">Edit HR Letter</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-edit-hr-letter-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Edit HR Letter</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h5 class="mb-0 text-primary fw-bold"><i class="ti ti-file-text me-2"></i>Edit HR Letter Document</h5>
                </div>
                <div class="card-body p-4">
                    <form id="editHRLetterForm">
                        @csrf
                        <!-- Subject Field -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="subject">
                                Subject <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg" id="subject" name="subject"
                                value="{{ $letter->subject }}" placeholder="Enter letter subject" required>
                            <small class="text-muted">Enter a clear and concise subject for the HR Letter</small>
                        </div>

                        <!-- Content Field - CKEditor Document Editor -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">
                                Content <span class="text-danger">*</span>
                            </label>
                            <div class="document-editor border rounded">
                                <div class="document-editor__toolbar border-bottom"></div>
                                <div class="document-editor__editable-container">
                                    <div class="document-editor__editable">
                                        {!! $letter->content !!}
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">Format and customize your HR letter content using the rich document tools above.</small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('user.HRLetterList') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Back to List
                            </a>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary" id="previewBtn">
                                    <i class="ti ti-eye me-1"></i>Preview
                                </button>
                                <button type="submit" class="btn btn-success" id="updateBtn">
                                    <i class="ti ti-device-floppy me-1"></i>Update Letter
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
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white">
                        <i class="ti ti-file-text me-2"></i>HR Letter Preview
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="card border-0 shadow-sm mx-auto" style="max-width: 850px;">
                        <div class="card-body p-5 bg-white">
                            <h3 class="mb-4 text-center fw-bold text-dark" id="previewSubject"></h3>
                            <hr class="my-4">
                            <div id="previewContent" style="line-height: 1.8; font-size: 15px; color: #1e293b;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Close
                    </button>
                    <button type="button" class="btn btn-primary" id="confirmUpdateBtn">
                        <i class="ti ti-check me-1"></i>Confirm & Update
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
        const subject = document.getElementById('subject').value.trim();
        const content = editorInstance ? editorInstance.getData() : '';

        if (!subject || content.trim() === '' || content === '<p>&nbsp;</p>') {
            showToast('Please fill in both Subject and Content fields!', 'error');
            return;
        }

        document.getElementById('previewSubject').textContent = subject;
        document.getElementById('previewContent').innerHTML = content;

        const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
        previewModal.show();
    });

    // Confirm Update from Modal
    document.getElementById('confirmUpdateBtn').addEventListener('click', function() {
        const previewModalEl = document.getElementById('previewModal');
        const modalInstance = bootstrap.Modal.getInstance(previewModalEl);
        if (modalInstance) {
            modalInstance.hide();
        }
        document.getElementById('editHRLetterForm').requestSubmit();
    });

    // Handle form submission
    document.getElementById('editHRLetterForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate form
        const subject = document.getElementById('subject').value.trim();
        const content = editorInstance ? editorInstance.getData().trim() : '';

        if (!subject) {
            showToast('Please enter a subject.', 'error');
            document.getElementById('subject').focus();
            return;
        }

        if (!content || content === '<p>&nbsp;</p>') {
            showToast('Please enter content for the letter.', 'error');
            if (editorInstance) editorInstance.focus();
            return;
        }

        const updateBtn = document.getElementById('updateBtn');
        const originalText = updateBtn.innerHTML;

        // Show loading state
        updateBtn.innerHTML = '<i class="ti ti-loader-2 me-1"></i>Updating...';
        updateBtn.disabled = true;

        const requestData = {
            subject: subject,
            content: content,
            _token: '{{ csrf_token() }}'
        };

        fetch(`/hr-letter/update/{{ $letter->id }}`, {
            method: 'PUT',
            body: JSON.stringify(requestData),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                showToast(data.message, 'success');
                setTimeout(() => {
                    window.location.href = '{{ route("user.HRLetterList") }}';
                }, 1200);
            } else {
                showToast(data.message || 'An error occurred while updating the HR letter.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An unexpected error occurred. Please try again.', 'error');
        })
        .finally(() => {
            updateBtn.innerHTML = originalText;
            updateBtn.disabled = false;
        });
    });

    function startEditHrLetterTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Edit HR Letter Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Update details or templates of drafted HR letters.</p></div>'
                },
                {
                    title: 'Edit HR Letter',
                    intro: 'Update details or templates of drafted HR letters.'
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
        $('#start-edit-hr-letter-tour').on('click', function(e) {
            e.preventDefault();
            startEditHrLetterTour();
        });
    });
</script>
@endsection
