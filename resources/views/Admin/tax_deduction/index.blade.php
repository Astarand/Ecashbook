@extends('App.Layout')

@section('container')

<div class="pc-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">

                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/tax-deduction-master') }}">Deduction Master</a></li>
                        <li class="breadcrumb-item" aria-current="page">List</li>
                    </ul>
                </div>

                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Deduction Master List</h2>
                    </div>
                </div>

                <div class="col-md-8 text-end">
                    <a href="{{ route('tax.create') }}" class="btn btn-primary">
                        <i class="ti ti-square-plus"></i> Add Deduction
                    </a>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">

            <div class="card card-body table-card">

                <div class="table-responsive">

                    <table class="table tbl-product" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>#</th>
                                
                                <th>Accounting Module</th>
                                <th>Expense Type</th>
                                <th>Expense Head</th>
                                <th>Type</th>
                                <th>Tax Treatment</th>
                                <th>Allowed Ratio (%)</th>
                                <th>Allow Range (%)</th>
                                <th>Status</th>
                                <th width="140">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($data as $key => $deduction)

                                <tr>

                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if($deduction->accounting_module == 'Expense')
                                            <span class="badge bg-primary">
                                                Expense
                                            </span>
                                        @elseif($deduction->accounting_module == 'Asset')
                                            <span class="badge bg-info">
                                                Asset
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>{{ ucfirst(str_replace('_',' ',$deduction->expense_type ?? '-')) }}</td>

                                    <td>{{ $deduction->expense_head_name ?? '-' }}</td>
									
									 <td>{{ $deduction->expense_head_type ?? '-' }}</td>

                                    <td>
                                        @if($deduction->tax_treatment == 'Fully Allowed')
                                            <span class="badge bg-success">
                                                Fully Allowed
                                            </span>

                                        @elseif($deduction->tax_treatment == 'Partial Allowed')
                                            <span class="badge bg-warning text-dark">
                                                Partial Allowed
                                            </span>

                                        @elseif($deduction->tax_treatment == 'Disallowed')
                                            <span class="badge bg-danger">
                                                Disallowed
                                            </span>

                                        @else
                                            <span class="badge bg-secondary">
                                                -
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($deduction->tax_treatment == 'Fully Allowed')
                                            {{ $deduction->allowed_ratio }}%
                                        @elseif($deduction->tax_treatment == 'Disallowed')
                                            0%
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        @if($deduction->tax_treatment == 'Partial Allowed')
                                            {{ $deduction->allow_start ?? 0 }}%
                                            -
                                            {{ $deduction->allow_end ?? 0 }}%
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        @if($deduction->is_active == 1)
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td>

                                        <a href="{{ route('tax.show', $deduction->id) }}"
                                        class="btn btn-sm btn-secondary"
                                        title="View">
                                            <i class="ti ti-eye"></i>
                                        </a>

                                        <a href="{{ route('tax.edit', $deduction->id) }}"
                                        class="btn btn-sm btn-primary"
                                        title="Edit">
                                            <i class="ti ti-pencil"></i>
                                        </a>

                                        <form action="{{ route('tax.delete', $deduction->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this record?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="11" class="text-center text-muted">
                                        No records found
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>
</div>

@endsection