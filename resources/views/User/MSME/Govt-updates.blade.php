@extends('App.Layout')

@section('container')
<div class="pc-content">
    <div class="page-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center text-white">
                        <div class="d-flex align-items-center">
                            <div class="header-icon-box bg-white bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="ph-duotone ph-newspaper fs-1 text-white"></i>
                            </div>
                            <div>
                                <h3 class="mb-1 fw-bold text-white">MSME Government Updates</h3>
                                <p class="mb-0 opacity-75 small">Stay informed with the latest notifications, circulars, and policy updates issued by regulatory bodies</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTERS BAR --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="ph-duotone ph-funnel text-muted"></i></span>
                                <input type="text" id="updateSearch" class="form-control bg-light border-0" placeholder="Filter announcements..." style="border-radius: 0 6px 6px 0;">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex flex-wrap gap-2 justify-content-md-end" id="typeFilters">
                                <button class="btn btn-sm btn-primary type-filter px-3" data-type="all">All Updates</button>
                                <button class="btn btn-sm btn-light-secondary type-filter px-3" data-type="circular">Official Circulars</button>
                                <button class="btn btn-sm btn-light-secondary type-filter px-3" data-type="tax">Tax & GST</button>
                                <button class="btn btn-sm btn-light-secondary type-filter px-3" data-type="compliance">Compliance Concessions</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FEED CARDS LIST --}}
    <div class="row" id="updatesContainer">
        {{-- Card 1 --}}
        <div class="col-12 mb-3 update-item" data-type="tax">
            <div class="card border-0 shadow-sm rounded-3 hover-lift border-start border-4 border-info">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light-info text-info fw-semibold px-3 py-2 rounded-pill small">Tax & GST</span>
                            <span class="text-muted small"><i class="ph ph-calendar me-1"></i> July 02, 2026</span>
                        </div>
                        <span class="text-secondary small fw-semibold"><i class="ph ph-bank me-1"></i> Ministry of Finance</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2 update-title">Extension of GST Annual Return GSTR-9 Filing Timelines for Small Enterprises</h5>
                    <p class="text-muted small mb-3">The Central Board of Indirect Taxes and Customs (CBIC) announced a three-month compliance filing extension for small businesses having turnover under ₹5 Crores to ease filing congestions.</p>
                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-2">
                        <span class="text-muted small"><i class="ph ph-shield-check text-success me-1"></i> Verified Policy Update</span>
                        <a href="#" class="btn btn-link text-primary btn-sm fw-semibold p-0"><i class="ph ph-download-simple me-1"></i> Circular PDF</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2 --}}
        <div class="col-12 mb-3 update-item" data-type="circular">
            <div class="card border-0 shadow-sm rounded-3 hover-lift border-start border-4 border-primary">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light-primary text-primary fw-semibold px-3 py-2 rounded-pill small">Official Circular</span>
                            <span class="text-muted small"><i class="ph ph-calendar me-1"></i> June 25, 2026</span>
                        </div>
                        <span class="text-secondary small fw-semibold"><i class="ph ph-bank me-1"></i> Ministry of MSME</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2 update-title">Udyam Registration Integration with Income Tax Filing Portal</h5>
                    <p class="text-muted small mb-3">Circular No: MSME/2026/04. Udyam Portal now directly syncs Investment and Turnover details with the Income Tax Department's ITR database for instant verification and classification updates.</p>
                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-2">
                        <span class="text-muted small"><i class="ph ph-shield-check text-success me-1"></i> Verified Policy Update</span>
                        <a href="#" class="btn btn-link text-primary btn-sm fw-semibold p-0"><i class="ph ph-download-simple me-1"></i> Circular PDF</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3 --}}
        <div class="col-12 mb-3 update-item" data-type="compliance">
            <div class="card border-0 shadow-sm rounded-3 hover-lift border-start border-4 border-success">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light-success text-success fw-semibold px-3 py-2 rounded-pill small">Compliance Concessions</span>
                            <span class="text-muted small"><i class="ph ph-calendar me-1"></i> June 18, 2026</span>
                        </div>
                        <span class="text-secondary small fw-semibold"><i class="ph ph-bank me-1"></i> Reserve Bank of India</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2 update-title">Restructuring Framework for MSME Business Loans Affected by Market Shifts</h5>
                    <p class="text-muted small mb-3">RBI issued guidelines permitting banks to restructure loans of viable MSME units up to ₹25 Crores without a downgrade in asset classification, offering breathing space to industries.</p>
                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-2">
                        <span class="text-muted small"><i class="ph ph-shield-check text-success me-1"></i> Verified Policy Update</span>
                        <a href="#" class="btn btn-link text-primary btn-sm fw-semibold p-0"><i class="ph ph-download-simple me-1"></i> Circular PDF</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- NO RESULTS FOUND MESSAGE --}}
    <div class="row d-none" id="noUpdatesFound">
        <div class="col-12 text-center py-5">
            <div class="mb-3 text-muted">
                <i class="ph-duotone ph-smiley-sad fs-1"></i>
            </div>
            <h5 class="fw-bold text-dark">No updates match your criteria</h5>
            <p class="text-muted">Try adjusting your search queries or select a different filter category.</p>
        </div>
    </div>
</div>

<style>
.header-icon-box {
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.06) !important;
}
</style>
@endsection

@section('page-script')
<script>
$(document).ready(function() {
    // Search input event
    $('#updateSearch').on('keyup', function() {
        filterUpdates();
    });

    // Type filter clicks
    $('.type-filter').on('click', function() {
        $('.type-filter').removeClass('btn-primary').addClass('btn-light-secondary');
        $(this).removeClass('btn-light-secondary').addClass('btn-primary');
        filterUpdates();
    });

    function filterUpdates() {
        const query = $('#updateSearch').val().toLowerCase();
        const type = $('#typeFilters .btn-primary').data('type');
        let visibleCount = 0;

        $('.update-item').each(function() {
            const cardType = $(this).data('type');
            const cardTitle = $(this).find('.update-title').text().toLowerCase();
            const cardDesc = $(this).find('.text-muted').text().toLowerCase();

            const matchesSearch = cardTitle.includes(query) || cardDesc.includes(query);
            const matchesType = (type === 'all') || (cardType === type);

            if (matchesSearch && matchesType) {
                $(this).removeClass('d-none');
                visibleCount++;
            } else {
                $(this).addClass('d-none');
            }
        });

        if (visibleCount === 0) {
            $('#noUpdatesFound').removeClass('d-none');
        } else {
            $('#noUpdatesFound').addClass('d-none');
        }
    }
});
</script>
@endsection
