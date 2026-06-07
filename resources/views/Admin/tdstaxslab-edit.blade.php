@extends('App.Layout')

@section('container')
<div class="pc-content">

    <div class="page-header mb-3">
        <h4>Update TDS Rule</h4>
    </div>

    <div class="card">
        <div class="card-body">

            <form id="tdsRuleEditFrm">
                @csrf
                <input type="hidden" name="id" value="{{ $tdsRule->id }}">

                <div class="row">

                    {{-- MODULE --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Module *</label>
                        <select name="module" id="module" class="form-control" disabled>
                            <option value="">Select</option>
                            @foreach(['Expenses','Purchase','Assets'] as $m)
                                <option value="{{ $m }}" {{ $tdsRule->module==$m?'selected':'' }}>
                                    {{ $m }}
                                </option>
                            @endforeach
                        </select>
                        <!-- Add a hidden input to submit the value -->
                        <input type="hidden" name="module" value="{{ $tdsRule->module }}">
                    </div>

                    {{-- CATEGORY --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Category *</label>
                        <input type="text" name="category" class="form-control"
                               value="{{ $tdsRule->category }}" required>
                    </div>

                    {{-- SECTION --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label">TDS Section *</label>
                        <input type="text" name="tds_section"
                               value="{{ $tdsRule->tds_section }}"
                               class="form-control" required>
                    </div>

                    {{-- RATE --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">TDS Rate(%) *</label>
                        <input type="text" name="tds_rate" id="tds_rate"
                               value="{{ $tdsRule->tds_rate }}"
                               class="form-control" required>
                    </div>

                    {{-- ENTITY --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Entity *</label>
                        <select name="entity" class="form-control">
                            @foreach(['All','Proprietorship','Firm'] as $e)
                                <option value="{{ $e }}" {{ $tdsRule->entity==$e?'selected':'' }}>
                                    {{ $e }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- THRESHOLD --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Threshold</label>
                        <input type="text" name="threshold" id="threshold"
                               value="{{ $tdsRule->threshold_limit }}"
                               class="form-control">
                    </div>

                    {{-- NOTES --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Notes</label>
                        <input type="text" name="notes"
                               value="{{ $tdsRule->notes }}"
                               class="form-control">
                    </div>
                </div>

                {{-- SALARY SLABS --}}
                <div id="salarySlabBox"
                     class="mt-4 {{ $tdsRule->category=='Salary & Wages'?'':'d-none' }}">

                    <h5>Salary Tax Slabs (Section 192)</h5>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>From</th>
                                <th>To</th>
                                <th>Rate %</th>
                                <th width="60">Action</th>
                            </tr>
                        </thead>
                        <tbody id="slabRows">
                            @foreach($tdsRule->salarySlabs as $slab)
                                <tr>
                                    <td><input type="number" class="form-control" value="{{ $slab->from_amount }}"></td>
                                    <td><input type="text" class="form-control" value="{{ $slab->to_amount }}"></td>
                                    <td><input type="number" class="form-control" value="{{ $slab->tax_rate }}"></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm removeRow">×</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="button" id="addSlabRow" class="btn btn-sm btn-success">
                        + Add Slab
                    </button>
                </div>

                <div class="text-end mt-4">
                    <a href="{{ url('/tds-tax-slab-list') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button class="btn btn-primary">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

	$('#tds_rate,#threshold').on('input', function () {
		this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
	});

    $('#tdsRuleEditFrm').on('submit', function(e){
        e.preventDefault();

        let formData = new FormData(this);
        let slabs = [];

        if (!$('#salarySlabBox').hasClass('d-none')) {
            $('#slabRows tr').each(function(){
                let from = $(this).find('input:eq(0)').val();
                let to   = $(this).find('input:eq(1)').val();
                let rate = $(this).find('input:eq(2)').val();

                if(from && rate){
                    slabs.push({
                        from_amount: from,
                        to_amount: to ? to : 'Above',
                        tax_rate: rate
                    });
                }
            });
        }

        if(slabs.length){
            formData.append('salary_slabs', JSON.stringify(slabs));
        }

        $.ajax({
            url: "/update_tds_tax_slab/{{ $tdsRule->id }}",
            type: "POST",
            data: formData,
            contentType:false,
            processData:false,
            headers:{
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            success:function(res){
                if(res.class==='succ'){
                    showToast(res.message,'success');
                    setTimeout(()=>location.href=res.redirect,1500);
                }
            }
        });
    });

    $('#addSlabRow').click(function(){
        $('#slabRows').append(`
            <tr>
                <td><input type="number" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="number" class="form-control"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm removeRow">×</button>
                </td>
            </tr>
        `);
    });

    $(document).on('click','.removeRow',function(){
        $(this).closest('tr').remove();
    });
</script>

@endsection
