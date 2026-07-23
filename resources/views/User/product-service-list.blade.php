@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="#">Business Operations</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.ProductServiceList') }}">Product & Services</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Product / Services List</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-product-service-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Product / Service List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ route('user.AddProductService') }}" class="btn btn-primary" id="add-product-service-btn"><i class="ti ti-square-plus"></i> Add New Product / Service</a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card" id="product-service-table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
                                <th>Product ID</th>
                                <th>Product / Service Name</th>
                                <th>Listing Date</th>
                                <th>HSN / SAC Code</th>
                                <th>Type</th>
                                <th>Selling Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($items as $item)
                            <tr>
                                <td class="text-end"><?php echo $i++; ?></td>
                                <td><span class="text-muted text-hover-primary">{{ $item->prodId }}</span></td>
                                <td><span class="text-muted text-hover-primary">
                                        @if($item->item_type =='service')
                                        {{ $item->service_name }}
                                        @else
                                        {{ $item->item_name }}
                                        @endif</span></td>
                                <td><a class="text-muted text-hover-primary" href="#">{{$item->created_at}}</a></td>
                                <td><span class="text-muted text-hover-primary">
                                        @if($item->item_type =='service')
                                        {{$item->sac_code}}
                                        @else
                                        {{ $item->hsn_code }}
                                        @endif
                                    </span></td>
                                <td><span class="text-muted text-hover-primary">{{$item->item_type}}
                                    </span></td>
                                <td><span class="text-muted text-hover-primary">₹
                                        @if($item->item_type =='service')
                                        {{$item->ser_selling_price}}
                                        @else
                                        {{ $item->selling_price }}
                                        @endif
                                    </span></td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a href="{{ url('/view-product/'.base64_encode($item->id)) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>

                                            @if(Auth::user()->u_type != 1 && Auth::user()->u_type != 4)

                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                    <a href="{{ url('/edit-product/'.base64_encode($item->id)) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                        <i class="ti ti-edit-circle f-18"></i>
                                                    </a>
                                                </li>

                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                    <a href="#" class="avtar avtar-xs btn-link-danger btn-pc-default delete-btn"
                                                    data-id="{{$item->id}}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#delete_modal">
                                                        <i class="ti ti-trash f-18"></i>
                                                    </a>
                                                </li>

                                            @endif
                                            {{-- <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                <a href="{{ url('/edit-product/'.base64_encode($item->id)) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                <a href="#" class="avtar avtar-xs btn-link-danger btn-pc-default delete-btn" data-id="{{$item->id}}" data-bs-toggle="modal" data-bs-target="#delete_modal">
                                                    <i class="ti ti-trash f-18"></i>
                                                </a>
                                            </li> --}}
                                        </ul>
                                    </div>
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
                    <h3>Delete Product/Service</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="confirmDelete" data-bs-dismiss="modal" class="w-100 btn btn-danger">
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

@endsection

@section('page-script')
<script>
    let deleteId = null; // Store the ID of the customer to be deleted

    // Capture the customer ID when the delete button is clicked
    $(document).on('click', '.delete-btn', function (e) {
		e.preventDefault(); 
        deleteId = $(this).data('id');
        //alert(deleteId);
    });
    // alert(deleteId);
    // Handle the delete confirmation
    $('#confirmDelete').on('click', function() {
        //alert('hello');
        if (deleteId) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                url: '/product-delete/' + deleteId, // Update with your delete route
                type: 'DELETE',
                success: function(response) {
                    alert(response.message); // Show success message
                    location.reload(); // Reload the page
                },
                error: function(xhr) {
                    alert("Error deleting customer!");
                }
            });
        }
    });

    function startProductServiceTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Product & Service Directory',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-box" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Manage your list of inventory products and service items, record HSN/SAC codes, and monitor selling prices.</p></div>'
                },
                {
                    element: '#add-product-service-btn',
                    title: 'Add Product / Service',
                    intro: 'Click here to create a new item profile, set tax codes (HSN/SAC), specify inventory type, and establish default unit pricing.'
                },
                {
                    element: '#product-service-table-card',
                    title: 'Product & Services Catalog',
                    intro: 'Review your registered products and services, showing codes, classification (Product vs Service), and default selling prices.'
                },
                {
                    element: '.prod-action-links',
                    title: 'Action Controls',
                    intro: 'View details of catalog items, edit pricing or code properties, or remove records from your directories.'
                }
            ],
            showBullets: true,
            showProgress: true,
            helperElementPadding: 5,
            exitOnOverlayClick: false,
            skipIfNoElement: true,
            doneLabel: 'Done',
            nextLabel: 'Next',
            prevLabel: 'Prev',
            skipLabel: 'Skip'
        }).start();
    }

    $(document).ready(function() {
        $('#start-product-service-tour').on('click', function(e) {
            e.preventDefault();
            startProductServiceTour();
        });
    });
</script>
@endsection