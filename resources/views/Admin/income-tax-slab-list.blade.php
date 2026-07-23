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
                        <li class="breadcrumb-item"><a href="{{ url('/income-tax-slab-list') }}">Income Tax Slab</a></li>
                        <li class="breadcrumb-item" aria-current="page">Income Tax Slab Master</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Income Tax Slab Master</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ route('admin.create-income-tax-slab') }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Slab</a>
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
                                <th class="text-end">#</th>
                                <th>Entity Type</th>
                                <th>Tax Regime</th>
                                <th>Taxpayer Category</th>
                                <th>FY / AY</th>
                                <th>Income Range</th>
                                <th>Tax Rate %</th>
                                <th>Status</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($slabs as $key => $slab)
                                <tr>
                                    <td class="text-end">{{ $key + 1 }}</td>
                                    <td>
                                        <span class="badge bg-light-primary">{{ $slab->entity_type }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $slab->tax_regime === 'New' ? 'bg-success' : 'bg-info' }}">
                                            {{ $slab->tax_regime }}
                                        </span>
                                    </td>
                                    <td>{{ $slab->taxpayer_category }}</td>
                                    <td>
                                        <small>{{ $slab->applicable_fy }} / {{ $slab->assessment_year }}</small>
                                    </td>
									<td>
										₹{{ number_format($slab->income_slab_from, 0) }}
										-
										{{ is_null($slab->income_slab_to) 
											? 'Unlimited' 
											: '₹' . number_format($slab->income_slab_to, 0) 
										}}
									</td>
                                    <td>
                                        <strong>{{ $slab->tax_rate }}%</strong>
                                        @if($slab->surcharge_rate > 0 || $slab->cess_rate > 0)
                                            <br><small class="text-muted">
                                                @if($slab->surcharge_rate > 0) S: {{ $slab->surcharge_rate }}% @endif
                                                @if($slab->cess_rate > 0) C: {{ $slab->cess_rate }}% @endif
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status-toggle" type="checkbox" 
                                                id="status_{{ $slab->id }}" 
                                                {{ $slab->status ? 'checked' : '' }}
                                                data-id="{{ $slab->id }}"
                                                onchange="updateStatus(this)">
                                            <label class="form-check-label" for="status_{{ $slab->id }}">
                                                @if($slab->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.view-income-tax-slab', $slab->id) }}" class="btn btn-sm btn-info" title="View">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.edit-income-tax-slab', $slab->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="ti ti-pencil"></i>
                                        </a>

                                        <a href="javascript:void(0);" data-id="{{ $slab->id }}" class="btn btn-sm btn-danger deleteSlab" data-bs-toggle="modal" data-bs-target="#delete_modal" title="Delete">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <p class="text-muted">No Income Tax Slabs found.</p>
                                    </td>
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

<!-- Delete Confirmation Modal -->
<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Income Tax Slab</h3>
                    <p>Are you sure you want to delete this slab?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="del_slab" class="w-100 btn btn-primary">
                                Delete
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-secondary">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let slabIdToDelete = null;

// Toast notification function
function showToast(message, type = 'info') {
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
        document.body.appendChild(toastContainer);
    }

    const toastId = 'toast_' + Date.now();
    const toastElement = document.createElement('div');
    
    let bgColor = 'bg-info';
    let icon = 'ti-info-circle';
    
    if (type === 'success') {
        bgColor = 'bg-success';
        icon = 'ti-check';
    } else if (type === 'error') {
        bgColor = 'bg-danger';
        icon = 'ti-alert-triangle';
    } else if (type === 'warning') {
        bgColor = 'bg-warning';
        icon = 'ti-alert-circle';
    }

    toastElement.id = toastId;
    toastElement.className = `alert ${bgColor} alert-dismissible fade show d-flex align-items-center`;
    toastElement.style.cssText = 'min-width: 300px; margin-bottom: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);';
    toastElement.innerHTML = `
        <i class="ti ${icon} me-2"></i>
        <span>${message}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    toastContainer.appendChild(toastElement);

    setTimeout(() => {
        const element = document.getElementById(toastId);
        if (element) {
            element.remove();
        }
    }, 5000);
}

// Handle delete button click
document.querySelectorAll('.deleteSlab').forEach(btn => {
    btn.addEventListener('click', function() {
        slabIdToDelete = this.getAttribute('data-id');
    });
});

// Confirm delete with AJAX
document.getElementById('del_slab').addEventListener('click', function() {
    if (slabIdToDelete) {
        // Show loading state
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="ti ti-loader animate-spin me-2"></i> Deleting...';

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/income-tax-slab-delete/' + slabIdToDelete, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = 'Delete';

            if (data.success) {
                showToast(data.message || 'Income Tax Slab deleted successfully!', 'success');
                
                // Reload page after 2 seconds
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showToast(data.message || 'Error deleting Income Tax Slab', 'error');
            }

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('delete_modal'));
            modal.hide();
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerHTML = 'Delete';
            console.error('Error:', error);
            showToast('An error occurred while deleting. Please try again.', 'error');
        });
    }
});

// Update status with AJAX
function updateStatus(checkbox) {
    const slabId = checkbox.getAttribute('data-id');
    const status = checkbox.checked ? 1 : 0;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/income-tax-slab-update-status/' + slabId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Status updated successfully!', 'success');
            
            // Update badge
            const badge = checkbox.nextElementSibling.querySelector('.badge');
            if (status) {
                badge.classList.remove('bg-danger');
                badge.classList.add('bg-success');
                badge.textContent = 'Active';
            } else {
                badge.classList.remove('bg-success');
                badge.classList.add('bg-danger');
                badge.textContent = 'Inactive';
            }
        } else {
            showToast(data.message || 'Error updating status', 'error');
            checkbox.checked = !checkbox.checked;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating status', 'error');
        checkbox.checked = !checkbox.checked;
    });
}
</script>

@endsection
