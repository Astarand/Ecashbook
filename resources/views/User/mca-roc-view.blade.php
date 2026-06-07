@extends('App.Layout')

@section('container')
<div class="pc-content">

<!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Tax Filing & Returns</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/mca-roc/list') }}">MCA/ROC Filings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View MCA/ROC Details</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">View MCA / ROC Filing Details</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div class="page-header mb-3">
        <h2 class="text-uppercase">MCA / ROC Filing Details</h2>
    </div>

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered table-striped align-middle">

                {{-- ================= COMPANY DETAILS ================= --}}
                <tr class="table-primary">
                    <th colspan="2">Company Basic Details</th>
                </tr>
                <tr>
                    <th>Company Name</th>
                    <td>{{ $application->company_name }}</td>
                </tr>
                <tr>
                    <th>CIN</th>
                    <td>{{ $application->cin }}</td>
                </tr>
                <tr>
                    <th>PAN</th>
                    <td>{{ $application->pan }}</td>
                </tr>
                <tr>
                    <th>Registered Office Address</th>
                    <td>{{ $application->reg_office_address }}</td>
                </tr>
                <tr>
                    <th>MCA Registered Email</th>
                    <td>{{ $application->mca_email }}</td>
                </tr>
                <tr>
                    <th>Mobile No</th>
                    <td>{{ $application->mobile }}</td>
                </tr>
                <tr>
                    <th>Date of Incorporation</th>
                    <td>{{ $application->inc_date ? date('d-m-Y', strtotime($application->inc_date)) : '-' }}</td>
                </tr>
                <tr>
                    <th>NIC Code</th>
                    <td>{{ $application->nic_code }}</td>
                </tr>

                {{-- ================= EVENTS ================= --}}
                <tr class="table-primary">
                    <th colspan="2">Event-Based Filings</th>
                </tr>
                <tr>
                    <th>Change in Director</th>
                    <td>{{ $application->event_change_director ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <th>Change in Registered Office</th>
                    <td>{{ $application->event_change_reg_office ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <th>Share Allotment</th>
                    <td>{{ $application->event_share_allotment ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <th>Transfer of Shares</th>
                    <td>{{ $application->event_transfer_shares ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <th>Appointment of Auditor</th>
                    <td>{{ $application->event_appointment_auditor ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <th>Resignation of Auditor</th>
                    <td>{{ $application->event_resignation_auditor ? 'Yes' : 'No' }}</td>
                </tr>

                {{-- ================= DOCUMENTS ================= --}}
                <tr class="table-primary">
                    <th colspan="2">Documents Submitted</th>
                </tr>
                <tr>
                    <th>MOA & AOA</th>
                    <td>
						{{ $application->doc_moa_aoa ? 'Yes' : 'No' }}
						@if($application->file_doc_moa_aoa)
							<br>
							<a href="{{ asset($application->file_doc_moa_aoa) }}" target="_blank">
								View File
							</a>
						@endif
					</td>
                </tr>
                <tr>
                    <th>Certificate of Incorporation</th>
                    <td>{{ $application->doc_coi ? 'Yes' : 'No' }}
						@if($application->file_doc_coi)
							<br>
							<a href="{{ asset($application->file_doc_coi) }}" target="_blank">
								View File
							</a>
						@endif
					</td>
                </tr>
                <tr>
                    <th>Previous ROC Filings</th>
                    <td>{{ $application->doc_prev_roc ? 'Yes' : 'No' }}
						@if($application->file_doc_prev_roc)
							<br>
							<a href="{{ asset($application->file_doc_prev_roc) }}" target="_blank">
								View File
							</a>
						@endif
					</td>
                </tr>
                <tr>
                    <th>DSC Authorization Letter</th>
                    <td>{{ $application->doc_dsc_auth ? 'Yes' : 'No' }}
						@if($application->file_doc_dsc_auth)
							<br>
							<a href="{{ asset($application->file_doc_dsc_auth) }}" target="_blank">
								View File
							</a>
						@endif
					</td>
                </tr>
                <tr>
                    <th>Auditor Appointment Letter</th>
                    <td>
						{{ $application->doc_auditor_appointment ? 'Yes' : 'No' }}
						@if($application->file_doc_auditor_appointment)
							<br>
							<a href="{{ asset($application->file_doc_auditor_appointment) }}" target="_blank">
								View File
							</a>
						@endif
					</td>
                </tr>

                {{-- ================= DECLARATION ================= --}}
                <tr class="table-primary">
                    <th colspan="2">Client Declaration</th>
                </tr>
                <tr>
                    <th>Client Name</th>
                    <td>{{ $application->client_name }}</td>
                </tr>
                <tr>
                    <th>Designation</th>
                    <td>{{ $application->designation }}</td>
                </tr>
                <tr>
                    <th>Signature</th>
                    <td>{{ $application->signature }}</td>
                </tr>
                <tr>
                    <th>Signed Date</th>
                    <td>{{ $application->signed_date ? date('d-m-Y', strtotime($application->signed_date)) : '-' }}</td>
                </tr>

                {{-- ================= META ================= --}}
                <tr class="table-primary">
                    <th colspan="2">Application Info</th>
                </tr>
                <tr>
                    <th>Submitted On</th>
                    <td>{{ $application->created_at ? $application->created_at->format('d-m-Y h:i A') : '-' }}</td>
                </tr>
                <tr>
                    <th>Last Updated</th>
                    <td>{{ $application->updated_at ? $application->updated_at->format('d-m-Y h:i A') : '-' }}</td>
                </tr>

            </table>

            <a href="{{ url('/mca-roc/list') }}" class="btn btn-secondary mt-3">
                Back
            </a>

        </div>
    </div>
</div>
@endsection
