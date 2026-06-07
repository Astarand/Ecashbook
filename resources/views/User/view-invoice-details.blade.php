@extends('App.Layout')

@section('container')

<div class="pc-content">
  <!-- [ Main Content ] start -->
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-12">
              <div class="row align-items-center g-3">
                <div class="col-sm-6">
                  <div class="d-flex align-items-center mb-2">
                    <img src="../assets/images/logo-small.png" class="img-fluid" alt="images">
                  </div>
                </div>
                <div class="col-sm-6 text-sm-end">
                  <h6>Invoice No: <span class="text-muted f-w-400">{{ $invoice->invoice_number }}</span></h6>
                  <h6>Date: <span class="text-muted f-w-400">{{ date("d-m-Y",strtotime($invoice->invoice_date)) }}</span></h6>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="border rounded p-3">
                <h6 class="mb-0">Issued By:</h6>
                <h5>{{ $invoice->issued_by_company_name }}</h5>
                <p class="mb-0">{{ $invoice->issued_by_address1 }}</p>
                <p class="mb-0">{{ $invoice->issued_by_address2 }}, {{ $invoice->issued_by_city }}, {{ $invoice->issued_by_state }}, {{ $invoice->issued_by_pincode }}</p>
                <p class="mb-0">{{ $invoice->issued_by_contact_no }} </p>
                <p class="mb-0">{{ $invoice->issued_by_email_address }}</p>
                <p class="mb-0">{{ $invoice->issued_by_gst }}</p>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="border rounded p-3">
                <h6 class="mb-0">Issued To:</h6>
                <h5>{{ $invoice->issued_to_company_name }}</h5>
                <p class="mb-0">{{ $invoice->issued_to_address1 }}, {{ $invoice->issued_to_address2 }}</p>
                <p class="mb-0">{{ $invoice->issued_to_city }}, {{ $invoice->issued_to_pincode }}</p>
                <p class="mb-0">{{ $invoice->issued_to_contact_no }}</p>
                <p class="mb-0">{{ $invoice->issued_to_email_address }}</p>
                <p class="mb-0">{{ $invoice->issued_to_gst }}</p>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="border rounded p-3">
                <h6 class="mb-0">Bank Details:</h6>
                <h5>{{ $invoice->bank_name }}</h5>
                <p class="mb-0">{{ $invoice->account_no }}</p>
                <p class="mb-0">{{ $invoice->account_holder_name }}</p>
                <p class="mb-0">{{ $invoice->branch_name }}</p>
                <p class="mb-0">{{ $invoice->ifsc_code }}</p>
              </div>
            </div>
            <div class="col-12">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Product / Service</th>
                      <th>Price</th>
                      <th>HSN/SAC Code</th>
                      <th>Qty</th>
                      <th>GST Mode</th>
                      <th>CGST</th>
                      <th>SGST</th>
                      <th>IGST/UGST</th>
                      <th>Total Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                    $totalTax = 0;
                    @endphp
                    @foreach ($invoiceProducts as $key => $product)
                    @php
                    $productTotalTax = (float) $product->cgst + (float) $product->sgst + (float) $product->igst;
                    $totalTax += $productTotalTax;
                    @endphp
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>{{ $product->product_name }}</td>
                      <td>₹{{ number_format($product->price, 2) }}</td>
                      <td>{{ $product->hsn_sac_code }}</td>
                      <td>{{ $product->quantity }}</td>
                      <td>{{ $product->gst_type }}</td>
                      <td>₹{{ number_format($product->cgst, 2) }}</td>
                      <td>₹{{ number_format($product->sgst, 2) }}</td>
                      <td>₹{{ number_format($product->igst, 2) }}</td>
                      <td>₹{{ number_format($product->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="text-start">
                <hr class="mb-2 mt-1 border-secondary border-opacity-50">
              </div>
            </div>
            <div class="col-12">
              <div class="invoice-total ms-auto">
                <div class="row">
                  <div class="col-6">
                    <p class="text-muted mb-1 text-start">Sub Total :</p>
                  </div>
                  <div class="col-6">
                    <p class="mb-1 text-end">₹
                      <?php
                      $sub = (float) $invoice->discount_amount + (float) $invoice->total_amount;
                      echo number_format($sub, 2);
                      ?>
                    </p>
                  </div>
                  <div class="col-6">
                    <p class="text-muted mb-1 text-start">Discount :</p>
                  </div>
                  <div class="col-6">
                    <p class="mb-1 text-end text-success">{{ $invoice->discount_amount }}</p>
                  </div>
                  <div class="col-6">
                    <p class="text-muted mb-1 text-start">Taxes :</p>
                  </div>
                  <div class="col-6">
                    <p class="mb-1 text-end total_tax">₹{{ number_format($totalTax, 2) }}</p>
                  </div>
                  <div class="col-6">
                    <p class="f-w-600 mb-1 text-start">Grand Total :</p>
                  </div>
                  <div class="col-6">
                    <p class="f-w-600 mb-1 text-end">{{ $invoice->total_amount }}</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12">
              @if(!empty($invoice->notes))
                  <label class="form-label">Note</label>
                  <p class="mb-0">{{ $invoice->notes }}</p>
              @endif
              {{-- <p class="mb-0">It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance
                projects. Thank You!</p> --}}
            </div>
            <div class="col-12 text-end d-print-none">
              <button class="btn btn-outline-secondary btn-print-invoice">Download</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->
</div>
<script>
  document.querySelector('.btn-print-invoice').addEventListener('click', function() {
    var link2 = document.createElement('link');
    link2.innerHTML =
      '<style>@media print{*,::after,::before{text-shadow:none!important;box-shadow:none!important}a:not(.btn){text-decoration:none}abbr[title]::after{content:" ("attr(title) ")"}pre{white-space:pre-wrap!important}blockquote,pre{border:1px solid #adb5bd;page-break-inside:avoid}thead{display:table-header-group}img,tr{page-break-inside:avoid}h2,h3,p{orphans:3;widows:3}h2,h3{page-break-after:avoid}@page{size:a3}body{min-width:992px!important}.container{min-width:992px!important}.page-header,.pc-sidebar,.pc-mob-header,.pc-header,.pct-customizer,.modal,.navbar{display:none}.pc-container{top:0;}.invoice-contact{padding-top:0;}@page,.card-body,.card-header,body,.pcoded-content{padding:0;margin:0}.badge{border:1px solid #000}.table{border-collapse:collapse!important}.table td,.table th{background-color:#fff!important}.table-bordered td,.table-bordered th{border:1px solid #dee2e6!important}.table-dark{color:inherit}.table-dark tbody+tbody,.table-dark td,.table-dark th,.table-dark thead th{border-color:#dee2e6}.table .thead-dark th{color:inherit;border-color:#dee2e6}}</style>';
    document.getElementsByTagName('head')[0].appendChild(link2);
    window.print();
  });
</script>
@endsection