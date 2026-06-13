@extends('App.Layout')

@section('container')

<div class="pc-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/deduction-master') }}">Deduction Master</a></li>
                        <li class="breadcrumb-item" aria-current="page">Deduction Master List</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Deduction Master List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ route('deduction-master.create') }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add Deduction</a>
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
                                <th>Deduction Name</th>
                                <th>Income Tax Section</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Limit Type</th>
                                <th>Base Amount Source</th>
                                <th>Automation Mode</th>
                                <th>Limit Value</th>
                                <th>FY</th>
                                <th>Linked Module</th>
                                <th>Status</th>
                                <th width="140">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deductions as $key => $deduction)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $deduction->deduction_name }}</td>
                                    <td>{{ $deduction->income_tax_section }}</td>
                                    <td>{{ $deduction->deduction_category }}</td>
                                    <td>{{ $deduction->deduction_type }}</td>

                                    <td>{{ $deduction->limit_type }}</td>
                                    <td>{{ $deduction->base_amount_source }}</td>
                                    <td>{{ $deduction->automation_mode }}</td>
                                    <td>{{ $deduction->limit_value }}</td>

                                    <td>{{ $deduction->applicable_fy }}</td>
                                    <td>{{ $deduction->linked_module }}</td>

                                    <td>
                                        @if($deduction->active_status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('deduction-master.show', $deduction->id) }}"
                                            class="btn btn-sm btn-secondary"
                                            title="View Deduction">
                                            <i class="ti ti-eye"></i>
                                        </a>

                                        <a href="{{ route('deduction-master.edit', $deduction->id) }}"
                                            class="btn btn-sm btn-primary"
                                            title="Edit Deduction">
                                            <i class="ti ti-pencil"></i>
                                        </a>

                                        <form action="{{ route('deduction-master.destroy', $deduction->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this deduction?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Delete Deduction">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
