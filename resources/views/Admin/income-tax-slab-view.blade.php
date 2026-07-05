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
                        <li class="breadcrumb-item" aria-current="page">{{ isset($slab) ? 'View' : 'Create' }} Income Tax Slab</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">{{ isset($slab) ? 'View' : 'Create' }} Income Tax Slab</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ isset($slab) ? 'View' : 'Add New' }} Income Tax Slab Master</h5>
                </div>
                <div class="card-body">
                                    <form id="incomeTaxSlabForm" class="needs-validation">
                        @csrf

                        <div class="row">
                            <!-- Entity Type -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Entity Type <span class="text-danger">*</span></label>
                                <select class="form-control" name="entity_type" required>
                                    <option value="">-- Select Entity Type --</option>
                                    <option value="Proprietorship" {{ isset($slab) && $slab->entity_type === 'Proprietorship' ? 'selected' : '' }}>Proprietorship</option>
                                    <option value="LLP" {{ isset($slab) && $slab->entity_type === 'LLP' ? 'selected' : '' }}>LLP</option>
                                    <option value="Pvt Ltd" {{ isset($slab) && $slab->entity_type === 'Pvt Ltd' ? 'selected' : '' }}>Pvt Ltd</option>
                                    <option value="Public Ltd" {{ isset($slab) && $slab->entity_type === 'Public Ltd' ? 'selected' : '' }}>Public Ltd</option>
                                    <option value="HUF" {{ isset($slab) && $slab->entity_type === 'HUF' ? 'selected' : '' }}>HUF</option>
                                    <option value="Partnership" {{ isset($slab) && $slab->entity_type === 'Partnership' ? 'selected' : '' }}>Partnership</option>
                                </select>
                                @error('entity_type') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Company Type -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Type <span class="text-danger">*</span></label>
                                <select class="form-control" name="company_type" required>
                                    <option value="">-- Select Company Type --</option>
                                    <option value="Domestic" {{ isset($slab) && $slab->company_type === 'Domestic' ? 'selected' : '' }}>Domestic</option>
                                    <option value="Foreign" {{ isset($slab) && $slab->company_type === 'Foreign' ? 'selected' : '' }}>Foreign</option>
                                    <option value="OPC" {{ isset($slab) && $slab->company_type === 'OPC' ? 'selected' : '' }}>OPC</option>
                                </select>
                                @error('company_type') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Applicable FY -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Applicable FY <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="applicable_fy" placeholder="e.g., 2024-25" 
                                    value="{{ isset($slab) ? $slab->applicable_fy : '' }}" required>
                                @error('applicable_fy') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Assessment Year -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Assessment Year <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="assessment_year" placeholder="e.g., AY 2024-25" 
                                    value="{{ isset($slab) ? $slab->assessment_year : '' }}" required>
                                @error('assessment_year') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Tax Regime -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tax Regime <span class="text-danger">*</span></label>
                                <select class="form-control" name="tax_regime" required>
                                    <option value="">-- Select Tax Regime --</option>
                                    <option value="Old" {{ isset($slab) && $slab->tax_regime === 'Old' ? 'selected' : '' }}>Old</option>
                                    <option value="New" {{ isset($slab) && $slab->tax_regime === 'New' ? 'selected' : '' }}>New</option>
                                </select>
                                @error('tax_regime') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Taxpayer Category -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Taxpayer Category <span class="text-danger">*</span></label>
                                <select class="form-control" name="taxpayer_category" required>
                                    <option value="">-- Select Taxpayer Category --</option>
                                    <option value="Individual" {{ isset($slab) && $slab->taxpayer_category === 'Individual' ? 'selected' : '' }}>Individual</option>
                                    <option value="Firm" {{ isset($slab) && $slab->taxpayer_category === 'Firm' ? 'selected' : '' }}>Firm</option>
                                    <option value="Company" {{ isset($slab) && $slab->taxpayer_category === 'Company' ? 'selected' : '' }}>Company</option>
                                    <option value="Others" {{ isset($slab) && $slab->taxpayer_category === 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                                @error('taxpayer_category') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <hr class="my-4">

                            <!-- Income Slab From -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Income Slab From (₹) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="income_slab_from" placeholder="Starting income" 
                                    value="{{ isset($slab) ? $slab->income_slab_from : '' }}" required>
                                @error('income_slab_from') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Income Slab To -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Income Slab To (₹) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="income_slab_to" placeholder="Ending income" 
                                    value="{{ isset($slab) ? $slab->income_slab_to : '' }}" required>
                                @error('income_slab_to') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Tax Rate -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tax Rate (%) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="tax_rate" placeholder="e.g., 5" 
                                    value="{{ isset($slab) ? $slab->tax_rate : '' }}" required>
                                @error('tax_rate') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Surcharge Rate -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Surcharge Rate (%)</label>
                                <input type="number" step="0.01" class="form-control" name="surcharge_rate" placeholder="e.g., 0" 
                                    value="{{ isset($slab) ? $slab->surcharge_rate : '' }}">
                                @error('surcharge_rate') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Cess Rate -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Health & Education Cess (%)</label>
                                <input type="number" step="0.01" class="form-control" name="cess_rate" placeholder="e.g., 4" 
                                    value="{{ isset($slab) ? $slab->cess_rate : '' }}">
                                @error('cess_rate') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <hr class="my-4">

                            <!-- Checkboxes -->
                            <div class="col-12 mb-3">
                                <h6 class="mb-3">Additional Tax Applicability</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="marginal_relief_applicable" id="marginal_relief" value="1"
                                                {{ isset($slab) && $slab->marginal_relief_applicable ? 'checked' : '' }}>
                                            <label class="form-check-label" for="marginal_relief">
                                                Marginal Relief Applicable
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="mat_applicable" id="mat_applicable" value="1"
                                                {{ isset($slab) && $slab->mat_applicable ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mat_applicable">
                                                MAT Applicable
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="amt_applicable" id="amt_applicable" value="1"
                                                {{ isset($slab) && $slab->amt_applicable ? 'checked' : '' }}>
                                            <label class="form-check-label" for="amt_applicable">
                                                AMT Applicable
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="rebate_applicable" id="rebate_applicable" value="1"
                                                {{ isset($slab) && $slab->rebate_applicable ? 'checked' : '' }} onchange="toggleRebateFields()">
                                            <label class="form-check-label" for="rebate_applicable">
                                                Rebate Applicable
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rebate Section & Limit -->
                            <div id="rebate-section" style="display: {{ isset($slab) && $slab->rebate_applicable ? 'block' : 'none' }}">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Rebate Section</label>
                                    <input type="text" class="form-control" name="rebate_section" placeholder="e.g., 87A" 
                                        value="{{ isset($slab) ? $slab->rebate_section : '' }}">
                                    @error('rebate_section') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Rebate Limit (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="rebate_limit" placeholder="Max rebate" 
                                        value="{{ isset($slab) ? $slab->rebate_limit : '' }}">
                                    @error('rebate_limit') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="col-12 mb-3">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="3" placeholder="Enter any notes...">{{ isset($slab) ? $slab->notes : '' }}</textarea>
                                @error('notes') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1"
                                        {{ isset($slab) && $slab->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">
                                        Active
                                    </label>
                                </div>
                                @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-12">
                                <a href="{{ url('/income-tax-slab-list') }}" class="btn btn-secondary ms-2">
                                    <i class="ti ti-arrow-left"></i> Back to List
                                </a>

                                <a href="{{ url('/income-tax-slab-edit/'.$slab->id) }}"
                                    class="btn btn-primary">
                                    Back to Edit
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

<script>
const slabId = "{{ isset($slab) ? $slab->id : '' }}";
const isEdit = slabId ? true : false;

function toggleRebateFields() {
    const checkbox = document.getElementById('rebate_applicable');
    const rebateSection = document.getElementById('rebate-section');
    rebateSection.style.display = checkbox.checked ? 'block' : 'none';
}

// Toast notification function
function showToast(message, type = 'info') {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
        document.body.appendChild(toastContainer);
    }

    // Create toast element
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

    // Auto remove after 5 seconds
    setTimeout(() => {
        const element = document.getElementById(toastId);
        if (element) {
            element.remove();
        }
    }, 5000);
}

// Form submission with AJAX
document.getElementById('submitBtn').addEventListener('click', function() {
    const form = document.getElementById('incomeTaxSlabForm');
    
    // Validate required fields
    if (!validateForm()) {
        return;
    }

    // Show loading state
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="ti ti-loader animate-spin me-2"></i> Saving...';

    // Prepare form data
    const formData = new FormData(form);
    
    // Remove empty values
    for (let [key, value] of formData.entries()) {
        if (value === '' && key !== 'notes') {
            formData.delete(key);
        }
    }

    // Determine URL based on create or update
    const url = isEdit 
        ? `/income-tax-slab-update/${slabId}`
        : '/income-tax-slab-store';

    // Get CSRF token
    const csrfToken = document.querySelector('input[name="_token"]').value;

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = isEdit 
            ? '<i class="ti ti-device-floppy"></i> Update Income Tax Slab'
            : '<i class="ti ti-device-floppy"></i> Create Income Tax Slab';

        if (data.success) {
            showToast(data.message || 'Income Tax Slab saved successfully!', 'success');
            
            // Redirect after 2 seconds
            setTimeout(() => {
                window.location.href = '/income-tax-slab-list';
            }, 2000);
        } else {
            showToast(data.message || 'Error saving Income Tax Slab', 'error');
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = isEdit 
            ? '<i class="ti ti-device-floppy"></i> Update Income Tax Slab'
            : '<i class="ti ti-device-floppy"></i> Create Income Tax Slab';
        
        console.error('Error:', error);
        showToast('An error occurred while saving. Please try again.', 'error');
    });
});

// Client-side validation
function validateForm() {
    const requiredFields = {
        entity_type: 'Entity Type',
        company_type: 'Company Type',
        applicable_fy: 'Applicable FY',
        assessment_year: 'Assessment Year',
        tax_regime: 'Tax Regime',
        taxpayer_category: 'Taxpayer Category',
        income_slab_from: 'Income Slab From',
        income_slab_to: 'Income Slab To',
        tax_rate: 'Tax Rate'
    };

    for (let [field, label] of Object.entries(requiredFields)) {
        const element = document.querySelector(`[name="${field}"]`);
        if (!element || !element.value) {
            showToast(`${label} is required`, 'error');
            element?.focus();
            return false;
        }
    }

    // Validate numeric fields
    const incomeSo = parseFloat(document.querySelector('[name="income_slab_from"]').value);
    const incomeEnd = parseFloat(document.querySelector('[name="income_slab_to"]').value);
    
    if (incomeSo >= incomeEnd) {
        showToast('Income Slab To must be greater than Income Slab From', 'error');
        return false;
    }

    return true;
}

// Prevent form submission on Enter
document.getElementById('incomeTaxSlabForm').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
    }
});
</script>

@endsection
