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
                        <li class="breadcrumb-item" aria-current="page">View Deduction</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <h3>View Deduction</h3>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Deduction Name</label>
                    <input type="text" readonly class="form-control" value="{{ $deduction->deduction_name }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Income Tax Section</label>
                    <input type="text" readonly class="form-control" value="{{ $deduction->income_tax_section }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Deduction Category</label>
                    <input type="text" readonly class="form-control" value="{{ $deduction->deduction_category }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Deduction Type</label>
                    <input type="text" readonly class="form-control" value="{{ $deduction->deduction_type }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Limit Type</label>
                    <input type="text" readonly class="form-control" value="{{ $deduction->limit_type }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Base Amount Source</label>
                    <input type="text" readonly class="form-control" value="{{ $deduction->base_amount_source }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Automation Mode</label>
                    <input type="text" readonly class="form-control" value="{{ $deduction->automation_mode }}">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Limit Value</label>
                    <input type="text" readonly class="form-control" value="{{ $deduction->limit_value }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Limit Formula</label>
                    <input type="text" readonly class="form-control" value="{{ $deduction->limit_formula }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Applicable FY</label>
                    <input type="text" readonly class="form-control" value="{{ $deduction->applicable_fy }}">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Linked Module</label>
                    <input type="text" readonly class="form-control" value="{{ $deduction->linked_module }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Remarks / Notes</label>
                    <input type="text" readonly class="form-control" value="{{ $deduction->remarks }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Allow / Disallow</label>
                    <input type="text" readonly class="form-control" value="{{ ucfirst($deduction->allow_disallow_status) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Active Status</label>
                    <input type="text" readonly class="form-control" value="{{ $deduction->active_status == 1 || $deduction->active_status === '1' ? 'Active' : 'Inactive' }}">
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('deduction-master.index') }}" class="btn btn-secondary">Back to List</a>
                <a href="{{ route('deduction-master.edit', $deduction->id) }}" class="btn btn-primary">Edit</a>
            </div>
        </div>
    </div>
</div>

@endsection