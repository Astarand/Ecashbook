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

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label>Accounting Module</label>

                        <input type="hidden"
                            name="accounting_module"
                            value="{{ $deduction->accounting_module }}">

                        <select class="form-control" disabled>
                            <option value="Expense" selected>
                                {{ $deduction->accounting_module }}
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Expense Type</label>

                        <input type="hidden"
                            name="expense_type"
                            value="{{ $deduction->expense_type }}">

                        <select class="form-control" disabled>
                            <option selected>
                                {{ ucfirst(str_replace('_',' ',$deduction->expense_type)) }}
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Expense Head</label>

                        <input type="hidden"
                            name="expense_head"
                            value="{{ $deduction->expense_head }}">

                        <input type="text"
                            class="form-control"
                            value="{{ $deduction->expense_head }}"
                            readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Tax Treatment <span class="text-danger">*</span></label>
                        <select name="tax_treatment"
                                id="tax_treatment"
                                class="form-control"
                                required>

                            <option value="">Select</option>

                            <option value="Fully Allowed"
                                {{ $deduction->tax_treatment == 'Fully Allowed' ? 'selected' : '' }}>
                                Fully Allowed
                            </option>

                            <option value="Partial Allowed"
                                {{ $deduction->tax_treatment == 'Partial Allowed' ? 'selected' : '' }}>
                                Partial Allowed
                            </option>

                            <option value="Disallowed"
                                {{ $deduction->tax_treatment == 'Disallowed' ? 'selected' : '' }}>
                                Disallowed
                            </option>

                        </select>
                    </div>

                    <div class="col-md-4 mb-3 {{ in_array($deduction->tax_treatment,['Fully Allowed','Disallowed']) ? '' : 'd-none' }}"
                        id="allowedRatioDiv">

                        <label>Allowed Deduction Ratio (%)</label>

                        <input type="number"
                            step="0.01"
                            name="allowed_ratio"
                            id="allowed_ratio"
                            class="form-control"
                            value="{{ $deduction->allowed_ratio }}"
                            {{ $deduction->tax_treatment == 'Disallowed' ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-4 mb-3 {{ $deduction->tax_treatment == 'Partial Allowed' ? '' : 'd-none' }}"
                        id="allowStartDiv">

                        <label>Allow Start (%)</label>

                        <input type="number"
                            step="0.01"
                            name="allow_start"
                            class="form-control"
                            value="{{ $deduction->allow_start }}">
                    </div>

                    <div class="col-md-4 mb-3 {{ $deduction->tax_treatment == 'Partial Allowed' ? '' : 'd-none' }}"
                        id="allowEndDiv">

                        <label>Allow End (%)</label>

                        <input type="number"
                            step="0.01"
                            name="allow_end"
                            class="form-control"
                            value="{{ $deduction->allow_end }}">
                    </div>

                </div>

                <div class="text-end">
                    <a href="{{ route('tax.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
<script>
    $('#tax_treatment').change(function() {

            let value = $(this).val();

            $('#allowedRatioDiv').addClass('d-none');
            $('#allowStartDiv').addClass('d-none');
            $('#allowEndDiv').addClass('d-none');

            if (value == 'Fully Allowed') {

                $('#allowedRatioDiv').removeClass('d-none');
                $('#allowed_ratio').prop('readonly', false).val('');

            } else if (value == 'Partial Allowed') {

                $('#allowStartDiv').removeClass('d-none');
                $('#allowEndDiv').removeClass('d-none');

            } else if (value == 'Disallowed') {

                $('#allowedRatioDiv').removeClass('d-none');
                $('#allowed_ratio')
                    .val(0)
                    .prop('readonly', true);
            }
        });
</script>

@endsection