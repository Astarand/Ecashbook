@extends('App.Layout')

@section('container')
<div class="pc-content">
    <!-- Breadcrumb -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Requirment Claims</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Expenditure Claims</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Expenditure Claims</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenditureModal"><i class="ti ti-square-plus"></i> Add New Expenditure Claim</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Table + Add button -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Details</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($claims as $i => $claim)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($claim->claim_date)->format('d M Y') }}</td>
                                <td>{{ $claim->category }}</td>
                                <td>{{ $claim->claim_amount }}</td>
                                <td>{{ $claim->payment_method }}</td>
                                <td>{{ $claim->description }}</td>
                                <td>
                                    @if($claim->status === 'Approved')
                                    <span class="badge bg-success">Approved</span>
                                    @elseif($claim->status === 'Rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                    @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary" data-bs-toggle="modal" data-bs-target="#viewClaimModal{{ $claim->id }}"><i class="ti ti-eye f-20"></i> </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary" data-bs-toggle="modal" data-bs-target="#editClaimModal{{ $claim->id }}"><i class="ti ti-edit f-20"></i> </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">No claims submitted yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Expenditure Modal -->
    <div class="modal fade" id="addExpenditureModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content shadow-lg border-0" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Enhanced Header -->
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="ti ti-receipt fs-4"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0 fw-bold">New Expenditure Claim</h5>
                            <small class="opacity-75">Submit your expense claim</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Date Section -->
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <h6 class="text-primary mb-3 fw-bold">
                                    <i class="ti ti-calendar me-2"></i>Expense Information
                                </h6>
                                <div class="form-floating">
                                    <input type="date" name="date" class="form-control" id="expense_date" required>
                                    <label for="expense_date">Date of Expenses</label>
                                </div>
                            </div>
                        </div>

                        <!-- Employee & Category Section -->
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <h6 class="text-success mb-3 fw-bold">
                                    <i class="ti ti-user me-2"></i>Employee & Category Details
                                </h6>
                                <div class="row g-3">
                                    
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <select name="category" class="form-select" id="category_select" required>
                                                <option value="">Choose Category</option>
                                                <option value="travel">Travel Expenses</option>
                                                <option value="accommodation">Accommodation</option>
                                                <option value="meals">Meals & Entertainment</option>
                                                <option value="office_supplies">Office Supplies</option>
                                                <option value="communication">Communication</option>
                                                <option value="training">Training & Development</option>
                                                <option value="equipment">Equipment & Technology</option>
                                                <option value="client_entertainment">Client Entertainment</option>
                                                <option value="shipping">Shipping & Delivery</option>
                                                <option value="marketing">Marketing & Advertising</option>
                                                <option value="office_rent">Office Rent & Utilities</option>
                                                <option value="employee_welfare">Employee Welfare & Health</option>
                                                <option value="legal">Legal and Professional Services</option>
                                                <option value="miscellaneous">Miscellaneous</option>
                                            </select>
                                            <label for="category_select">Expenses Category</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Details Section -->
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <h6 class="text-info mb-3 fw-bold">
                                    <i class="ti ti-file-text me-2"></i>Expense Details
                                </h6>
                                <div class="form-floating">
                                    <textarea name="details" class="form-control" id="expense_details" style="height: 100px"
                                        placeholder="Describe your expenses in detail..." required></textarea>
                                    <label for="expense_details">Expense Details</label>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Please provide detailed information about your expenses
                                </small>
                            </div>
                        </div>

                        <!-- Amount & Payment Section -->
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <h6 class="text-warning mb-3 fw-bold">
                                    <i class="ti ti-currency-rupee me-2"></i>Amount & Payment Method
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="number" name="claim_amount" class="form-control" id="claim_amount"
                                                step="0.01" min="0" placeholder="0.00" required>
                                            <label for="claim_amount">Expenses Amount (₹)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select name="payment_method" class="form-select" id="payment_method" required>
                                                <option value="">Choose Payment Method</option>
                                                <option value="cash">Cash</option>
                                                <option value="upi">UPI</option>
                                                <option value="card">Card</option>
                                                <option value="banking">Banking</option>
                                            </select>
                                            <label for="payment_method">Payment Method</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- File Upload Section -->
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <h6 class="text-secondary mb-3 fw-bold">
                                    <i class="ti ti-paperclip me-2"></i>Attachments
                                    <small class="text-muted fw-normal">(Optional)</small>
                                </h6>

                                <div class="border border-2 border-dashed rounded p-4 text-center bg-light">
                                    <div class="mb-3">
                                        <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                    </div>
                                    <label class="form-label fw-bold text-muted mb-2">Attach Receipt / Image</label>
                                    <input type="file" name="receipt" class="form-control"
                                        accept=".jpg,.jpeg,.png,.pdf">
                                    <small class="text-muted mt-2 d-block">
                                        <i class="ti ti-info-circle me-1"></i>
                                        Upload receipts, bills or related documents (Max 2MB per file)
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Footer -->
                <div class="modal-footer bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <small class="text-muted">
                            <i class="ti ti-shield-check me-1 text-success"></i>
                            Claim will be reviewed by finance team
                        </small>
                        <div>
                            <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
                                <i class="ti ti-x me-1"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-check me-1"></i>Submit Claim
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Update Expenditure Modal -->
    @foreach($claims as $claim)
    <!-- View Modal -->
    <div class="modal fade" id="viewClaimModal{{ $claim->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <!-- Enhanced Header -->
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="ti ti-eye fs-4"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0 fw-bold">Expenditure Claim Details</h5>
                            <small class="opacity-75">Claim ID: #{{ $claim->id }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Employee & Basic Info -->
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-transparent border-bottom">
                                    <h6 class="mb-0 text-primary fw-bold">
                                        <i class="ti ti-user-circle me-2"></i>Employee Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        
                                        <div class="col-sm-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-1">Claim Date:</label>
                                            <p class="mb-0 fs-6">
                                                <i class="ti ti-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($claim->claim_date)->format('d M Y') }}
                                            </p>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-1">Category:</label>
                                            <div>
                                                <span class="badge bg-secondary fs-6 px-3 py-2">{{ ucfirst(str_replace('_', ' ', $claim->category)) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-1">Status:</label>
                                            <div>
                                                @if($claim->status === 'Approved')
                                                <span class="badge bg-success fs-6 px-3 py-2">
                                                    <i class="ti ti-check me-1"></i>Approved
                                                </span>
                                                @elseif($claim->status === 'Rejected')
                                                <span class="badge bg-danger fs-6 px-3 py-2">
                                                    <i class="ti ti-x me-1"></i>Rejected
                                                </span>
                                                @else
                                                <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                                    <i class="ti ti-clock me-1"></i>Pending
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Details & Payment Method -->
                        <div class="col-md-6">
                            <div class="card border h-100">
                                <div class="card-header bg-transparent border-bottom">
                                    <h6 class="mb-0 text-success fw-bold">
                                        <i class="ti ti-currency-rupee me-2"></i>Financial Details
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted mb-1">Claim Amount:</label>
                                        <div class="d-flex align-items-center">
                                            <span class="h5 text-success mb-0 fw-bold">₹{{ number_format($claim->claim_amount, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted mb-1">Payment Method:</label>
                                        <div class="d-flex align-items-center">
                                            @php
                                            $paymentIcon = match(strtolower($claim->payment_method)) {
                                            'cash' => 'ti ti-cash',
                                            'upi' => 'ti ti-device-mobile',
                                            'card' => 'ti ti-credit-card',
                                            'banking' => 'ti ti-building-bank',
                                            default => 'ti ti-wallet'
                                            };
                                            @endphp
                                            <i class="{{ $paymentIcon }} me-2 text-info"></i>
                                            <span class="badge bg-light text-dark fs-6 px-3 py-1">{{ ucfirst($claim->payment_method) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attachments -->
                        @if($claim->receipt)
                        <div class="col-md-6">
                            <div class="card border h-100">
                                <div class="card-header bg-transparent border-bottom">
                                    <h6 class="mb-0 text-secondary fw-bold">
                                        <i class="ti ti-paperclip me-2"></i>Attachments
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        @php
                                        $receipts = is_array($claim->receipt) ? $claim->receipt : [$claim->receipt];
                                        @endphp
                                        @foreach($receipts as $receipt)
                                        @php
                                        $extension = strtolower(pathinfo($receipt, PATHINFO_EXTENSION));
                                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                        $filePath = asset('storage/receipts/'.$receipt);
                                        @endphp
                                        <div class="col-6">
                                            <div class="border rounded p-2 text-center bg-light">
                                                @if($isImage)
                                                <a href="{{ $filePath }}" target="_blank" class="text-decoration-none">
                                                    <img src="{{ $filePath }}" alt="Receipt" class="img-fluid rounded mb-1" style="max-height: 60px; object-fit: cover;">
                                                    <div class="small text-muted">View</div>
                                                </a>
                                                @else
                                                <a href="{{ $filePath }}" target="_blank" class="text-decoration-none">
                                                    <div class="mb-1">
                                                        @php
                                                        $fileIcon = match($extension) {
                                                        'pdf' => 'ti-file-type-pdf text-danger',
                                                        'doc', 'docx' => 'ti-file-word text-primary',
                                                        default => 'ti-file text-secondary'
                                                        };
                                                        @endphp
                                                        <i class="{{ $fileIcon }} fs-2"></i>
                                                    </div>
                                                    <div class="small text-muted">{{ strtoupper($extension) }}</div>
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Expense Description - Full Width -->
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-transparent border-bottom">
                                    <h6 class="mb-0 text-warning fw-bold">
                                        <i class="ti ti-file-text me-2"></i>Expense Description
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label fw-bold text-muted mb-1">Details:</label>
                                            <div class="border rounded p-3 bg-light">
                                                <p class="mb-0 text-break ">{{ $claim->description ?? 'No details provided' }}</p>
                                            </div>
                                        </div>
                                        @if($claim->comments)
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label fw-bold text-muted mb-1">Comments:</label>
                                            <div class="border rounded p-3 bg-light">
                                                <p class="mb-0 text-break">{{ $claim->comments }}</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Footer -->
                <div class="modal-footer bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <small class="text-muted">
                            <i class="ti ti-clock me-1"></i>
                            Submitted on {{ \Carbon\Carbon::parse($claim->created_at)->format('d M Y \a\t H:i') }}
                        </small>
                        <div>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="ti ti-x me-1"></i>Close
                            </button>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Edit Modal -->
    <div class="modal fade" id="editClaimModal{{ $claim->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content edit-claim-form border-0 shadow-lg" data-id="{{ $claim->id }}" enctype="multipart/form-data">
                @csrf
                <!-- Enhanced Header -->
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="ti ti-edit fs-4"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0 fw-bold">Edit Expenditure Claim</h5>
                            <small class="opacity-75">Claim ID: #{{ $claim->id }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Employee & Basic Info Section -->
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-transparent border-bottom">
                                    <h6 class="mb-0 text-primary fw-bold">
                                        <i class="ti ti-user-circle me-2"></i>Basic Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        
                                        <div class="col-md-12">
                                            <div class="form-floating">
                                                <input type="date" name="date" class="form-control" id="edit_date_{{ $claim->id }}" value="{{ $claim->claim_date }}" required>
                                                <label for="edit_date_{{ $claim->id }}">Date of Expenses</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Details Section -->
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-transparent border-bottom">
                                    <h6 class="mb-0 text-info fw-bold">
                                        <i class="ti ti-currency-rupee me-2"></i>Financial Details
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="number" name="claim_amount" class="form-control" id="edit_amount_{{ $claim->id }}"
                                                    step="0.01" min="0" value="{{ $claim->claim_amount }}" placeholder="0.00" required>
                                                <label for="edit_amount_{{ $claim->id }}">Expenses Amount (₹)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select name="payment_method" class="form-select" id="edit_payment_{{ $claim->id }}" required>
                                                    <option value="">Choose Method</option>
                                                    @foreach(['cash','upi','card','banking'] as $pm)
                                                    <option value="{{ $pm }}" {{ $claim->payment_method == $pm ? 'selected' : '' }}>{{ $pm }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="edit_payment_{{ $claim->id }}">Payment Method</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Category & Status Section -->
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-transparent border-bottom">
                                    <h6 class="mb-0 text-success fw-bold">
                                        <i class="ti ti-category me-2"></i>Category
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <div class="form-floating">
                                                <select name="category" class="form-select" id="edit_category_{{ $claim->id }}" required>
                                                <option value="">Choose Category</option>
                                                @foreach([
                                                    'travel' => 'Travel Expenses',
                                                    'accommodation' => 'Accommodation',
                                                    'meals' => 'Meals & Entertainment',
                                                    'office_supplies' => 'Office Supplies',
                                                    'communication' => 'Communication',
                                                    'training' => 'Training & Development',
                                                    'equipment' => 'Equipment & Technology',
                                                    'client_entertainment' => 'Client Entertainment',
                                                    'shipping' => 'Shipping & Delivery',
                                                    'marketing' => 'Marketing & Advertising',
                                                    'office_rent' => 'Office Rent & Utilities',
                                                    'employee_welfare' => 'Employee Welfare & Health',
                                                    'legal' => 'Legal and Professional Services',
                                                    'miscellaneous' => 'Miscellaneous'
                                                ] as $key => $cat)
                                                    <option value="{{ $key }}" {{ $claim->category == $key ? 'selected' : '' }}>
                                                        {{ $cat }}
                                                    </option>
                                                @endforeach
                                            </select>

                                                <label for="edit_category_{{ $claim->id }}">Expenses Category</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description & Comments Section -->
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-transparent border-bottom">
                                    <h6 class="mb-0 text-warning fw-bold">
                                        <i class="ti ti-file-text me-2"></i>Description
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <div class="form-floating">
                                                <textarea name="details" class="form-control" id="edit_details_{{ $claim->id }}"
                                                    style="height: 120px" placeholder="Describe your expenses..." required>{{ $claim->description }}</textarea>
                                                <label for="edit_details_{{ $claim->id }}">Expense Details</label>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-6">
                                            <div class="form-floating">
                                                <textarea name="comments" class="form-control" id="edit_comments_{{ $claim->id }}"
                                                    style="height: 120px" placeholder="Additional comments...">{{ $claim->comments }}</textarea>
                                                <label for="edit_comments_{{ $claim->id }}">Comments (Optional)</label>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- File Upload Section -->
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-transparent border-bottom">
                                    <h6 class="mb-0 text-secondary fw-bold">
                                        <i class="ti ti-paperclip me-2"></i>Attachments
                                        <small class="text-muted fw-normal">(Optional)</small>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="border border-2 border-dashed rounded p-3 text-center bg-light">
                                                <div class="mb-2">
                                                    <i class="ti ti-cloud-upload fs-2 text-muted"></i>
                                                </div>
                                                <label class="form-label fw-bold text-muted mb-2">Update Receipt / Image</label>
                                                <input type="file" name="receipt" class="form-control"
                                                    accept=".jpg,.jpeg,.png,.pdf">
                                                <input type="hidden" name="old_receipt" value="{{ $claim->receipt }}">
                                                <small class="text-muted mt-1 d-block">
                                                    <i class="ti ti-info-circle me-1"></i>
                                                    Leave empty to keep existing files
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Current Attachments -->
                                        @if($claim->receipt)
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-muted mb-2">Current Attachments:</label>
                                            <div class="border rounded p-3 bg-light">
                                                @php
                                                $receipts = is_array($claim->receipt) ? $claim->receipt : [$claim->receipt];
                                                @endphp
                                                @foreach($receipts as $receipt)
                                                @php
                                                $extension = strtolower(pathinfo($receipt, PATHINFO_EXTENSION));
                                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                                $filePath = asset('storage/receipts/'.$receipt);
                                                @endphp
                                                <div class="d-flex align-items-center mb-2 p-2 border rounded">
                                                    @if($isImage)
                                                    <img src="{{ $filePath }}" alt="Receipt" class="me-2 rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                    @php
                                                    $fileIcon = match($extension) {
                                                    'pdf' => 'ti-file-type-pdf text-danger',
                                                    'doc', 'docx' => 'ti-file-word text-primary',
                                                    default => 'ti-file text-secondary'
                                                    };
                                                    @endphp
                                                    <i class="{{ $fileIcon }} fs-3 me-2"></i>
                                                    @endif
                                                    <div class="flex-grow-1">
                                                        <small class="text-muted d-block">{{ basename($receipt) }}</small>
                                                        <a href="{{ $filePath }}" target="_blank" class="small text-primary">View File</a>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        
                    </div>
                </div>

                <!-- Enhanced Footer -->
                <div class="modal-footer bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <small class="text-muted">
                            <i class="ti ti-info-circle me-1"></i>
                            Changes will be saved immediately upon update
                        </small>
                        <div>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="ti ti-x me-1"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-warning text-dark ms-2">
                                <i class="ti ti-device-floppy me-1"></i>Update Claim
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    @endforeach
</div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.edit-claim-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const claimId = this.getAttribute('data-id');
                const formData = new FormData(this);

                fetch(`/User_employee_expenditure_claims/update/${claimId}`, {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status) {
                        showToast(res.message, "success");
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(res.message || "Update failed", "error");
                    }
                })
                .catch(() => showToast("Server error", "error"));
            });
        });
    });

    //---- Add the Expenditure Claims -----
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector("#addExpenditureModal form");

        form.addEventListener("submit", function(e) {
            e.preventDefault();

            let formData = new FormData(form);

            fetch("{{ route('userEmployeeExpenditure.store') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        showToast(data.message, "success");
                        form.reset();
                        let modal = bootstrap.Modal.getInstance(document.getElementById('addExpenditureModal'));
                        modal.hide();
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        // If validation error
                        if (typeof data.message === "object") {
                            Object.values(data.message).forEach(msgArr => {
                                msgArr.forEach(msg => showToast(msg, "error"));

                            });
                        } else {
                            showToast(data.message, "error");
                        }
                    }
                })
                .catch(err => {
                    showToast("Something went wrong!", "error");
                    console.error(err);
                });
        });
    });
</script>