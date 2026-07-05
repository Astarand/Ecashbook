@extends('App.Layout')

@section('container')

<div class="pc-content">
  <!-- [ breadcrumb ] start -->
  <div class="page-header">
    <div class="page-block">
      <div class="row align-items-center">
        <div class="col-md-12">
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.subscription.index') }}">Subscription Plans</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Plan</li>
          </ul>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-12">
          <div class="page-header-title">
            <h2 class="mb-0">Edit Subscription Plan</h2>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- [ breadcrumb ] end -->

  <!-- Plan Form -->
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5>Edit Subscription Plan</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.subscription.update', $plan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="form-label">Plan Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="name" placeholder="Enter plan name" value="{{ old('name', $plan->name) }}" required>
                  @error('name')
                  <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="form-label">Plan Type <span class="text-danger">*</span></label>
                  <select class="form-select" name="type" required>
                    <option value="">Select Plan Type</option>
                    <option value="starter" {{ old('type', $plan->type) == 'starter' ? 'selected' : '' }}>Starter</option>
                    <option value="professional" {{ old('type', $plan->type) == 'professional' ? 'selected' : '' }}>Professional</option>
                    <option value="premium" {{ old('type', $plan->type) == 'premium' ? 'selected' : '' }}>Premium</option>
                    <option value="enterprise" {{ old('type', $plan->type) == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                  </select>
                  @error('type')
                  <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="form-label">Monthly Price (₹) <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" name="monthly_price" placeholder="0.00" value="{{ old('monthly_price', $plan->monthly_price) }}" step="0.01" min="0" required>
                  @error('monthly_price')
                  <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="form-label">Yearly Price (₹) <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" name="yearly_price" placeholder="0.00" value="{{ old('yearly_price', $plan->yearly_price) }}" step="0.01" min="0" required>
                  @error('yearly_price')
                  <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label class="form-label">Description</label>
                  <textarea class="form-control" name="description" rows="3" placeholder="Enter plan description">{{ old('description', $plan->description) }}</textarea>
                  @error('description')
                  <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label class="form-label">Features</label>
                  <div class="features-container">
                    @if(count($plan->features) > 0)
                    @foreach($plan->features as $index => $feature)
                    <div class="feature-item mb-2 row align-items-center">
                      <div class="col-md-5">
                        <input type="text" class="form-control" name="features[{{ $index }}][name]" placeholder="Feature name" value="{{ $feature->name }}">
                      </div>
                      <div class="col-md-5">
                        <select class="form-select" name="features[{{ $index }}][is_enabled]">
                          <option value="1" {{ $feature->is_enabled ? 'selected' : '' }}>Enabled</option>
                          <option value="0" {{ !$feature->is_enabled ? 'selected' : '' }}>Disabled</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-feature" {{ count($plan->features) === 1 ? 'disabled' : '' }}>
                          <i class="ti ti-minus"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn-sm add-feature">
                          <i class="ti ti-plus"></i>
                        </button>
                      </div>
                    </div>
                    @endforeach
                    @else
                    <div class="feature-item mb-2 row align-items-center">
                      <div class="col-md-5">
                        <input type="text" class="form-control" name="features[0][name]" placeholder="Feature name">
                      </div>
                      <div class="col-md-5">
                        <select class="form-select" name="features[0][is_enabled]">
                          <option value="1">Enabled</option>
                          <option value="0">Disabled</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-feature" disabled>
                          <i class="ti ti-minus"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn-sm add-feature">
                          <i class="ti ti-plus"></i>
                        </button>
                      </div>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group mb-3">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-12">
                <div class="text-end">
                  <a href="{{ route('admin.subscription.index') }}" class="btn btn-secondary me-2">Cancel</a>
                  <button type="submit" class="btn btn-primary">Update Plan</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const featuresContainer = document.querySelector('.features-container');

    // Add new feature field
    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('add-feature') || e.target.closest('.add-feature')) {
        const featureItems = document.querySelectorAll('.feature-item');
        const featureCount = featureItems.length;

        const newFeature = document.createElement('div');
        newFeature.className = 'feature-item mb-2 row align-items-center';
        newFeature.innerHTML = `
          <div class="col-md-5">
            <input type="text" class="form-control" name="features[${featureCount}][name]" placeholder="Feature name">
          </div>
          <div class="col-md-5">
            <select class="form-select" name="features[${featureCount}][is_enabled]">
              <option value="1">Enabled</option>
              <option value="0">Disabled</option>
            </select>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm remove-feature">
              <i class="ti ti-minus"></i>
            </button>
            <button type="button" class="btn btn-primary btn-sm add-feature">
              <i class="ti ti-plus"></i>
            </button>
          </div>
        `;

        featuresContainer.appendChild(newFeature);
        updateRemoveButtons();
      }
    });

    // Remove feature field
    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('remove-feature') || e.target.closest('.remove-feature')) {
        const button = e.target.closest('.remove-feature');
        if (button.disabled) return;

        const featureItem = button.closest('.feature-item');
        featureItem.remove();
        updateRemoveButtons();
        reindexFeatures();
      }
    });

    // Update remove buttons (disable if only one feature remains)
    function updateRemoveButtons() {
      const featureItems = document.querySelectorAll('.feature-item');
      const removeButtons = document.querySelectorAll('.remove-feature');

      if (featureItems.length === 1) {
        removeButtons[0].disabled = true;
      } else {
        removeButtons.forEach(button => {
          button.disabled = false;
        });
      }
    }

    // Reindex feature fields to maintain sequential indexes
    function reindexFeatures() {
      const featureItems = document.querySelectorAll('.feature-item');

      featureItems.forEach((item, index) => {
        const nameInput = item.querySelector('input[name^="features"]');
        const enabledSelect = item.querySelector('select[name^="features"]');

        nameInput.name = `features[${index}][name]`;
        enabledSelect.name = `features[${index}][is_enabled]`;
      });
    }
  });
</script>
@endsection

@endsection