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
			? '<span class="badge bg-success">Yes</span>' 
			: '<span class="badge bg-danger">No</span>';
	}

	function fileLink($path){
		if(!$path) return '-';
		
		return '<a href="'.asset($path).'" target="_blank" class="btn btn-sm btn-primary">View</a>';
	}
	@endphp

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h5>View Business Health Check-up</h5>
		  <a href="{{ route('admin.company.checks') }}" class="btn btn-secondary">Back</a>
        </div>
        <div class="card-body">          
			<h5 class="mb-3 text-primary">Company Information</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Company Name</th>
                    <td>{{ $data->comp_name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Company Type</th>
                    <td>{{ $data->comp_type }}</td>
                </tr>
                <tr>
                    <th>CIN</th>
                    <td>{{ $data->cin }}</td>
                </tr>
                <tr>
                    <th>Incorporation Date</th>
                    <td>{{ date('d-m-Y', strtotime($data->inc_date)) }}</td>
                </tr>
                <tr>
                    <th>PAN</th>
                    <td>{{ $data->comp_pan_no }}</td>
                </tr>
                <tr>
                    <th>Turnover Last Year</th>
                    <td>{{ $data->turnover_last_year }}</td>
                </tr>
            </table>

            {{-- Registrations --}}
            <h5 class="mt-4 text-primary">Registrations & Licenses</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Trade License</th>
                    <td>{!! yesNo($data->trade_chk) !!}</td>
                    <td>{!! fileLink($data->trade_doc) !!}</td>
                </tr>
                <tr>
                    <th>Shop Establishment</th>
                    <td>{!! yesNo($data->shop_est_chk) !!}</td>
                    <td>{!! fileLink($data->shop_est_doc) !!}</td>
                </tr>
                <tr>
                    <th>GST</th>
                    <td>{!! yesNo($data->gst_chk) !!}</td>
                    <td>{!! fileLink($data->gst_doc) !!}</td>
                </tr>
                <tr>
                    <th>TAN</th>
                    <td>{!! yesNo($data->tan_chk) !!}</td>
                    <td>{!! fileLink($data->tan_doc) !!}</td>
                </tr>
                <tr>
                    <th>MSME</th>
                    <td>{!! yesNo($data->msme_chk) !!}</td>
                    <td>{!! fileLink($data->msme_doc) !!}</td>
                </tr>
                <tr>
                    <th>EPF</th>
                    <td>{!! yesNo($data->epf_chk) !!}</td>
                    <td>{!! fileLink($data->epf_doc) !!}</td>
                </tr>
                <tr>
                    <th>ESI</th>
                    <td>{!! yesNo($data->esi_chk) !!}</td>
                    <td>{!! fileLink($data->esi_doc) !!}</td>
                </tr>
                <tr>
                    <th>FSSAI</th>
                    <td>{!! yesNo($data->fssai_chk) !!}</td>
                    <td>{!! fileLink($data->fssai_doc) !!}</td>
                </tr>
                <tr>
                    <th>Factory License</th>
                    <td>{!! yesNo($data->fact_chk) !!}</td>
                    <td>{!! fileLink($data->fact_doc) !!}</td>
                </tr>
                <tr>
                    <th>Fire NOC</th>
                    <td>{!! yesNo($data->fire_chk) !!}</td>
                    <td>{!! fileLink($data->fire_doc) !!}</td>
                </tr>
            </table>

            {{-- Financial & Compliance --}}
            <h5 class="mt-4 text-primary">Financial & Compliance Review</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Financial Review</th>
                    <td>{{ $data->financial_review }}</td>
                </tr>
                <tr>
                    <th>Tax Review</th>
                    <td>{{ $data->tax_review }}</td>
                </tr>
                <tr>
                    <th>Compliance Review</th>
                    <td>{{ $data->compliance_review }}</td>
                </tr>
                <tr>
                    <th>Statutory Auditor</th>
                    <td>{{ $data->statutory_auditor }}</td>
                </tr>
                <tr>
                    <th>Business Status</th>
                    <td>{{ $data->bus_status }}</td>
                </tr>
            </table>
			
			<h5 class="mt-4 text-primary">Statutory Compliance Status</h5>
			<table class="table table-bordered">
				<tr><th>KYC DIN</th><td>{{ $data->kyc_din }}</td></tr>
				<tr><th>Return of Loan</th><td>{{ $data->return_loan }}</td></tr>
				<tr><th>AGM Status</th><td>{{ $data->agm_status }}</td></tr>
				<tr><th>ROC Status</th><td>{{ $data->roc_status }}</td></tr>
				<tr><th>Audit Report</th><td>{{ $data->audit_report }}</td></tr>
				<tr><th>GST Status</th><td>{{ $data->gst_status }}</td></tr>
				<tr><th>Income Tax Status</th><td>{{ $data->income_tax_status }}</td></tr>
				<tr><th>TDS Status</th><td>{{ $data->tds_status }}</td></tr>
				<tr><th>PF / ESI Status</th><td>{{ $data->pf_esi_status }}</td></tr>
				<tr><th>Trade License Status</th><td>{{ $data->trade_status }}</td></tr>
				<tr><th>Professional Tax Status</th><td>{{ $data->ptax_status }}</td></tr>
				<tr><th>Labour Law</th><td>{{ $data->labour_law }}</td></tr>
				<tr><th>MSME Status</th><td>{{ $data->msme_status }}</td></tr>
				<tr><th>DPDP Act</th><td>{{ $data->dpdp_act }}</td></tr>
				<tr><th>XBRL Filing</th><td>{{ $data->xbrl_filing }}</td></tr>
				<tr><th>Intellectual Property</th><td>{{ $data->intellectual_property }}</td></tr>
			</table>

            {{-- Risk & Priority --}}
            <h5 class="mt-4 text-primary">Risk & Priority</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Risk Level</th>
                    <td>{{ $data->risk_level }}</td>
                </tr>
                <tr>
                    <th>Priority 1</th>
                    <td>{{ $data->priority_one }}</td>
                </tr>
                <tr>
                    <th>Priority 2</th>
                    <td>{{ $data->priority_two }}</td>
                </tr>
                <tr>
                    <th>Priority 3</th>
                    <td>{{ $data->priority_three }}</td>
                </tr>
            </table>
			
			<h5 class="mt-4 text-primary">Specialist Review</h5>
			<table class="table table-bordered">
				<tr>
					<th>Specialist Review</th>
					<td  class="text-wrap">{{ $data->specialist_review }}</td>
				</tr>
			</table>


			<h5 class="mt-4 text-primary">Prepared by (CA / Accountant / Compliance Officer) </h5>
            <table class="table table-bordered">               
                <tr>
                    <th>Officer Name</th>
                    <td>{{ $data->officer_name }}</td>
                </tr>
                <tr>
                    <th>Designation</th>
                    <td>{{ $data->designation }}</td>
                </tr>
                <tr>
                    <th>Approval Date</th>
                    <td>{{ $data->app_date }}</td>
                </tr>
            </table>
			
            {{-- Admin Section --}}
            <h5 class="mt-4 text-primary">Admin Approval & Certificate</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge 
                            {{ $data->admin_status == 1 ? 'bg-success' : 'bg-danger' }}">
                            {{ $data->admin_status == 1 ? 'Approved' : 'Pending' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Admin Remark</th>
                    <td class="text-wrap">{{ $data->admin_remark }}</td>
                </tr>
				<tr>
                    <th>Admin Certificate File</th>
                    <td>{!! fileLink($data->admin_certificate) !!}</td>
                </tr>
            </table>
			
        </div>
      </div>
    </div>
  </div>
</div>

<script>
 
</script>
@endsection