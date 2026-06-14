    @extends('App.Layout')

    @section('container')
    <div class="pc-content">
        <!-- Breadcrumb -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <ul class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">HR & Payroll Management</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Purchase Requisitions</li>
                            </ul>
                            <a href="javascript:void(0);" id="start-supply-requisitions-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                                <u>How does this Page works?</u>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="page-header-title">
                            <h2 class="mb-0">Purchase Requisition</h2>
                        </div>
                    </div>
                    <div class="col-md-8 text-end mt-2">
                        <a href="#" class="btn btn-primary tour-add-requisition" data-bs-toggle="modal" data-bs-target="#addRequisitionModal"><i class="ti ti-square-plus"></i> Add New Purchase Requisition</a>
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
                                    <th>Employee</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                    <th>Priority</th>
                                    <th>Details</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requisitions as $i => $req)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($req->requisition_date)->format('d M Y') }}</td>
                                    <td>{{ $req->employee_name }}</td>
                                    <td>{{ $req->category }}</td>
                                    <td>{{ $req->quantity }}</td>
                                    <td>{{ $req->amount }}</td>
                                    <td>{{ $req->priority }}</td>
                                    <td>{{ $req->details }}</td>
                                    <td>
                                        {{-- ✅ Status badge --}}
                                        @if($req->status === 'Approved')
                                        <span class=" badge bg-success">Approved</span>
                                        @elseif($req->status === 'Rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                        @else
                                        <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-light-primary tour-requisition-actions" data-bs-toggle="modal" data-bs-target="#viewRequisitionModal{{ $req->id }}"><i class="ti ti-eye"></i></a>
                                        <a href="#" class="btn btn-sm btn-light-warning" data-bs-toggle="modal" data-bs-target="#editRequisitionModal{{ $req->id }}"><i class="ti ti-edit"></i></a>
                                        {{-- <a href="#" class="btn btn-sm btn-light-danger"><i class="ti ti-trash"></i></a> --}}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No requisitions submitted yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Add Requisition Modal -->
        <div class="modal fade" id="addRequisitionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form class="modal-content border-0 shadow-lg" method="POST" action="{{ route('requisition.store') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Enhanced Header -->
                    <div class="modal-header">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="ti ti-shopping-cart fs-4"></i>
                            </div>
                            <div>
                                <h5 class="modal-title mb-0 fw-bold">New Purchase Requisition</h5>
                                <small class="opacity-75">Submit your purchase request</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <!-- Employee & Date Section -->
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-transparent border-bottom">
                                        <h6 class="mb-0 text-primary fw-bold">
                                            <i class="ti ti-user-circle me-2"></i>Requestor Information
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <select name="employee_id" class="form-select" id="req_employee" required>
                                                        <option value="">Choose Employee</option>
                                                        @foreach($employees as $emp)
                                                        <option value="{{ $emp->employee_id }}">
                                                            {{ $emp->employee_name }} ({{ $emp->employee_id }})
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="req_employee">Employee</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="date" name="date" class="form-control" id="req_date" required>
                                                    <label for="req_date">Date of Requisition</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Category & Priority Section -->
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-transparent border-bottom">
                                        <h6 class="mb-0 text-success fw-bold">
                                            <i class="ti ti-category me-2"></i>Category & Priority
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <div class="form-floating">
                                                    <select name="category" class="form-select" id="req_category" required>
                                                        <option value="">Choose Category</option>
                                                        <option value="office_supplies">Office Supplies</option>
                                                        <option value="technology">Technology & Gadgets</option>
                                                        <option value="furniture">Furniture & Fixtures</option>
                                                        <option value="stationery">Stationery & Printing Materials</option>
                                                        <option value="uniforms">Uniforms & Apparel</option>
                                                        <option value="breakroom">Breakroom Supplies</option>
                                                        <option value="software">Software & Licenses</option>
                                                        <option value="ppe">Personal Protective Equipment (PPE)</option>
                                                        <option value="marketing">Marketing Materials</option>
                                                        <option value="decor">Office Décor</option>
                                                        <option value="travel">Travel & Event Materials</option>
                                                        <option value="gifts">Client Gifts & Corporate Merchandise</option>
                                                        <option value="cleaning">Cleaning & Maintenance Supplies</option>
                                                        <option value="wellness">Health & Wellness Products</option>
                                                        <option value="miscellaneous">Miscellaneous/Other Materials</option>
                                                    </select>
                                                    <label for="req_category">Requisition Category</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <select name="priority" class="form-select" id="req_priority" required>
                                                        <option value="">Choose Priority</option>
                                                        <option value="Top Priority">Top Priority</option>
                                                        <option value="Normal Priority">Normal Priority</option>
                                                        {{-- <option value="low">Low Priority</option> --}}
                                                    </select>
                                                    <label for="req_priority">Approval Priority</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Item Details Section -->
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-transparent border-bottom">
                                        <h6 class="mb-0 text-info fw-bold">
                                            <i class="ti ti-file-text me-2"></i>Item Details
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="form-floating">
                                                    <textarea name="details" class="form-control" id="req_details"
                                                        style="height: 100px" placeholder="Describe the items you need..." required></textarea>
                                                    <label for="req_details">Requisition Details</label>
                                                </div>
                                                <small class="text-muted mt-1 d-block">
                                                    <i class="ti ti-info-circle me-1"></i>
                                                    Please provide detailed specifications, brand preferences, or specific requirements
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quantity & Amount Section -->
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-transparent border-bottom">
                                        <h6 class="mb-0 text-warning fw-bold">
                                            <i class="ti ti-calculator me-2"></i>Quantity & Budget
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="number" name="quantity" class="form-control" id="req_quantity"
                                                        min="1" placeholder="0" required>
                                                    <label for="req_quantity">Quantity</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="number" name="amount" class="form-control" id="req_amount"
                                                        step="0.01" min="0" placeholder="0.00" required>
                                                    <label for="req_amount">Estimated Amount (₹)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="text" name="return_exchange" class="form-control" id="req_return"
                                                        placeholder="Return/exchange details (if any)">
                                                    <label for="req_return">Return/Exchange</label>
                                                </div>
                                            </div>
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
                                        <div class="border border-2 border-dashed rounded p-4 text-center bg-light">
                                            <div class="mb-3">
                                                <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                            </div>
                                            <label class="form-label fw-bold text-muted mb-2">Attach Reference Images</label>
                                            <input type="file" name="attachment" class="form-control" accept=".jpg,.jpeg,.png,.pdf">

                                            <small class="text-muted mt-2 d-block">
                                                <i class="ti ti-info-circle me-1"></i>
                                                Upload product images, catalogs, or specification documents (Max 5MB per file)
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Comments Section -->
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-transparent border-bottom">
                                        <h6 class="mb-0 text-dark fw-bold">
                                            <i class="ti ti-message-circle me-2"></i>Additional Information
                                            <small class="text-muted fw-normal">(Optional)</small>
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-floating">
                                            <textarea name="comments" class="form-control" id="req_comments"
                                                style="height: 80px" placeholder="Any additional comments or special instructions..."></textarea>
                                            <label for="req_comments">Comments</label>
                                        </div>
                                        <small class="text-muted mt-1 d-block">
                                            <i class="ti ti-lightbulb me-1"></i>
                                            Include any special delivery requirements, usage timeline, or approval justification
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
                                Requisition will be reviewed by procurement team
                            </small>
                            <div>
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="ti ti-x me-1"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-primary ms-2">
                                    <i class="ti ti-send me-1"></i>Submit Requisition
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        @foreach($requisitions as $req)
        <!-- Enhanced View Requisition Modal -->
        <div class="modal fade" id="viewRequisitionModal{{ $req->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <!-- Enhanced Header -->
                    <div class="modal-header">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="ti ti-eye fs-4"></i>
                            </div>
                            <div>
                                <h5 class="modal-title mb-0 fw-bold">Supply Requisition Details</h5>
                                <small class="opacity-75">Requisition ID: #{{ $req->id }}</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <!-- Basic Information -->
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-transparent border-bottom">
                                        <h6 class="mb-0 text-primary fw-bold">
                                            <i class="ti ti-user-circle me-2"></i>Requisition Information
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label fw-bold text-muted mb-1">Employee Name:</label>
                                                <p class="mb-0 fs-6">{{ $req->employee_name ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label fw-bold text-muted mb-1">Requisition Date:</label>
                                                <p class="mb-0 fs-6">
                                                    <i class="ti ti-calendar me-1"></i>
                                                    {{ \Carbon\Carbon::parse($req->requisition_date)->format('d M Y') }}
                                                </p>
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label fw-bold text-muted mb-1">Category:</label>
                                                <div>
                                                    <span class="badge bg-secondary fs-6 px-3 py-2">{{ ucfirst(str_replace('_', ' ', $req->category)) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label fw-bold text-muted mb-1">Status:</label>
                                                <div>
                                                    @if($req->status === 'Approved')
                                                    <span class="badge bg-success fs-6 px-3 py-2">
                                                        <i class="ti ti-check me-1"></i>Approved
                                                    </span>
                                                    @elseif($req->status === 'Rejected')
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

                            <!-- Quantity, Amount & Priority -->
                            <div class="col-md-6">
                                <div class="card border h-100">
                                    <div class="card-header bg-transparent border-bottom">
                                        <h6 class="mb-0 text-success fw-bold">
                                            <i class="ti ti-calculator me-2"></i>Quantity & Budget
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted mb-1">Quantity:</label>
                                            <div class="d-flex align-items-center">
                                                <span class="h6 text-primary mb-0 fw-bold">{{ $req->quantity }}</span>
                                                <span class="text-muted ms-2">units</span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted mb-1">Estimated Amount:</label>
                                            <div class="d-flex align-items-center">
                                                <span class="h5 text-success mb-0 fw-bold">₹{{ number_format($req->amount, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label fw-bold text-muted mb-1">Priority:</label>
                                            <div class="d-flex align-items-center">
                                                @php
                                                $priorityClass = match(strtolower($req->priority)) {
                                                'high', 'top priority' => 'bg-danger',
                                                'normal', 'normal priority' => 'bg-warning text-dark',
                                                'low' => 'bg-info',
                                                default => 'bg-secondary'
                                                };
                                                $priorityIcon = match(strtolower($req->priority)) {
                                                'high', 'top priority' => 'ti ti-alert-triangle',
                                                'normal', 'normal priority' => 'ti ti-clock',
                                                'low' => 'ti ti-arrow-down',
                                                default => 'ti ti-minus'
                                                };
                                                @endphp
                                                <i class="{{ $priorityIcon }} me-2"></i>
                                                <span class="badge {{ $priorityClass }}">{{ ucfirst($req->priority) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Attachments -->
                            @if($req->attachment)
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
                                            $attachments = is_array($req->attachment) ? $req->attachment : [$req->attachment];
                                            @endphp
                                            @foreach($attachments as $attachment)
                                            @php
                                            $extension = strtolower(pathinfo($attachment, PATHINFO_EXTENSION));
                                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                            $filePath = asset('storage/attachments/'.$attachment);
                                            @endphp
                                            <div class="col-6">
                                                <div class="border rounded p-2 text-center bg-light">
                                                    @if($isImage)
                                                    <a href="{{ $filePath }}" target="_blank" class="text-decoration-none">
                                                        <img src="{{ $filePath }}" alt="Attachment" class="img-fluid rounded mb-1" style="max-height: 60px; object-fit: cover;">
                                                        <div class="small text-muted">View</div>
                                                    </a>
                                                    @else
                                                    <a href="{{ $filePath }}" target="_blank" class="text-decoration-none">
                                                        <div class="mb-1">
                                                            @php
                                                            $fileIcon = match($extension) {
                                                            'pdf' => 'ti ti-file-type-pdf text-danger',
                                                            'doc', 'docx' => 'ti ti-file-word text-primary',
                                                            default => 'ti ti-file text-secondary'
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

                            <!-- Requisition Details -->
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-transparent border-bottom">
                                        <h6 class="mb-0 text-warning fw-bold">
                                            <i class="ti ti-file-text me-2"></i>Requisition Details
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted mb-1">Item Details:</label>
                                                <div class="border rounded p-3 bg-light">
                                                    <p class="mb-0 text-break">{{ $req->details ?? 'No details provided' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted mb-1">Return/Exchange:</label>
                                                <div class="border rounded p-3 bg-light">
                                                    <p class="mb-0 text-break">
                                                        {{ $req->return_exchange ?: 'No return/exchange specified' }}
                                                    </p>
                                                </div>
                                            </div>
                                            @if($req->comments)
                                            <div class="col-12">
                                                <label class="form-label fw-bold text-muted mb-1">Additional Comments:</label>
                                                <div class="border rounded p-3 bg-light">
                                                    <p class="mb-0 text-break">{{ $req->comments }}</p>
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
                                Submitted on {{ \Carbon\Carbon::parse($req->created_at)->format('d M Y \a\t H:i') }}
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


        <!-- Enhanced Edit Requisition Modal -->
        <div class="modal fade" id="editRequisitionModal{{ $req->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form class="modal-content edit-requisition-form border-0 shadow-lg" data-id="{{ $req->id }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Enhanced Header -->
                    <div class="modal-header">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="ti ti-edit fs-4"></i>
                            </div>
                            <div>
                                <h5 class="modal-title mb-0 fw-bold">Edit Supply Requisition</h5>
                                <small class="opacity-75">Requisition ID: #{{ $req->id }}</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <!-- Employee & Date Section -->
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-transparent border-bottom">
                                        <h6 class="mb-0 text-primary fw-bold">
                                            <i class="ti ti-user-circle me-2"></i>Requestor Information
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <select name="employee_id" class="form-select" id="edit_req_employee_{{ $req->id }}" required>
                                                        <option value="">Choose Employee</option>
                                                        @foreach($employees as $emp)
                                                        <option value="{{ $emp->employee_id }}" {{ $emp->employee_id == $req->employee_id ? 'selected' : '' }}>
                                                            {{ $emp->employee_name }} ({{ $emp->employee_id }})
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="edit_req_employee_{{ $req->id }}">Employee</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="date" name="date" class="form-control" id="edit_req_date_{{ $req->id }}" value="{{ $req->requisition_date }}" required>
                                                    <label for="edit_req_date_{{ $req->id }}">Date of Requisition</label>
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
                                            <i class="ti ti-category me-2"></i>Category & Status
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <div class="form-floating">
                                                    <select name="category" class="form-select" id="edit_req_category_{{ $req->id }}" required>
                                                        <option value="">Choose Category</option>
                                                        @foreach([
                                                            'office_supplies' => 'Office Supplies',
                                                            'technology' => 'Technology & Gadgets',
                                                            'furniture' => 'Furniture & Fixtures',
                                                            'stationery' => 'Stationery & Printing Materials',
                                                            'uniforms' => 'Uniforms & Apparel',
                                                            'breakroom' => 'Breakroom Supplies',
                                                            'software' => 'Software & Licenses',
                                                            'ppe' => 'Personal Protective Equipment (PPE)',
                                                            'marketing' => 'Marketing Materials',
                                                            'decor' => 'Office Décor',
                                                            'travel' => 'Travel & Event Materials',
                                                            'gifts' => 'Client Gifts & Corporate Merchandise',
                                                            'cleaning' => 'Cleaning & Maintenance Supplies',
                                                            'wellness' => 'Health & Wellness Products',
                                                            'miscellaneous' => 'Miscellaneous/Other Materials'
                                                        ] as $key => $cat)
                                                            <option value="{{ $key }}" {{ $req->category == $key ? 'selected' : '' }}>
                                                            {{ $cat }}
                                                            </option>
                                                        @endforeach
                                                        </select>

                                                    <label for="edit_req_category_{{ $req->id }}">Requisition Category</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <select name="status" class="form-select" id="edit_req_status_{{ $req->id }}" required>
                                                        <option value="Pending" {{ $req->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="Approved" {{ $req->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                                        <option value="Rejected" {{ $req->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                                    </select>
                                                    <label for="edit_req_status_{{ $req->id }}">Status</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Item Details Section -->
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-transparent border-bottom">
                                        <h6 class="mb-0 text-info fw-bold">
                                            <i class="ti ti-file-text me-2"></i>Item Details
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-floating">
                                            <textarea name="details" class="form-control" id="edit_req_details_{{ $req->id }}"
                                                style="height: 100px" placeholder="Describe the items you need..." required>{{ $req->details }}</textarea>
                                            <label for="edit_req_details_{{ $req->id }}">Requisition Details</label>
                                        </div>
                                        <small class="text-muted mt-2 d-block">
                                            <i class="ti ti-info-circle me-1"></i>
                                            Please provide detailed specifications, brand preferences, or specific requirements
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Quantity, Amount & Priority Section -->
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-transparent border-bottom">
                                        <h6 class="mb-0 text-warning fw-bold">
                                            <i class="ti ti-calculator me-2"></i>Quantity, Budget & Priority
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="number" name="quantity" class="form-control" id="edit_req_quantity_{{ $req->id }}"
                                                        min="1" value="{{ $req->quantity }}" placeholder="0" required>
                                                    <label for="edit_req_quantity_{{ $req->id }}">Quantity</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="number" name="amount" class="form-control" id="edit_req_amount_{{ $req->id }}"
                                                        step="0.01" min="0" value="{{ $req->amount }}" placeholder="0.00" required>
                                                    <label for="edit_req_amount_{{ $req->id }}">Estimated Amount (₹)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <select name="priority" class="form-select" id="edit_req_priority_{{ $req->id }}" required>
                                                        <option value="">Choose Priority</option>
                                                        <option value="Top Priority" {{ $req->priority == 'Top Priority' ? 'selected' : '' }}>High Priority</option>
                                                        <option value="Normal Priority" {{ $req->priority == 'Normal Priority' ? 'selected' : '' }}>Normal Priority</option>
                                                        {{-- <option value="low">Low Priority</option> --}}
                                                    </select>
                                                    <label for="edit_req_priority_{{ $req->id }}">Approval Priority</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Return/Exchange & Comments Section -->
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-transparent border-bottom">
                                        <h6 class="mb-0 text-secondary fw-bold">
                                            <i class="ti ti-message-circle me-2"></i>Additional Information
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <div class="form-floating">
                                                    <input type="text" name="return_exchange" class="form-control" id="edit_req_return_{{ $req->id }}"
                                                        value="{{ $req->return_exchange }}" placeholder="Return/exchange details (if any)">
                                                    <label for="edit_req_return_{{ $req->id }}">Return/Exchange</label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-floating">
                                                    <textarea name="comments" class="form-control" id="edit_req_comments_{{ $req->id }}"
                                                        style="height: 80px" placeholder="Additional comments...">{{ $req->comments }}</textarea>
                                                    <label for="edit_req_comments_{{ $req->id }}">Comments (Optional)</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- File Upload Section -->
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-transparent border-bottom">
                                        <h6 class="mb-0 text-dark fw-bold">
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
                                                    <label class="form-label fw-bold text-muted mb-2">Update Attachment</label>
                                                    <input type="file" name="attachment" class="form-control"
                                                        accept=".jpg,.jpeg,.png,.pdf">
                                                    <input type="hidden" name="old_attachment" value="{{ $req->attachment }}">
                                                    <small class="text-muted mt-1 d-block">
                                                        <i class="ti ti-info-circle me-1"></i>
                                                        Leave empty to keep existing files
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- Current Attachments -->
                                            @if($req->attachment)
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-muted mb-2">Current Attachments:</label>
                                                <div class="border rounded p-3 bg-light">
                                                    @php
                                                    $attachments = is_array($req->attachment) ? $req->attachment : [$req->attachment];
                                                    @endphp
                                                    @foreach($attachments as $attachment)
                                                    @php
                                                    $extension = strtolower(pathinfo($attachment, PATHINFO_EXTENSION));
                                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                                    $filePath = asset('storage/attachments/'.$attachment);
                                                    @endphp
                                                    <div class="d-flex align-items-center mb-2 p-2 border rounded">
                                                        @if($isImage)
                                                        <img src="{{ $filePath }}" alt="Attachment" class="me-2 rounded" style="width: 40px; height: 40px; object-fit: cover;">
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
                                                            <small class="text-muted d-block">{{ basename($attachment) }}</small>
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
                                    <i class="ti ti-device-floppy me-1"></i>Update Requisition
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

@section('page-script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form[action='{{ route('requisition.store') }}']");

            form.addEventListener("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(form);

                fetch("{{ route('requisition.store') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, "success");
                        form.reset();
                        const modal = bootstrap.Modal.getInstance(document.getElementById("addRequisitionModal"));
                        modal.hide();
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else if (data.errors) {
                        showToast(Object.values(data.errors)[0][0], "error");
                    } else {
                        showToast(data.message || "Something went wrong", "error");
                    }
                })
                .catch(() => showToast("Server error", "error"));
            });


            // Edit requisition AJAX
            document.querySelectorAll('.edit-requisition-form').forEach(function(form){
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    let reqId = this.getAttribute('data-id');
                    let formData = new FormData(this);

                    fetch("{{ url('/supply-requisitions/update') }}/" + reqId, {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(res => {
                        if(res.success){
                            showToast(res.message, "success");
                            setTimeout(()=>location.reload(), 1200);
                        } else {
                            showToast(res.message || "Update failed", "error");
                        }
                    })
                    .catch(() => showToast("Server error", "error"));
                });
            });
        });

        // --- Interactive Tour ---
        function startSupplyRequisitionsTour() {
            if (typeof introJs !== 'function') return;

            introJs().setOptions({
                steps: [
                    {
                        title: 'Purchase Requisitions Guide',
                        intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-shopping-cart" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Request new office supplies, technological equipment, or inventory materials.</p></div>'
                    },
                    {
                        element: '.tour-add-requisition',
                        title: 'Add Purchase Requisition',
                        intro: 'Click here to submit a new requisition request, specify items, quantity, budget, and attach catalogs.'
                    },
                    {
                        element: '#pc-dt-simple',
                        title: 'Requisitions Table',
                        intro: 'Track submission dates, requisition categories, priority, and current status of all requests.'
                    },
                    {
                        element: '.tour-requisition-actions',
                        title: 'View Requisition',
                        intro: 'Click here to view detailed specifications, comments, and reference documents for this request.',
                        position: 'left'
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
            $('#start-supply-requisitions-tour').on('click', function(e) {
                e.preventDefault();
                startSupplyRequisitionsTour();
            });
        });
    </script>
@endsection
