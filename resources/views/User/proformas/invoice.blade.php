@extends('App.Layout')

@section('container')
<div class="pc-content">
    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <!-- Top Header: Logo and Proforma Invoice Details -->
                        <div class="col-12 pb-3 border-bottom">
                            <div class="row align-items-center g-3">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-2">
                                        @php
                                            $logoUrl = optional($compDetails)->comp_logo ? asset('storage/profile/' . optional($compDetails)->comp_logo) : asset('storage/profile/e-cashbook.png');
                                        @endphp
                                        <img src="{{ $logoUrl }}" id="uploadedImage" class="img-fluid" alt="Company Logo" style="max-height: 55px; max-width: 200px; width: auto; object-fit: contain;" onerror="this.src='{{ asset('storage/profile/e-cashbook.png') }}'">
                                    </div>
                                </div>
                                <div class="col-sm-6 text-sm-end">
                                    <h4 class="mb-1 fw-bold text-dark">PROFORMA INVOICE</h4>
                                    <h5 class="mb-1 text-muted">Invoice No: <span class="text-primary fw-bold">#{{ $inv_num }}</span></h5>
                                    <h6 class="mb-0 text-muted">Date: <span class="fw-semibold text-dark">{{ date("d-m-Y", strtotime($invDate ?? $sales->created_at ?? date('Y-m-d'))) }}</span></h6>
                                </div>
                            </div>
                        </div>

                        <!-- Party Details (Issued By & Issued To & Shipping) -->
                        @php
                            $shipAddressParts = array_filter([
                                $sales->ship_addone ?? '',
                                $sales->ship_addtwo ?? '',
                                $sales->ship_city ?? '',
                                $sales->ship_pin ?? ''
                            ]);
                            $hasShipping = !empty($shipAddressParts) && trim(implode('', $shipAddressParts)) !== '';
                            $partyCol = $hasShipping ? 'col-md-4' : 'col-md-6';
                        @endphp

                        <div class="{{ $partyCol }}">
                            <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                                <h6 class="mb-2 fw-bold text-primary"><i class="ti ti-building me-1"></i> Issued By (Company Details):</h6>
                                <h6 class="fw-bold mb-1 text-dark">{{ $compDetails->comp_name ?? '' }}</h6>
                                <p class="mb-1 text-muted" style="font-size: 0.88rem;">{{ $compDetails->comp_bill_addone ?? '' }} {{ $compDetails->comp_bill_addtwo ?? '' }}</p>
                                @if(!empty($compDetails->comp_pan_no))
                                    <p class="mb-1 text-muted" style="font-size: 0.88rem;"><strong>PAN:</strong> {{ $compDetails->comp_pan_no }}</p>
                                @endif
                                @if(!empty($compDetails->gst_no))
                                    <p class="mb-0 text-muted" style="font-size: 0.88rem;"><strong>GSTIN:</strong> {{ $compDetails->gst_no }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="{{ $partyCol }}">
                            <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                                <h6 class="mb-2 fw-bold text-primary"><i class="ti ti-user me-1"></i> Issued To (Customer):</h6>
                                <h6 class="fw-bold mb-1 text-dark">{{ $custDetails->cust_name ?? 'N/A' }}</h6>
                                <p class="mb-1 text-muted" style="font-size: 0.88rem;">
                                    {{ optional($sales)->bill_addone ?? ($custDetails->cust_bill_addone ?? '') }} {{ optional($sales)->bill_addtwo ?? ($custDetails->cust_bill_addtwo ?? '') }}
                                    @if(optional($sales)->bill_pin || optional($custDetails)->cust_bill_pin) - {{ optional($sales)->bill_pin ?? $custDetails->cust_bill_pin }} @endif
                                </p>
                                @if(!empty($custDetails->cust_pan))
                                    <p class="mb-1 text-muted" style="font-size: 0.88rem;"><strong>PAN:</strong> {{ $custDetails->cust_pan }}</p>
                                @endif
                                @if(!empty($custDetails->cust_gst_no))
                                    <p class="mb-0 text-muted" style="font-size: 0.88rem;"><strong>GSTIN:</strong> {{ $custDetails->cust_gst_no }}</p>
                                @endif
                            </div>
                        </div>

                        @if($hasShipping)
                        <div class="{{ $partyCol }}">
                            <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                                <h6 class="mb-2 fw-bold text-primary"><i class="ti ti-truck-delivery me-1"></i> Shipping Address:</h6>
                                <p class="mb-1 text-dark" style="font-size: 0.88rem;">{{ implode(', ', $shipAddressParts) }}</p>
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
                                            <th style="width: 38%;">Description of Goods / Service</th>
                                            <th style="width: 14%;">HSN / SAC</th>
                                            <th style="width: 10%;">Quantity</th>
                                            <th style="width: 12%;">Unit Price</th>
                                            <th style="width: 8%;">Discount</th>
                                            <th style="width: 13%;" class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $cgst = 0;
                                            $sgst = 0;
                                            $igst = 0;
                                            $gst_trans = "";
                                            $taxableAmt = 0;
                                            $totalDisc = 0;
                                            $totalTax = 0;
                                            $totalAmount = 0;
                                            $totalGovPay = 0;
                                            $totalSerPay = 0;
                                            $items = !empty($sales_values) ? $sales_values : (!empty($proformas_values) ? $proformas_values : []);
                                        @endphp
                                        @if(!empty($items))
                                            @foreach($items as $k => $value)
                                                @php
                                                    $taxableAmt += ($value->rate * $value->quantity);
                                                    $totalDisc += ($value->disc_amt ?? 0);
                                                    $totalTax += ($value->tax_amt ?? 0);
                                                    $cgst += ($value->tax_amt ?? 0) / 2;
                                                    $sgst += ($value->tax_amt ?? 0) / 2;
                                                    $igst += ($value->tax_amt ?? 0);
                                                    $totalGovPay += ($value->gov_pay ?? 0);
                                                    $totalSerPay += ($value->ser_pay ?? 0);
                                                    $totalAmount += ($value->amount ?? 0);
                                                    $gst_trans = $value->gst_trans ?? ($value->tax_type ?? '');
                                                @endphp
                                                <tr>
                                                    <td>{{ $k + 1 }}</td>
                                                    <td><span class="fw-semibold text-dark">{{ $value->item_name }}</span></td>
                                                    <td><span class="badge bg-light text-dark border">{{ ($value->sac_code != "") ? $value->sac_code : ($value->hsn_code ?? 'N/A') }}</span></td>
                                                    <td>{{ $value->quantity }} {{ $value->base_unit ?? '' }}</td>
                                                    <td>₹{{ number_format($value->rate, 2) }}</td>
                                                    <td>{{ $value->disc ?? 0 }}%</td>
                                                    <td class="text-end fw-bold text-dark">₹{{ number_format($value->amount, 2) }}</td>
                                                </tr>
                                            @endforeach
                                            @php
                                                $calculatedGrandTotal = $totalAmount + $totalTax + $totalGovPay + $totalSerPay;
                                                if (!empty($special_discount_amount) && $special_discount_amount > 0) {
                                                    $calculatedGrandTotal -= $special_discount_amount;
                                                }
                                                $finalGrandTotal = getRoundedAmount($calculatedGrandTotal);
                                            @endphp
                                        @else
                                            @php
                                                $finalGrandTotal = 0;
                                            @endphp
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Summary & Total Amount Layout -->
                        <div class="col-12 mt-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light-subtle rounded-3 border">
                                        <h6 class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem;">Total Amount in Words:</h6>
                                        <h6 class="fw-bold text-dark mb-0">{{ ucwords(Helper::convert_number_to_words($finalGrandTotal)) }} Rupees Only.</h6>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light-subtle rounded-3 border">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Sub Total (Taxable):</span>
                                            <span class="fw-semibold">₹{{ number_format($taxableAmt, 2) }}</span>
                                        </div>
                                        @if($totalDisc > 0)
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Discount:</span>
                                            <span class="fw-semibold text-success">- ₹{{ number_format($totalDisc, 2) }}</span>
                                        </div>
                                        @endif
                                        @if($gst_trans == 'intrastate')
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">CGST:</span>
                                                <span class="fw-semibold">₹{{ number_format($cgst, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">SGST:</span>
                                                <span class="fw-semibold">₹{{ number_format($sgst, 2) }}</span>
                                            </div>
                                        @else
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">IGST / Taxes:</span>
                                                <span class="fw-semibold">₹{{ number_format($igst, 2) }}</span>
                                            </div>
                                        @endif

                                        @if(!empty($special_discount_amount) && $special_discount_amount > 0)
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Special Discount:</span>
                                                <span class="fw-semibold text-success">- ₹{{ number_format($special_discount_amount, 2) }}</span>
                                            </div>
                                        @endif

                                        @if($totalGovPay > 0)
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Government Fees:</span>
                                                <span class="fw-semibold">₹{{ number_format($totalGovPay, 2) }}</span>
                                            </div>
                                        @endif

                                        @if($totalSerPay > 0)
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Service Charges:</span>
                                                <span class="fw-semibold">₹{{ number_format($totalSerPay, 2) }}</span>
                                            </div>
                                        @endif

                                        <div class="d-flex justify-content-between pt-2 border-top">
                                            <h5 class="fw-bold text-dark mb-0">Grand Total:</h5>
                                            <h5 class="fw-bold text-primary mb-0">₹{{ number_format($finalGrandTotal, 2) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Authorized Signatory -->
                        <div class="col-12 mt-4">
                            <div class="row justify-content-end">
                                <div class="col-sm-4 text-end">
                                    @php
                                        $sigPath = !empty($sales->signature) ? asset('uploads/invoice-signature/' . $sales->signature) : null;
                                    @endphp
                                    <div style="height: 60px; min-height: 60px;" class="d-flex align-items-center justify-content-end mb-1">
                                        @if(!empty($sigPath))
                                            <img src="{{ $sigPath }}" class="img-fluid" alt="Signature" style="max-height: 55px; width: auto;" onerror="this.style.display='none'">
                                        @endif
                                    </div>
                                    <div class="border-top border-dark border-2 pt-1">
                                        <p class="mb-0 fw-bold text-dark">Authorized Signatory</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Cards: Bank Details, Delivery Details, Terms -->
                        <div class="col-md-4 mt-3">
                            <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                                <h6 class="mb-2 fw-bold text-primary"><i class="ti ti-building-bank me-1"></i> Bank Details:</h6>
                                @if(!empty($bankDetails))
                                    <p class="mb-1 text-muted" style="font-size: 0.88rem;"><strong>Account Holder:</strong> {{ $bankDetails->accholder_name ?? '' }}</p>
                                    <p class="mb-1 text-muted" style="font-size: 0.88rem;"><strong>Bank Name:</strong> {{ $bankDetails->bank_name ?? '' }}</p>
                                    <p class="mb-1 text-muted" style="font-size: 0.88rem;"><strong>Account No:</strong> <span class="fw-bold text-dark">{{ $bankDetails->bank_ac_no ?? '' }}</span></p>
                                    <p class="mb-0 text-muted" style="font-size: 0.88rem;"><strong>IFSC Code:</strong> <span class="fw-bold text-dark">{{ $bankDetails->ifsc_code ?? '' }}</span></p>
                                @else
                                    <p class="mb-0 text-muted" style="font-size: 0.88rem;">No bank details available.</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                                <h6 class="mb-2 fw-bold text-primary"><i class="ti ti-clipboard-list me-1"></i> Delivery Details:</h6>
                                <p class="mb-1 text-muted" style="font-size: 0.88rem;"><strong>Buyer's Order No:</strong> {{ $sales->buyer_orderno ?? 'N/A' }}</p>
                                @if(!empty($sales->dispa_docno_one))
                                    <p class="mb-1 text-muted" style="font-size: 0.88rem;"><strong>Dispatch Doc No:</strong> {{ $sales->dispa_docno_one }}</p>
                                @endif
                                @if(!empty($sales->disp_through))
                                    <p class="mb-1 text-muted" style="font-size: 0.88rem;"><strong>Dispatched Through:</strong> {{ $sales->disp_through }}</p>
                                @endif
                                <p class="mb-0 text-muted" style="font-size: 0.88rem;"><strong>Supplier's Ref:</strong> {{ $sales->supplier_refno ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                                <h6 class="mb-2 fw-bold text-primary"><i class="ti ti-notes me-1"></i> Terms of Delivery / Notes:</h6>
                                <div class="mb-0 text-muted" style="font-size: 0.88rem; line-height: 1.5;">
                                    @if(!empty($sales->terms_delivery))
                                        {!! nl2br(e($sales->terms_delivery)) !!}
                                    @else
                                        1. This Proforma Invoice is not a tax invoice.<br>
                                        2. Payment terms as agreed upon.<br>
                                        3. Thank you for your business!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12 text-end d-print-none mt-4">
                            <a href="{{ url('/sales-proforma') }}" class="btn btn-outline-secondary me-2">
                                <i class="ti ti-arrow-left me-1"></i> Back
                            </a>
                            <a href="{{ url('/proforma-invoice-pdf/'.base64_encode($sales->id).'/pdf') }}" target="_blank" class="btn btn-primary">
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