<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Proforma Invoice #{{ $inv_num }}</title>
    <style>
        @page {
            margin: 8mm 10mm 8mm 10mm;
            size: A4 portrait;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8.8px;
            line-height: 1.35;
            color: #0f172a;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .r-sym {
            font-family: 'DejaVu Sans', sans-serif !important;
            font-size: inherit;
            font-weight: normal;
        }

        .nowrap {
            white-space: nowrap;
        }

        /* Top Header - Neutral Black Theme */
        .header-table {
            margin-bottom: 7px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 5px;
        }

        .header-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }

        .invoice-title {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 2px 0;
            letter-spacing: 0.5px;
        }

        .invoice-meta {
            font-size: 8.8px;
            color: #334155;
            margin: 1px 0;
        }

        /* Party Details (Company, Billing, Shipping) */
        .party-table {
            margin-bottom: 7px;
            border-collapse: separate;
            border-spacing: 6px 0;
        }

        .party-box {
            vertical-align: top;
            padding: 5px 8px;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            border-radius: 4px;
        }

        .box-title {
            font-size: 8.5px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 2px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 2px;
        }

        .party-name {
            font-size: 10px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 1px;
        }

        .party-desc {
            font-size: 8.5px;
            color: #334155;
            margin: 1px 0;
        }

        /* Line Items Table */
        .items-table {
            margin-top: 2px;
            margin-bottom: 7px;
            border: 1px solid #cbd5e1;
        }

        .items-table th {
            background-color: #f1f5f9;
            color: #000000;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 4.5px 5px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }

        .items-table td {
            padding: 4px 5px;
            border: 1px solid #e2e8f0;
            font-size: 8.8px;
            color: #0f172a;
        }

        .items-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Summary & Total Amount Layout */
        .summary-section {
            margin-bottom: 7px;
            border-collapse: separate;
            border-spacing: 8px 0;
            page-break-inside: avoid;
        }

        .words-card {
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            background-color: #f8fafc;
            padding: 6px 8px;
            vertical-align: top;
        }

        .words-title {
            font-size: 8.5px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 3px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 2px;
        }

        .words-text {
            color: #000000;
            font-weight: bold;
            font-size: 9.2px;
            line-height: 1.35;
        }

        .totals-card {
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            background-color: #ffffff;
            padding: 5px 7px;
            vertical-align: top;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 2px 4px;
            font-size: 8.8px;
        }

        .totals-row-label {
            color: #334155;
            text-align: left;
        }

        .totals-row-val {
            text-align: right;
            font-weight: 600;
            color: #000000;
            white-space: nowrap;
        }

        .grand-total-box {
            background-color: #f1f5f9;
            border-top: 1.5px solid #0f172a;
            border-bottom: 1.5px solid #0f172a;
            margin-top: 3px;
            padding: 3px 5px;
        }

        .grand-total-box td {
            font-size: 10px !important;
            font-weight: bold !important;
            color: #000000 !important;
            padding: 2px 0;
            white-space: nowrap;
        }

        /* Bottom Section / Cards */
        .avoid-break {
            page-break-inside: avoid;
        }

        .info-table {
            margin-bottom: 7px;
            border-collapse: separate;
            border-spacing: 6px 0;
            page-break-inside: avoid;
        }

        .info-box {
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            background-color: #f8fafc;
            padding: 5px 7px;
            font-size: 8.5px;
        }

        /* Authorized Signatory Section */
        .signatory-table {
            margin-top: 22px;
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .signatory-table td {
            border: none;
            padding: 0;
        }

        .sign-line {
            border-top: 1.5px solid #0f172a;
            padding-top: 3px;
            font-size: 8.8px;
            font-weight: bold;
            color: #000000;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
    </style>
</head>

<body>
    <!-- Top Header with Dynamic Logo and Proforma Invoice Details -->
    <table class="header-table">
        <tr>
            <td style="width: 55%; vertical-align: middle;">
                @php
                    $logoFile = optional($compDetails)->comp_logo;
                    $logoPath = null;
                    if (!empty($logoFile)) {
                        if (file_exists(public_path('storage/profile/' . $logoFile))) {
                            $logoPath = public_path('storage/profile/' . $logoFile);
                        } elseif (file_exists(storage_path('app/public/profile/' . $logoFile))) {
                            $logoPath = storage_path('app/public/profile/' . $logoFile);
                        } elseif (file_exists(public_path('uploads/profile/' . $logoFile))) {
                            $logoPath = public_path('uploads/profile/' . $logoFile);
                        }
                    }
                    if (empty($logoPath) || !file_exists($logoPath)) {
                        if (file_exists(public_path('storage/profile/e-cashbook.png'))) {
                            $logoPath = public_path('storage/profile/e-cashbook.png');
                        } elseif (file_exists(storage_path('app/public/profile/e-cashbook.png'))) {
                            $logoPath = storage_path('app/public/profile/e-cashbook.png');
                        }
                    }

                    $logoSrc = '';
                    if (!empty($logoPath) && file_exists($logoPath)) {
                        $logoData = base64_encode(file_get_contents($logoPath));
                        $logoMime = mime_content_type($logoPath) ?: 'image/png';
                        $logoSrc = 'data:' . $logoMime . ';base64,' . $logoData;
                    }
                @endphp
                @if(!empty($logoSrc))
                    <img src="{{ $logoSrc }}" id="uploadedImage" style="max-height: 40px; max-width: 175px; width: auto;" alt="Logo">
                @else
                    <h2 style="margin: 0; color: #000000; font-size: 14px;">{!! $compDetails->comp_name ?? '' !!}</h2>
                @endif
            </td>
            <td style="width: 45%; text-align: right; vertical-align: middle;">
                <div class="invoice-title">PROFORMA INVOICE</div>
                <div class="invoice-meta"><strong>Invoice No:</strong> <span style="font-weight: bold; color: #000000;">#{{ $inv_num }}</span></div>
                <div class="invoice-meta"><strong>Date:</strong> {{ date('d-m-Y', strtotime($invDate ?? $sales->created_at ?? date('Y-m-d'))) }}</div>
            </td>
        </tr>
    </table>

    <!-- Party Details (Company, Billing, Shipping) -->
    @php
        $shipAddressParts = array_filter([
            $sales->ship_addone ?? '',
            $sales->ship_addtwo ?? '',
            $sales->ship_city ?? '',
            $sales->ship_pin ?? ''
        ]);
        $hasShipping = !empty($shipAddressParts) && trim(implode('', $shipAddressParts)) !== '';
        $partyWidth = $hasShipping ? '33.33%' : '50%';
    @endphp

    <table class="party-table">
        <tr>
            <!-- Company Details -->
            <td class="party-box" style="width: {{ $partyWidth }};">
                <div class="box-title">Company Details (Issuer)</div>
                <div class="party-name">{!! $compDetails->comp_name ?? '' !!}</div>
                <p class="party-desc">{{ $compDetails->comp_bill_addone ?? '' }} {{ $compDetails->comp_bill_addtwo ?? '' }}</p>
                @if(!empty($compDetails->comp_pan_no))
                    <p class="party-desc"><strong>PAN:</strong> {{ $compDetails->comp_pan_no }}</p>
                @endif
                @if(!empty($compDetails->gst_no))
                    <p class="party-desc"><strong>GSTIN:</strong> {{ $compDetails->gst_no }}</p>
                @endif
            </td>

            <!-- Billing Address -->
            <td class="party-box" style="width: {{ $partyWidth }};">
                <div class="box-title">Issued To (Customer)</div>
                <div class="party-name">{!! $custDetails->cust_name ?? 'N/A' !!}</div>
                <p class="party-desc">
                    {{ optional($sales)->bill_addone ?? ($custDetails->cust_bill_addone ?? '') }} {{ optional($sales)->bill_addtwo ?? ($custDetails->cust_bill_addtwo ?? '') }}
                    @if(optional($sales)->bill_pin || optional($custDetails)->cust_bill_pin) - {{ optional($sales)->bill_pin ?? $custDetails->cust_bill_pin }} @endif
                </p>
                @if(!empty($custDetails->cust_pan))
                    <p class="party-desc"><strong>PAN:</strong> {{ $custDetails->cust_pan }}</p>
                @endif
                @if(!empty($custDetails->cust_gst_no))
                    <p class="party-desc"><strong>GSTIN:</strong> {{ $custDetails->cust_gst_no }}</p>
                @endif
            </td>

            <!-- Shipping Address (Only if present) -->
            @if($hasShipping)
            <td class="party-box" style="width: {{ $partyWidth }};">
                <div class="box-title">Shipping Address</div>
                <p class="party-desc" style="margin-top: 2px;">{{ implode(', ', $shipAddressParts) }}</p>
            </td>
            @endif
        </tr>
    </table>

    <!-- Line Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 4%; text-align: center;">#</th>
                <th style="width: 36%;">Description of Goods / Service</th>
                <th style="width: 14%;">HSN / SAC</th>
                <th style="width: 8%; text-align: center;">Qty</th>
                <th style="width: 14%; text-align: right;">Unit Price</th>
                <th style="width: 8%; text-align: center;">Disc</th>
                <th style="width: 16%; text-align: right;">Amount</th>
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
                        <td class="text-center">{{ $k + 1 }}</td>
                        <td><strong>{{ $value->item_name }}</strong></td>
                        <td>{{ ($value->sac_code != "") ? $value->sac_code : $value->hsn_code }}</td>
                        <td class="text-center">{{ $value->quantity }} {{ $value->base_unit ?? '' }}</td>
                        <td class="text-right nowrap"><span class="r-sym">&#8377;</span>{{ number_format($value->rate, 2) }}</td>
                        <td class="text-center">{{ $value->disc ?? 0 }}%</td>
                        <td class="text-right nowrap"><strong><span class="r-sym">&#8377;</span>{{ number_format($value->amount, 2) }}</strong></td>
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

    <!-- 📌 Summary & Total Amount Layout - Clean Black & Neutral Theme -->
    <table class="summary-section avoid-break">
        <tr>
            <!-- Left: Amount in words Card -->
            <td class="words-card" style="width: 52%;">
                <div class="words-title">Total Amount in Words</div>
                <div class="words-text">{{ ucwords(Helper::convert_number_to_words($finalGrandTotal)) }} Rupees Only.</div>
            </td>

            <!-- Right: Structured Totals Card -->
            <td class="totals-card" style="width: 48%;">
                <table class="totals-table">
                    <tr>
                        <td class="totals-row-label">Sub Total (Taxable):</td>
                        <td class="totals-row-val nowrap"><span class="r-sym">&#8377;</span>{{ number_format($taxableAmt, 2) }}</td>
                    </tr>
                    @if($totalDisc > 0)
                    <tr>
                        <td class="totals-row-label">Item Discount:</td>
                        <td class="totals-row-val nowrap"><span class="r-sym">&#8377;</span>{{ number_format($totalDisc, 2) }}</td>
                    </tr>
                    @endif
                    @if($gst_trans == 'intrastate')
                        <tr>
                            <td class="totals-row-label">CGST:</td>
                            <td class="totals-row-val nowrap"><span class="r-sym">&#8377;</span>{{ number_format($cgst, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="totals-row-label">SGST:</td>
                            <td class="totals-row-val nowrap"><span class="r-sym">&#8377;</span>{{ number_format($sgst, 2) }}</td>
                        </tr>
                    @else
                        <tr>
                            <td class="totals-row-label">IGST / Taxes:</td>
                            <td class="totals-row-val nowrap"><span class="r-sym">&#8377;</span>{{ number_format($igst, 2) }}</td>
                        </tr>
                    @endif

                    @if(!empty($special_discount_amount) && $special_discount_amount > 0)
                        <tr>
                            <td class="totals-row-label">Special Discount:</td>
                            <td class="totals-row-val nowrap">- <span class="r-sym">&#8377;</span>{{ number_format($special_discount_amount, 2) }}</td>
                        </tr>
                    @endif

                    @if($totalGovPay > 0)
                        <tr>
                            <td class="totals-row-label">Government Fees:</td>
                            <td class="totals-row-val nowrap"><span class="r-sym">&#8377;</span>{{ number_format($totalGovPay, 2) }}</td>
                        </tr>
                    @endif

                    @if($totalSerPay > 0)
                        <tr>
                            <td class="totals-row-label">Service Charges:</td>
                            <td class="totals-row-val nowrap"><span class="r-sym">&#8377;</span>{{ number_format($totalSerPay, 2) }}</td>
                        </tr>
                    @endif
                </table>

                <table class="grand-total-box" style="width: 100%;">
                    <tr>
                        <td style="text-align: left;">Grand Total:</td>
                        <td style="text-align: right;" class="nowrap"><span class="r-sym">&#8377;</span>{{ number_format($finalGrandTotal, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- 📌 Authorized Signatory with ample height & clearance for DSC -->
    <table class="signatory-table avoid-break">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: right; vertical-align: bottom;">
                @php
                    $sigPath = !empty($sales->signature) ? public_path('uploads/invoice-signature/' . $sales->signature) : null;
                    $sigSrc = '';
                    if (!empty($sigPath) && file_exists($sigPath)) {
                        $sigData = base64_encode(file_get_contents($sigPath));
                        $sigMime = mime_content_type($sigPath) ?: 'image/png';
                        $sigSrc = 'data:' . $sigMime . ';base64,' . $sigData;
                    }
                @endphp
                <div style="height: 52px; min-height: 52px; margin-bottom: 4px; text-align: right;">
                    @if(!empty($sigSrc))
                        <img src="{{ $sigSrc }}" style="max-height: 48px; width: auto;" alt="Signature">
                    @endif
                </div>
                <div class="sign-line">Authorized Signatory</div>
            </td>
        </tr>
    </table>

    <!-- 📌 ROW 1: Bank Details & Terms/Notes in structured boxes (avoid-break) -->
    <table class="info-table avoid-break">
        <tr>
            <!-- Bank Details (52%) -->
            <td class="info-box" style="width: 52%; vertical-align: top;">
                <div class="box-title">Bank Details</div>
                @if(!empty($bankDetails))
                    <table style="width: 100%; font-size: 8.5px; border-collapse: collapse;">
                        <tr>
                            <td style="width: 52%; padding: 2px 4px 2px 0; vertical-align: top; border: none;">
                                <span style="color: #64748b;">Account Holder:</span><br>
                                <strong style="color: #000000;">{{ $bankDetails->accholder_name ?? '' }}</strong>
                            </td>
                            <td style="width: 48%; padding: 2px 0 2px 4px; vertical-align: top; border: none;">
                                <span style="color: #64748b;">Account No:</span><br>
                                <strong style="color: #000000;">{{ $bankDetails->bank_ac_no ?? '' }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 2px 4px 2px 0; vertical-align: top; border: none;">
                                <span style="color: #64748b;">Bank Name:</span><br>
                                <strong style="color: #000000;">{{ $bankDetails->bank_name ?? '' }}</strong>
                            </td>
                            <td style="padding: 2px 0 2px 4px; vertical-align: top; border: none;">
                                <span style="color: #64748b;">IFSC Code:</span><br>
                                <strong style="color: #000000;">{{ $bankDetails->ifsc_code ?? '' }}</strong>
                            </td>
                        </tr>
                        @if(!empty($bankDetails->bank_branch))
                        <tr>
                            <td colspan="2" style="padding: 2px 0; vertical-align: top; border: none;">
                                <span style="color: #64748b;">Branch:</span> <strong style="color: #000000;">{{ $bankDetails->bank_branch }}</strong>
                            </td>
                        </tr>
                        @endif
                    </table>
                @else
                    <p style="margin: 0; color: #64748b; font-size: 8.5px;">No bank details available.</p>
                @endif
            </td>

            <!-- Terms & Remarks (48%) -->
            <td class="info-box" style="width: 48%; vertical-align: top;">
                <div class="box-title">Terms & Conditions / Note</div>
                <div class="party-desc" style="line-height: 1.45; font-size: 8.5px; color: #334155;">
                    @if(!empty($sales->terms_delivery))
                        {!! nl2br(e($sales->terms_delivery)) !!}
                    @else
                        1. This Proforma Invoice is not a tax invoice.<br>
                        2. Payment terms as agreed upon.<br>
                        3. Thank you for your business!
                    @endif
                </div>
            </td>
        </tr>
    </table>
</body>
</html>