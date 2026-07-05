@extends('App.Layout')

@section('container')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="content-page-header">
                    <h5>View All Notifications</h5>
                    @if(session()->has('status') && session('status') == 'success')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>{{session('message')}}</strong>
                        </div>
                    @elseif(session()->has('status') && session('status') != 'success')
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>{{session('message')}}</strong>
                    </div>
                    @endif
                    <div class="list-btn">
                        <ul class="filter-list">
                            
                        </ul>
                    </div>
                </div>
            </div>
            <!--<div id="filter_inputs" class="card filter-card">
                <div class="card-body pb-0">
                    <div class="row">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    </div>
                </div>
            </div>-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-body table-card">
                        <div class="table-responsive">
                            <table class="table tbl-product" id="pc-dt-simple">
                                <thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th>Title</th>
										<th>Notification</th>
										<th>Date</th>
									</tr>
                                </thead>
                                <tbody>
                                @php($inc =  1)
                                @foreach($notifications as $row)
                                    <tr>
                                        <td>{{$inc}}</td>
                                        <td>
                                            {{$row->name}}
                                        </td>
                                        <td>
                                            {{$row->noti_title}}
                                        </td>
                                        <td>
                                            {{$row->msg}}
                                        </td>
                                        <td>
                                            {{$row->created_at}}
                                        </td>
                                        
                                    </tr>
                                    @php($inc++)
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <div class="modal custom-modal fade" id="delete_modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                    <h3>Delete Vendor</h3>
                    <p>Are you sure want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="reset" data-bs-dismiss="modal" class="w-100 btn btn-primary" id="del_vendor">Delete</button>
                        </div>
                        <div class="col-6">
                            <button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary paid-cancel-btn">Cancel</button>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
$(document).ready(function(){
    let delId   =   0;
    $(document).on('click', '.delete_vendor', function(){
        delId   =   $(this).data('id');
        $('#delete_modal').modal('show');
    });
    $(document).on('click', '#delete-continue-btn', function(){
        window.location.href = "{{url('/deletevendor/')}}/"+delId;
    });

    $("#delete_modal").on("hidden.bs.modal", function () {
        delId   =   0;
    });
});
</script>
@endsection