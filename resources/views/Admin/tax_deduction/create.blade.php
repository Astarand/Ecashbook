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
                        <li class="breadcrumb-item">Create</li>
                    </ul>
                </div>

                <div class="col-md-12">
                    <h2 class="mb-0">Create Tax Deduction</h2>
                </div>

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('tax.store') }}" method="POST">
                @csrf

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label>Deduction Name <span class="text-danger">*</span></label>
                        <input type="text" name="deduction_name" class="form-control" value="{{ old('deduction_name') }}" required>
                    </div>
					
					<div class="col-md-4 mb-3">
                        <label>TAX Section </label>
                        <input type="text" name="income_tax_section" class="form-control" value="{{ old('income_tax_section') }}" >
                    </div>

                    {{-- CATEGORY --}}
                    <div class="col-md-4 mb-3">
                        <label>Deduction Category <span class="text-danger">*</span></label>
                        <select name="deduction_category" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($dropdowns['deduction_category'] as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TYPE --}}
                    <div class="col-md-4 mb-3">
                        <label>Deduction Type <span class="text-danger">*</span></label>
                        <select name="deduction_type" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($dropdowns['deduction_type'] as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TAX --}}
                    <div class="col-md-4 mb-3">
                        <label>Tax Treatment <span class="text-danger">*</span></label>
                        <select name="tax_treatment" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($dropdowns['tax_treatment'] as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- LIMIT TYPE --}}
                    <div class="col-md-4 mb-3">
                        <label>Limit Type  <span class="text-danger">*</span></label>
                        <select name="limit_type" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($dropdowns['limit_type'] as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- BASE --}}
                    <div class="col-md-4 mb-3">
                        <label>Base Amount Source</label>
                        <select name="base_amount_source" class="form-control">
                            <option value="">Select</option>
                            @foreach($dropdowns['base_amount_source'] as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- AUTO --}}
                    <div class="col-md-4 mb-3">
                        <label>Automation Mode  <span class="text-danger">*</span></label>
                        <select name="automation_mode" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($dropdowns['automation_mode'] as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Limit Value <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" value="0" name="limit_value" class="form-control" required>
                    </div>
					
					<div class="col-md-4 mb-3">
                        <label>Limit Rate(%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" value="0" name="limit_rate" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Limit Formula</label>
                        <input type="text" name="limit_formula" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Applicable FY <span class="text-danger">*</span></label>
                        <input type="text" name="applicable_fy" class="form-control" required placeholder="2025-2026">
                    </div>

                    {{-- MODULE --}}
                    <div class="col-md-4 mb-3">
                        <label>Linked Module <span class="text-danger">*</span></label>
                        <select name="linked_module" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($dropdowns['linked_module'] as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Rule Priority</label>
                        <input type="number" name="rule_priority" class="form-control" value="0">
                    </div>

                </div>

                <div class="text-end">
                    <a href="{{ route('tax.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection