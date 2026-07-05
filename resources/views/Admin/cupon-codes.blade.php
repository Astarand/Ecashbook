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
                        <li class="breadcrumb-item active" aria-current="page">Coupon Codes</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5>Coupon Codes</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#couponModal">
                            <i class="ti ti-plus me-1"></i> Create New Coupon
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tbl-product my-3" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Discount</th>
                                    <th>Type</th>
                                    <th>Valid From</th>
                                    <th>Valid Until</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($coupons as $coupon)
                            <tr>
                                <td>
                                    <span class="badge bg-light-primary text-primary" style="font-size: 14px; font-family: monospace;">
                                        {{ $coupon->code }}
                                    </span>
                                </td>
                                <td>{{ $coupon->discount }}{{ $coupon->type == 'percentage' ? '%' : ' ₹' }}</td>
                                <td>{{ ucfirst($coupon->type) }}</td>
                                <td>{{ \Carbon\Carbon::parse($coupon->valid_from)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($coupon->valid_until)->format('d M Y') }}</td>
                                <td>
                                    @if($coupon->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a
                                                    href="javascript:void(0);"
                                                    class="avtar avtar-xs btn-link-secondary btn-pc-default view-coupon-btn"                                                   
                                                    data-id="{{ $coupon->id }}">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                <a href="javascript:void(0);" 
                                                class="avtar avtar-xs btn-link-success btn-pc-default edit-coupon-btn" 
                                                data-id="{{ $coupon->id }}">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                <a href="javascript:void(0);" onclick="showDeleteModal({{ $coupon->id }})" class="avtar avtar-xs btn-link-danger btn-pc-default">
                                                    <i class="ti ti-trash f-18"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No coupons found.</td>
                            </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Coupon Modal -->
    <div class="modal fade" id="couponModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Create New Coupon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('admin.coupon.save_coupon') }}" enctype="multipart/form-data" id="couponForm">
                        @csrf
                        <input type="hidden" name="id" id="coupon_id">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Basic Coupon Details -->
                                <div class="mb-3">
                                    <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="couponCode" name="code" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="generateCode()">
                                            Generate
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Discount <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="discount" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                                            <select class="form-select" name="type" required>
                                                <option value="percentage">Percentage (%)</option>
                                                <option value="fixed">Fixed Amount (₹)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Valid From <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="valid_from" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Valid Until <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="valid_until" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Plan Selection Section -->
                                <div class="plan-selection-container">
                                    <label class="form-label d-flex justify-content-between align-items-center">
                                        <span>Applicable Plans <span class="text-danger">*</span></span>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleAllPlans()">
                                            Toggle All
                                        </button>
                                    </label>
                                    <div class="plan-cards">
                                        <!-- Basic Plan -->
                                        <div class="plan-card mb-3">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input plan-checkbox" type="checkbox" id="plan1" name="plans[]" value="1">
                                                            <label class="form-check-label fw-medium" for="plan1">
                                                                Basic Plan
                                                            </label>
                                                        </div>
                                                        <span class="badge bg-light-primary">₹499/month</span>
                                                    </div>
                                                    <div class="plan-type-options" data-plan="1">
                                                        <div class="btn-group w-100" role="group">
                                                            <input type="checkbox" class="btn-check" id="plan1-monthly" name="plan_types[1][]" value="monthly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan1-monthly">Monthly</label>

                                                            <input type="checkbox" class="btn-check" id="plan1-yearly" name="plan_types[1][]" value="yearly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan1-yearly">Yearly</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Standard Plan -->
                                        <div class="plan-card mb-3">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input plan-checkbox" type="checkbox" id="plan2" name="plans[]" value="2">
                                                            <label class="form-check-label fw-medium" for="plan2">
                                                                Standard Plan
                                                            </label>
                                                        </div>
                                                        <span class="badge bg-light-primary">₹999/month</span>
                                                    </div>
                                                    <div class="plan-type-options" data-plan="2">
                                                        <div class="btn-group w-100" role="group">
                                                            <input type="checkbox" class="btn-check" id="plan2-monthly" name="plan_types[2][]" value="monthly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan2-monthly">Monthly</label>

                                                            <input type="checkbox" class="btn-check" id="plan2-yearly" name="plan_types[2][]" value="yearly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan2-yearly">Yearly</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Premium Plan -->
                                        <div class="plan-card mb-3">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input plan-checkbox" type="checkbox" id="plan3" name="plans[]" value="3">
                                                            <label class="form-check-label fw-medium" for="plan3">
                                                                Premium Plan
                                                            </label>
                                                        </div>
                                                        <span class="badge bg-light-primary">₹1999/month</span>
                                                    </div>
                                                    <div class="plan-type-options" data-plan="3">
                                                        <div class="btn-group w-100" role="group">
                                                            <input type="checkbox" class="btn-check" id="plan3-monthly" name="plan_types[3][]" value="monthly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan3-monthly">Monthly</label>

                                                            <input type="checkbox" class="btn-check" id="plan3-yearly" name="plan_types[3][]" value="yearly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan3-yearly">Yearly</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Enterprise Plan -->
                                        <div class="plan-card mb-3">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input plan-checkbox" type="checkbox" id="plan4" name="plans[]" value="4">
                                                            <label class="form-check-label fw-medium" for="plan4">
                                                                Enterprise Plan
                                                            </label>
                                                        </div>
                                                        <span class="badge bg-light-primary">₹4999/month</span>
                                                    </div>
                                                    <div class="plan-type-options" data-plan="4">
                                                        <div class="btn-group w-100" role="group">
                                                            <input type="checkbox" class="btn-check" id="plan4-monthly" name="plan_types[4][]" value="monthly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan4-monthly">Monthly</label>

                                                            <input type="checkbox" class="btn-check" id="plan4-yearly" name="plan_types[4][]" value="yearly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan4-yearly">Yearly</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Ultimate Plan -->
                                        <div class="plan-card mb-3">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input plan-checkbox" type="checkbox" id="plan5" name="plans[]" value="5">
                                                            <label class="form-check-label fw-medium" for="plan5">
                                                                Ultimate Plan
                                                            </label>
                                                        </div>
                                                        <span class="badge bg-light-primary">₹9999/month</span>
                                                    </div>
                                                    <div class="plan-type-options" data-plan="5">
                                                        <div class="btn-group w-100" role="group">
                                                            <input type="checkbox" class="btn-check" id="plan5-monthly" name="plan_types[5][]" value="monthly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan5-monthly">Monthly</label>

                                                            <input type="checkbox" class="btn-check" id="plan5-yearly" name="plan_types[5][]" value="yearly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan5-yearly">Yearly</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="my-3">
                                    <div class="custom-switch">
                                        <label class="switch-label" for="is_active">Make this coupon active?</label>
                                        <div class="switch-toggle">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveCoupon()">Save Coupon</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header text-center">
                        <h3>Delete Coupon</h3>
                        <p>Are you sure you want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" id="confirmDelete" class="w-100 btn btn-danger">
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

    <!-- Edit Modal -->
    <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Coupon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCouponForm">
                        <input type="hidden" id="edit_coupon_id" name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_code" name="code" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Discount <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="edit_discount" name="discount" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                                            <select class="form-select" id="edit_discount_type" name="type" required>
                                                <option value="percentage">Percentage (%)</option>
                                                <option value="fixed">Fixed Amount (₹)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Valid From <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="edit_valid_from" name="valid_from" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Valid Until <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="edit_valid_until" name="valid_until" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="plan-selection-container">
                                    <label class="form-label d-flex justify-content-between align-items-center">
                                        <span>Applicable Plans <span class="text-danger">*</span></span>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleAllPlans('edit')">
                                            Toggle All
                                        </button>
                                    </label>
                                    <div class="plan-cards">
                                        <!-- Basic Plan -->
                                        <div class="plan-card mb-3">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input plan-checkbox" type="checkbox" id="plan1" name="plans[]" value="1">
                                                            <label class="form-check-label fw-medium" for="plan1">
                                                                Basic Plan
                                                            </label>
                                                        </div>
                                                        <span class="badge bg-light-primary">₹499/month</span>
                                                    </div>
                                                    <div class="plan-type-options" data-plan="1">
                                                        <div class="btn-group w-100" role="group">
                                                            <input type="checkbox" class="btn-check" id="plan1-monthly" name="plan_types[1][]" value="monthly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan1-monthly">Monthly</label>

                                                            <input type="checkbox" class="btn-check" id="plan1-yearly" name="plan_types[1][]" value="yearly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan1-yearly">Yearly</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Standard Plan -->
                                        <div class="plan-card mb-3">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input plan-checkbox" type="checkbox" id="plan2" name="plans[]" value="2">
                                                            <label class="form-check-label fw-medium" for="plan2">
                                                                Standard Plan
                                                            </label>
                                                        </div>
                                                        <span class="badge bg-light-primary">₹999/month</span>
                                                    </div>
                                                    <div class="plan-type-options" data-plan="2">
                                                        <div class="btn-group w-100" role="group">
                                                            <input type="checkbox" class="btn-check" id="plan2-monthly" name="plan_types[2][]" value="monthly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan2-monthly">Monthly</label>

                                                            <input type="checkbox" class="btn-check" id="plan2-yearly" name="plan_types[2][]" value="yearly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan2-yearly">Yearly</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Premium Plan -->
                                        <div class="plan-card mb-3">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input plan-checkbox" type="checkbox" id="plan3" name="plans[]" value="3">
                                                            <label class="form-check-label fw-medium" for="plan3">
                                                                Premium Plan
                                                            </label>
                                                        </div>
                                                        <span class="badge bg-light-primary">₹1999/month</span>
                                                    </div>
                                                    <div class="plan-type-options" data-plan="3">
                                                        <div class="btn-group w-100" role="group">
                                                            <input type="checkbox" class="btn-check" id="plan3-monthly" name="plan_types[3][]" value="monthly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan3-monthly">Monthly</label>

                                                            <input type="checkbox" class="btn-check" id="plan3-yearly" name="plan_types[3][]" value="yearly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan3-yearly">Yearly</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Enterprise Plan -->
                                        <div class="plan-card mb-3">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input plan-checkbox" type="checkbox" id="plan4" name="plans[]" value="4">
                                                            <label class="form-check-label fw-medium" for="plan4">
                                                                Enterprise Plan
                                                            </label>
                                                        </div>
                                                        <span class="badge bg-light-primary">₹4999/month</span>
                                                    </div>
                                                    <div class="plan-type-options" data-plan="4">
                                                        <div class="btn-group w-100" role="group">
                                                            <input type="checkbox" class="btn-check" id="plan4-monthly" name="plan_types[4][]" value="monthly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan4-monthly">Monthly</label>

                                                            <input type="checkbox" class="btn-check" id="plan4-yearly" name="plan_types[4][]" value="yearly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan4-yearly">Yearly</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Ultimate Plan -->
                                        <div class="plan-card mb-3">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input plan-checkbox" type="checkbox" id="plan5" name="plans[]" value="5">
                                                            <label class="form-check-label fw-medium" for="plan5">
                                                                Ultimate Plan
                                                            </label>
                                                        </div>
                                                        <span class="badge bg-light-primary">₹9999/month</span>
                                                    </div>
                                                    <div class="plan-type-options" data-plan="5">
                                                        <div class="btn-group w-100" role="group">
                                                            <input type="checkbox" class="btn-check" id="plan5-monthly" name="plan_types[5][]" value="monthly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan5-monthly">Monthly</label>

                                                            <input type="checkbox" class="btn-check" id="plan5-yearly" name="plan_types[5][]" value="yearly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan5-yearly">Yearly</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="my-3">
                                    <div class="custom-switch">
                                        <label class="switch-label" for="is_active">Make this coupon active?</label>
                                        <div class="switch-toggle">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateCoupon()">Update Coupon</button>
                </div>
            </div>
        </div>
    </div>
<!--view model-->
    <div class="modal fade" id="view_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Coupon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="viewCouponForm">
                        <input type="hidden" id="edit_coupon_id" name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="view_code" name="code" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Discount <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="view_discount" name="discount" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                                            <select class="form-select" id="view_discount_type" name="type" required>
                                                <option value="percentage">Percentage (%)</option>
                                                <option value="fixed">Fixed Amount (₹)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Valid From <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="view_valid_from" name="valid_from" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Valid Until <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="view_valid_until" name="valid_until" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" id="view_description" name="description" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="plan-selection-container">
                                    <label class="form-label d-flex justify-content-between align-items-center">
                                        <span>Applicable Plans <span class="text-danger">*</span></span>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleAllPlans('edit')">
                                            Toggle All
                                        </button>
                                    </label>
                                    <div class="plan-cards">
                                        <!-- Basic Plan -->
                                        <div class="plan-card mb-3">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input plan-checkbox" type="checkbox" id="plan1" name="plans[]" value="1">
                                                            <label class="form-check-label fw-medium" for="plan1">
                                                                Basic Plan
                                                            </label>
                                                        </div>
                                                        <span class="badge bg-light-primary">₹499/month</span>
                                                    </div>
                                                    <div class="plan-type-options" data-plan="1">
                                                        <div class="btn-group w-100" role="group">
                                                            <input type="checkbox" class="btn-check" id="plan1-monthly" name="plan_types[1][]" value="monthly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan1-monthly">Monthly</label>

                                                            <input type="checkbox" class="btn-check" id="plan1-yearly" name="plan_types[1][]" value="yearly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan1-yearly">Yearly</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Standard Plan -->
                                        <div class="plan-card mb-3">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input plan-checkbox" type="checkbox" id="plan2" name="plans[]" value="2">
                                                            <label class="form-check-label fw-medium" for="plan2">
                                                                Standard Plan
                                                            </label>
                                                        </div>
                                                        <span class="badge bg-light-primary">₹999/month</span>
                                                    </div>
                                                    <div class="plan-type-options" data-plan="2">
                                                        <div class="btn-group w-100" role="group">
                                                            <input type="checkbox" class="btn-check" id="plan2-monthly" name="plan_types[2][]" value="monthly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan2-monthly">Monthly</label>

                                                            <input type="checkbox" class="btn-check" id="plan2-yearly" name="plan_types[2][]" value="yearly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan2-yearly">Yearly</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Premium Plan -->
                                        <div class="plan-card mb-3">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input plan-checkbox" type="checkbox" id="plan3" name="plans[]" value="3">
                                                            <label class="form-check-label fw-medium" for="plan3">
                                                                Premium Plan
                                                            </label>
                                                        </div>
                                                        <span class="badge bg-light-primary">₹1999/month</span>
                                                    </div>
                                                    <div class="plan-type-options" data-plan="3">
                                                        <div class="btn-group w-100" role="group">
                                                            <input type="checkbox" class="btn-check" id="plan3-monthly" name="plan_types[3][]" value="monthly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan3-monthly">Monthly</label>

                                                            <input type="checkbox" class="btn-check" id="plan3-yearly" name="plan_types[3][]" value="yearly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan3-yearly">Yearly</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Enterprise Plan -->
                                        <div class="plan-card mb-3">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input plan-checkbox" type="checkbox" id="plan4" name="plans[]" value="4">
                                                            <label class="form-check-label fw-medium" for="plan4">
                                                                Enterprise Plan
                                                            </label>
                                                        </div>
                                                        <span class="badge bg-light-primary">₹4999/month</span>
                                                    </div>
                                                    <div class="plan-type-options" data-plan="4">
                                                        <div class="btn-group w-100" role="group">
                                                            <input type="checkbox" class="btn-check" id="plan4-monthly" name="plan_types[4][]" value="monthly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan4-monthly">Monthly</label>

                                                            <input type="checkbox" class="btn-check" id="plan4-yearly" name="plan_types[4][]" value="yearly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan4-yearly">Yearly</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Ultimate Plan -->
                                        <div class="plan-card mb-3">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input plan-checkbox" type="checkbox" id="plan5" name="plans[]" value="5">
                                                            <label class="form-check-label fw-medium" for="plan5">
                                                                Ultimate Plan
                                                            </label>
                                                        </div>
                                                        <span class="badge bg-light-primary">₹9999/month</span>
                                                    </div>
                                                    <div class="plan-type-options" data-plan="5">
                                                        <div class="btn-group w-100" role="group">
                                                            <input type="checkbox" class="btn-check" id="plan5-monthly" name="plan_types[5][]" value="monthly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan5-monthly">Monthly</label>

                                                            <input type="checkbox" class="btn-check" id="plan5-yearly" name="plan_types[5][]" value="yearly" disabled>
                                                            <label class="btn btn-outline-primary btn-sm" for="plan5-yearly">Yearly</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="my-3">
                                    <div class="custom-switch">
                                        <label class="switch-label" for="is_active">Make this coupon active?</label>
                                        <div class="switch-toggle">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>                    
                </div>
            </div>
        </div>
    </div>
</div>

@section('page-script')
<style>
    /* Custom Switch Styling */
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

    /* Table Styles */
    .table> :not(caption)>*>* {
        padding: 1rem 1rem;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    .btn-icon+.btn-icon {
        margin-left: 0.5rem;
    }

    /* Plan Selection Styles */
    .plan-selection-container {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        border: 1px solid #e2e8f0;
        max-height: 400px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .plan-cards {
        max-height: 320px;
        overflow-y: auto;
        padding-right: 0.5rem;
        margin-right: -0.5rem;
        padding-top: 0.5rem;
    }

    .plan-cards::-webkit-scrollbar {
        width: 6px;
    }

    .plan-cards::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .plan-cards::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .plan-cards::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .plan-cards::-webkit-scrollbar-thumb:hover {
        background-color: #94a3b8;
    }

    .plan-selection-container::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 20px;
        background: linear-gradient(to top, rgba(248, 250, 252, 1), rgba(248, 250, 252, 0));
        pointer-events: none;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .plan-card .card {
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .plan-card .card:hover {
        border-color: #cbd5e1;
    }

    .plan-type-options {
        opacity: 0.5;
        pointer-events: none;
        transition: all 0.2s ease;
    }

    .plan-type-options.active {
        opacity: 1;
        pointer-events: auto;
    }

    .badge.bg-light-primary {
        background-color: #eff6ff;
        color: #3b82f6;
    }

    .btn-group .btn-outline-primary {
        border-color: #e2e8f0;
        color: #64748b;
    }

    .btn-group .btn-check:checked+.btn-outline-primary {
        background-color: #4f46e5;
        border-color: #4f46e5;
        color: white;
    }

    .btn-group .btn-outline-primary:hover:not(.active) {
        background-color: #f8fafc;
        border-color: #cbd5e1;
        color: #4f46e5;
    }

    /* Add status badge styles */
    .badge {
        padding: 6px 12px;
        font-weight: 500;
        font-size: 12px;
        border-radius: 6px;
    }

    .badge.bg-success {
        background-color: #dcfce7 !important;
        color: #16a34a;
    }

    .badge.bg-warning {
        background-color: #fef9c3 !important;
        color: #ca8a04;
    }

    .badge.bg-danger {
        background-color: #fee2e2 !important;
        color: #dc2626;
    }

    /* Modal styles */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .modal-body {
        padding: 2rem;
    }

    .btn-light {
        background-color: #f1f5f9;
        border-color: #e2e8f0;
        color: #64748b;
    }

    .btn-light:hover {
        background-color: #e2e8f0;
        border-color: #cbd5e1;
        color: #475569;
    }

    /* Add action menu styles */
    .dropdown-toggle {
        padding: 0.5rem;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .dropdown-toggle:hover {
        background-color: #f1f5f9;
    }

    .dropdown-menu {
        min-width: 160px;
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-top: 0.5rem;
    }

    .dropdown-item {
        padding: 0.5rem 0.75rem;
        color: #475569;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f1f5f9;
        color: #4f46e5;
    }

    .dropdown-item i {
        color: #64748b;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover i {
        color: #4f46e5;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    /* Update table styles */
    .table td {
        vertical-align: middle;
    }
</style>

<script>
    // Generate random coupon code
    function generateCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let code = '';
        for (let i = 0; i < 8; i++) {
            code += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('couponCode').value = code;
    }

    // Edit coupon
    function editCoupon(id) {
        // Fetch coupon details and populate modal
        document.getElementById('modalTitle').textContent = 'Edit Coupon';
        $('#couponModal').modal('show');
    }

    // Function to show delete modal
    function showDeleteModal(id) {
        const modal = new bootstrap.Modal(document.getElementById('delete_modal'));
        const confirmBtn = document.getElementById('confirmDelete');

        // Store the coupon ID for deletion
        confirmBtn.dataset.couponId = id;

        // Show the modal
        modal.show();

        // Handle delete confirmation
        confirmBtn.onclick = function() {
            deleteCoupon(this.dataset.couponId);
        };
    }

    // Function to delete coupon
    function deleteCoupon(id) {
        // Make API call to delete coupon
        fetch(`/admin/coupon/delete/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('delete_modal')).hide();
                    // Refresh table or remove row
                    location.reload();
                } else {
                    alert('Failed to delete coupon');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the coupon');
            });
    }

    // Function to show edit modal
    $(document).ready(function () {
    $('.edit-coupon-btn').on('click', function () {
        alert('Edit button clicked');
        const couponId = $(this).data('id');

        $.ajax({
            url: `/admin/coupon/edit/${couponId}`, // or use a named route URL
            type: 'GET',
            success: function (response) {
                // Fill form fields
                $('#edit_coupon_id').val(response.id);
                $('#edit_code').val(response.code);
                $('#edit_discount').val(response.discount);
                $('#edit_discount_type').val(response.type);
                $('#edit_valid_from').val(response.valid_from);
                $('#edit_valid_until').val(response.valid_until);
                $('#edit_description').val(response.description ?? '');

                // Populate applicable plans
                let planHtml = '';
                response.all_plans.forEach(plan => {
                    const checked = response.applied_plan_ids.includes(plan.id) ? 'checked' : '';
                    planHtml += `
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="plans[]" value="${plan.id}" id="plan-${plan.id}" ${checked}>
                            <label class="form-check-label" for="plan-${plan.id}">${plan.title}</label>
                        </div>
                    `;
                });
                $('#edit_modal .plan-cards').html(planHtml);

                // Open modal
                $('#edit_modal').modal('show');
            },
                    error: function (xhr) {
                        alert('Failed to load coupon data.');
                    }
                });
            });
       


        $('.view-coupon-btn').on('click', function () {
        alert('View button clicked');
        const couponId = $(this).data('id');

        $.ajax({
            url: `/admin/coupon/edit/${couponId}`, // or use a named route URL
            type: 'GET',
            success: function (response) {
                // Fill form fields
                $('#edit_coupon_id').val(response.id);
                $('#view_code').val(response.code);
                $('#view_discount').val(response.discount);
                $('#view_discount_type').val(response.type);
                $('#view_valid_from').val(response.valid_from);
                $('#view_valid_until').val(response.valid_until);
                $('#view_description').val(response.description ?? '');

                // Populate applicable plans
                let planHtml = '';
                response.all_plans.forEach(plan => {
                    const checked = response.applied_plan_ids.includes(plan.id) ? 'checked' : '';
                    planHtml += `
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="plans[]" value="${plan.id}" id="plan-${plan.id}" ${checked}>
                            <label class="form-check-label" for="plan-${plan.id}">${plan.title}</label>
                        </div>
                    `;
                });
                $('#view_modal .plan-cards').html(planHtml);

                // Open modal
                $('#view_modal').modal('show');
            },
                    error: function (xhr) {
                        alert('Failed to load coupon data.');
                    }
                });
            });
        });

    // Function to update coupon
    function updateCoupon() {
    const form = document.getElementById('editCouponForm');
    const formData = new FormData(form);
    const id = formData.get('id');

    //  Convert FormData to a plain object
    const plainData = {};
    formData.forEach((value, key) => {
        if (plainData[key]) {
            // If key already exists (like for checkbox arrays), convert to array
            if (!Array.isArray(plainData[key])) {
                plainData[key] = [plainData[key]];
            }
            plainData[key].push(value);
        } else {
            plainData[key] = value;
        }
    });

    //  Send as JSON
    fetch(`/admin/coupon/update/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(plainData)
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Debug
        if (data.status === 'success') {
           Swal.fire({
        title: 'Success',
        text: data.message,
        icon: 'success',
        confirmButtonText: 'OK'
        }).then(() => {
            // Redirect after user clicks OK
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                location.reload(); // fallback if no redirect provided
            }
    });
        } else {
            alert(data.message || 'Failed to update coupon');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the coupon');
    });
}

   

    // Handle plan checkbox changes
    document.addEventListener('DOMContentLoaded', function() {
        const planCheckboxes = document.querySelectorAll('.plan-checkbox');

        planCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const planId = this.value;
                const typeOptions = document.querySelector(`.plan-type-options[data-plan="${planId}"]`);
                const typeCheckboxes = typeOptions.querySelectorAll('input[type="checkbox"]');

                if (this.checked) {
                    typeOptions.classList.add('active');
                    typeCheckboxes.forEach(cb => {
                        cb.disabled = false;
                        cb.checked = true; // Automatically check both options
                    });
                } else {
                    typeOptions.classList.remove('active');
                    typeCheckboxes.forEach(cb => {
                        cb.disabled = true;
                        cb.checked = false;
                    });
                }
            });
        });
    });

    // Toggle all plans
    function toggleAllPlans() {
        const planCheckboxes = document.querySelectorAll('.plan-checkbox');
        const allChecked = Array.from(planCheckboxes).every(cb => cb.checked);

        planCheckboxes.forEach(checkbox => {
            checkbox.checked = !allChecked;
            // Trigger the change event to handle the subscription type checkboxes
            checkbox.dispatchEvent(new Event('change'));
        });
    }

    // Validate plan selection before save
    function saveCoupon() {
    const formData = new FormData(document.getElementById('couponForm'));

    fetch("{{ route('admin.coupon.save_coupon') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest', // <--- Add this line
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            location.reload();
        } else {
            if (data.errors) {
                console.error(data.errors);
                alert('Validation errors occurred.');
            } else {
                alert(data.message || 'Something went wrong.');
            }
        }
    })
    .catch(err => {
        console.error(err);
        alert('Server error occurred.');
    });
}




    // Initialize datatable
    $(document).ready(function() {
        $('#coupon-table').DataTable({
            order: [
                [3, 'desc']
            ],
            pageLength: 10,
            dom: '<"row align-items-center"<"col-md-6"l><"col-md-6"f>><"table-responsive my-3"t><"row align-items-center"<"col-md-6"i><"col-md-6"p>>'
        });
    });

    // Handle status change
    document.addEventListener('DOMContentLoaded', function() {
        const statusButtons = document.querySelectorAll('.status-change');

        statusButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const newStatus = this.dataset.status;
                const text = newStatus === '1' ? 'activate' : 'deactivate';

                if (confirm(`Are you sure you want to ${text} this coupon?`)) {
                    // Handle status change
                    console.log('Changing status:', id, newStatus);

                    // Update button text and data after success
                    this.dataset.status = newStatus === '1' ? '0' : '1';
                    this.innerHTML = `
                        <i class="ti ti-bell-off f-18 me-2"></i>
                        ${newStatus === '1' ? 'Deactivate' : 'Activate'}
                    `;
                }
            });
        });
    });

    // Update click handlers for action buttons
    document.addEventListener('DOMContentLoaded', function() {
        // Edit button click handler
        document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target=""]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                if (id) {
                    editCoupon(id);
                }
            });
        });

        // Delete button click handler
        document.querySelectorAll('.status-change[data-status="1"]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                if (id) {
                    showDeleteModal(id);
                }
            });
        });
    });
</script>
@endsection

@endsection