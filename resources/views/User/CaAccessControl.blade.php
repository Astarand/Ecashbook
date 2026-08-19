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
                            <li class="breadcrumb-item"><a href="{{ url('/assign_ca') }}">Business Setup</a></li>
                            <li class="breadcrumb-item active" aria-current="page">CA Access Control</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">CA / Accountant Access Control</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- Info Alert Box -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm rounded-4 mb-0 p-3 p-md-4" style="background: linear-gradient(135deg, #e0f2fe 0%, #f0fdfa 100%); border-left: 5px solid #008CAD !important;">
                <div class="d-flex align-items-start gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 42px; height: 42px; background: rgba(0, 140, 173, 0.15); color: #008CAD;">
                        <i class="ti ti-shield-lock fs-3"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1" style="color: #0369a1;">Secure & Governed Review-Only Access</h6>
                        <p class="mb-1 text-secondary" style="font-size: 0.88rem; line-height: 1.5;">
                            Your CA / Accountant will have <strong class="text-primary" style="color: #008CAD !important;">Review-Only</strong> access to the specific modules you authorize below, with <strong class="text-danger">no Edit rights</strong>, ensuring compliance accuracy and financial integrity.
                        </p>
                        <p class="mb-0 fw-semibold text-dark" style="font-size: 0.84rem;">
                            <i class="ti ti-circle-check text-success me-1"></i> Enter Once. MethotX Reuses, Validates & Connects Your Data Across Your Business.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <!-- Card Header with Bulk Actions -->
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="ti ti-lock-access text-primary fs-4 me-2"></i> Module Authorization
                        </h5>
                        <span class="badge bg-light-primary text-primary px-3 py-1 rounded-pill fw-semibold" id="activeCountBadge" style="font-size: 0.78rem;">
                            <span id="activeCount">0</span> of {{ count($accountant_access) }} Modules Active
                        </span>
                    </div>

                    <!-- Quick Bulk Actions -->
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold" id="grantAllBtn">
                            <i class="ti ti-check-all me-1"></i> Allow All
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold" id="revokeAllBtn">
                            <i class="ti ti-x me-1"></i> Revoke All
                        </button>
                    </div>
                </div>

                <div class="card-body p-4">
                    @php
                        $caPermissions = json_decode($compDetails->ca_permissions ?? '', true) ?? [];

                        $iconMap = [
                            'sales' => 'ti-file-invoice',
                            'invoice' => 'ti-file-invoice',
                            'purchase' => 'ti-shopping-cart',
                            'expense' => 'ti-receipt',
                            'banking' => 'ti-building-bank',
                            'bank' => 'ti-building-bank',
                            'gst' => 'ti-receipt-tax',
                            'tax' => 'ti-receipt-tax',
                            'report' => 'ti-chart-pie',
                            'reports' => 'ti-chart-pie',
                            'inventory' => 'ti-packages',
                            'stock' => 'ti-packages',
                            'payroll' => 'ti-users',
                            'employee' => 'ti-users',
                            'vouchers' => 'ti-cash',
                            'vouch' => 'ti-cash',
                            'ledger' => 'ti-book',
                            'compliance' => 'ti-certificate',
                            'audit' => 'ti-clipboard-check',
                        ];
                    @endphp

                    <div class="row g-3">
                        @foreach($accountant_access as $key => $access)
                            @php
                                $moduleValue = trim($access->module_value);
                                $viewChecked = isset($caPermissions[$moduleValue]['view']) && $caPermissions[$moduleValue]['view'];
                                $editChecked = isset($caPermissions[$moduleValue]['edit']) && $caPermissions[$moduleValue]['edit'];
                                $isGranted = ($viewChecked || $editChecked);

                                $modKey = strtolower($moduleValue);
                                $iconClass = 'ti-shield-lock';
                                foreach($iconMap as $k => $v) {
                                    if(str_contains($modKey, $k)) {
                                        $iconClass = $v;
                                        break;
                                    }
                                }
                            @endphp

                            <div class="col-md-6 col-xl-4">
                                <div class="ca-access-card p-3 h-100 d-flex flex-column justify-content-between {{ $isGranted ? 'access-active' : '' }}" id="card-{{ $key }}">
                                    <div class="d-flex align-items-start justify-content-between gap-3 mb-2">
                                        <div class="d-flex align-items-center gap-3 min-w-0">
                                            <div class="module-icon-box flex-shrink-0">
                                                <i class="ti {{ $iconClass }}"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <h6 class="mb-0 fw-bold text-dark text-truncate" title="{{ $access->module_name }}">
                                                    {{ $access->module_name }}
                                                </h6>
                                                <small class="text-muted text-truncate d-block" style="font-size: 0.76rem;">
                                                    Review & audit permissions
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Switch Button -->
                                        <div class="form-check form-switch m-0 flex-shrink-0">
                                            <input
                                                class="form-check-input permission-checkbox"
                                                type="checkbox"
                                                role="switch"
                                                data-module="{{ $moduleValue }}"
                                                data-type="view"
                                                data-user="{{ $userId }}"
                                                data-card="card-{{ $key }}"
                                                id="view-{{ $key }}"
                                                {!! $viewChecked ? 'checked' : '' !!}>
                                        </div>
                                    </div>

                                    <!-- Bottom Status Pill -->
                                    <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-2">
                                        <span class="text-muted small" style="font-size: 0.74rem;">Access Level</span>
                                        <span class="badge rounded-pill fw-semibold status-pill" style="font-size: 0.72rem;">
                                            @if($isGranted)
                                                <span class="text-success"><i class="ti ti-check me-1"></i>View Only</span>
                                            @else
                                                <span class="text-muted"><i class="ti ti-lock me-1"></i>Restricted</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Bottom Actions -->
                    <div class="d-flex align-items-center justify-content-between mt-4 pt-3 border-top">
                        <div class="text-muted small">
                            <i class="ti ti-info-circle me-1"></i> Changes will take effect immediately after saving.
                        </div>
                        <button type="button" id="savePermissionBtn" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm savePermissionBtn d-inline-flex align-items-center fw-semibold" data-user="{{ $userId }}" style="background-color: #008CAD; border-color: #008CAD;">
                            <i class="ti ti-device-floppy me-1 fs-5"></i> Save Permissions
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

@endsection

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        const activeCountSpan = document.getElementById('activeCount');

        function updateCounterAndCard(cb) {
            const cardId = cb.getAttribute('data-card');
            const card = document.getElementById(cardId);
            if (card) {
                const statusPill = card.querySelector('.status-pill');
                if (cb.checked) {
                    card.classList.add('access-active');
                    if (statusPill) {
                        statusPill.innerHTML = '<span class="text-success"><i class="ti ti-check me-1"></i>View Only</span>';
                    }
                } else {
                    card.classList.remove('access-active');
                    if (statusPill) {
                        statusPill.innerHTML = '<span class="text-muted"><i class="ti ti-lock me-1"></i>Restricted</span>';
                    }
                }
            }

            // Update total active count
            const activeCount = Array.from(checkboxes).filter(c => c.checked).length;
            if (activeCountSpan) {
                activeCountSpan.textContent = activeCount;
            }
        }

        // Initialize counts and visual states
        checkboxes.forEach(cb => {
            updateCounterAndCard(cb);
            cb.addEventListener('change', function () {
                updateCounterAndCard(this);
            });
        });

        // Grant All
        const grantAllBtn = document.getElementById('grantAllBtn');
        if (grantAllBtn) {
            grantAllBtn.addEventListener('click', function () {
                checkboxes.forEach(cb => {
                    cb.checked = true;
                    updateCounterAndCard(cb);
                });
            });
        }

        // Revoke All
        const revokeAllBtn = document.getElementById('revokeAllBtn');
        if (revokeAllBtn) {
            revokeAllBtn.addEventListener('click', function () {
                checkboxes.forEach(cb => {
                    cb.checked = false;
                    updateCounterAndCard(cb);
                });
            });
        }

        // Save Permissions Handler
        $(document).on('click', '.savePermissionBtn', function () {
            let $btn = $(this);
            let originalHtml = $btn.html();
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
            $btn.prop('disabled', true).html('<i class="ti ti-loader-2 spin me-1"></i> Saving...');

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
                        showToast('Permissions saved successfully', 'success');
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
                    $btn.prop('disabled', false).html(originalHtml);
                }
            });
        });
    });
</script>
