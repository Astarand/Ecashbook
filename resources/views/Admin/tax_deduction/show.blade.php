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

                <tr>
                    <th>Accounting Module</th>
                    <td>
                        @if($deduction->accounting_module == 'Expense')
                            <span class="badge bg-primary">Expense</span>
                        @else
                            {{ $deduction->accounting_module ?? '-' }}
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Expense Type</th>
                    <td>
                        {{ ucfirst(str_replace('_', ' ', $deduction->expense_type ?? '-')) }}
                    </td>
                </tr>

                <tr>
                    <th>Expense Head</th>
                    <td>{{ $deduction->expense_head_name ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Tax Treatment</th>
                    <td>
                        @if($deduction->tax_treatment == 'Fully Allowed')
                            <span class="badge bg-success">Fully Allowed</span>

                        @elseif($deduction->tax_treatment == 'Partial Allowed')
                            <span class="badge bg-warning text-dark">Partial Allowed</span>

                        @elseif($deduction->tax_treatment == 'Disallowed')
                            <span class="badge bg-danger">Disallowed</span>

                        @else
                            -
                        @endif
                    </td>
                </tr>

                @if($deduction->tax_treatment == 'Fully Allowed')
                    <tr>
                        <th>Allowed Deduction Ratio (%)</th>
                        <td>{{ $deduction->allowed_ratio ?? 0 }}%</td>
                    </tr>
                @endif

                @if($deduction->tax_treatment == 'Partial Allowed')
                    <tr>
                        <th>Allow Start (%)</th>
                        <td>{{ $deduction->allow_start ?? 0 }}%</td>
                    </tr>

                    <tr>
                        <th>Allow End (%)</th>
                        <td>{{ $deduction->allow_end ?? 0 }}%</td>
                    </tr>
                @endif

                @if($deduction->tax_treatment == 'Disallowed')
                    <tr>
                        <th>Allowed Deduction Ratio (%)</th>
                        <td>0%</td>
                    </tr>
                @endif

                <tr>
                    <th>Status</th>
                    <td>
                        @if($deduction->is_active == 1)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Created At</th>
                    <td>
                        {{ $deduction->created_at ? \Carbon\Carbon::parse($deduction->created_at)->format('d-m-Y h:i A') : '-' }}
                    </td>
                </tr>

            </table>

            <div class="text-end">
                <a href="{{ route('tax.index') }}" class="btn btn-secondary">
                    Back
                </a>

                <a href="{{ route('tax.edit', $deduction->id) }}"
                class="btn btn-primary">
                    Edit
                </a>
            </div>

        </div>
    </div>

</div>

@endsection