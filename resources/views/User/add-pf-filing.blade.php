@extends('App.Layout')

@section('container')

<div class="pc-content">
        <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/customer-list') }}">PF Management</a></li>
                        <li class="breadcrumb-item" aria-current="page">Add PF filing Record</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-add-pf-filing-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Add PF Record</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form class="row">
                        <div class="col-xl-6 mb-3">
                            <label class="form-label">Company EPF No <span class="text-danger">*</span></label>
                            <input type="text" id="" name="" class="form-control" placeholder="Company EPF No" value="" readonly>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <label class="form-label">Company ESIC Number<span class="text-danger">*</span></label>
                            <input type="text" id="" name="" class="form-control" placeholder="Company ESIC Number" value="" readonly>
                        </div>
                        <div class="col-xl-4 mb-3">
                            <label class="form-label">PF Month <span class="text-danger">*</span></label>
                            <select class="form-control" name="" id="">
                                <option value="">Select Month</option>
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                            </select>
                        </div>
                        <div class="col-xl-4 mb-3">
                            <label class="form-label">Employee Name <span class="text-danger">*</span></label>
                            <select class="form-control" name="" id="">
                                <option value="">Select Employee</option>
                                <option value="John Doe">John Doe</option>
                                <option value="Jane Smith">Jane Smith</option>
                                <option value="Mike Johnson">Mike Johnson</option>
                                <option value="Sarah Williams">Sarah Williams</option>
                                <option value="Robert Brown">Robert Brown</option>
                            </select>
                        </div>
                        <div class="col-xl-4 mb-3">
                            <label class="form-label">UAN No <span class="text-danger">*</span></label>
                            <input type="name" id="" name="" class="form-control" placeholder="UAN No" value="1236579840" readonly>
                        </div>
                        <div class="col-xl-4 mb-3">
                            <label class="form-label">Basic Salary <span class="text-danger">*</span></label>
                            <input type="name" id="" name="" class="form-control" placeholder="Basic Salary" value="12000" readonly>
                        </div>
                        <div class="col-xl-4 mb-3">
                            <label class="form-label">Employee PF (12%) <span class="text-danger">*</span></label>
                            <input type="name" id="" name="" class="form-control" placeholder="Employee PF (12%)" value="1440" readonly>
                        </div>
                        <div class="col-xl-4 mb-3">
                            <label class="form-label">Employer PF (3.67%) <span class="text-danger">*</span></label>
                            <input type="name" id="" name="" class="form-control" placeholder="Employer PF" value="1440" readonly>
                        </div>
                        <div class="col-xl-4 mb-3">
                            <label class="form-label">Pension Contribution (8.33%) <span class="text-danger">*</span></label>
                            <input type="name" id="" name="" class="form-control" placeholder="Pension Contribution" value="999.6" readonly>
                        </div>
                        <div class="col-xl-4 mb-3">
                            <label class="form-label">Total Contribution <span class="text-danger">*</span></label>
                            <input type="name" id="" name="" class="form-control" placeholder="Total Contribution" value="3879.6" readonly>
                        </div>
                        <div class="col-xl-4 mb-3">
                            <label class="form-label">PF Challan Ref<span class="text-danger">*</span></label>
                            <input type="name" id="" name="" class="form-control" placeholder="PF Challan Ref" value="PF1234567890">
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="text-end btn-page mt-4">
            <a href="#" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Submit PF Record</button>
        </div>
    </div>

</div>


@section('page-script')
<script>
    function startAddPfFilingTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Log PF Contribution Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Record monthly EPF deposits and filing acknowledgments.</p></div>'
                },
                {
                    title: 'Log PF Contribution',
                    intro: 'Record monthly EPF deposits and filing acknowledgments.'
                }
            ],
            showBullets: true,
            showProgress: true,
            helperElementPadding: 5,
            exitOnOverlayClick: false,
            doneLabel: 'Done',
            nextLabel: 'Next',
            prevLabel: 'Prev',
            skipLabel: 'Skip'
        }).start();
    }

    $(document).ready(function() {
        $('#start-add-pf-filing-tour').on('click', function(e) {
            e.preventDefault();
            startAddPfFilingTour();
        });
    });
</script>
@endsection

