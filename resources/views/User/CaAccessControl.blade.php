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
                        <li class="breadcrumb-item"><a href="">Business Setup</a></li>
                        <li class="breadcrumb-item" aria-current="page">Ca Access Control</li>
                    </ul>
                    
                </div>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Ca Access Control</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
        <div class="row">

            <!-- Only show CA / Accountant Access Details -->
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5>CA / Accountant Access Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            @php
                                $caPermissions = json_decode($compDetails->ca_permissions ?? '', true) ?? [];
                            @endphp

                            @foreach($accountant_access as $key => $access)

                                @php
                                    $moduleValue = trim($access->module_value);
                                    $viewChecked = isset($caPermissions[$moduleValue]['view']) && $caPermissions[$moduleValue]['view'];
                                    $editChecked = isset($caPermissions[$moduleValue]['edit']) && $caPermissions[$moduleValue]['edit'];
                                    $mainChecked = ($viewChecked || $editChecked);
                                @endphp

                                <div class="col-md-6 mb-3">
                                    <div class="p-3 border rounded-3 bg-light-subtle">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h6 class="mb-1 text-dark fw-semibold">{{ $access->module_name }}</h6>
                                                <small class="text-muted">
                                                    Allow CA to access this module
                                                </small>
                                            </div>

                                            <div class="form-check form-switch d-flex align-items-center gap-2 mb-0">
                                                <input
                                                    class="form-check-input permission-checkbox m-0"
                                                    type="checkbox"
                                                    role="switch"
                                                    data-module="{{ $moduleValue }}"
                                                    data-type="view"
                                                    data-user="{{ $userId }}"
                                                    id="view-{{ $key }}"
                                                    {!! $viewChecked ? 'checked' : '' !!}>

                                                <label class="form-check-label text-muted mb-0"
                                                    for="view-{{ $key }}">
                                                    View Access
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endforeach

                        </div>

                        <div class="text-end mt-3">
                            <button type="button" id="savePermissionBtn" class="btn btn-primary savePermissionBtn" data-user="{{ $userId }}">Save Permissions</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    <!-- [ Main Content ] end -->
</div>
<script>
    $(function () {


        // delegated handler to support dynamic content
        $(document).on('click', '.savePermissionBtn', function () {

            let $btn = $(this);
            let originalText = $btn.text();
            let userId = $btn.data('user');

            // collect permissions
            let permissions = {};

            $('.permission-checkbox[data-user="' + userId + '"]').each(function () {
                let module = $(this).data('module');
                let type = $(this).data('type');

                if (!permissions[module]) {
                    permissions[module] = {};
                }

                permissions[module][type] = $(this).is(':checked');
            });

            // disable button while saving
            $btn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: "{{ route('save.ca.permissions') }}",
                type: "POST",
                contentType: "application/json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: JSON.stringify({
                    user_id: userId,
                    permissions: permissions
                }),
                success: function (response) {
                    if (response && response.message) {
                        showToast(response.message, "success");
                    } else {
                        showToast('Permissions saved', 'success');
                    }
                },
                error: function (xhr) {
                    let msg = 'Failed to save permissions';
                    try {
                        const json = xhr.responseJSON;
                        if (json && json.message) msg = json.message;
                    } catch (e) {}
                    showToast(msg, 'error');
                },
                complete: function () {
                    $btn.prop('disabled', false).text(originalText);
                }
            });
        });

    });
</script>
@endsection

