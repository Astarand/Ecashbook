@extends('App.Layout')

@section('container')

<div class="pc-content">

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">

                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tax.index') }}">Tax Deduction Master</a></li>
                        <li class="breadcrumb-item">View</li>
                    </ul>
                </div>

                <div class="col-md-12">
                    <h2 class="mb-0">Deduction Details</h2>
                </div>

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered">

                <tr><th>Name</th><td>{{ $deduction->deduction_name }}</td></tr>
                <tr><th>TAX Section</th><td>{{ $deduction->income_tax_section }}</td></tr>
                <tr><th>Category</th><td>{{ $deduction->deduction_category }}</td></tr>
                <tr><th>Type</th><td>{{ $deduction->deduction_type }}</td></tr>

                <tr><th>Tax Treatment</th><td>{{ $deduction->tax_treatment }}</td></tr>
                <tr><th>Limit Type</th><td>{{ $deduction->limit_type }}</td></tr>
                <tr><th>Base Source</th><td>{{ $deduction->base_amount_source }}</td></tr>
                <tr><th>Automation</th><td>{{ $deduction->automation_mode }}</td></tr>

                <tr><th>Limit Value</th><td>{{ $deduction->limit_value }}</td></tr>
                <tr><th>Limit Rate(%)</th><td>{{ $deduction->limit_rate }}</td></tr>
                <tr><th>Formula</th><td>{{ $deduction->limit_formula }}</td></tr>
                <tr><th>FY</th><td>{{ $deduction->applicable_fy }}</td></tr>
                <tr><th>Module</th><td>{{ $deduction->linked_module }}</td></tr>
                <tr><th>Priority</th><td>{{ $deduction->rule_priority }}</td></tr>

            </table>

            <div class="text-end">
                <a href="{{ route('tax.index') }}" class="btn btn-secondary">Back</a>
                <a href="{{ route('tax.edit', $deduction->id) }}" class="btn btn-primary">Edit</a>
            </div>

        </div>
    </div>

</div>

@endsection