<!-- Add/Edit Modal -->
<div class="modal fade" id="dropdownModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="dropdownForm">
            @csrf

            <input type="hidden" id="id" name="id">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        Add Dropdown Value
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <!-- Module -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Module <span class="text-danger">*</span>
                            </label>

                           <select class="form-select" name="module" id="module">
								<option value="">-- Select Module --</option>
								<option value="Expense">Expense</option>
								<option value="Assets">Assets</option>
								<option value="Purchase">Purchase</option>
							</select>


                            <small class="text-danger error_module"></small>
                        </div>

                        <!-- Dropdown Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Dropdown Name <span class="text-danger">*</span>
                            </label>
								   
							<select class="form-select" name="dropdown_name" id="dropdown_name">
								<option value="">-- Select Module --</option>
								<option value="direct">Direct</option>
								<option value="indirect">Indirect</option>
								<option value="non_operating">Non-Operating</option>
							</select>

                            <small class="text-danger error_dropdown_name"></small>
                        </div>

                        <!-- Option Text -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Option Text <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   class="form-control"
                                   name="option_text"
                                   id="option_text">

                            <small class="text-danger error_option_text"></small>
                        </div>
						
						 <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Type <span class="text-danger">*</span>
                            </label>
								   
							<select class="form-select" name="type" id="type">
								<option value="">-- Select Type --</option>
								<option value="Operating">Operating</option>
								<option value="Non-Operating">Non-Operating</option>
							</select>

                            <small class="text-danger error_type"></small>
                        </div>

                        <!-- Sort Order -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Sort Order
                            </label>

                            <input type="number"
                                   class="form-control"
                                   name="sort_order"
                                   id="sort_order"
                                   value="1">

                            <small class="text-danger error_sort_order"></small>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Status
                            </label>

                            <select class="form-select"
                                    name="status"
                                    id="status">

                                <option value="1">
                                    Active
                                </option>

                                <option value="0">
                                    Inactive
                                </option>

                            </select>

                            <small class="text-danger error_status"></small>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit"
                            class="btn btn-primary"
                            id="saveBtn">

                        Save

                    </button>

                </div>

            </div>

        </form>
    </div>
</div>

<script>

$(document).ready(function(){

    // Add Button

    $("#addBtn").click(function(){

        $("#dropdownForm")[0].reset();

        $("#id").val('');

        $(".text-danger").html('');

        $("#modalTitle").html('Add Dropdown Value');

        $("#saveBtn").html('Save');

    });


    // Save & Update

    $("#dropdownForm").submit(function(e){

        e.preventDefault();

        $(".text-danger").html('');

        let id=$("#id").val();

        let url='';

        if(id=='')
        {
            url="{{ route('dropdown.store') }}";
        }
        else
        {
            url="/dropdown-values/update/"+id;
        }

        $.ajax({

            url:url,

            type:'POST',

            data:$(this).serialize(),

            success:function(res){

                if(res.status==0)
                {
                    $.each(res.errors,function(key,value){

                        $(".error_"+key).html(value[0]);

                    });

                    return;
                }

                $('#dropdownModal').modal('hide');

                Swal.fire({

                    icon:'success',

                    title:res.msg,

                    timer:1500,

                    showConfirmButton:false

                }).then(()=>{

                    location.reload();

                });

            }

        });

    });



    // Edit

    $(".editBtn").click(function(){

        let id=$(this).data('id');

        $.get("/dropdown-values/edit/"+id,function(res){

            $("#modalTitle").html("Edit Dropdown Value");

            $("#saveBtn").html("Update");

            $("#id").val(res.id);

            $("#module").val(res.module);

            $("#dropdown_name").val(res.dropdown_name);

            $("#option_text").val(res.option_text);
            $("#type").val(res.type);

            $("#sort_order").val(res.sort_order);

            $("#status").val(res.status);

            $("#dropdownModal").modal("show");

        });

    });

});

</script>