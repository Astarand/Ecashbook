@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/ca-dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Task Wise Quote Set</li>
                    </ul>
                    <a href="javascript:void(0);" onclick="startCAQuoteSetTour();" id="start-ca-quoteset-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Task Wise Quote Set</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end mt-2">
                    <a href="{{ route('ca.AddQuote') }}" id="add-quote-btn" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Quote</a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="row">
      <div class="col-12">
        <div class="card table-card" id="quotes-table-card">
          <div class="card-header d-flex align-items-center justify-content-between pt-4 pb-3">
            <h3 class="mb-0">Quote Set</h3>
          </div>
          <div class="card-body pt-2 pb-4">
            <div class="table-responsive">
              <table class="table table-hover" id="pc-dt-simple">
                <thead>
                  <tr class="text-center">
                    <th>#</th>
                    <th>Task Category</th>
                    <th>Goverment Fees</th>
                    <th>Services Charges</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php $i = 1; ?>
                @foreach ($quotes as $val)
                    <tr class="text-center">
                        <td><?php echo $i++; ?></td>
                        <td>{{$val->category_name}}</td>
                        <td>₹{{$val->govfee}}</td>
                        <td>₹{{$val->service_charge}}</td>
                        <td>
                            {{-- <a href="{{ url('/view-quote/'.base64_encode($val->id)) }}" class="avtar avtar-xs btn-link-secondary">
                            <i class="ti ti-eye f-20"></i>
                            </a> --}}
                            <a href="{{ url('/edit-quote/'.base64_encode($val->id)) }}" class="avtar avtar-xs btn-link-secondary">
                            <i class="ti ti-edit f-20"></i>
                            </a>
                            <a href="#" data-id="{{$val->id}}" class="avtar avtar-xs btn-link-secondary delete-btn" data-bs-toggle="modal" data-bs-target="#delete_modal">
                            <i class="ti ti-trash f-20"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Quote</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" id="del_quot" data-id="" class="w-100 btn btn-primary">
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
    function startCAQuoteSetTour() {
        function launch() {
            introJs().setOptions({
                steps: [
                    {
                        title: 'Task Wise Quote Set',
                        intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-tags" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Manage predefined government fees and service charges for different task categories.</p></div>'
                    },
                    {
                        element: '#add-quote-btn',
                        title: 'Add New Quote',
                        intro: 'Click here to define tax rates, firm service charges, and government fees for a new task category.'
                    },
                    {
                        element: '#quotes-table-card',
                        title: 'Quote Records Table',
                        intro: 'Browse all configured task categories along with their respective government fees and service charges.'
                    },
                    {
                        element: '.delete-btn',
                        title: 'Manage Quotes',
                        intro: 'Use these action links to edit quote settings or delete a task quote definition.'
                    }
                ],
                showBullets: true,
                showProgress: true,
                helperElementPadding: 5,
                exitOnOverlayClick: false,
                doneLabel: 'Done',
                nextLabel: 'Next',
                prevLabel: 'Prev',
                skipLabel: 'Skip',
                skipIfNoElement: true
            }).start();
        }

        if (typeof introJs === 'function') {
            launch();
        } else {
            if (!document.getElementById('introjs-cdn-css')) {
                let css = document.createElement('link');
                css.id = 'introjs-cdn-css';
                css.rel = 'stylesheet';
                css.href = 'https://cdn.jsdelivr.net/npm/intro.js@7.2.0/introjs.min.css';
                document.head.appendChild(css);
            }
            let js = document.createElement('script');
            js.src = 'https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js';
            js.onload = function() {
                launch();
            };
            document.body.appendChild(js);
        }
    }

    $(document).ready(function () {
        let deleteId = null;

        $('.delete-btn').on('click', function () {
            deleteId = $(this).data('id'); 
        });

        $('#del_quot').on('click', function () {
            if (deleteId) {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });
        
                $.ajax({
                    url: '/delQuote',
                    type: 'POST',
                    data: { id: deleteId },
                    success: function (response) {
                        showToast(response.message, response.class);
                        setTimeout(function () {
                            window.location.href = response.redirect;
                        }, 2000);
                    },
                    error: function (xhr) {
                        showToast("Error deleting Quote!", 'error');
                    }
                });
            }
        });

        $('#start-ca-quoteset-tour').on('click', function(e) {
            e.preventDefault();
            startCAQuoteSetTour();
        });
    });
</script>
@endsection