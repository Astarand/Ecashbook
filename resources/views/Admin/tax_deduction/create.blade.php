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

                    {{-- <div class="col-md-4 mb-3">
                        <label>Deduction Name <span class="text-danger">*</span></label>
                        <input type="text" name="deduction_name" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>TAX Section</label>
                        <input type="text" name="income_tax_section" class="form-control">
                    </div> --}}

                    <div class="col-md-4 mb-3">
                        <label>Accounting Module <span class="text-danger">*</span></label>
                        <select name="accounting_module" id="accounting_module" class="form-control" required>
                            <option value="">Select</option>
                            <option value="Expense">Expense</option>
                            {{-- <option value="Asset">Asset</option> --}}
                        </select>
                    </div>

                    <div class="col-md-4 mb-3 d-none" id="expenseTypeDiv">
                        <label>Expense Type</label>
                        <select name="expense_type" id="expense_type" class="form-control">
                            <option value="">Select</option>
                            <option value="direct">Direct</option>
                            <option value="indirect">Indirect</option>
                            <option value="non_operating">Non Operating</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3 d-none" id="expenseHeadDiv">
                        <label>Expense Head</label>
                        <select name="expense_head" id="expense_head" class="form-control">
                            <option value="">Select</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Tax Treatment <span class="text-danger">*</span></label>
                        <select name="tax_treatment" id="tax_treatment" class="form-control" required>
                            <option value="">Select</option>
                            <option value="Fully Allowed">Fully Allowed</option>
                            <option value="Partial Allowed">Partial Allowed</option>
                            <option value="Disallowed">Disallowed</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3 d-none" id="allowedRatioDiv">
                        <label>Allowed Deduction Ratio (%)</label>
                        <input type="number" step="0.01"
                            name="allowed_ratio"
                            id="allowed_ratio"
                            class="form-control">
                    </div>

                    <div class="col-md-4 mb-3 d-none" id="allowStartDiv">
                        <label>Allow Start (%)</label>
                        <input type="number" step="0.01"
                            name="allow_start"
                            class="form-control">
                    </div>

                    <div class="col-md-4 mb-3 d-none" id="allowEndDiv">
                        <label>Allow End (%)</label>
                        <input type="number" step="0.01"
                            name="allow_end"
                            class="form-control">
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
<script>
    $(document).ready(function() {

        $('#accounting_module').change(function() {

            if ($(this).val() == 'Expense') {
                $('#expenseTypeDiv').removeClass('d-none');
            } else {
                $('#expenseTypeDiv').addClass('d-none');
                $('#expenseHeadDiv').addClass('d-none');
            }
        });

        $('#expense_type').change(function() {

            let expenseType = $(this).val();

            if (expenseType != '') {

                $.ajax({
                    url: "{{ route('expense.heads') }}",
                    type: "GET",
                    data: {
                        expense_type: expenseType
                    },
                    success: function(res) {

                        let html = '<option value="">Select</option>';

                        $.each(res, function(i, item) {
                            html += '<option value="'+item.option_value+'">'+item.option_text+'</option>';
                        });

                        $('#expense_head').html(html);
                        $('#expenseHeadDiv').removeClass('d-none');
                    }
                });
            }
        });

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

    });
</script>

@endsection