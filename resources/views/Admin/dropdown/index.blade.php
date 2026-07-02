@extends('App.Layout')

@section('container')

<div class="pc-content">

    <div class="page-header">
        <div class="page-block">

            <div class="row align-items-center">

                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}">Home</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('dropdown.index') }}">
                                Dropdown Values
                            </a>
                        </li>

                        <li class="breadcrumb-item" aria-current="page">
                            Dropdown Value List
                        </li>
                    </ul>
                </div>

                <div class="col-md-6">
                    <div class="page-header-title">
                        <h2 class="mb-0">
                            Dropdown Value List
                        </h2>
                    </div>
                </div>

                <div class="col-md-6 text-end">
                    <button
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#dropdownModal"
                        id="addBtn">

                        <i class="ti ti-square-plus"></i>

                        Add Dropdown Value

                    </button>
                </div>

            </div>

        </div>
    </div>


    <div class="row">

        <div class="col-sm-12">

            <div class="card card-body table-card">

                <div class="table-responsive">

                    <table class="table tbl-product" id="pc-dt-simple">

                        <thead>

                        <tr>

                            <th>#</th>
                            <th>Module</th>
                            <th>Dropdown Name</th>
							<th>Option Text</th>
							<th>Type</th>
                            <th>Option Value</th>
                            <th>Sort Order</th>
                            <th>Status</th>
                            <th width="150">Action</th>

                        </tr>

                        </thead>

                        <tbody>

                        @foreach($dropdowns as $key=>$row)

                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $row->module }}</td>
                            <td>{{ $row->dropdown_name }}</td>
							<td>{{ $row->option_text }}</td>
							<td>{{ $row->type }}</td>
                            <td>{{ $row->option_value }}</td>
                            <td>{{ $row->sort_order }}</td>
                            <td>
                                @if($row->status==1)
                                    <span class="badge bg-success">
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td>
                                <button
                                    class="btn btn-secondary btn-sm viewBtn"
                                    data-id="{{ $row->id }}"
                                    title="View">

                                    <i class="ti ti-eye"></i>

                                </button>

                                <!--<button
                                    class="btn btn-primary btn-sm editBtn"
                                    data-id="{{ $row->id }}"
                                    title="Edit">

                                    <i class="ti ti-pencil"></i>

                                </button>-->

                                <button
                                    class="btn btn-danger btn-sm deleteBtn"
                                    data-id="{{ $row->id }}"
                                    title="Delete">

                                    <i class="ti ti-trash"></i>

                                </button>

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

@include('Admin.dropdown.modal')

@include('Admin.dropdown.view_modal')

@endsection