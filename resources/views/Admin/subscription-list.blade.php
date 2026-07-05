@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Subscription Plans</li>
                    </ul>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('admin.subscription-create') }}" class="btn btn-primary">
                        <i class="ph-duotone ph-plus-circle me-2"></i> Add a New Plan
                    </a>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Subscription Plans</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- Plan Cards -->
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card">
                @foreach ($subscriptions as $plan)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="plan-icon bg-light-primary rounded p-3 me-3">
                                        <i class="{{ $plan['icon'] }} fs-4 text-primary"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1">{{ $plan['title'] }}</h4>
                                        <p class="mb-0 text-muted">₹{{ number_format($plan['monthly_price'], 2) }}/month, ₹{{ number_format($plan['yearly_price'], 2) }}/year, CA Percentage: {{ $plan['ca_percentage'] }}%</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="row text-center">
                                    <div class="col-6 border-end">
                                        <h5 class="mb-1">{{ $plan['total_subscribers'] }}</h5> {{-- Replace with dynamic subscription count if available --}}
                                        <p class="mb-0 text-muted small">Subscriptions</p>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="mb-1">₹{{ number_format($plan['total_revenue'],2) }}</h5> {{-- Replace with dynamic revenue if available --}}
                                        <p class="mb-0 text-muted small">Revenue</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.view-plan', ['id' => base64_encode($plan['id'])]) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-eye me-1"></i> View
                                    </a>
                                    <a href="{{ route('admin.edit-plan', ['id' => base64_encode($plan['id'])]) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="ti ti-edit me-1"></i> Edit
                                    </a>
                                    <button 
                                        class="btn btn-sm toggle-status-btn {{ $plan['status'] ? 'btn-success' : 'btn-outline-secondary' }}" 
                                        data-id="{{ $plan['id'] }}">
                                        <i class="ti ti-check me-1"></i> 
                                        {{ $plan['status'] ? 'Active' : 'Inactive' }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Optional: Show feature list under each plan --}}
                        @if (!empty($plan['features']))
                        <div class="mt-3">
                            <h6 class="text-muted">Features:</h6>
                            <ul class="mb-0">
                                @foreach ($plan['features'] as $feature)
                                    <li>{{ $feature['name'] }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach

            </div>
        </div>
</div>

@section('page-script')
<script>
    $(document).on('click', '.toggle-status-btn', function () {
    const button = $(this);
    const id = button.data('id');

    $.ajax({
        url: '{{ route("subscription.toggle-status") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            id: id
        },
        success: function (response) {
            if (response.status === 'success') {
                // Update button class and text
                button
                    .removeClass('btn-success btn-outline-secondary')
                    .addClass(response.class)
                    .html(`<i class="ti ti-check me-1"></i> ${response.new_status}`);
            } else {
                alert(response.message);
            }
        },
        error: function () {
            alert('Something went wrong!');
        }
    });
});


</script>
@endsection

@endsection