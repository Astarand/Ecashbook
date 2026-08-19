@extends('App.Layout')

@section('container')

<div class="pc-content">
  <div class="row">
    <div class="col-sm-12">
      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
          <div class="row g-3">
            <!-- Header: Dynamic Logo & Invoice No / Date -->
            <div class="col-12 pb-3 border-bottom">
              <div class="row align-items-center g-3">
                <div class="col-sm-6">
                  <div class="d-flex align-items-center mb-2">
                    @php
                        $logoUrl = optional($compDetails)->comp_logo 
                            ? asset('storage/profile/' . optional($compDetails)->comp_logo) 
                            : asset('storage/profile/e-cashbook.png');
                    @endphp
                    <img src="{{ $logoUrl }}" id="uploadedImage" class="img-fluid" alt="Company Logo" style="max-height: 55px; max-width: 200px; width: auto; object-fit: contain;">
                  </div>
                </div>
                <div class="col-sm-6 text-sm-end">
                  <h5 class="mb-1 fw-bold text-dark">PURCHASE INVOICE: <span class="text-primary">#{{ $inv_num }}</span></h5>
                  <h6 class="mb-0 text-muted">Date: <span class="fw-semibold text-dark">{{ date("d-m-Y", strtotime($invDate)) }}</span></h6>
                </div>
              </div>
            </div>

            <!-- Party Details (Company Details, Billing Address, Shipping Address) -->
            @php
                $shipAddressParts = array_filter([
                    $sales->ship_addone ?? '',
                    $sales->ship_pin ?? ''
                ]);
                $hasShipping = !empty($shipAddressParts) && trim(implode('', $shipAddressParts)) !== '';
                $colClass = $hasShipping ? 'col-sm-4' : 'col-sm-6';
            @endphp

            <!-- Company Details -->
            <div class="{{ $colClass }}">
              <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                <h6 class="mb-2 fw-bold text-primary">Company Details:</h6>
                <h6 class="fw-bold mb-1 text-dark">{!! $compDetails->comp_name !!}</h6>
                <p class="mb-1 text-muted" style="font-size: 0.88rem;">{{ $compDetails->comp_bill_addone ?? '' }} {{ $compDetails->comp_bill_addtwo ?? '' }}</p>
                @if(!empty($compDetails->comp_pan_no))
                  <p class="mb-1 text-muted" style="font-size: 0.88rem;"><strong>PAN:</strong> {{ $compDetails->comp_pan_no }}</p>
                @endif
                @if(!empty($compDetails->gst_no))
                  <p class="mb-0 text-muted" style="font-size: 0.88rem;"><strong>GSTIN:</strong> {{ $compDetails->gst_no }}</p>
                @endif
              </div>
            </div>

            <!-- Billing Address (Vendor) -->
            <div class="{{ $colClass }}">
              <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                <h6 class="mb-2 fw-bold text-primary">Billing Address:</h6>
                <h6 class="fw-bold mb-1 text-dark">{!! $custDetails->vendor_name ?? 'N/A' !!}</h6>
                <p class="mb-1 text-muted" style="font-size: 0.88rem;">
                  {{ optional($sales)->bill_addone ?? '' }}
                  @if(optional($sales)->cust_bill_pin) - {{ optional($sales)->cust_bill_pin }} @endif
                </p>
                @if(!empty($custDetails->vendor_pan))
                  <p class="mb-1 text-muted" style="font-size: 0.88rem;"><strong>PAN:</strong> {{ $custDetails->vendor_pan }}</p>
                @endif
                @if(!empty($custDetails->vendor_gstin))
                  <p class="mb-0 text-muted" style="font-size: 0.88rem;"><strong>GSTIN:</strong> {{ $custDetails->vendor_gstin }}</p>
                @endif
              </div>
            </div>

            <!-- Shipping Address (Only if present) -->
            @if($hasShipping)
            <div class="{{ $colClass }}">
              <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                <h6 class="mb-2 fw-bold text-primary">Shipping Address:</h6>
                <p class="mb-0 text-muted" style="font-size: 0.88rem;">{{ implode(', ', $shipAddressParts) }}</p>
              </div>
            </div>
            @endif

            <!-- Line Items Table -->
            <div class="col-12 mt-4">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th style="width: 5%;">#</th>
                      <th style="width: 35%;">Description of Goods</th>
                      <th style="width: 15%;">HSN / SAC</th>
                      <th style="width: 10%;">Quantity</th>
                      <th style="width: 12%;">Unit Price</th>
                      <th style="width: 10%;">Discount</th>
                      <th style="width: 13%;" class="text-end">Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                    $cgst = 0;
                    $igst = 0;
                    $taxableAmt = 0;
                    $totalDisc = 0;
                    $totalTax = 0;
                    $totalAmount = 0;
                    @endphp
                    @if(!empty($sales_values))
                      @foreach($sales_values as $k => $value)
                        @php
                            $taxableAmt += $value->amount;
                            $totalDisc += $value->disc_amt;
                            $totalTax += $value->tax_amt;
                            $cgst += ($value->amount) * 9 / 100;
                            $igst += ($value->amount) * 9 / 100;
                            $totalAmount += $value->amount;
                        @endphp
                        <tr>
                          <td>{{ $k + 1 }}</td>
                          <td><span class="fw-semibold text-dark">{{ $value->item_name }}</span></td>
                          <td><span class="badge bg-light text-dark border">{{ ($value->sac_code != "") ? $value->sac_code : $value->hsn_code }}</span></td>
                          <td>{{ $value->quantity }} {{ $value->base_unit ?? '' }}</td>
                          <td>₹{{ number_format($value->rate, 2) }}</td>
                          <td>{{ $value->disc_amt }}%</td>
                          <td class="text-end fw-bold">₹{{ number_format($value->amount, 2) }}</td>
                        </tr>
                      @endforeach
                      @php
                          $shippingCost = $sales->shipping_cost ?? 0;
                          $totalAmount = ceil(($totalAmount + $cgst + $igst + $shippingCost));
                      @endphp
                    @endif
                  </tbody>
                </table>
              </div>
              <div class="text-start">
                <hr class="mb-2 mt-1 border-secondary border-opacity-50">
              </div>
            </div>

            <!-- Summary & Totals Breakdown -->
            <div class="col-12">
              <div class="row g-3">
                <div class="col-sm-6">
                  <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                    <h6 class="mb-2 fw-bold text-primary">Total Amount in Words:</h6>
                    <p class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">
                      {{ ucwords(Helper::convert_number_to_words($totalAmount)) }} Rupees Only.
                    </p>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="border rounded-3 p-3 bg-light-subtle ms-auto" style="max-width: 380px;">
                    <div class="row g-2">
                      <div class="col-6">
                        <p class="text-muted mb-1 text-start">Taxable Amount:</p>
                      </div>
                      <div class="col-6">
                        <p class="mb-1 text-end fw-semibold">₹{{ number_format($taxableAmt, 2) }}</p>
                      </div>

                      @if($totalDisc > 0)
                      <div class="col-6">
                        <p class="text-muted mb-1 text-start">Discount:</p>
                      </div>
                      <div class="col-6">
                        <p class="mb-1 text-end fw-semibold">₹{{ number_format($totalDisc, 2) }}</p>
                      </div>
                      @endif

                      <div class="col-6">
                        <p class="text-muted mb-1 text-start">CGST 9.0%:</p>
                      </div>
                      <div class="col-6">
                        <p class="mb-1 text-end">₹{{ number_format($cgst, 2) }}</p>
                      </div>

                      <div class="col-6">
                        <p class="text-muted mb-1 text-start">IGST 9.0%:</p>
                      </div>
                      <div class="col-6">
                        <p class="mb-1 text-end">₹{{ number_format($igst, 2) }}</p>
                      </div>

                      @if(!empty($shippingCost) && (float)$shippingCost > 0)
                        <div class="col-6">
                          <p class="text-muted mb-1 text-start">Shipping Cost:</p>
                        </div>
                        <div class="col-6">
                          <p class="mb-1 text-end">₹{{ number_format((float)$shippingCost, 2) }}</p>
                        </div>
                      @endif

                      <div class="col-12"><hr class="my-1"></div>

                      <div class="col-6">
                        <h6 class="fw-bold mb-0 text-start text-dark">Total Amount:</h6>
                      </div>
                      <div class="col-6">
                        <h6 class="fw-bold mb-0 text-end text-primary" style="font-size: 1.15rem;">₹{{ number_format($totalAmount, 2) }}</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Authorized Signatory -->
            <div class="col-12">
              <div class="row justify-content-end mt-3">
                <div class="col-sm-4 text-end">
                  @php
                      $sigPath = !empty($sales->signature) ? public_path('uploads/invoice-signature/' . $sales->signature) : null;
                      $sigSrc = '';
                      if (!empty($sigPath) && file_exists($sigPath)) {
                          $sigSrc = asset('uploads/invoice-signature/' . $sales->signature);
                      }
                  @endphp
                  <div style="height: 55px; min-height: 55px; margin-bottom: 5px;" class="d-flex align-items-end justify-content-end">
                    @if(!empty($sigSrc))
                      <img src="{{ $sigSrc }}" class="img-fluid" alt="Signature" style="max-height: 50px; width: auto; object-fit: contain;">
                    @endif
                  </div>
                  <div class="border-top border-2 pt-2">
                    <p class="mb-0 fw-semibold text-dark">Authorized Signatory</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- 📌 ROW 1: Payment Details & Delivery Details in a single row -->
            <div class="col-12 mt-3">
              <div class="row g-3">
                <!-- Payment Details -->
                <div class="col-sm-6">
                  <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                    <h6 class="mb-2 fw-bold text-primary d-flex align-items-center">
                      <i class="ti ti-credit-card me-1 fs-5"></i> Payment Details
                    </h6>
                    <div style="font-size: 0.88rem;">
                      <p class="mb-1 text-muted"><strong>Mode Of Payment:</strong> <span class="text-dark">{{ $sales->mode_of_pay ?? 'N/A' }}</span></p>
                      @if(!empty($sales->dispa_docno_one))
                        <p class="mb-1 text-muted"><strong>Dispatch Doc No:</strong> <span class="text-dark">{{ $sales->dispa_docno_one }}</span></p>
                      @endif
                      @if(!empty($sales->disp_through))
                        <p class="mb-1 text-muted"><strong>Dispatched Through:</strong> <span class="text-dark">{{ $sales->disp_through }}</span></p>
                      @endif
                      @if(!empty($sales->ship_pin))
                        <p class="mb-0 text-muted"><strong>Destination:</strong> <span class="text-dark">{{ $sales->ship_pin }}</span></p>
                      @endif
                    </div>
                  </div>
                </div>

                <!-- Delivery Details -->
                <div class="col-sm-6">
                  <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                    <h6 class="mb-2 fw-bold text-primary d-flex align-items-center">
                      <i class="ti ti-truck-delivery me-1 fs-5"></i> Delivery Details
                    </h6>
                    <div style="font-size: 0.88rem;">
                      <p class="mb-1 text-muted"><strong>Buyer's Order No:</strong> <span class="text-dark">{{ $sales->buyer_orderno ?? 'N/A' }}</span></p>
                      <p class="mb-1 text-muted"><strong>Supplier's Ref:</strong> <span class="text-dark">{{ $sales->supplier_refno ?? 'N/A' }}</span></p>
                      <p class="mb-0 text-muted"><strong>Other Reference(s):</strong> <span class="text-dark">{{ $sales->other_refno ?? 'N/A' }}</span></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- 📌 ROW 2: Terms of Delivery in a single row -->
            <div class="col-12 mt-3">
              <div class="border rounded-3 p-3 bg-light-subtle">
                <h6 class="mb-2 fw-bold text-primary d-flex align-items-center">
                  <i class="ti ti-file-certificate me-1 fs-5"></i> Terms of Delivery
                </h6>
                <p class="mb-0 text-muted" style="font-size: 0.88rem; line-height: 1.5;">
                  {{ (!empty($sales->terms_delivery) && $sales->terms_delivery != 'undefined') ? $sales->terms_delivery : 'Standard commercial terms and conditions apply.' }}
                </p>
              </div>
            </div>

            <!-- Action Buttons -->
            @php
                $invId = base64_encode($sales->id);
            @endphp
            <div class="col-12 text-end d-print-none mt-4 pt-3 border-top">
              <a href="{{ route('user.PurchaseInvoices') }}" class="btn btn-outline-secondary rounded-pill px-4 me-2">
                <i class="ti ti-arrow-left me-1"></i> Back
              </a>

              <a href="{{ url('/purchase-invoice-pdf/'.$invId.'/pdf') }}" target="_blank" class="btn btn-primary rounded-pill px-4 shadow-sm" style="background-color: #008CAD; border-color: #008CAD;">
                <i class="ti ti-download me-1"></i> Download PDF
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection