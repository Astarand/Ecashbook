<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">
                    View Dropdown Value
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Module</label>
                        <p id="view_module" class="border rounded p-2 bg-light mb-0"></p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Dropdown Name</label>
                        <p id="view_dropdown_name" class="border rounded p-2 bg-light mb-0"></p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Option Text</label>
                        <p id="view_option_text" class="border rounded p-2 bg-light mb-0"></p>
                    </div>
					
					<div class="col-md-6 mb-3">
                        <label class="fw-bold">Type</label>
                        <p id="view_type" class="border rounded p-2 bg-light mb-0"></p>
                    </div>
					
					<div class="col-md-6 mb-3">
                        <label class="fw-bold">Option Value</label>
                        <p id="view_option_value" class="border rounded p-2 bg-light mb-0"></p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Sort Order</label>
                        <p id="view_sort_order" class="border rounded p-2 bg-light mb-0"></p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Status</label>
                        <p id="view_status" class="border rounded p-2 bg-light mb-0"></p>
                    </div>

                    

                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Close
                </button>

            </div>

        </div>
    </div>
</div>

<script>

$(document).ready(function(){

    // ==========================
    // View Record
    // ==========================

    $(document).on('click','.viewBtn',function(){

        let id = $(this).data('id');

        $.ajax({

            url : "/dropdown-values/view/" + id,

            type : "GET",

            success:function(res){

                $("#view_module").text(res.module);

                $("#view_dropdown_name").text(res.dropdown_name);

                $("#view_option_value").text(res.option_value);

                $("#view_option_text").text(res.option_text);
                $("#view_type").text(res.type);

                $("#view_sort_order").text(res.sort_order);

                if(res.status==1)
                {
                    $("#view_status").html('<span class="badge bg-success">Active</span>');
                }
                else
                {
                    $("#view_status").html('<span class="badge bg-danger">Inactive</span>');
                }

                $("#viewModal").modal('show');

            }

        });

    });



    // ==========================
    // Delete Record
    // ==========================

    $(document).on('click','.deleteBtn',function(){

        let id=$(this).data('id');

        Swal.fire({

            title:'Are you sure?',

            text:"You won't be able to revert this!",

            icon:'warning',

            showCancelButton:true,

            confirmButtonColor:'#3085d6',

            cancelButtonColor:'#d33',

            confirmButtonText:'Yes, Delete'

        }).then((result)=>{

            if(result.isConfirmed){

                $.ajax({

                    url:"/dropdown-values/delete/"+id,

                    type:"POST",

                    data:{
                        _token:"{{ csrf_token() }}",
                        _method:"DELETE"
                    },

                    success:function(res){

                        if(res.status==1){

                            Swal.fire({

                                icon:'success',

                                title:'Deleted Successfully',

                                timer:1500,

                                showConfirmButton:false

                            }).then(()=>{

                                location.reload();

                            });

                        }

                    }

                });

            }

        });

    });

});

</script>