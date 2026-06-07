@extends('App.Layout')

@section('container')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="content-page-header">
                    <h5>Customer Details new</h5>
                </div>
            </div>
            <div class="card customer-details-group">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center">
                                    <span class="customer-widget-img d-inline-flex">
                                    <img class="rounded-circle" src="../assets/images/application/img-prod-3.jpg" alt="">
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>{{ $customers->name }}</h6>
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center">
                                    <span class="customer-widget-icon d-inline-flex">
                                    <i class="fe fe-mail"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Email Address</h6>
                                        <p>{{ $customers->comp_email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center">
                                    <span class="customer-widget-icon d-inline-flex">
                                    <i class="fe fe-phone"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Phone Number</h6>
                                        <p>{{ $customers->comp_phone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center">
                                    <span class="customer-widget-icon d-inline-flex">
                                    <i class="fe fe-airplay"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Company Name</h6>
                                        <p>{{ $customers->comp_name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center">
                                    <span class="customer-widget-icon d-inline-flex">
                                    <i class="fe fe-globe"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Website</h6>
                                        <p class="customer-mail">{{ $customers->comp_website }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center">
                                    <span class="customer-widget-icon d-inline-flex">
                                    <i class="fe fe-briefcase"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Company Address</h6>
                                        <p>{{ $customers->comp_bill_addone.','.$customers->comp_bill_pin }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                <div class="col-xl-2 col-lg-4 col-sm-6 col-12 d-flex">
                    <div class="card inovices-card w-100">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="inovices-widget-icon bg-info-light">
                                <img src="{{asset('public/assets/img/icons/receipt-item.svg')}}" alt="">
                                </span>
                                <div class="dash-count">
                                    <div class="dash-title">Total No of Task</div>
                                    <div class="dash-counts">
                                        <p>{{ isset($taskDetails[0]->totalTask)?$taskDetails[0]->totalTask:0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-sm-6 col-12 d-flex">
                    <div class="card inovices-card w-100">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="inovices-widget-icon bg-primary-light">
                                <img src="{{asset('public/assets/img/icons/transaction-minus.svg')}}" alt="">
                                </span>
                                <div class="dash-count">
                                    <div class="dash-title">No of Ongoing Task</div>
                                    <div class="dash-counts">
                                        <p>{{ isset($taskDetails[0]->totalOngoing)?$taskDetails[0]->totalOngoing:0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-sm-6 col-12 d-flex">
                    <div class="card inovices-card w-100">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="inovices-widget-icon bg-green-light">
                                <img src="{{asset('public/assets/img/icons/message-edit.svg')}}" alt="">
                                </span>
                                <div class="dash-count">
                                    <div class="dash-title">No of Pending Task</div>
                                    <div class="dash-counts">
                                        <p>{{ isset($taskDetails[0]->totalPending)?$taskDetails[0]->totalPending:0 }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-sm-6 col-12 d-flex">
                    <div class="card inovices-card w-100">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="inovices-widget-icon bg-primary-light">
                                <img src="{{asset('public/assets/img/icons/clipboard-close.svg')}}" alt="">
                                </span>
                                <div class="dash-count">
                                    <div class="dash-title">Total Payments</div>
                                    <div class="dash-counts">
                                        <p><i class="fa-solid fa-indian-rupee-sign"></i> {{ isset($taskDetails[0]->totalAmount)?$taskDetails[0]->totalAmount:0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-sm-6 col-12 d-flex">
                    <div class="card inovices-card w-100">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="inovices-widget-icon bg-warning-light">
                                <img src="{{asset('public/assets/img/icons/archive-book.svg')}}" alt="">
                                </span>
                                <div class="dash-count">
                                    <div class="dash-title">Total Due payment</div>
                                    <div class="dash-counts">
                                        <p><i class="fa-solid fa-indian-rupee-sign"></i> {{ isset($taskDetails[0]->totalDue)?$taskDetails[0]->totalDue:0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-sm-6 col-12 d-flex">
                    <div class="card inovices-card w-100">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="inovices-widget-icon bg-danger-light">
                                <img src="https://ecashbook.in/public/assets/img/icons/3d-rotate.svg')}}" alt="">
                                </span>
                                <div class="dash-count">
                                    <div class="dash-title">Recurring</div>
                                    <div class="dash-counts">
                                        <p><i class="fa-solid fa-indian-rupee-sign"></i> {{ isset($taskDetails[0]->totalRecurring)?$taskDetails[0]->totalRecurring:0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="content-page-header">
                        <h5></h5>
                        <div class="list-btn">
                            <ul class="filter-list">
                                <li>
                                    <a class="btn btn-primary" href="#"><i class="fe fe-eye me-2" aria-hidden="true"></i>View Customer Account Details</a>
                                </li>
                                <li>
                                    <a class="btn btn-primary" href="#"><i class="fe fe-download me-2" aria-hidden="true"></i>Generate Invoice</a>
                                </li>
                            </ul>
                        </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-table">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-stripped table-hover datatable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>
                                                <label class="custom_check">
                                                <input type="checkbox" name="invoice">
                                                <span class="checkmark"></span>
                                                </label>Date
                                            </th>
                                            <th>Task Category</th>
                                            <th>Govt Fees</th>
                                            <th>Service Charge</th>
                                            <th>Total Amount</th>
                                            <th>Payment Mode</th>
                                            <th>Payment Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php $i = 1; ?>
										@foreach ($tasks as $val)
                                        <tr>
                                            <td>
                                                <label class="custom_check">
                                                <input type="checkbox" name="invoice">
                                                <span class="checkmark"></span>
                                                </label>
                                                <a href="javascript:void(0);" class="invoice-link">{{ date("d M Y",strtotime($val->task_date))}}</a>
                                            </td>
                                            <td>{{$val->task_category}}</td>
                                            <td><i class="fa-solid fa-indian-rupee-sign"></i> &nbsp; {{$val->gov_fees}}</td>
                                            <td><i class="fa-solid fa-indian-rupee-sign"></i> &nbsp; {{$val->services_charges}}</td>
                                            <td><i class="fa-solid fa-indian-rupee-sign"></i> &nbsp; 2000</td>
                                            <td>UPI</td>

                                            <td><span class="badge bg-danger-light">Due</span></td>
                                            <td class="d-flex align-items-center">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class=" btn-action-icon " data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <ul>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ url('/edit-task/'.base64_encode($val->id)) }}"><i class="far fa-edit me-2"></i>Edit</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete_modal"><i class="far fa-trash-alt me-2"></i>Delete</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ url('/view-task/'.base64_encode($val->id)) }}"><i class="far fa-eye me-2"></i>View</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-send me-2"></i>Send</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-download me-2"></i>Invoice</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
										@endforeach
                                        
                                    </tbody>
                                </table>
								<div class="d-flex justify-content-center">
									<?php echo $tasks_pagination->links() ?>
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
                                <h3>Delete Customer Details</h3>
                                <p>Are you sure want to delete?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                        <a href="#" data-bs-dismiss="modal" class="btn btn-primary paid-continue-btn">Delete</a>
                                    </div>
                                    <div class="col-6">
                                        <a href="#" data-bs-dismiss="modal" class="btn btn-primary paid-cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
