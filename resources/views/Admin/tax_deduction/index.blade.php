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
                                <th>Name</th>
                                <th>Section</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Limit Type</th>
                                <th>Base Source</th>
                                <th>Automation</th>
                                <th>Limit Value</th>
                                <th>Limit Rate</th>
                                <th>FY</th>
                                <th>Module</th>
                                <th>Tax Treatment</th>
                                <th>Status</th>
                                <th width="140">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($data as $key => $deduction)

                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>{{ $deduction->deduction_name ?? '-' }}</td>

                                    <td>{{ $deduction->income_tax_section ?? '-' }}</td>

                                    <td>{{ $deduction->deduction_category ?? '-' }}</td>

                                    <td>{{ $deduction->deduction_type ?? '-' }}</td>

                                    <td>{{ $deduction->limit_type ?? '-' }}</td>

                                    <td>{{ $deduction->base_amount_source ?? '-' }}</td>

                                    <td>
                                        @if(!empty($deduction->automation_mode))
                                            <span class="badge bg-info">
                                                {{ $deduction->automation_mode }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        {{ $deduction->limit_value !== null ? $deduction->limit_value : '-' }}
                                    </td>
									<td>
                                        {{ $deduction->limit_rate }} %
                                    </td>

                                    <td>{{ $deduction->applicable_fy ?? '-' }}</td>

                                    <td>{{ $deduction->linked_module ?? '-' }}</td>
                                    <td>
										@if($deduction->tax_treatment == 'Allowable')
											<span class="badge bg-success">Allowable</span>
										@elseif($deduction->tax_treatment == 'Disallowed')
											<span class="badge bg-danger">Disallowed</span>
										@else
											<span class="badge bg-secondary">-</span>
										@endif
									</td>

                                    <td>
                                        @if($deduction->is_active == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
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
                                    <td colspan="13" class="text-center text-muted">
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