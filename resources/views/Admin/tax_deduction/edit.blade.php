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
                        <li class="breadcrumb-item">Edit</li>
                    </ul>
                </div>

                <div class="col-md-12">
                    <h2 class="mb-0">Edit Tax Deduction</h2>
                </div>

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('tax.update', $deduction->id) }}" method="POST">
                @csrf
                @method('POST')

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label>Deduction Name <span class="text-danger">*</span></label>
                        <input type="text" name="deduction_name" class="form-control" value="{{ $deduction->deduction_name }}" required>
                    </div>
					
					<div class="col-md-4 mb-3">
                        <label>TAX Section </label>
                        <input type="text" name="income_tax_section" class="form-control" value="{{ $deduction->income_tax_section }}">
                    </div>

                    {{-- CATEGORY --}}
                    <div class="col-md-4 mb-3">
                        <label>Category</label>
                        <select name="deduction_category" class="form-control">
                            @foreach($dropdowns['deduction_category'] as $item)
                                <option value="{{ $item }}" {{ $deduction->deduction_category == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TYPE --}}
                    <div class="col-md-4 mb-3">
                        <label>Deduction Type <span class="text-danger">*</span></label>
                        <select name="deduction_type" class="form-control">
                            @foreach($dropdowns['deduction_type'] as $item)
                                <option value="{{ $item }}" {{ $deduction->deduction_type == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TAX --}}
                    <div class="col-md-4 mb-3">
                        <label>Tax Treatment <span class="text-danger">*</span></label>
                        <select name="tax_treatment" class="form-control">
                            @foreach($dropdowns['tax_treatment'] as $item)
                                <option value="{{ $item }}" {{ $deduction->tax_treatment == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- LIMIT TYPE --}}
                    <div class="col-md-4 mb-3">
                        <label>Limit Type</label>
                        <select name="limit_type" class="form-control">
                            @foreach($dropdowns['limit_type'] as $item)
                                <option value="{{ $item }}" {{ $deduction->limit_type == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- BASE --}}
                    <div class="col-md-4 mb-3">
                        <label>Base Amount Source</label>
                        <select name="base_amount_source" class="form-control">
                            @foreach($dropdowns['base_amount_source'] as $item)
                                <option value="{{ $item }}" {{ $deduction->base_amount_source == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- AUTO --}}
                    <div class="col-md-4 mb-3">
                        <label>Automation Mode</label>
                        <select name="automation_mode" class="form-control">
                            @foreach($dropdowns['automation_mode'] as $item)
                                <option value="{{ $item }}" {{ $deduction->automation_mode == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Limit Value</label>
                        <input type="number" step="0.01" name="limit_value" class="form-control" value="{{ $deduction->limit_value }}" >
                    </div>
					
					<div class="col-md-4 mb-3">
                        <label>Limit Rate(%)</label>
                        <input type="number" step="0.01" name="limit_rate" class="form-control" value="{{ $deduction->limit_rate }}" >
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Limit Formula</label>
                        <input type="text" name="limit_formula" class="form-control" value="{{ $deduction->limit_formula }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>FY <span class="text-danger">*</span></label>
                        <input type="text" name="applicable_fy" class="form-control" value="{{ $deduction->applicable_fy }}" required  placeholder="2025-2026">
                    </div>

                    {{-- MODULE --}}
                    <div class="col-md-4 mb-3">
                        <label>Linked Module <span class="text-danger">*</span></label>
                        <select name="linked_module" class="form-control" required>
                            @foreach($dropdowns['linked_module'] as $item)
                                <option value="{{ $item }}" {{ $deduction->linked_module == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Rule Priority</label>
                        <input type="number" name="rule_priority" class="form-control" value="{{ $deduction->rule_priority }}">
                    </div>

                </div>

                <div class="text-end">
                    <a href="{{ route('tax.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection