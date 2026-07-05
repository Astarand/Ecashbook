@extends('App.Layout')

@section('container')

<div class="pc-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">HR & Payroll Management</a></li>
                        <li class="breadcrumb-item"><a href="#">HR, Payroll & Attendance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Holiday Calendar</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-holiday-list-alt-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Holiday Summary</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mt-4 g-4">
        <!-- Total Holidays -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 opacity-25">
                    <i class="fas fa-calendar-alt" style="font-size: 8rem; color: #667eea; transform: rotate(-15deg);"></i>
                </div>
                <div class="card-body text-center p-4 position-relative" style="z-index: 1;">
                    <h1 class="display-3 fw-bold text-primary mb-2">
                        {{ $holidays->filter(fn($h) => \Carbon\Carbon::parse($h->holidayDate)->year == date('Y'))->count() }}
                    </h1>
                    <h6 class="text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1.5px;">Total Holidays</h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ date('Y') }}</span>
                </div>
            </div>
        </div>

        <!-- National Holidays -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 opacity-25">
                    <i class="fas fa-flag" style="font-size: 8rem; color: #dc3545; transform: rotate(-15deg);"></i>
                </div>
                <div class="card-body text-center p-4 position-relative" style="z-index: 1;">
                    <h1 class="display-3 fw-bold text-danger mb-2">
                        {{ $holidays->filter(fn($h) => $h->holidayType === 'National' && \Carbon\Carbon::parse($h->holidayDate)->year == date('Y'))->count() }}
                    </h1>
                    <h6 class="text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1.5px;">National Holidays</h6>
                    <span class="badge bg-danger bg-opacity-10 text-danger">Government</span>
                </div>
            </div>
        </div>

        <!-- Festival Holidays -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 opacity-25">
                    <i class="fas fa-gifts" style="font-size: 8rem; color: #ffc107; transform: rotate(-15deg);"></i>
                </div>
                <div class="card-body text-center p-4 position-relative" style="z-index: 1;">
                    <h1 class="display-3 fw-bold text-warning mb-2">
                        {{ $holidays->filter(fn($h) => $h->holidayType === 'Festival' && \Carbon\Carbon::parse($h->holidayDate)->year == date('Y'))->count() }}
                    </h1>
                    <h6 class="text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1.5px;">Festival Holidays</h6>
                    <span class="badge bg-warning bg-opacity-10 text-warning">Cultural</span>
                </div>
            </div>
        </div>

        <!-- Company Holidays -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 opacity-25">
                    <i class="fas fa-building" style="font-size: 8rem; color: #0dcaf0; transform: rotate(-15deg);"></i>
                </div>
                <div class="card-body text-center p-4 position-relative" style="z-index: 1;">
                    <h1 class="display-3 fw-bold text-info mb-2">
                        {{ $holidays->filter(fn($h) => $h->holidayType === 'Company' && \Carbon\Carbon::parse($h->holidayDate)->year == date('Y'))->count() }}
                    </h1>
                    <h6 class="text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1.5px;">Company Holidays</h6>
                    <span class="badge bg-info bg-opacity-10 text-info">Organization</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Holiday Table -->
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-list me-2 text-primary"></i>Holiday List - {{ $currentYear }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table tbl-product align-middle" id="pc-dt-simple">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Holiday Name</th>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($holidays as $index => $holiday)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $holiday->holidayName }}</td>
                                <td>{{ \Carbon\Carbon::parse($holiday->holidayDate)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($holiday->holidayDate)->format('l') }}</td>
                                <td>
                                    <span class="badge 
                                        @if($holiday->holidayType === 'National') bg-danger 
                                        @elseif($holiday->holidayType === 'Festival') bg-warning 
                                        @elseif($holiday->holidayType === 'Company') bg-info 
                                        @else bg-secondary 
                                        @endif">
                                        {{ $holiday->holidayType }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No holidays found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@section('page-script')
<script>
    function startHolidayListAltTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Holiday Calendar List Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">List official company off-days, holidays, and weekend schedules.</p></div>'
                },
                {
                    title: 'Holiday Calendar List',
                    intro: 'List official company off-days, holidays, and weekend schedules.'
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
        $('#start-holiday-list-alt-tour').on('click', function(e) {
            e.preventDefault();
            startHolidayListAltTour();
        });
    });
</script>
@endsection

