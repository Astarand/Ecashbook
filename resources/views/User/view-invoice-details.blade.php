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
                        $logoUrl = optional($compDetails)->comp_logo ? asset('storage/profile/' . optional($compDetails)->comp_logo) : asset('storage/profile/e-cashbook.png');
                    @endphp
                    <img src="{{ $logoUrl }}" id="uploadedImage" class="img-fluid" alt="Company Logo" style="max-height: 55px; max-width: 200px; width: auto; object-fit: contain;">
                  </div>
                </div>
                <div class="col-sm-6 text-sm-end">
                  <h5 class="mb-1 fw-bold text-dark">Invoice No: <span class="text-primary">#{{ $invoice->invoice_number }}</span></h5>
                  <h6 class="mb-0 text-muted">Date: <span class="fw-semibold text-dark">{{ date("d-m-Y", strtotime($invoice->invoice_date)) }}</span></h6>
                </div>
              </div>
            </div>

            <!-- Party Details (Issued By & Issued To) -->
            <div class="col-sm-6">
              <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                <h6 class="mb-2 fw-bold text-primary">Issued By:</h6>
                <h6 class="fw-bold mb-1 text-dark">{{ $invoice->issued_by_company_name }}</h6>
                <p class="mb-1 text-muted" style="font-size: 0.88rem;">{{ $invoice->issued_by_address1 }} {{ $invoice->issued_by_address2 ? ', '.$invoice->issued_by_address2 : '' }}</p>
                <p class="mb-1 text-muted" style="font-size: 0.88rem;">{{ $invoice->issued_by_city }} {{ $invoice->issued_by_state ? ', '.$invoice->issued_by_state : '' }} {{ $invoice->issued_by_pincode ? '- '.$invoice->issued_by_pincode : '' }}</p>
                @if(!empty($invoice->issued_by_contact_no))
                  <p class="mb-1 text-muted" style="font-size: 0.88rem;"><strong>Contact:</strong> {{ $invoice->issued_by_contact_no }}</p>
                @endif
                @if(!empty($invoice->issued_by_gst))
                  <p class="mb-0 text-muted" style="font-size: 0.88rem;"><strong>GSTIN:</strong> {{ $invoice->issued_by_gst }}</p>
                @endif
              </div>
            </div>

            <div class="col-sm-6">
              <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                <h6 class="mb-2 fw-bold text-primary">Issued To (Customer):</h6>
                <h5 class="fw-bold mb-1 text-dark">{{ $invoice->issued_to_company_name ?? 'N/A' }}</h5>
                <p class="mb-1 text-muted" style="font-size: 0.88rem;">{{ $invoice->issued_to_address1 }} {{ $invoice->issued_to_address2 ? ', '.$invoice->issued_to_address2 : '' }}</p>
                <p class="mb-1 text-muted" style="font-size: 0.88rem;">{{ $invoice->issued_to_city }} {{ $invoice->issued_to_state ? ', '.$invoice->issued_to_state : '' }} {{ $invoice->issued_to_pincode ? '- '.$invoice->issued_to_pincode : '' }}</p>
                @if(!empty($invoice->issued_to_contact_no))
                  <p class="mb-1 text-muted" style="font-size: 0.88rem;"><strong>Contact:</strong> {{ $invoice->issued_to_contact_no }}</p>
                @endif
                @if(!empty($invoice->issued_to_gst))
                  <p class="mb-0 text-muted" style="font-size: 0.88rem;"><strong>GSTIN:</strong> {{ $invoice->issued_to_gst }}</p>
                @endif
              </div>
            </div>

            <!-- Line Items Table -->
            <div class="col-12 mt-4">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th style="width: 5%;">#</th>
                      <th style="width: 35%;">Product / Service</th>
                      <th style="width: 15%;">HSN / SAC</th>
                      <th style="width: 10%;">Qty</th>
                      <th style="width: 12%;">Price</th>
                      <th style="width: 10%;">CGST</th>
                      <th style="width: 10%;">SGST</th>
                      <th style="width: 13%;" class="text-end">Total Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                    $subTotal = 0;
                    $totalCgst = 0;
                    $totalSgst = 0;
                    $totalIgst = 0;
                    @endphp
                    @foreach ($invoiceProducts as $key => $product)
                    @php
                    $itemBase = (float)$product->price * (float)$product->quantity;
                    $subTotal += $itemBase;
                    $totalCgst += (float) $product->cgst;
                    $totalSgst += (float) $product->sgst;
                    $totalIgst += (float) $product->igst;
                    @endphp
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td><span class="fw-semibold text-dark">{{ $product->product_name }}</span></td>
                      <td><span class="badge bg-light text-dark border">{{ $product->hsn_sac_code ?? 'N/A' }}</span></td>
                      <td>{{ $product->quantity }}</td>
                      <td>₹{{ number_format((float)$product->price, 2) }}</td>
                      <td>₹{{ number_format((float)$product->cgst, 2) }}</td>
                      <td>₹{{ number_format((float)$product->sgst, 2) }}</td>
                      <td class="text-end fw-bold">₹{{ number_format((float)$product->total_price, 2) }}</td>
                    </tr>
                    @endforeach
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
                    <p class="fw-bold text-dark mb-2" style="font-size: 0.95rem;">
                      {{ !empty($invoice->total_amount_in_words) ? $invoice->total_amount_in_words : ucwords(Helper::convert_number_to_words((float)$invoice->total_amount)) . ' Rupees Only.' }}
                    </p>
                    @if(!empty($invoice->notes))
                      <hr class="my-2">
                      <p class="mb-0 text-muted" style="font-size: 0.85rem;"><strong>Note:</strong> {{ $invoice->notes }}</p>
                    @endif
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="border rounded-3 p-3 bg-light-subtle ms-auto" style="max-width: 380px;">
                    <div class="row g-2">
                      <div class="col-6">
                        <p class="text-muted mb-1 text-start">Sub Total:</p>
                      </div>
                      <div class="col-6">
                        <p class="mb-1 text-end fw-semibold">₹{{ number_format($subTotal, 2) }}</p>
                      </div>

                      @if((float)$invoice->discount_amount > 0)
                      <div class="col-6">
                        <p class="text-muted mb-1 text-start">Discount:</p>
                      </div>
                      <div class="col-6">
                        <p class="mb-1 text-end fw-semibold">₹{{ number_format((float)$invoice->discount_amount, 2) }}</p>
                      </div>
                      @endif

                      @if($totalCgst > 0)
                      <div class="col-6">
                        <p class="text-muted mb-1 text-start">CGST:</p>
                      </div>
                      <div class="col-6">
                        <p class="mb-1 text-end">₹{{ number_format($totalCgst, 2) }}</p>
                      </div>
                      @endif

                      @if($totalSgst > 0)
                      <div class="col-6">
                        <p class="text-muted mb-1 text-start">SGST:</p>
                      </div>
                      <div class="col-6">
                        <p class="mb-1 text-end">₹{{ number_format($totalSgst, 2) }}</p>
                      </div>
                      @endif

                      @if($totalIgst > 0)
                      <div class="col-6">
                        <p class="text-muted mb-1 text-start">IGST:</p>
                      </div>
                      <div class="col-6">
                        <p class="mb-1 text-end">₹{{ number_format($totalIgst, 2) }}</p>
                      </div>
                      @endif

                      <div class="col-12"><hr class="my-1"></div>

                      <div class="col-6">
                        <h6 class="fw-bold mb-0 text-start text-dark">Grand Total:</h6>
                      </div>
                      <div class="col-6">
                        <h6 class="fw-bold mb-0 text-end text-primary" style="font-size: 1.15rem;">₹{{ number_format((float)$invoice->total_amount, 2) }}</h6>
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
                      $sigSrc = '';
                      if (!empty($invoice->upload_signature)) {
                          $sigFile = $invoice->upload_signature;
                          if (file_exists(public_path('storage/custom_invoice_img/' . $sigFile))) {
                              $sigSrc = asset('storage/custom_invoice_img/' . $sigFile);
                          } elseif (file_exists(public_path('uploads/custom_invoice_img/' . $sigFile))) {
                              $sigSrc = asset('uploads/custom_invoice_img/' . $sigFile);
                          }
                      }
                  @endphp
                  <div style="height: 55px; min-height: 55px; margin-bottom: 5px;" class="d-flex align-items-end justify-content-end">
                    @if(!empty($sigSrc))
                      <img src="{{ $sigSrc }}" class="img-fluid" alt="Signature" style="max-height: 50px; width: auto; object-fit: contain;">
                    @endif
                  </div>
                  <div class="border-top border-2 pt-2">
                    <p class="mb-0 fw-semibold text-dark">{{ !empty($invoice->signature_name) ? $invoice->signature_name : 'Authorized Signatory' }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- 📌 ROW 1: Bank Details in a single row -->
            <div class="col-12 mt-3">
              <div class="border rounded-3 p-3 bg-light-subtle">
                <h6 class="mb-2 fw-bold text-primary d-flex align-items-center">
                  <i class="ti ti-building-bank me-1 fs-5"></i> Bank Details
                </h6>
                @if(!empty($invoice->bank_name) || !empty($bankDetails))
                  @php
                      $bName = $invoice->bank_name ?? ($bankDetails->bank_name ?? '');
                      $bHolder = $invoice->account_holder_name ?? ($bankDetails->accholder_name ?? '');
                      $bAcNo = $invoice->account_no ?? ($bankDetails->bank_ac_no ?? '');
                      $bIfsc = $invoice->ifsc_code ?? ($bankDetails->ifsc_code ?? '');
                      $bBranch = $invoice->branch_name ?? ($bankDetails->bank_branch ?? '');
                  @endphp
                  <div class="row g-2" style="font-size: 0.88rem;">
                    <div class="col-sm-6">
                      <p class="mb-1 text-muted"><strong>Account Holder:</strong> <span class="text-dark">{{ $bHolder }}</span></p>
                      <p class="mb-1 text-muted"><strong>Bank Name:</strong> <span class="text-dark">{{ $bName }}</span></p>
                      @if(!empty($bBranch))
                        <p class="mb-0 text-muted"><strong>Branch:</strong> <span class="text-dark">{{ $bBranch }}</span></p>
                      @endif
                    </div>
                    <div class="col-sm-6">
                      <p class="mb-1 text-muted"><strong>Account No:</strong> <span class="text-dark fw-bold">{{ $bAcNo }}</span></p>
                      <p class="mb-1 text-muted"><strong>IFSC Code:</strong> <span class="text-dark fw-bold">{{ $bIfsc }}</span></p>
                    </div>
                  </div>
                @else
                  <p class="mb-0 text-muted" style="font-size: 0.88rem;">No bank details available.</p>
                @endif
              </div>
            </div>

            <!-- 📌 ROW 2: Notes & Terms & Conditions in a single row -->
            <div class="col-12 mt-3">
              <div class="row g-3">
                @if(!empty($invoice->notes))
                  <div class="col-sm-6">
                    <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                      <h6 class="mb-2 fw-bold text-primary d-flex align-items-center">
                        <i class="ti ti-notes me-1 fs-5"></i> Note
                      </h6>
                      <p class="mb-0 text-muted" style="font-size: 0.88rem; line-height: 1.5;">{{ $invoice->notes }}</p>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                      <h6 class="mb-2 fw-bold text-primary d-flex align-items-center">
                        <i class="ti ti-file-certificate me-1 fs-5"></i> Terms & Conditions
                      </h6>
                      <p class="mb-0 text-muted" style="font-size: 0.88rem; line-height: 1.5;">
                        {{ !empty($invoice->terms_and_conditions) ? $invoice->terms_and_conditions : 'Standard commercial terms and conditions apply.' }}
                      </p>
                    </div>
                  </div>
                @else
                  <div class="col-12">
                    <div class="border rounded-3 p-3 bg-light-subtle">
                      <h6 class="mb-2 fw-bold text-primary d-flex align-items-center">
                        <i class="ti ti-file-certificate me-1 fs-5"></i> Terms & Conditions
                      </h6>
                      <p class="mb-0 text-muted" style="font-size: 0.88rem; line-height: 1.5;">
                        {{ !empty($invoice->terms_and_conditions) ? $invoice->terms_and_conditions : 'Standard commercial terms and conditions apply.' }}
                      </p>
                    </div>
                  </div>
                @endif
              </div>
            </div>

            <!-- Action Buttons -->
            @php
                $invId = base64_encode($invoice->id);
            @endphp
            <div class="col-12 text-end d-print-none mt-4 pt-3 border-top">
              <a href="{{ route('user.CustomInvoiceList') }}" class="btn btn-outline-secondary rounded-pill px-4 me-2">
                <i class="ti ti-arrow-left me-1"></i> Back
              </a>

              <a href="{{ url('/custom-invoice-pdf/'.$invId) }}" target="_blank" class="btn btn-primary rounded-pill px-4 shadow-sm" style="background-color: #008CAD; border-color: #008CAD;">
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