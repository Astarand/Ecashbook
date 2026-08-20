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
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">HR & Payroll Management</a></li>
                        <li class="breadcrumb-item"><a href="#">HR, Payroll & Attendance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Generate New Policy</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-add-employee-policy-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Generate New Policy</h2>
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
                    <h5><i class="ti ti-file-text me-2"></i>Policy Document</h5>
                </div>
                <div class="card-body">
                    <form id="policyForm">
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
                                placeholder="Enter policy subject (e.g., Company Leave Policy 2025)"
                                required>
                            <small class="text-muted">Enter a clear and concise subject for the policy</small>
                        </div>

                        <!-- Policy Content - CKEditor Document Editor -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-3">
                                Policy Content <span class="text-danger">*</span>
                            </label>
                            <div class="document-editor border rounded">
                                <div class="document-editor__toolbar border-bottom"></div>
                                <div class="document-editor__editable-container">
                                    <div class="document-editor__editable">
                                        <h2 style="text-align: center">COMPANY POLICY DOCUMENT</h2>
                                        <h3 style="text-align: center">Official Policy Letter</h3>
                                        <p>Dear Employee,</p>
                                        <p>We are pleased to present this <i>Company Policy</i> and hope you will understand the guidelines as well as your responsibilities outlined in this document.</p>
                                        <p>Please find below the full policy details.</p>
                                        <figure class="table ck-widget ck-widget_selectable" contenteditable="false">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th class="ck-editor__editable ck-editor__nested-editable" contenteditable="true" colspan="2">Policy Sections</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="ck-editor__editable ck-editor__nested-editable" contenteditable="true">Section 1</td>
                                                        <td class="ck-editor__editable ck-editor__nested-editable" contenteditable="true">Introduction and <strong>Purpose</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="ck-editor__editable ck-editor__nested-editable" contenteditable="true">Section 2</td>
                                                        <td class="ck-editor__editable ck-editor__nested-editable" contenteditable="true">Scope and <mark class="marker-yellow">Applicability</mark></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="ck-editor__editable ck-editor__nested-editable" contenteditable="true">Section 3</td>
                                                        <td class="ck-editor__editable ck-editor__nested-editable" contenteditable="true">Guidelines and <strong>Compliance</strong></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </figure>
                                        <blockquote>
                                            <p>Company policies are essential for maintaining a professional work environment and ensuring all employees understand their rights and responsibilities. Clear policies help create transparency and accountability within the organization.</p>
                                            <p>HR Department</p>
                                        </blockquote>
                                        <p>Please review this policy carefully and ensure you <mark class="marker-yellow">understand all sections</mark> before acknowledging your acceptance.</p>
                                        <p>We look forward to your cooperation.</p>
                                        <p><strong>HR Manager</strong></p>
                                        <p><strong>Human Resources Department</strong></p>
                                        <p><strong>Company Name</strong></p>
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
                                    <i class="ti ti-check me-1"></i>Generate Policy
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
                        <i class="ti ti-file-text me-2"></i>Policy Preview
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
        const subject = document.getElementById('policySubject').value;
        const content = editorInstance.getData();

        if (!subject || content.trim() === '' || content === '<p>&nbsp;</p>') {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Information',
                text: 'Please fill in both Subject and Content fields!'
            });
            return;
        }

        document.getElementById('previewSubject').textContent = subject;
        document.getElementById('previewContent').innerHTML = content;
        
        const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
        previewModal.show();
    });

    // Confirm Generate from Modal
    document.getElementById('confirmGenerateBtn').addEventListener('click', function() {
        document.getElementById('policyForm').requestSubmit();
    });

    // Form Submission
    document.getElementById('policyForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const subject = document.getElementById('policySubject').value;
        const content = editorInstance.getData();

        if (!subject || content.trim() === '' || content === '<p>&nbsp;</p>') {
            Swal.fire({
                icon: 'error',
                title: 'Missing Information',
                text: 'Please fill in all required fields!'
            });
            return;
        }

        // Show loading
        Swal.fire({
            title: 'Generating Policy...',
            html: 'Please wait while we create your policy document.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Simulate processing
        setTimeout(function() {
            const previewModal = bootstrap.Modal.getInstance(document.getElementById('previewModal'));
            if (previewModal) {
                previewModal.hide();
            }

            Swal.fire({
                icon: 'success',
                title: 'Policy Generated!',
                text: 'Your policy has been created successfully.',
                confirmButtonText: 'OK'
            }).then(() => {
                document.getElementById('policyForm').reset();
                editorInstance.setData('');
            });
        }, 2000);
    });

    function startAddEmployeePolicyTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Add Employee Policy Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Upload and publish new rules, guidelines, or handbooks.</p></div>'
                },
                {
                    title: 'Add Employee Policy',
                    intro: 'Upload and publish new rules, guidelines, or handbooks.'
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
        $('#start-add-employee-policy-tour').on('click', function(e) {
            e.preventDefault();
            startAddEmployeePolicyTour();
        });
    });
</script>
@endsection
