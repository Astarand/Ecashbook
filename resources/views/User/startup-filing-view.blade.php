@extends('App.Layout')

@section('container')
<div class="pc-content">

<!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Tax Filing & Returns</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/startup-filing/list') }}">Startup Filing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Details</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Startup Filing Details</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="page-header">
        <h2>STARTUP INCUBATOR SERVICE DETAILS</h2>
    </div>

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered">

                <!-- APPLICANT DETAILS -->
                <tr class="table-secondary">
                    <th colspan="2">Applicant Details</th>
                </tr>
                <tr><th>Business Name</th><td>{{ $application->business_name }}</td></tr>
                <tr><th>Founder Name</th><td>{{ $application->founder_name }}</td></tr>
                <tr><th>Mobile</th><td>{{ $application->mobile }}</td></tr>
                <tr><th>Email</th><td>{{ $application->email }}</td></tr>
                <tr><th>Business Address</th><td>{{ $application->business_address }}</td></tr>
                <tr><th>Industry Type</th><td>{{ $application->industry_type }}</td></tr>

                <!-- BUSINESS STAGE -->
                <tr class="table-secondary">
                    <th colspan="2">Business Stage</th>
                </tr>
                <tr><th>Idea Stage</th><td>{{ $application->idea_stage ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Prototype / MVP</th><td>{{ $application->prototype ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Early Revenue</th><td>{{ $application->early_revenue ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Growth Stage</th><td>{{ $application->growth_stage ? 'Yes' : 'No' }}</td></tr>

                <!-- CONTACT PERSON -->
                <tr class="table-secondary">
                    <th colspan="2">Contact Person Details</th>
                </tr>
                <tr><th>Contact Person</th><td>{{ $application->contact_person }}</td></tr>
                <tr><th>Designation</th><td>{{ $application->designation }}</td></tr>
                <tr><th>Mobile</th><td>{{ $application->contact_mobile }}</td></tr>
                <tr><th>Email</th><td>{{ $application->contact_email }}</td></tr>

                <!-- BUSINESS FORMATION & REGISTRATION -->
                <tr class="table-secondary">
                    <th colspan="2">Business Formation & Registration</th>
                </tr>
                <tr><th>Company Registration</th><td>{{ $application->company_registration ? 'Yes' : 'No' }}</td></tr>
                <tr><th>GST Registration</th><td>{{ $application->gst_registration ? 'Yes' : 'No' }}</td></tr>
                <tr><th>MSME / UDYAM</th><td>{{ $application->msme ? 'Yes' : 'No' }}</td></tr>
                <tr><th>PAN / TAN</th><td>{{ $application->pan_tan ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Trade License / Shop Act</th><td>{{ $application->trade_license ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Trademark Registration</th><td>{{ $application->trademark ? 'Yes' : 'No' }}</td></tr>
                <tr><th>DSC</th><td>{{ $application->dsc ? 'Yes' : 'No' }}</td></tr>
                <tr><th>EPF & ESIC</th><td>{{ $application->epf_esic ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Startup Registration</th><td>{{ $application->startup_registration ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Professional Tax</th><td>{{ $application->professional_tax ? 'Yes' : 'No' }}</td></tr>

                <!-- COMPLIANCE & FINANCIAL SETUP -->
                <tr class="table-secondary">
                    <th colspan="2">Compliance & Financial Setup</th>
                </tr>
                <tr><th>Accounting Software Setup</th><td>{{ $application->accounting_setup ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Chart of Accounts</th><td>{{ $application->chart_accounts ? 'Yes' : 'No' }}</td></tr>
                <tr><th>GST / TDS / IT Guidance</th><td>{{ $application->tax_guidance ? 'Yes' : 'No' }}</td></tr>
                <tr><th>ROC / MCA Setup</th><td>{{ $application->roc_setup ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Payroll Compliance</th><td>{{ $application->payroll ? 'Yes' : 'No' }}</td></tr>

                <!-- BUSINESS MODEL & STRATEGY -->
                <tr class="table-secondary">
                    <th colspan="2">Business Model & Strategy</th>
                </tr>
                <tr><th>Business Model Canvas</th><td>{{ $application->business_model ? 'Yes' : 'No' }}</td></tr>
                <tr><th>SWOT & Market Research</th><td>{{ $application->swot ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Pricing Strategy</th><td>{{ $application->pricing ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Financial Planning</th><td>{{ $application->financial_planning ? 'Yes' : 'No' }}</td></tr>

                <!-- FUNDING & INVESTOR SUPPORT -->
                <tr class="table-secondary">
                    <th colspan="2">Funding & Investor Support</th>
                </tr>
                <tr><th>Pitch Deck</th><td>{{ $application->pitch_deck ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Financial Projections</th><td>{{ $application->financial_projection ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Valuation Assistance</th><td>{{ $application->valuation ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Investor Connect</th><td>{{ $application->investor_connect ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Govt. Scheme Support</th><td>{{ $application->govt_scheme ? 'Yes' : 'No' }}</td></tr>

                <!-- MENTORSHIP -->
                <tr class="table-secondary">
                    <th colspan="2">Mentorship & Skill Development</th>
                </tr>
                <tr><th>1:1 Mentoring</th><td>{{ $application->mentoring ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Workshops / Masterclasses</th><td>{{ $application->workshop ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Legal Mentoring</th><td>{{ $application->legal_mentoring ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Marketing Mentoring</th><td>{{ $application->marketing_mentoring ? 'Yes' : 'No' }}</td></tr>

                <!-- TECHNOLOGY ENABLEMENT -->
                <tr class="table-secondary">
                    <th colspan="2">Technology & Digital Enablement</th>
                </tr>
                <tr><th>Website / E-commerce</th><td>{{ $application->website ? 'Yes' : 'No' }}</td></tr>
                <tr><th>CRM Setup</th><td>{{ $application->crm ? 'Yes' : 'No' }}</td></tr>
                <tr><th>ERP Access</th><td>{{ $application->erp ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Digital Marketing</th><td>{{ $application->digital_marketing ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Automation Tools</th><td>{{ $application->automation ? 'Yes' : 'No' }}</td></tr>

                <!-- GO-TO-MARKET -->
                <tr class="table-secondary">
                    <th colspan="2">Go-To-Market & Branding</th>
                </tr>
                <tr><th>Brand Identity</th><td>{{ $application->brand_identity ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Social Media Setup</th><td>{{ $application->social_media ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Product Launch Plan</th><td>{{ $application->product_plan ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Marketing Templates</th><td>{{ $application->marketing_template ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Dealer / Distributor Support</th><td>{{ $application->dealer ? 'Yes' : 'No' }}</td></tr>

                <!-- GROWTH MONITORING -->
                <tr class="table-secondary">
                    <th colspan="2">Growth Monitoring</th>
                </tr>
                <tr><th>Monthly Business Report</th><td>{{ $application->monthly_report ? 'Yes' : 'No' }}</td></tr>
                <tr><th>KPI Dashboard</th><td>{{ $application->kpi ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Cashflow Advisory</th><td>{{ $application->cashflow ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Scaling Support</th><td>{{ $application->scaling_support ? 'Yes' : 'No' }}</td></tr>

                <!-- DECLARATION -->
                <tr class="table-secondary">
                    <th colspan="2">Declaration</th>
                </tr>
                <tr><th>Authorized Signatory</th><td>{{ $application->authorized_signatory }}</td></tr>
                <tr><th>Signatory Name</th><td>{{ $application->signatory_name }}</td></tr>
                <tr>
                    <th>Signed Date</th>
                    <td>{{ $application->signed_date ? $application->signed_date->format('d-m-Y') : '-' }}</td>
                </tr>

            </table>

            <a href="{{ url('/startup-filing/list') }}" class="btn btn-secondary mt-3">
                Back
            </a>

        </div>
    </div>
</div>
@endsection
