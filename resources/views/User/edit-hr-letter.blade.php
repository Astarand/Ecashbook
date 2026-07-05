@extends('App.Layout')
@section('page-style')
<style>
    .ck-editor__editable {
        min-height: 300px;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
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
            <div class="card">
                <div class="card-header">
                    <h5>Edit HR Letter</h5>
                </div>
                <div class="card-body">
                    <form id="editHRLetterForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="subject">Subject <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="subject" name="subject"
                                        value="{{ $letter->subject }}" placeholder="Enter letter subject" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="content">Content <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="content" name="content" rows="15"
                                        placeholder="Enter letter content" required>{{ $letter->content }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('user.HRLetterList') }}" class="btn btn-secondary">
                                        <i class="ti ti-arrow-left me-1"></i>Back to List
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="updateBtn">
                                        <i class="ti ti-device-floppy me-1"></i>Update Letter
                                    </button>
                                </div>
                            </div>
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
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    let editor;

    // Initialize CKEditor
    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', '|',
                'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'blockQuote', 'insertTable', '|',
                'undo', 'redo'
            ]
        })
        .then(newEditor => {
            editor = newEditor;
        })
        .catch(error => {
            console.error(error);
        });

    // Handle form submission
    document.getElementById('editHRLetterForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate form
        const subject = document.getElementById('subject').value.trim();
        const content = editor.getData().trim();

        if (!subject) {
            showToast('Please enter a subject.', 'error');
            document.getElementById('subject').focus();
            return;
        }

        if (!content) {
            showToast('Please enter content for the letter.', 'error');
            editor.focus();
            return;
        }

        const updateBtn = document.getElementById('updateBtn');
        const originalText = updateBtn.innerHTML;

        // Show loading state
        updateBtn.innerHTML = '<i class="ti ti-loader-2 me-1"></i>Updating...';
        updateBtn.disabled = true;

        const requestData = {
            subject: document.getElementById('subject').value,
            content: editor.getData(),
            _token: '{{ csrf_token() }}'
        };

        console.log('Updating HR Letter ID:', '{{ $letter->id }}');
        console.log('Request Data:', requestData);

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
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                // Show success message
                showToast(data.message, 'success');
                setTimeout(() => {
                    window.location.href = '{{ route("user.HRLetterList") }}';
                }, 1500);
            } else {
                // Show error message
                showToast(data.message || 'An error occurred while updating the HR letter.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An unexpected error occurred. Please try again.', 'error');
        })
        .finally(() => {
            // Reset button state
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
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Update details or templates of drafted HR letters.</p></div>'
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
