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
                        <li class="breadcrumb-item"><a href="{{ url('/customer-list') }}">PF Management</a></li>
                        <li class="breadcrumb-item" aria-current="page">Add PF filing Record</li>
                    </ul>
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

@endsection
