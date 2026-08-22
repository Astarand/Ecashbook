@extends('App.Layout')

@section('container')
<div class="pc-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.EmployeePolicyList') }}">Employment Policies</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Policy Log</li>
                    </ul>
                </div>
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h2 class="mb-0">View Policy Log</h2>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('user.EmployeePolicyList') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Back to Policies
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table table-hover tbl-product" id="pc-dt-simple">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Created Date</th>
                                <th>Updated Date</th>
                                <th>Policy</th>
                                <th>Action</th>
                                <th>Previous Content</th>
                                <th>Updated Content</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($policyLogs as $index => $log)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $log->created_at ? \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i') : 'N/A' }}</td>
                                <td>{{ $log->updated_at ? \Carbon\Carbon::parse($log->updated_at)->format('d-m-Y H:i') : 'N/A' }}</td>
                                <td>{{ $log->title }}</td>
                                <td>
                                    <span class="badge {{ $log->action === 'created' ? 'bg-success' : 'bg-primary' }}">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td>
                                    @if(filled($log->old_data))
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#policyContentModal" data-content-target="#old-policy-content-{{ $log->id }}">
                                        <i class="ti ti-eye me-1"></i> View
                                    </button>
                                    <div id="old-policy-content-{{ $log->id }}" class="d-none">{!! $log->old_data !!}</div>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if(filled($log->new_data))
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#policyContentModal" data-content-target="#new-policy-content-{{ $log->id }}">
                                        <i class="ti ti-eye me-1"></i> View
                                    </button>
                                    <div id="new-policy-content-{{ $log->id }}" class="d-none">{!! $log->new_data !!}</div>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No policy updates found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="policyContentModal" tabindex="-1" aria-labelledby="policyContentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="policyContentModalLabel">Policy Content</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="policyContentModalBody"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    $('#policyContentModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const contentTarget = button.data('content-target');
        const content = contentTarget ? $(contentTarget).html() : '';

        $('#policyContentModalBody').html(content || '<span class="text-muted">No content available.</span>');
    });
</script>
@endsection
