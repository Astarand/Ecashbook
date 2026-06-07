@extends('App.Layout')

@section('container')

<div class="pc-content">
  <!-- [ breadcrumb ] start -->
  <div class="page-header">
    <div class="page-block">
      <div class="row align-items-center">
        <div class="col-md-12">
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.subscription-list') }}">Subscription Plans</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create New Plan</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- [ breadcrumb ] end -->

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5>Create New Subscription Plan</h5>
        </div>
        <div class="card-body">
          <form action="javascript:void(0);" name="addSubscriptionFrm" id="addSubscriptionFrm" method="post" enctype="multipart/form-data">
           <input type="hidden" name="id" id="planId" value="">
					  @csrf
            <div class="row">
              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label class="form-label">Select Plan Icon <span class="text-danger">*</span></label>
                  <div class="d-flex align-items-center">
                    <input type="hidden" name="icon" id="selected-icon" required>
                    <div class="selected-icon-display me-2 d-flex align-items-center justify-content-center bg-light" style="width: 40px; height: 40px; border-radius: 8px;">
                      <i class="ph-duotone ph-rocket fs-4"></i>
                    </div>
                    <div class="icon-selector-wrapper position-relative flex-grow-1">
                      <button type="button" class="btn btn-outline-primary w-100 text-start d-flex align-items-center" id="icon-selector-btn">
                        <span class="flex-grow-1">Select Icon</span>
                        <i class="ti ti-chevron-down"></i>
                      </button>
                      <div class="icon-selector-dropdown position-absolute w-100 mt-1 p-2 bg-white border rounded shadow-sm" style="display: none; z-index: 1000;">
                        <div class="icon-grid d-flex flex-wrap gap-2">
                          <div class="icon-option p-2 rounded cursor-pointer d-flex align-items-center justify-content-center" data-icon="ph-duotone ph-rocket" style="min-width: 40px; height: 40px;">
                            <i class="ph-duotone ph-rocket fs-4"></i>
                          </div>
                          <div class="icon-option p-2 rounded cursor-pointer d-flex align-items-center justify-content-center" data-icon="ph-duotone ph-grains" style="min-width: 40px; height: 40px;">
                            <i class="ph-duotone ph-grains fs-4"></i>
                          </div>
                          <div class="icon-option p-2 rounded cursor-pointer d-flex align-items-center justify-content-center" data-icon="ph-duotone ph-flower-lotus" style="min-width: 40px; height: 40px;">
                            <i class="ph-duotone ph-flower-lotus fs-4"></i>
                          </div>
                          <div class="icon-option p-2 rounded cursor-pointer d-flex align-items-center justify-content-center" data-icon="ph-duotone ph-buildings" style="min-width: 40px; height: 40px;">
                            <i class="ph-duotone ph-buildings fs-4"></i>
                          </div>
                          <div class="icon-option p-2 rounded cursor-pointer d-flex align-items-center justify-content-center" data-icon="ph-duotone ph-person-arms-spread" style="min-width: 40px; height: 40px;">
                            <i class="ph-duotone ph-person-arms-spread fs-4"></i>
                          </div>
                          <div class="icon-option p-2 rounded cursor-pointer d-flex align-items-center justify-content-center" data-icon="ph-duotone ph-users" style="min-width: 40px; height: 40px;">
                            <i class="ph-duotone ph-users fs-4"></i>
                          </div>
                          <div class="icon-option p-2 rounded cursor-pointer d-flex align-items-center justify-content-center" data-icon="ph-duotone ph-crown" style="min-width: 40px; height: 40px;">
                            <i class="ph-duotone ph-crown fs-4"></i>
                          </div>
                          <div class="icon-option p-2 rounded cursor-pointer d-flex align-items-center justify-content-center" data-icon="ph-duotone ph-star" style="min-width: 40px; height: 40px;">
                            <i class="ph-duotone ph-star fs-4"></i>
                          </div>
                          <div class="icon-option p-2 rounded cursor-pointer d-flex align-items-center justify-content-center" data-icon="ph-duotone ph-diamond" style="min-width: 40px; height: 40px;">
                            <i class="ph-duotone ph-diamond fs-4"></i>
                          </div>
                          <div class="icon-option p-2 rounded cursor-pointer d-flex align-items-center justify-content-center" data-icon="ph-duotone ph-lightning" style="min-width: 40px; height: 40px;">
                            <i class="ph-duotone ph-lightning fs-4"></i>
                          </div>
                          <div class="icon-option p-2 rounded cursor-pointer d-flex align-items-center justify-content-center" data-icon="ph-duotone ph-trophy" style="min-width: 40px; height: 40px;">
                            <i class="ph-duotone ph-trophy fs-4"></i>
                          </div>
                          <div class="icon-option p-2 rounded cursor-pointer d-flex align-items-center justify-content-center" data-icon="ph-duotone ph-medal" style="min-width: 40px; height: 40px;">
                            <i class="ph-duotone ph-medal fs-4"></i>
                          </div>
                          <div class="icon-option p-2 rounded cursor-pointer d-flex align-items-center justify-content-center" data-icon="ph-duotone ph-chart-line-up" style="min-width: 40px; height: 40px;">
                            <i class="ph-duotone ph-chart-line-up fs-4"></i>
                          </div>
                          <div class="icon-option p-2 rounded cursor-pointer d-flex align-items-center justify-content-center" data-icon="ph-duotone ph-shield-star" style="min-width: 40px; height: 40px;">
                            <i class="ph-duotone ph-shield-star fs-4"></i>
                          </div>
                          <div class="icon-option p-2 rounded cursor-pointer d-flex align-items-center justify-content-center" data-icon="ph-duotone ph-sparkle" style="min-width: 40px; height: 40px;">
                            <i class="ph-duotone ph-sparkle fs-4"></i>
                          </div>
                          <div class="icon-option p-2 rounded cursor-pointer d-flex align-items-center justify-content-center" data-icon="ph-duotone ph-fire" style="min-width: 40px; height: 40px;">
                            <i class="ph-duotone ph-fire fs-4"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  @error('plan_icon')
                  <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label class="form-label">Plan Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="title" id="title" placeholder="Enter plan name" value="" required>
                  @error('name')
                  <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group mb-2">
                  <label class="form-label">Monthly Price (₹) <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" name="monthly_price" placeholder="0.00" value="" required>
                  @error('monthly_price')
                  <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group mb-2">
                  <label class="form-label">Yearly Price (₹) <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" name="yearly_price" placeholder="0.00" value="=" required>
                  @error('yearly_price')
                  <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
			   <div class="col-md-2">
                <div class="form-group mb-2">
                  <label class="form-label">CA Percentage (%) <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" name="ca_percentage" placeholder="0.00" value="=" required>
                  @error('ca_percentage')
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
                    <div class="feature-item mb-2 row align-items-center">
                      <div class="col-md-9">
                        <!--<input type="text" class="form-control" name="features[0][name]" placeholder="Feature name">-->
						<select class="form-select" name="features[0][feature_id]">
							<option value="">-- Select Feature --</option>
							@foreach($menuFeatures as $feature)
								<option value="{{ $feature->id }}">
									{{ $feature->code }}
								</option>
							@endforeach
						</select>
                      </div>
                      <div class="col-md-2">
                        <select class="form-select" name="features[0][is_enabled]">
                          <option value="1">Enabled</option>
                          <option value="0">Disabled</option>
                        </select>
                      </div>
                      <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-feature" disabled>
                          <i class="ti ti-minus"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn-sm add-feature">
                          <i class="ti ti-plus"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group mb-3">
                  <div class="custom-switch">
                    <label class="switch-label" for="is_active">Want to make this plan as active?</label>
                    <div class="switch-toggle">
                      <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-12">
                <div class="text-end">
                  <a href="{{ route('admin.subscription-list')}}" class="btn btn-danger me-2"><i class="ph-duotone ph-prohibit me-2"></i> Cancel</a>
                  <button type="submit" class="btn btn-primary">Create Plan <i class="ph-duotone ph-arrow-circle-up-right"></i></button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>

	const featureOptions = `
        <option value="">-- Select Feature --</option>
        @foreach($menuFeatures as $feature)
            <option value="{{ $feature->id }}">{{ $feature->code }}</option>
        @endforeach
    `;
  document.addEventListener('DOMContentLoaded', function() {
    const featuresContainer = document.querySelector('.features-container');
    const iconSelectorBtn = document.getElementById('icon-selector-btn');
    const iconSelectorDropdown = document.querySelector('.icon-selector-dropdown');
    const selectedIconInput = document.getElementById('selected-icon');
    const selectedIconDisplay = document.querySelector('.selected-icon-display i');
    const iconOptions = document.querySelectorAll('.icon-option');

    // Add new feature field
    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('add-feature') || e.target.closest('.add-feature')) {
        const featureItems = document.querySelectorAll('.feature-item');
        const featureCount = featureItems.length;

        const newFeature = document.createElement('div');
        newFeature.className = 'feature-item mb-2 row align-items-center';
        newFeature.innerHTML = `
          <div class="col-md-9">
            <!--<input type="text" class="form-control" name="features[${featureCount}][name]" placeholder="Feature name">-->
			<select class="form-select" name="features[${featureCount}][feature_id]">
				${featureOptions}
			</select>
          </div>
          <div class="col-md-2">
            <select class="form-select" name="features[${featureCount}][is_enabled]">
              <option value="1">Enabled</option>
              <option value="0">Disabled</option>
            </select>
          </div>
          <div class="col-md-1">
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

    // Toggle dropdown
    iconSelectorBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      iconSelectorDropdown.style.display = iconSelectorDropdown.style.display === 'none' ? 'block' : 'none';
    });

    // Handle icon selection
    iconOptions.forEach(option => {
      option.addEventListener('click', function() {
        const iconClass = this.dataset.icon;
        selectedIconInput.value = iconClass;
        selectedIconDisplay.className = `${iconClass} fs-4`;
        iconSelectorDropdown.style.display = 'none';

        // Update active state
        iconOptions.forEach(opt => opt.classList.remove('active'));
        this.classList.add('active');
      });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
      if (!e.target.closest('.icon-selector-wrapper')) {
        iconSelectorDropdown.style.display = 'none';
      }
    });

    // Set default icon
    if (!selectedIconInput.value) {
      selectedIconInput.value = 'ph-duotone ph-rocket';
      iconOptions[0].classList.add('active');
    }
  });

      $('form#addSubscriptionFrm').bind('submit', function(e){			
      e.preventDefault(); // Prevent default form submission
      let formData = new FormData(this);
      var planId = $("#planId").val();
      var suburl = (planId == "") ?  '/save_plan' :  '/update_plan';

      //var formData = $(this).serialize(); // Capture form data

      $.ajax({
        url: suburl,
        method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
             if (res.status === 'success') {
            Swal.fire({
                title: 'Success',
                text: res.message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                // Redirect after user clicks OK
                window.location.href = res.redirect;
            });
        }
            },
            error: function (xhr) {
                console.log(xhr.responseJSON.errors);
            }
        });
    });

  // Initialize the icon selector dropdown
  document.addEventListener('DOMContentLoaded', function() {
    const iconSelectorDropdown = document.querySelector('.icon-selector-dropdown');
    iconSelectorDropdown.style.display = 'none'; // Hide dropdown initially
  });

</script>

<style>
  .icon-option {
    cursor: pointer;
    transition: all 0.2s ease;
    background-color: #f8f9fa;
    border: 2px solid transparent;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    position: relative;
    overflow: hidden;
  }

  .icon-option::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, #6366f1, #8b5cf6);
    opacity: 0;
    transition: opacity 0.2s ease;
    z-index: 0;
  }

  .icon-option i {
    position: relative;
    z-index: 1;
    color: #64748b;
    transition: all 0.2s ease;
  }

  .icon-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .icon-option:hover i {
    color: #4f46e5;
  }

  .icon-option.active {
    border-color: #4f46e5;
    background-color: #fff;
  }

  .icon-option.active i {
    color: #4f46e5;
  }

  .icon-option.active::before {
    opacity: 0.1;
  }

  .icon-selector-dropdown {
    max-height: 280px;
    overflow-y: auto;
    padding: 1rem !important;
    border-radius: 16px !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
  }

  .icon-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(48px, 1fr));
    gap: 12px;
  }

  .selected-icon-display {
    border: 2px solid #e2e8f0;
    background: white !important;
    border-radius: 12px !important;
    width: 48px !important;
    height: 48px !important;
    transition: all 0.2s ease;
  }

  .selected-icon-display i {
    color: #4f46e5;
    font-size: 1.5rem;
  }

  #icon-selector-btn {
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-weight: 500;
    border-color: #e2e8f0;
    color: #64748b;
    transition: all 0.2s ease;
  }

  #icon-selector-btn:hover {
    border-color: #4f46e5;
    color: #4f46e5;
  }

  /* Custom scrollbar for the dropdown */
  .icon-selector-dropdown::-webkit-scrollbar {
    width: 8px;
  }

  .icon-selector-dropdown::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
  }

  .icon-selector-dropdown::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
  }

  .icon-selector-dropdown::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
  }

  /* Enhanced Switch Styling */
  .custom-switch {
    padding: 1rem 1.5rem;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.3s ease;
  }

  .custom-switch:hover {
    background: #f1f5f9;
  }

  .switch-label {
    color: #64748b;
    font-weight: 500;
    margin: 0;
    cursor: pointer;
    user-select: none;
  }

  .switch-toggle {
    position: relative;
    min-width: 48px;
  }

  .custom-switch .form-check-input {
    width: 48px;
    height: 24px;
    margin: 0;
    background-image: none;
    background-color: #e2e8f0;
    border: none;
    cursor: pointer;
    position: relative;
    border-radius: 27px;
  }

  .custom-switch .form-check-input:checked {
    background-color: #4f46e5;
    border-color: #4f46e5;
  }

  .custom-switch .form-check-input:focus {
    box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
    border-color: #4f46e5;
  }

  .custom-switch .form-check-input:before {
    content: '';
    position: absolute;
    width: 18px;
    height: 18px;
    left: 3px;
    top: 3px;
    background-color: white;
    border-radius: 50%;
    transition: transform 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .custom-switch .form-check-input:checked:before {
    transform: translateX(24px);
  }

  .custom-switch:hover .form-check-input:not(:checked) {
    background-color: #cbd5e1;
  }

  .custom-switch:hover .form-check-input:checked {
    background-color: #4338ca;
  }

  /* Animation for the switch */
  @keyframes switchOn {
    0% {
      transform: translateX(0);
    }

    50% {
      transform: translateX(28px);
    }

    100% {
      transform: translateX(24px);
    }
  }

  @keyframes switchOff {
    0% {
      transform: translateX(24px);
    }

    50% {
      transform: translateX(-4px);
    }

    100% {
      transform: translateX(0);
    }
  }

  .custom-switch .form-check-input:checked:before {
    animation: switchOn 0.3s ease forwards;
  }

  .custom-switch .form-check-input:not(:checked):before {
    animation: switchOff 0.3s ease forwards;
  }
</style>
@endsection