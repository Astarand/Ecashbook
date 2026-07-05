@extends('App.Layout')

@section('container')

<div class="pc-content">
  <!-- [ Main Content ] start -->
  <div class="row">
    <div class="col-sm-12">
      <div class="card card-body table-card">
        <div class="table-responsive">
          <table class="table tbl-product my-3" id="pc-dt-simple">
            <thead>
              <tr class="text-center">
                <th>Name</th>
                <th>Document</th>
                <th>Date</th>
                <th>Message By</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr class="text-center">
                <td>360 Business & Services</td>
                <td>Annual GST Return</td>
                <td>21-09-2024</td>
                <td>Rishi Basu</td>
                <td><span class="badge text-bg-danger">Pending</span></td>
                <td class="text-center">
                  <a href="{{route ('user.CompliancesChat')}}" class="avtar avtar-xs btn-link-secondary">
                    <i class="ti ti-message-circle f-20"></i>
                  </a>
                </td>
              </tr>
              <tr class="text-center">
                <td>360 Business & Services</td>
                <td>Annual GST Return</td>
                <td>21-09-2024</td>
                <td>Rishi Basu</td>
                <td><span class="badge text-bg-warning">On-going</span></td>
                <td class="text-center">
                  <a href="#" class="avtar avtar-xs btn-link-secondary">
                    <i class="ti ti-message-circle f-20"></i>
                  </a>
                </td>
              </tr>
              <tr class="text-center">
                <td>360 Business & Services</td>
                <td>Annual GST Return</td>
                <td>21-09-2024</td>
                <td>Rishi Basu</td>
                <td><span class="badge text-bg-success">Complete</span></td>
                <td class="text-center">
                  <a href="#" class="avtar avtar-xs btn-link-secondary">
                    <i class="ti ti-message-circle f-20"></i>
                  </a>
                </td>
              </tr>
              <tr class="text-center">
                <td>360 Business & Services</td>
                <td>Annual GST Return</td>
                <td>21-09-2024</td>
                <td>Rishi Basu</td>
                <td><span class="badge text-bg-success">Complete</span></td>
                <td class="text-center">
                  <a href="#" class="avtar avtar-xs btn-link-secondary">
                    <i class="ti ti-message-circle f-20"></i>
                  </a>
                </td>
              </tr>
              <tr class="text-center">
                <td>360 Business & Services</td>
                <td>Annual GST Return</td>
                <td>21-09-2024</td>
                <td>Rishi Basu</td>
                <td><span class="badge text-bg-success">Complete</span></td>
                <td class="text-center">
                  <a href="#" class="avtar avtar-xs btn-link-secondary">
                    <i class="ti ti-message-circle f-20"></i>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->
</div>



@endsection