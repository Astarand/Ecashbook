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
                        <li class="breadcrumb-item" aria-current="page">HR Letter Master List</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">HR Letter Master List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ route('user.GenerateHRLetter') }}" class="btn btn-primary">
                        <i class="ti ti-square-plus"></i> Generate New Letter
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
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
                            <tr id="row-{{ $letter->id }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $letter->subject }}</td>
                                <td>
                                    @if ($letter->status === 'active')
                                        <span class="badge bg-success status-badge-{{ $letter->id }}">Active</span>
                                    @elseif ($letter->status === 'deactive')
                                        <span class="badge bg-warning text-dark status-badge-{{ $letter->id }}">Inactive</span>
                                    @else
                                        <span class="badge bg-danger status-badge-{{ $letter->id }}">Deleted</span>
                                    @endif
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">

                                            {{-- View --}}
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a href="#!" class="avtar avtar-xs btn-link-success btn-pc-default viewLetterBtn"
                                                    data-id="{{ $letter->id }}">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>

                                            {{-- Edit --}}
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                <a href="#!" class="avtar avtar-xs btn-link-warning btn-pc-default editLetterBtn"
                                                    data-id="{{ $letter->id }}">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>

                                            {{-- Toggle Active/Deactive --}}
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                title="{{ $letter->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                <a href="#!" class="avtar avtar-xs btn-pc-default toggleStatusBtn {{ $letter->status === 'active' ? 'btn-link-secondary' : 'btn-link-success' }}"
                                                    data-id="{{ $letter->id }}"
                                                    data-status="{{ $letter->status }}">
                                                    <i class="ti {{ $letter->status === 'active' ? 'ti-toggle-right' : 'ti-toggle-left' }} f-18"></i>
                                                </a>
                                            </li>

                                            {{-- Print/PDF --}}
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Download PDF">
                                                <a href="#!" class="avtar avtar-xs btn-link-info btn-pc-default pdfLetterBtn"
                                                    data-id="{{ $letter->id }}">
                                                    <i class="ti ti-file f-18"></i>
                                                </a>
                                            </li>

                                            {{-- Delete --}}
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                <a href="#!" class="avtar avtar-xs btn-link-danger btn-pc-default deleteLetterBtn"
                                                    data-id="{{ $letter->id }}">
                                                    <i class="ti ti-trash f-18"></i>
                                                </a>
                                            </li>

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

{{-- Letter data stored safely in JS — no data-attribute encoding issues --}}
<script>
    const letterData = {
        @foreach($letters as $letter)
        {{ $letter->id }}: {
            subject: {!! json_encode($letter->subject) !!},
            content: {!! json_encode($letter->content) !!}
        },
        @endforeach
    };
</script>

{{-- View Modal --}}
<div class="modal fade" id="viewLetterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="ti ti-file-text me-2"></i>HR Letter Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="background:#fff;">
                <div id="viewContent" style="font-family: Arial, sans-serif; font-size: 14px; line-height: 1.8; color: #333; padding: 10px 20px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editLetterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="ti ti-edit me-2"></i>Edit HR Letter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editLetterId">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editLetterSubject" placeholder="Enter subject">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Content <span class="text-danger">*</span></label>
                    <div class="document-editor border rounded">
                        <div class="document-editor__toolbar border-bottom" id="editToolbar"></div>
                        <div class="document-editor__editable-container">
                            <div class="document-editor__editable" id="editEditable"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning" id="saveEditBtn">
                    <i class="ti ti-check me-1"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

{{-- PDF Print Frame (hidden) --}}
<iframe id="pdfFrame" style="display:none;"></iframe>

@endsection

@section('page-script')
<script src="{{ asset('assets/js/plugins/ckeditor/document/ckeditor.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let editEditorInstance = null;

    document.addEventListener('DOMContentLoaded', function () {

        // ── View ────────────────────────────────────────────────────────────
        document.querySelectorAll('.viewLetterBtn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const id   = this.dataset.id;
                const data = letterData[id];
                document.getElementById('viewContent').innerHTML = data.content;
                new bootstrap.Modal(document.getElementById('viewLetterModal')).show();
            });
        });

        // ── Edit ─────────────────────────────────────────────────────────────
        document.querySelectorAll('.editLetterBtn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const id   = this.dataset.id;
                const data = letterData[id];

                document.getElementById('editLetterId').value      = id;
                document.getElementById('editLetterSubject').value = data.subject;

                if (!editEditorInstance) {
                    DecoupledEditor.create(document.getElementById('editEditable'))
                        .then(editor => {
                            editEditorInstance = editor;
                            document.getElementById('editToolbar')
                                .appendChild(editor.ui.view.toolbar.element);
                            editor.setData(data.content);
                        })
                        .catch(console.error);
                } else {
                    editEditorInstance.setData(data.content);
                }

                new bootstrap.Modal(document.getElementById('editLetterModal')).show();
            });
        });

        // Save edit
        document.getElementById('saveEditBtn').addEventListener('click', function () {
            const id      = document.getElementById('editLetterId').value;
            const subject = document.getElementById('editLetterSubject').value.trim();
            const content = editEditorInstance ? editEditorInstance.getData().trim() : '';

            if (!subject || !content || content === '<p>&nbsp;</p>') {
                showToast('Please fill in both Subject and Content fields.', 'error');
                return;
            }

            fetch(`/admin/hr-letter/update/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ subject, content })
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('editLetterModal')).hide();
                    setTimeout(() => location.reload(), 1200);
                } else {
                    showToast(data.message || 'Update failed.', 'error');
                }
            })
            .catch(() => showToast('An error occurred.', 'error'));
        });

        // ── Toggle Status ────────────────────────────────────────────────────
        document.querySelectorAll('.toggleStatusBtn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const id        = this.dataset.id;
                const curStatus = this.dataset.status;
                const newStatus = curStatus === 'active' ? 'deactive' : 'active';
                const label     = newStatus === 'active' ? 'Activate' : 'Deactivate';

                Swal.fire({
                    title: `${label} this letter?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: `Yes, ${label}!`
                }).then(result => {
                    if (!result.isConfirmed) return;

                    fetch(`/admin/hr-letter/toggle-status/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ status: newStatus })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            showToast(data.message, 'success');
                            const badge = document.querySelector(`.status-badge-${id}`);
                            if (newStatus === 'active') {
                                badge.className = `badge bg-success status-badge-${id}`;
                                badge.innerText = 'Active';
                                this.classList.replace('btn-link-success', 'btn-link-secondary');
                                this.querySelector('i').classList.replace('ti-toggle-left', 'ti-toggle-right');
                                this.closest('[data-bs-toggle="tooltip"]').setAttribute('title', 'Deactivate');
                            } else {
                                badge.className = `badge bg-warning text-dark status-badge-${id}`;
                                badge.innerText = 'Inactive';
                                this.classList.replace('btn-link-secondary', 'btn-link-success');
                                this.querySelector('i').classList.replace('ti-toggle-right', 'ti-toggle-left');
                                this.closest('[data-bs-toggle="tooltip"]').setAttribute('title', 'Activate');
                            }
                            this.dataset.status = newStatus;
                        } else {
                            showToast(data.message || 'Failed to update status.', 'error');
                        }
                    })
                    .catch(() => showToast('An error occurred.', 'error'));
                });
            });
        });

        // ── PDF / Print ──────────────────────────────────────────────────────
        document.querySelectorAll('.pdfLetterBtn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const id   = this.dataset.id;
                const data = letterData[id];

                const html = `<!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="utf-8">
                        <title>${data.subject}</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 40px 50px; font-size: 14px; line-height: 1.8; color: #333; }
                            p { margin: 0 0 8px 0; }
                            strong { font-weight: bold; }
                            u { text-decoration: underline; }
                            table { width: 100%; border-collapse: collapse; }
                            td, th { padding: 6px 10px; border: 1px solid #ccc; }
                        </style>
                    </head>
                    <body>${data.content}</body>
                    </html>`;

                const frame = document.getElementById('pdfFrame');
                frame.onload = function () {
                    frame.contentWindow.focus();
                    frame.contentWindow.print();
                };
                frame.srcdoc = html;
            });
        });

        // ── Delete ───────────────────────────────────────────────────────────
        document.querySelectorAll('.deleteLetterBtn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const id = this.dataset.id;

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This will permanently delete the HR letter template.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then(result => {
                    if (!result.isConfirmed) return;

                    fetch(`/admin/hr-letter/delete/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            showToast(data.message, 'success');
                            document.getElementById(`row-${id}`).remove();
                        } else {
                            showToast(data.message || 'Delete failed.', 'error');
                        }
                    })
                    .catch(() => showToast('An error occurred.', 'error'));
                });
            });
        });

    });
</script>
@endsection
