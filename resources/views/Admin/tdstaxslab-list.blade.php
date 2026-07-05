@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/tds-tax-slab-list') }}">TDS TAX SLAB</a></li>
                        <li class="breadcrumb-item" aria-current="page">TDS TAX SLAB List</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">TDS TAX SLAB List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ route('admin.add-tds-tax-slab') }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New TDS TAX SLAB</a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
                                <th>Created</th>
                                <th>Module</th>
                                <th>Category</th>
                                <th>TDS Section</th>
                                <th>Rate / Slab</th>
                                <th>Entity</th>
                                <th>Status</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($tdslist as $key => $tds)
                                <tr>
                                    <td class="text-end">{{ $key + 1 }}</td>

                                    <td>{{ $tds->created_at->format('d-m-Y') }}</td>

                                    <td>
                                        <span class="badge bg-info">{{ $tds->module }}</span>
                                    </td>

                                    <td>{{ $tds->category_name ?? $tds->category }}</td>

                                    <td>{{ $tds->tds_section }}</td>

                                    <td>
                                        @if($tds->category === 'employee_benefits')
                                            <span class="badge bg-warning">As per slab</span>

                                            <ul class="small mb-0 mt-1">
                                                @foreach($tds->salarySlabs as $slab)
                                                    <li>
                                                        ₹{{ number_format($slab->from_amount) }}
                                                        –
                                                        {{ $slab->to_amount === 'Above'
                                                            ? 'Above'
                                                            : '₹'.number_format($slab->to_amount) }}
                                                        : {{ $slab->tax_rate }}%
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            {{ $tds->tds_rate }}
                                        @endif
                                    </td>

                                    <td>{{ $tds->entity }}</td>

                                    <td>
                                        @if($tds->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ url('/edit-tds-tax-slab/'.$tds->id) }}"
                                        class="btn btn-sm btn-primary">
                                            <i class="ti ti-pencil"></i>
                                        </a>

                                        <a href="javascript:void(0);"
                                        data-id="{{ $tds->id }}"
                                        class="btn btn-sm btn-danger deleteTds"
                                        data-bs-toggle="modal"
                                        data-bs-target="#delete_modal">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete TDS SLAB </h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" id="del_tds" class="w-100 btn btn-primary">
                                Delete
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-secondary paid-cancel-btn">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).on("click", ".expenses", function() {
        var itemId = $(this).data("id");

        $("#del_tds").off("click").on("click", function() {
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: "DELETE",
                url: "/deleteTdsTaxSlab/" + itemId, // Pass ID in the URL
                dataType: "json",
                success: function(data) {
                    if (data.status === "success") {
                        // window.location.href = data.redirect;
                        showToast("Delete Successfully", "success");
                        setTimeout(() => {
                            setTimeout(() => location.reload(), 2000);
                        }, 2000);
                    } else {
                        // alert(data.message); // Show error message if delete fails
                        showToast(data.message, "error");
                    }
                },
                error: function() {
                    // alert("Something went wrong. Please try again.");
                    showToast("Something went wrong. Please try again.", "error");
                }
            });
        });
    });
</script>

@endsection