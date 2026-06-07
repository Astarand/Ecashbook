@extends('App.Layout')

@section('container')
<div class="pc-content">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="row align-items-center g-3">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="{{ asset('assets/images/logo-small.png') }}" class="img-fluid" alt="Logo">
                                    </div>
                                </div>
                                <div class="col-sm-6 text-sm-end">
                                    <h6>PO Invoice No: <span class="text-muted f-w-400">#{{ $inv_num }}</span></h6>
                                    <h6>Date: <span class="text-muted f-w-400">{{ date("d-m-Y",strtotime($invDate)) }}</span></h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="border rounded p-3">
                                <h6 class="mb-0">Company Details:</h6>
                                <h6>{{ $compDetails->comp_name }}</h6>
								<p class="mb-0">{{ $compDetails->comp_bill_addone }}</p>
                                <p class="mb-0">PAN: {{ $compDetails->comp_pan_no }}</p>
                                <p class="mb-0">GSTIN: {{ $compDetails->gst_no }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="border rounded p-3">
                                <h6 class="mb-0">Billing Address:</h6>
                                <h5>{{ optional($custDetails)->vendor_name ?? '' }} </h5>								
								<p class="mb-0">{{ optional($sales)->bill_addone ?? '' }}, {{ optional($sales)->cust_bill_pin ?? '' }}</p>
                                <p class="mb-0">PAN: {{ $custDetails->vendor_pan }}</p>
                                <p class="mb-0">GSTIN: {{ optional($custDetails)->vendor_gstin ?? '' }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="border rounded p-3" style="height: 140px;">
                                <h6 class="mb-0">Shipping Address:</h6>
								<p class="mb-0">{{ ($sales->ship_addone || $sales->ship_pin) ? trim($sales->ship_addone.', '.$sales->ship_pin, ', ') : '' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Description of Goods</th>
                                            <th>HSN / SAC</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Discount</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cgst = 0;
                                        $igst = 0;
                                        $taxableAmt = 0;
                                        $totalDisc = 0;
                                        $totalTax = 0;
                                        $totalAmount = 0;
                                        ?>
                                        <?php if (!empty($sales_values)) {
                                            foreach ($sales_values as $k => $value) {
                                                $k = $k + 1;
                                        ?>
                                                <tr>
                                                    <td>{{ $k }}</td>
                                                    <td>{{ $value->item_name }}</td>
                                                    <td>{{ ($value->sac_code!="")?$value->sac_code:$value->hsn_code }}</td>
                                                    <td>{{ $value->quantity }}</td>
                                                    <td>₹{{ $value->rate }}</td>
                                                    <td>{{ $value->disc_amt }}%</td>
                                                    <td class="text-end">₹{{ $value->amount }}</td>
                                                </tr>
                                        <?php
                                                $totalDisc += $value->disc_amt;
                                                $totalTax += $value->tax_amt;
                                                $cgst += ($value->amount) * 9 / 100;
                                                $igst += ($value->amount) * 9 / 100;
                                                $taxableAmt += $value->amount;
                                                $totalAmount += $value->amount;
                                            }
                                            $totalAmount = ceil(($totalAmount + $cgst + $igst));
                                        } ?>
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
                                        <p class="text-muted mb-1 text-start">Taxable Amount:</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1 text-end">₹{{ $taxableAmt }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="text-muted mb-1 text-start">CGST 9.0%:</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1 text-end">₹{{ $cgst }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="text-muted mb-1 text-start">IGST 9.0%:</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1 text-end">₹{{ $igst }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="f-w-600 mb-1 text-start">Total Amount:</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="f-w-600 mb-1 text-end">₹{{ $totalAmount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <h5 class="mb-2 text-end">Total amount in words: <span class="text-muted">{{ ucwords(Helper::convert_number_to_words($totalAmount)) }} Rupees Only.</span></h5>
                        </div>
                        <div class="col-12">
                            <div class="row justify-content-end mt-3">
                                <div class="col-sm-4 text-end">
                                    <img src="{{ asset('assets/images/signature.png') }}" class="img-fluid" alt="Signature" style="width: 150px; height: 80px;">
                                    <div class="border-top border-2 pt-2">
                                        <p class="mb-0">Authorized Signatory</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="border rounded p-3">
                                <h6 class="mb-0">Payment Details:</h6>
                                <p class="mb-0">Mode Of Payment: {{ $sales->mode_of_pay ?? '' }}</p>
                                <p class="mb-0">Dispatch Document No:{{ $sales->dispa_docno_one ?? '' }}</p>
                                <p class="mb-0">Dispatched Through:{{ $sales->disp_through ?? '' }}</p>
                                <p class="mb-0">Destination:{{ $sales->ship_pin ?? '' }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="border rounded p-3">
                                <h6 class="mb-0">Delivery Details:</h6>
                                <p class="mb-0">Buyers Order No:{{ $sales->buyer_orderno ?? '' }}</p>
                                <p class="mb-0">Delivery Note Date:</p>
                                <p class="mb-0">Supplier's Ref:{{ $sales->supplier_refno ?? '' }}</p>
                                <p class="mb-0">Other Reference(s): {{ $sales->other_refno ?? '' }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="border rounded p-3" style="height: 135px;">
                                <h6 class="mb-0">Terms of Delivery:</h6>
                                <p class="mb-0">{{ (!empty($sales->terms_delivery) && $sales->terms_delivery != 'undefined') ? $sales->terms_delivery : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-12 text-end d-print-none">
                            <button class="btn btn-outline-secondary btn-print-invoice">Download</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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