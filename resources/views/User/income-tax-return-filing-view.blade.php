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
                        <li class="breadcrumb-item"><a href="{{ url('/itr/list') }}">Income Tax Filing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Details</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Income Tax Return (ITR) Filing Details</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <h3 class="mb-3">Income Tax Return (ITR) Filing Details</h3>

    <table class="table table-bordered table-striped">

        {{-- A. BASIC DETAILS --}}
        <tr class="table-secondary">
            <th colspan="2">Basic Details</th>
        </tr>
        <tr><th>Legal Name</th><td>{{ $application->legal_name }}</td></tr>
        <tr><th>Trade Name</th><td>{{ $application->trade_name }}</td></tr>
        <tr><th>PAN</th><td>{{ $application->pan }}</td></tr>
        <tr><th>Aadhaar</th><td>{{ $application->aadhaar }}</td></tr>
        <tr>
            <th>DOB / Incorporation Date</th>
            <td>{{ $application->dob_inc ? date('d-m-Y', strtotime($application->dob_inc)) : '-' }}</td>
        </tr>
        <tr><th>Mobile</th><td>{{ $application->mobile }}</td></tr>
        <tr><th>Email</th><td>{{ $application->email }}</td></tr>

        {{-- B. FILING TYPE --}}
        <tr class="table-secondary">
            <th colspan="2">Filing Type</th>
        </tr>
        <tr><th>Individual</th><td>{{ $application->filing_individual ? 'Yes' : 'No' }}</td></tr>
        <tr><th>Proprietorship</th><td>{{ $application->filing_proprietorship ? 'Yes' : 'No' }}</td></tr>
        <tr><th>Partnership Firm</th><td>{{ $application->filing_partnership ? 'Yes' : 'No' }}</td></tr>
        <tr><th>LLP</th><td>{{ $application->filing_llp ? 'Yes' : 'No' }}</td></tr>
        <tr><th>Company</th><td>{{ $application->filing_company ? 'Yes' : 'No' }}</td></tr>

        {{-- C. INCOME DETAILS (INDIVIDUAL) --}}
        <tr class="table-secondary">
            <th colspan="2">Income Details (Individual / Proprietorship)</th>
        </tr>
        <tr><th>Salary Form 16</th><td>{{ $application->ind_salary_16 ? 'Yes' : 'No' }}</td></tr>
        <tr><th>Bank Statements</th><td>{{ $application->ind_bank_stmt ? 'Yes' : 'No' }}</td></tr>
        <tr><th>Books of Accounts</th><td>{{ $application->ind_books ? 'Yes' : 'No' }}</td></tr>
        <tr><th>GST Returns</th><td>{{ $application->ind_gst_returns ? 'Yes' : 'No' }}</td></tr>
        <tr><th>Profit & Loss</th><td>{{ $application->ind_pl ? 'Yes' : 'No' }}</td></tr>
        <tr><th>Balance Sheet</th><td>{{ $application->ind_bs ? 'Yes' : 'No' }}</td></tr>
        <tr><th>Rental Income</th><td>{{ $application->ind_rental ? 'Yes' : 'No' }}</td></tr>
        <tr><th>Other Income</th><td>{{ $application->ind_other_income ? 'Yes' : 'No' }}</td></tr>

        {{-- D. INCOME DETAILS (FIRM / COMPANY) --}}
        <tr class="table-secondary">
            <th colspan="2">Income Details (Firm / LLP / Company)</th>
        </tr>
        <tr><th>Final Accounts</th><td>{{ $application->firm_final_accounts ? 'Yes' : 'No' }}</td></tr>
        <tr><th>Bank Statements</th><td>{{ $application->firm_bank_stmt ? 'Yes' : 'No' }}</td></tr>
        <tr><th>GST Summary</th><td>{{ $application->firm_gst_summary ? 'Yes' : 'No' }}</td></tr>
        <tr><th>TDS Summary</th><td>{{ $application->firm_tds ? 'Yes' : 'No' }}</td></tr>
        <tr><th>Depreciation</th><td>{{ $application->firm_depreciation ? 'Yes' : 'No' }}</td></tr>
        <tr><th>Loan Confirmations</th><td>{{ $application->firm_loan_conf ? 'Yes' : 'No' }}</td></tr>
        <tr><th>Related Party Transactions</th><td>{{ $application->firm_related_party ? 'Yes' : 'No' }}</td></tr>

        {{-- E. TAX DETAILS --}}
        <tr class="table-secondary">
            <th colspan="2">Tax Details</th>
        </tr>
        <tr><th>Form 26AS</th><td>{{ $application->tax_26as ? 'Yes' : 'No' }}</td></tr>
        <tr><th>AIS / TIS</th><td>{{ $application->tax_ais_tis ? 'Yes' : 'No' }}</td></tr>
        <tr><th>TDS Certificates</th><td>{{ $application->tax_tds_cert ? 'Yes' : 'No' }}</td></tr>
        <tr><th>Advance Tax Challans</th><td>{{ $application->tax_adv_challan ? 'Yes' : 'No' }}</td></tr>
        <tr><th>Self Assessment Tax</th><td>{{ $application->tax_self_assess ? 'Yes' : 'No' }}</td></tr>

        {{-- F. BUSINESS / PROFESSIONAL INFO --}}
        <tr class="table-secondary">
            <th colspan="2">Business / Professional Information</th>
        </tr>
        <tr><th>Nature of Business</th><td>{{ $application->nature_business }}</td></tr>
        <tr><th>Turnover Details</th><td>{{ $application->turnover_details }}</td></tr>
        <tr><th>Assets & Liabilities</th><td>{{ $application->asset_liab }}</td></tr>
        <tr><th>Stock Summary</th><td>{{ $application->stock_summary }}</td></tr>
        <tr><th>Capital Account</th><td>{{ $application->capital_account }}</td></tr>

        {{-- G. VERIFICATION PERSON --}}
        <tr class="table-secondary">
            <th colspan="2">Verification Person Details</th>
        </tr>
        <tr><th>Name</th><td>{{ $application->ver_name }}</td></tr>
        <tr><th>Designation</th><td>{{ $application->ver_designation }}</td></tr>
        <tr><th>PAN</th><td>{{ $application->ver_pan }}</td></tr>
        <tr><th>Mobile</th><td>{{ $application->ver_mobile }}</td></tr>
        <tr><th>Email</th><td>{{ $application->ver_email }}</td></tr>
        <tr>
            <th>DSC File</th>
            <td>
                @if($application->ver_dsc)
                    <a href="{{ asset('uploads/itr/'.$application->ver_dsc) }}" target="_blank">Download</a>
                @else
                    -
                @endif
            </td>
        </tr>

        {{-- H. REQUIRED DOCUMENTS --}}
        <tr class="table-secondary">
            <th colspan="2">Required Documents</th>
        </tr>
        <tr><th>PAN</th><td>
						{{ $application->req_pan ? 'Yes' : 'No' }}
						@if($application->file_req_pan)
							<br>
							<a href="{{ asset($application->file_req_pan) }}" target="_blank">
								View File
							</a>
						@endif
		</td></tr>
        <tr><th>Aadhaar</th><td>
						{{ $application->req_aadhaar ? 'Yes' : 'No' }}
						@if($application->file_req_aadhaar)
							<br>
							<a href="{{ asset($application->file_req_aadhaar) }}" target="_blank">
								View File
							</a>
						@endif
		</td></tr>
        <tr><th>Bank Passbook</th><td>
						{{ $application->req_bank_passbook ? 'Yes' : 'No' }}
						@if($application->file_req_bank_passbook)
							<br>
							<a href="{{ asset($application->file_req_bank_passbook) }}" target="_blank">
								View File
							</a>
						@endif
		</td></tr>
        <tr><th>Digital Signature</th><td>
						{{ $application->req_digital_signature ? 'Yes' : 'No' }}
						@if($application->file_req_digital_signature)
							<br>
							<a href="{{ asset($application->file_req_digital_signature) }}" target="_blank">
								View File
							</a>
						@endif
		</td></tr>
        <tr><th>Previous ITR</th><td>
						{{ $application->req_prev_itr ? 'Yes' : 'No' }}
						@if($application->file_req_prev_itr)
							<br>
							<a href="{{ asset($application->file_req_prev_itr) }}" target="_blank">
								View File
							</a>
						@endif
		</td></tr>

        {{-- I. CLIENT DECLARATION --}}
        <tr class="table-secondary">
            <th colspan="2">Client Declaration</th>
        </tr>
        <tr><th>Client Name</th><td>{{ $application->client_name }}</td></tr>
        <tr><th>Designation</th><td>{{ $application->client_designation }}</td></tr>
        <tr><th>Signature</th><td>{{ $application->client_signature }}</td></tr>
        <tr>
            <th>Date</th>
            <td>{{ $application->client_date ? date('d-m-Y', strtotime($application->client_date)) : '-' }}</td>
        </tr>

    </table>

    <a href="{{ route('itr.list') }}" class="btn btn-secondary mt-3">Back</a>

</div>
@endsection
