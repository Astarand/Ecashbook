@extends('App.Layout')
@section('container')

<div class="pc-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item"><a href="/employee/hr-letters">HR Letters</a></li>
                        <li class="breadcrumb-item" aria-current="page">View HR Letter</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">View HR Letter</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">

                    <!-- Letter Header -->
                    <div class="border-bottom pb-3 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-2 text-primary">
                                    <i class="ti ti-mail me-2"></i>
                                    Subject: {{ $letter->subject }}
                                </h4>
                                <p class="text-muted mb-1">
                                    <small>
                                        <strong>From:</strong> HR Department
                                    </small>
                                </p>
                                <p class="text-muted mb-1">
                                    <small>
                                        <strong>To:</strong> {{ Auth::user()->name }}
                                    </small>
                                </p>
                                <p class="text-muted mb-0">
                                    <small>
                                        <strong>Date:</strong>
                                        {{ \Carbon\Carbon::parse($letter->sent_at)->format('F d, Y h:i A') }}
                                    </small>
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="badge bg-light-success text-success px-3 py-2">
                                    <i class="ti ti-circle-check me-1"></i>
                                    Official Letter
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Letter Body -->
                    <div class="border rounded p-4 mb-4 bg-light">
                        {!! $letter->content !!}
                    </div>

                    <!-- Actions -->
                    <div class="border-top pt-3 mt-4">
                        <div class="d-flex justify-content-between">
                            <a href="{{ url('/employee/hr-letters') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back to Letters
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
