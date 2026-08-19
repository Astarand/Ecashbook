@extends('App.Layout')

@section('container')
<div class="pc-content">
    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <!-- Invoice Header: Logo & Invoice No / Date -->
                        <div class="col-12 pb-3 border-bottom">
                            <div class="row align-items-center g-3">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-2">
                                        @php
                                            $logoUrl = optional($compDetails)->comp_logo ? asset('storage/profile/' . optional($compDetails)->comp_logo) : asset('storage/profile/e-cashbook.png');
                                        @endphp
                                        <img src="{{ $logoUrl }}" id="uploadedImage" class="img-fluid" alt="Company Logo" style="max-height: 60px; max-width: 200px; width: auto; object-fit: contain;">
                                    </div>
                                </div>
                                <div class="col-sm-6 text-sm-end">
                                    <h5 class="mb-1 fw-bold text-dark">Invoice No: <span class="text-primary">#{{ $inv_num }}</span></h5>
                                    <h6 class="mb-0 text-muted">Date: <span class="fw-semibold text-dark">{{ date("d-m-Y", strtotime($invDate)) }}</span></h6>
                                </div>
                            </div>
                        </div>

                        <!-- Party Details Section (Company, Billing, Shipping) -->
                        @php
                            $shipAddressParts = array_filter([
                                $sales->ship_addone ?? '',
                                $sales->ship_addtwo ?? '',
                                $sales->ship_city ?? '',
                                $sales->ship_pin ?? ''
                            ]);
                            $hasShipping = !empty($shipAddressParts) && trim(implode('', $shipAddressParts)) !== '';
                            $partyColClass = $hasShipping ? 'col-sm-4' : 'col-sm-6';
                        @endphp

                        <!-- Company Details -->
                        <div class="{{ $partyColClass }}">
                            <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                                <h6 class="mb-2 fw-bold text-primary">Company Details:</h6>
                                <h6 class="fw-bold mb-1 text-dark">{{ $compDetails->comp_name }}</h6>
                                <p class="mb-1 text-muted" style="font-size: 0.88rem;">{{ $compDetails->comp_bill_addone ?? '' }} {{ $compDetails->comp_bill_addtwo ?? '' }}</p>
                                @if(!empty($compDetails->comp_pan_no))
                                    <p class="mb-1 text-muted" style="font-size: 0.88rem;"><strong>PAN:</strong> {{ $compDetails->comp_pan_no }}</p>
                                @endif
                                @if(!empty($compDetails->gst_no))
                                    <p class="mb-0 text-muted" style="font-size: 0.88rem;"><strong>GSTIN:</strong> {{ $compDetails->gst_no }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Billing Address -->
                        <div class="{{ $partyColClass }}">
                            <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                                <h6 class="mb-2 fw-bold text-primary">Billing Address:</h6>
                                <h5 class="fw-bold mb-1 text-dark">{{ $custDetails->cust_name ?? 'N/A' }}</h5>
                                <p class="mb-1 text-muted" style="font-size: 0.88rem;">
                                    {{ optional($sales)->bill_addone ?? '' }} {{ optional($sales)->bill_addtwo ?? '' }}
                                    @if(optional($sales)->bill_pin) - {{ optional($sales)->bill_pin }} @endif
                                </p>
                                @if(!empty($custDetails->cust_pan))
                                    <p class="mb-1 text-muted" style="font-size: 0.88rem;"><strong>PAN:</strong> {{ $custDetails->cust_pan }}</p>
                                @endif
                                @if(!empty($custDetails->cust_gst_no))
                                    <p class="mb-0 text-muted" style="font-size: 0.88rem;"><strong>GSTIN:</strong> {{ $custDetails->cust_gst_no }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Shipping Address (Only displayed if available) -->
                        @if($hasShipping)
                        <div class="{{ $partyColClass }}">
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
                                            <th style="width: 35%;">Description of Goods / Service</th>
                                            <th style="width: 15%;">HSN / SAC</th>
                                            <th style="width: 10%;">Quantity</th>
                                            <th style="width: 12%;">Unit Price</th>
                                            <th style="width: 10%;">Discount</th>
                                            <th style="width: 13%;" class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
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
                                        ?>
                                        <?php if (!empty($sales_values)) {
                                            foreach ($sales_values as $k => $value) {
                                                $k = $k + 1;
                                        ?>
                                                <tr>
                                                    <td>{{ $k }}</td>
                                                    <td style="white-space:normal; overflow-wrap:anywhere;">
                                                        <span class="fw-semibold text-dark">{{ $value->item_name }}</span>
                                                    </td>
                                                    <td><span class="badge bg-light text-dark border">{{ ($value->sac_code != "") ? $value->sac_code : $value->hsn_code }}</span></td>
                                                    <td>{{ $value->quantity }} {{ $value->base_unit ?? '' }}</td>
                                                    <td>₹{{ number_format($value->rate, 2) }}</td>
                                                    <td>{{ $value->disc_amt }}%</td>
                                                    <td class="text-end fw-bold">₹{{ number_format($value->amount, 2) }}</td>
                                                </tr>
                                        <?php
                                                $totalDisc += $value->disc_amt;
                                                $totalTax += $value->tax_amt;
                                                $cgst += $value->tax_amt / 2;
                                                $sgst += $value->tax_amt / 2;
                                                $igst += $value->tax_amt;
                                                $taxableAmt += $value->amount;
												$totalGovPay += $value->gov_pay; 
												$totalSerPay += $value->ser_pay;
                                                $totalAmount += $value->amount;
												$gst_trans = $value->gst_trans; 
                                            }
                                            $totalAmount = getRoundedAmount(($totalAmount + $totalTax + $totalGovPay + $totalSerPay));
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-start">
                                <hr class="mb-2 mt-1 border-secondary border-opacity-50">
                            </div>
                        </div>

                        <!-- Calculations Summary -->
                        <div class="col-12">
                            <div class="invoice-total ms-auto" style="max-width: 420px;">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <p class="text-muted mb-1 text-start">Taxable Amount:</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1 text-end fw-semibold">₹{{ number_format($taxableAmt, 2) }}</p>
                                    </div>

									<div class="col-6">
										<p class="text-muted mb-1 text-start">Discount:</p>
									</div>
									<div class="col-6">
										<p class="fw-semibold mb-1 text-end">₹{{ number_format($totalDisc, 2) }}</p>
									</div>

									@if($gst_trans == 'intrastate')
                                    <div class="col-6">
                                        <p class="text-muted mb-1 text-start">CGST 9.0%:</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1 text-end">₹{{ number_format($cgst, 2) }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="text-muted mb-1 text-start">SGST 9.0%:</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1 text-end">₹{{ number_format($sgst, 2) }}</p>
                                    </div>
									@else
									<div class="col-6">
										<p class="text-muted mb-1 text-start">IGST:</p>
									</div>
									<div class="col-6">
										<p class="fw-semibold mb-1 text-end">₹{{ number_format($igst, 2) }}</p>
									</div>
									@endif

                                    @if($totalGovPay > 0)
									<div class="col-6">
										<p class="text-muted mb-1 text-start">Government Fees:</p>
									</div>
									<div class="col-6">
										<p class="fw-semibold mb-1 text-end">₹{{ number_format($totalGovPay, 2) }}</p>
									</div>
                                    @endif

                                    @if($totalSerPay > 0)
									<div class="col-6">
										<p class="text-muted mb-1 text-start">Service Charges:</p>
									</div>
									<div class="col-6">
										<p class="fw-semibold mb-1 text-end">₹{{ number_format($totalSerPay, 2) }}</p>
									</div>
                                    @endif

                                    <div class="col-12"><hr class="my-1"></div>

                                    <div class="col-6">
                                        <h6 class="fw-bold mb-1 text-start text-dark">Total Amount:</h6>
                                    </div>
                                    <div class="col-6">
                                        <h6 class="fw-bold mb-1 text-end text-primary" style="font-size: 1.15rem;">₹{{ number_format($totalAmount, 2) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Amount in words -->
                        <div class="col-12">
                            <div class="alert alert-light border rounded-3 p-2 text-end mb-0">
                                <h6 class="mb-0 text-dark">Total amount in words: <span class="fw-bold text-primary">{{ ucwords(Helper::convert_number_to_words($totalAmount)) }} Rupees Only.</span></h6>
                            </div>
                        </div>

                        <!-- Authorized Signatory -->
                        <div class="col-12">
                            <div class="row justify-content-end mt-3">
                                <div class="col-sm-4 text-end">
                                    @if(!empty($sales->signature) && file_exists(public_path('uploads/invoice-signature/'.$sales->signature)))
                                        <img src="{{ asset('uploads/invoice-signature/'.$sales->signature) }}"
                                            class="img-fluid"
                                            alt="Signature"
                                            style="width: 150px; height: 75px; object-fit: contain;">
                                    @endif

                                    <div class="border-top border-2 pt-2 mt-2">
                                        <p class="mb-0 fw-semibold text-dark">Authorized Signatory</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 📌 ROW 1: Bank Details & QR Code in a single row -->
                        <div class="col-12 mt-3">
                            <div class="row g-3">
                                <div class="col-sm-8 col-md-9">
                                    <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                                        <h6 class="mb-2 fw-bold text-primary d-flex align-items-center">
                                            <i class="ti ti-building-bank me-1 fs-5"></i> Bank Details
                                        </h6>
                                        @if(!empty($bankDetails))
                                            <div class="row g-2" style="font-size: 0.88rem;">
                                                <div class="col-sm-6">
                                                    <p class="mb-1 text-muted"><strong>Account Holder:</strong> <span class="text-dark">{{ $bankDetails->accholder_name ?? '' }}</span></p>
                                                    <p class="mb-1 text-muted"><strong>Bank Name:</strong> <span class="text-dark">{{ $bankDetails->bank_name ?? '' }}</span></p>
                                                    <p class="mb-1 text-muted"><strong>Branch:</strong> <span class="text-dark">{{ $bankDetails->bank_branch ?? '' }}</span></p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p class="mb-1 text-muted"><strong>Account No:</strong> <span class="text-dark fw-bold">{{ $bankDetails->bank_ac_no ?? '' }}</span></p>
                                                    <p class="mb-1 text-muted"><strong>IFSC Code:</strong> <span class="text-dark fw-bold">{{ $bankDetails->ifsc_code ?? '' }}</span></p>
                                                </div>
                                            </div>
                                        @else
                                            <p class="mb-0 text-muted" style="font-size: 0.88rem;">No bank details available.</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-3">
                                    <div class="border rounded-3 p-3 h-100 d-flex flex-column justify-content-center align-items-center text-center bg-light-subtle">
                                        @if(!empty($bankDetails) && !empty($bankDetails->bank_qr_code) && file_exists(public_path('storage/' . $bankDetails->bank_qr_code)))
                                            <img src="{{ asset('storage/' . $bankDetails->bank_qr_code) }}" class="img-fluid rounded border" alt="QR Code" style="width: 95px; height: 95px; object-fit: contain;">
                                            <small class="text-muted mt-1 fw-semibold" style="font-size: 11px;">Scan to Pay</small>
                                        @else
                                            <i class="ti ti-qrcode fs-1 text-muted mb-1 opacity-50"></i>
                                            <p class="mb-0 text-muted small">No QR code available</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 📌 ROW 2: Delivery Details & Terms of Delivery in a single row -->
                        <div class="col-12 mt-2">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                                        <h6 class="mb-2 fw-bold text-primary d-flex align-items-center">
                                            <i class="ti ti-truck-delivery me-1 fs-5"></i> Delivery Details
                                        </h6>
                                        <div style="font-size: 0.88rem;">
                                            <p class="mb-1 text-muted"><strong>Buyer's Order No:</strong> <span class="text-dark">{{ $sales->buyer_orderno ?? 'N/A' }}</span></p>
                                            @if(!empty($sales->dispa_docno_one))
                                                <p class="mb-1 text-muted"><strong>Dispatch Doc No:</strong> <span class="text-dark">{{ $sales->dispa_docno_one }}</span></p>
                                            @endif
                                            @if(!empty($sales->disp_through))
                                                <p class="mb-1 text-muted"><strong>Dispatched Through:</strong> <span class="text-dark">{{ $sales->disp_through }}</span></p>
                                            @endif
                                            <p class="mb-1 text-muted"><strong>Supplier's Ref:</strong> <span class="text-dark">{{ $sales->supplier_refno ?? 'N/A' }}</span></p>
                                            <p class="mb-0 text-muted"><strong>Other Reference(s):</strong> <span class="text-dark">{{ $sales->other_refno ?? 'N/A' }}</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                                        <h6 class="mb-2 fw-bold text-primary d-flex align-items-center">
                                            <i class="ti ti-file-certificate me-1 fs-5"></i> Terms of Delivery
                                        </h6>
                                        <p class="mb-0 text-muted" style="font-size: 0.88rem; line-height: 1.5;">
                                            {{ !empty($sales->terms_delivery) ? $sales->terms_delivery : 'Standard commercial terms and conditions apply.' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        @php
                            $sid = base64_encode($sales->id);
                        @endphp
                        <div class="col-12 text-end d-print-none mt-4 pt-3 border-top">
                            <a href="{{ url('edit-sales-invoice/'.$sid) }}" class="btn btn-outline-secondary rounded-pill px-4 me-2">
                                <i class="ti ti-arrow-left me-1"></i> Back
                            </a>

                            <a href="{{ url('sale-invoices') }}" class="btn btn-outline-danger rounded-pill px-4 me-2">
                                Cancel
                            </a>

                            <button class="btn btn-outline-secondary rounded-pill px-4 me-2 btn-print-invoice">
                                <i class="ti ti-printer me-1"></i> Print
                            </button>

                            <a href="{{ url('/sales-invoice-pdf/'.base64_encode($sales->id).'/pdf') }}" target="_blank" class="btn btn-primary rounded-pill px-4 shadow-sm" style="background-color: #008CAD; border-color: #008CAD;">
                                <i class="ti ti-download me-1"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    @page {
        size: A4 portrait;
        margin: 0mm; /* Removing default page margins removes browser title, date, URL, and page numbers */
    }
    *, ::after, ::before {
        text-shadow: none !important;
        box-shadow: none !important;
    }
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    /* Hide all layout elements, sidebar, topbar, footer, and action buttons */
    .pc-sidebar,
    .pc-mob-header,
    .pc-header,
    .pct-customizer,
    .modal,
    .navbar,
    .page-header,
    .pc-footer,
    footer,
    .footer,
    .d-print-none {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .pc-container {
        top: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        position: static !important;
        min-height: auto !important;
    }
    .pc-content {
        margin: 0 !important;
        padding: 10mm 12mm !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
        margin: 0 !important;
        padding: 0 !important;
        background: transparent !important;
    }
    .card-body {
        padding: 0 !important;
    }
    .badge {
        border: 1px solid #dee2e6 !important;
    }
    .table {
        border-collapse: collapse !important;
    }
    .table td, .table th {
        background-color: #fff !important;
    }
    a {
        text-decoration: none !important;
        color: inherit !important;
    }
}
</style>

<script>
    document.querySelector('.btn-print-invoice').addEventListener('click', function() {
        var originalTitle = document.title;
        document.title = "Sales-Invoice-{{ $inv_num }}";
        window.print();
        document.title = originalTitle;
    });
</script>
@endsection