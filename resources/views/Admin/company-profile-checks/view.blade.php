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
            <li class="breadcrumb-item"><a href="{{ route('admin.company.checks') }}">View Business Health Check-up</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- [ breadcrumb ] end -->
  
	@php
	function yesNo($val){
		return $val == 1 
			? '<span class="badge bg-light-success text-success"><i class="ti ti-check me-1"></i>Yes</span>' 
			: '<span class="badge bg-light-danger text-danger"><i class="ti ti-x me-1"></i>No</span>';
	}

	function fileLink($path){
		if(!$path) return '<span class="text-muted"><i class="ti ti-minus"></i></span>';
		
		return '<a href="'.asset($path).'" target="_blank" class="avtar avtar-xs btn-light-primary btn-pc-default" data-bs-toggle="tooltip" title="View Attachment"><i class="ti ti-paperclip f-16"></i></a>';
	}
	@endphp

  <!-- Header Info Panel -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card shadow-sm border-0 mb-0">
        <div class="card-body p-4">
          <div class="row align-items-center justify-content-between">
            <div class="col-md-8">
              <div class="d-flex align-items-center gap-3">
                <div class="avtar avtar-lg btn-light-primary rounded-circle" style="width: 70px; height: 70px;">
                  <i class="ti ti-building f-30"></i>
                </div>
                <div>
                  <h4 class="mb-1 text-dark fw-bold">{{ $data->comp_name ?? '-' }}</h4>
                  <p class="text-muted mb-0 d-flex align-items-center gap-2">
                    <span class="badge bg-light-primary text-primary">{{ $data->comp_type }}</span>
                    <span>•</span>
                    <span>CIN: <code class="text-secondary">{{ $data->cin }}</code></span>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
              <a href="{{ route('admin.company.checks') }}" class="btn btn-outline-secondary btn-sm me-2"><i class="ti ti-arrow-left"></i> Back</a>
              <span class="badge bg-{{ $data->admin_status == 1 ? 'light-success text-success' : 'light-warning text-warning' }} p-2 fs-6">
                {{ $data->admin_status == 1 ? 'Approved' : 'Pending' }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Left Column - Company & Compliance Metadata -->
    <div class="col-xl-4 col-lg-5 col-12">
      <!-- Company Information Card -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent py-3">
          <h5 class="mb-0 text-dark fw-bold"><i class="ti ti-info-circle me-2 text-primary"></i>Company Information</h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="text-muted d-block mb-1 f-12 text-uppercase">Incorporation Date</label>
            <span class="fw-semibold text-dark"><i class="ti ti-calendar me-1"></i>{{ date('d-m-Y', strtotime($data->inc_date)) }}</span>
          </div>
          <div class="mb-3">
            <label class="text-muted d-block mb-1 f-12 text-uppercase">PAN Number</label>
            <span class="fw-semibold text-dark"><code class="text-dark">{{ $data->comp_pan_no }}</code></span>
          </div>
          <div class="mb-0">
            <label class="text-muted d-block mb-1 f-12 text-uppercase">Turnover Last Year</label>
            <span class="fw-semibold text-dark text-primary font-bold">₹ {{ number_format(floatval(str_replace([',', ' '], '', $data->turnover_last_year)), 2) ?? $data->turnover_last_year }}</span>
          </div>
        </div>
      </div>

      <!-- Risk & Priority Card -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent py-3">
          <h5 class="mb-0 text-dark fw-bold"><i class="ti ti-alert-triangle me-2 text-warning"></i>Risk & Priorities</h5>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <label class="text-muted d-block mb-2 f-12 text-uppercase">Risk Level Assessment</label>
            <span class="badge {{ $data->risk_level == 'High' ? 'bg-light-danger text-danger' : ($data->risk_level == 'Medium' ? 'bg-light-warning text-warning' : 'bg-light-success text-success') }} p-2 fs-6 w-100 text-center">
              <i class="ti ti-alert-circle me-1"></i>{{ $data->risk_level }} Risk
            </span>
          </div>
          
          <div class="mb-3">
            <label class="text-muted d-block mb-1 f-12 text-uppercase">Priority 1 (Critical Action)</label>
            <span class="text-dark fw-semibold">{{ $data->priority_one ?? '-' }}</span>
          </div>
          <div class="mb-3">
            <label class="text-muted d-block mb-1 f-12 text-uppercase">Priority 2 (High Action)</label>
            <span class="text-dark fw-semibold">{{ $data->priority_two ?? '-' }}</span>
          </div>
          <div class="mb-0">
            <label class="text-muted d-block mb-1 f-12 text-uppercase">Priority 3 (Medium Action)</label>
            <span class="text-dark fw-semibold">{{ $data->priority_three ?? '-' }}</span>
          </div>
        </div>
      </div>

      <!-- Prepared By Details -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent py-3">
          <h5 class="mb-0 text-dark fw-bold"><i class="ti ti-user-check me-2 text-success"></i>Prepared By</h5>
        </div>
        <div class="card-body">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="avtar avtar-s btn-light-success rounded-circle">
              <i class="ti ti-user f-18"></i>
            </div>
            <div>
              <h6 class="mb-0 text-dark fw-bold">{{ $data->officer_name }}</h6>
              <span class="text-muted f-12">{{ $data->designation }}</span>
            </div>
          </div>
          <div>
            <label class="text-muted d-block mb-1 f-12 text-uppercase">Approval Date</label>
            <span class="fw-semibold text-dark"><i class="ti ti-calendar-event me-1"></i>{{ $data->app_date }}</span>
          </div>
        </div>
      </div>

      <!-- Admin Remarks & Certificate -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent py-3">
          <h5 class="mb-0 text-dark fw-bold"><i class="ti ti-settings me-2 text-secondary"></i>Admin Verification</h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="text-muted d-block mb-1 f-12 text-uppercase">Admin Remarks</label>
            <p class="text-dark bg-light p-3 rounded text-wrap mb-0 f-14" style="line-height: 1.5;">{{ $data->admin_remark ?? 'No remarks provided.' }}</p>
          </div>
          <div class="d-flex justify-content-between align-items-center bg-light-primary p-3 rounded">
            <div>
              <span class="fw-bold text-primary mb-1 d-block"><i class="ti ti-certificate me-1"></i>Health Certificate</span>
              <span class="text-muted f-12">Official PDF report</span>
            </div>
            <div>
              {!! fileLink($data->admin_certificate) !!}
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column - Registrations & Compliance Review -->
    <div class="col-xl-8 col-lg-7 col-12">
      <!-- Registrations & Licenses -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent py-3">
          <h5 class="mb-0 text-dark fw-bold"><i class="ti ti-file-text me-2 text-primary"></i>Registrations & Licenses</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table tbl-product table-hover align-middle mb-0">
              <thead class="bg-light">
                <tr>
                  <th class="ps-4">Requirement</th>
                  <th>Status</th>
                  <th class="pe-4 text-end">Document</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="ps-4 fw-semibold text-dark">Trade License</td>
                  <td>{!! yesNo($data->trade_chk) !!}</td>
                  <td class="pe-4 text-end">{!! fileLink($data->trade_doc) !!}</td>
                </tr>
                <tr>
                  <td class="ps-4 fw-semibold text-dark">Shop Establishment</td>
                  <td>{!! yesNo($data->shop_est_chk) !!}</td>
                  <td class="pe-4 text-end">{!! fileLink($data->shop_est_doc) !!}</td>
                </tr>
                <tr>
                  <td class="ps-4 fw-semibold text-dark">GST</td>
                  <td>{!! yesNo($data->gst_chk) !!}</td>
                  <td class="pe-4 text-end">{!! fileLink($data->gst_doc) !!}</td>
                </tr>
                <tr>
                  <td class="ps-4 fw-semibold text-dark">TAN</td>
                  <td>{!! yesNo($data->tan_chk) !!}</td>
                  <td class="pe-4 text-end">{!! fileLink($data->tan_doc) !!}</td>
                </tr>
                <tr>
                  <td class="ps-4 fw-semibold text-dark">MSME</td>
                  <td>{!! yesNo($data->msme_chk) !!}</td>
                  <td class="pe-4 text-end">{!! fileLink($data->msme_doc) !!}</td>
                </tr>
                <tr>
                  <td class="ps-4 fw-semibold text-dark">EPF</td>
                  <td>{!! yesNo($data->epf_chk) !!}</td>
                  <td class="pe-4 text-end">{!! fileLink($data->epf_doc) !!}</td>
                </tr>
                <tr>
                  <td class="ps-4 fw-semibold text-dark">ESI</td>
                  <td>{!! yesNo($data->esi_chk) !!}</td>
                  <td class="pe-4 text-end">{!! fileLink($data->esi_doc) !!}</td>
                </tr>
                <tr>
                  <td class="ps-4 fw-semibold text-dark">FSSAI</td>
                  <td>{!! yesNo($data->fssai_chk) !!}</td>
                  <td class="pe-4 text-end">{!! fileLink($data->fssai_doc) !!}</td>
                </tr>
                <tr>
                  <td class="ps-4 fw-semibold text-dark">Factory License</td>
                  <td>{!! yesNo($data->fact_chk) !!}</td>
                  <td class="pe-4 text-end">{!! fileLink($data->fact_doc) !!}</td>
                </tr>
                <tr>
                  <td class="ps-4 fw-semibold text-dark">Fire NOC</td>
                  <td>{!! yesNo($data->fire_chk) !!}</td>
                  <td class="pe-4 text-end">{!! fileLink($data->fire_doc) !!}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Financial & Compliance Review -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent py-3">
          <h5 class="mb-0 text-dark fw-bold"><i class="ti ti-chart-bar me-2 text-primary"></i>Financial & Compliance Reviews</h5>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6 col-12">
              <div class="p-3 border rounded bg-light-alt height-100">
                <span class="text-muted d-block mb-1 f-12 text-uppercase">Financial Review</span>
                <span class="text-dark fw-semibold f-14">{{ $data->financial_review ?? '-' }}</span>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="p-3 border rounded bg-light-alt height-100">
                <span class="text-muted d-block mb-1 f-12 text-uppercase">Tax Review</span>
                <span class="text-dark fw-semibold f-14">{{ $data->tax_review ?? '-' }}</span>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="p-3 border rounded bg-light-alt height-100">
                <span class="text-muted d-block mb-1 f-12 text-uppercase">Compliance Review</span>
                <span class="text-dark fw-semibold f-14">{{ $data->compliance_review ?? '-' }}</span>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="p-3 border rounded bg-light-alt height-100">
                <span class="text-muted d-block mb-1 f-12 text-uppercase">Statutory Auditor</span>
                <span class="text-dark fw-semibold f-14">{{ $data->statutory_auditor ?? '-' }}</span>
              </div>
            </div>
            <div class="col-12">
              <div class="p-3 border rounded bg-light-alt">
                <span class="text-muted d-block mb-1 f-12 text-uppercase">Business Status / Outlook</span>
                <span class="text-dark fw-semibold f-14">{{ $data->bus_status ?? '-' }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Statutory Compliance Status -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent py-3">
          <h5 class="mb-0 text-dark fw-bold"><i class="ti ti-checkup-list me-2 text-primary"></i>Statutory Compliance Status</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table tbl-product table-hover mb-0">
              <thead class="bg-light">
                <tr>
                  <th class="ps-4" style="width: 40%;">Compliance Check</th>
                  <th class="pe-4">Status / Detail Remarks</th>
                </tr>
              </thead>
              <tbody>
                <tr><td class="ps-4 fw-semibold text-dark">KYC DIN</td><td class="pe-4 text-muted">{{ $data->kyc_din }}</td></tr>
                <tr><td class="ps-4 fw-semibold text-dark">Return of Loan</td><td class="pe-4 text-muted">{{ $data->return_loan }}</td></tr>
                <tr><td class="ps-4 fw-semibold text-dark">AGM Status</td><td class="pe-4 text-muted">{{ $data->agm_status }}</td></tr>
                <tr><td class="ps-4 fw-semibold text-dark">ROC Status</td><td class="pe-4 text-muted">{{ $data->roc_status }}</td></tr>
                <tr><td class="ps-4 fw-semibold text-dark">Audit Report</td><td class="pe-4 text-muted">{{ $data->audit_report }}</td></tr>
                <tr><td class="ps-4 fw-semibold text-dark">GST Status</td><td class="pe-4 text-muted">{{ $data->gst_status }}</td></tr>
                <tr><td class="ps-4 fw-semibold text-dark">Income Tax Status</td><td class="pe-4 text-muted">{{ $data->income_tax_status }}</td></tr>
                <tr><td class="ps-4 fw-semibold text-dark">TDS Status</td><td class="pe-4 text-muted">{{ $data->tds_status }}</td></tr>
                <tr><td class="ps-4 fw-semibold text-dark">PF / ESI Status</td><td class="pe-4 text-muted">{{ $data->pf_esi_status }}</td></tr>
                <tr><td class="ps-4 fw-semibold text-dark">Trade License Status</td><td class="pe-4 text-muted">{{ $data->trade_status }}</td></tr>
                <tr><td class="ps-4 fw-semibold text-dark">Professional Tax Status</td><td class="pe-4 text-muted">{{ $data->ptax_status }}</td></tr>
                <tr><td class="ps-4 fw-semibold text-dark">Labour Law</td><td class="pe-4 text-muted">{{ $data->labour_law }}</td></tr>
                <tr><td class="ps-4 fw-semibold text-dark">MSME Status</td><td class="pe-4 text-muted">{{ $data->msme_status }}</td></tr>
                <tr><td class="ps-4 fw-semibold text-dark">DPDP Act</td><td class="pe-4 text-muted">{{ $data->dpdp_act }}</td></tr>
                <tr><td class="ps-4 fw-semibold text-dark">XBRL Filing</td><td class="pe-4 text-muted">{{ $data->xbrl_filing }}</td></tr>
                <tr><td class="ps-4 fw-semibold text-dark">Intellectual Property</td><td class="pe-4 text-muted">{{ $data->intellectual_property }}</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Specialist Review -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent py-3">
          <h5 class="mb-0 text-dark fw-bold"><i class="ti ti-user-check me-2 text-primary"></i>Specialist Review & Advisory</h5>
        </div>
        <div class="card-body">
          <p class="text-dark bg-light-alt p-3 rounded text-wrap mb-0 f-14" style="line-height: 1.6; border-left: 4px solid #4f46e5;">
            {{ $data->specialist_review ?? 'No specialist review provided yet.' }}
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<script>
 
</script>
@endsection