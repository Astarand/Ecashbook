<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Custom Invoice #{{ $invoice->invoice_number }}</title>
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

        /* Party Details (Company, Customer) */
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
    <!-- Top Header with Dynamic Logo and Invoice Details -->
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
                    <h2 style="margin: 0; color: #000000; font-size: 14px;">{!! $invoice->issued_by_company_name ?? '' !!}</h2>
                @endif
            </td>
            <td style="width: 45%; text-align: right; vertical-align: middle;">
                <div class="invoice-title">CUSTOM INVOICE</div>
                <div class="invoice-meta"><strong>Invoice No:</strong> <span style="font-weight: bold; color: #000000;">#{{ $invoice->invoice_number }}</span></div>
                <div class="invoice-meta"><strong>Date:</strong> {{ date('d-m-Y', strtotime($invoice->invoice_date)) }}</div>
            </td>
        </tr>
    </table>

    <!-- Party Details (Issued By & Issued To) -->
    <table class="party-table">
        <tr>
            <!-- Issued By -->
            <td class="party-box" style="width: 50%;">
                <div class="box-title">Issued By</div>
                <div class="party-name">{!! $invoice->issued_by_company_name !!}</div>
                <p class="party-desc">{{ $invoice->issued_by_address1 }} {{ $invoice->issued_by_address2 ? ', '.$invoice->issued_by_address2 : '' }}</p>
                <p class="party-desc">{{ $invoice->issued_by_city }} {{ $invoice->issued_by_state ? ', '.$invoice->issued_by_state : '' }} {{ $invoice->issued_by_pincode ? '- '.$invoice->issued_by_pincode : '' }}</p>
                @if(!empty($invoice->issued_by_contact_no))
                    <p class="party-desc"><strong>Contact:</strong> {{ $invoice->issued_by_contact_no }}</p>
                @endif
                @if(!empty($invoice->issued_by_gst))
                    <!--<p class="party-desc"><strong>GSTIN:</strong> {{ $invoice->issued_by_gst }}</p>-->
                @endif
            </td>

            <!-- Issued To -->
            <td class="party-box" style="width: 50%;">
                <div class="box-title">Issued To (Customer)</div>
                <div class="party-name">{!! $invoice->issued_to_company_name ?? 'N/A' !!}</div>
                <p class="party-desc">{{ $invoice->issued_to_address1 }} {{ $invoice->issued_to_address2 ? ', '.$invoice->issued_to_address2 : '' }}</p>
                <p class="party-desc">
					{{ !empty($invoice->issued_to_city) && strtolower(trim($invoice->issued_to_city)) !== 'null' ? $invoice->issued_to_city : '' }}
					{{ !empty($invoice->issued_to_state) && strtolower(trim($invoice->issued_to_state)) !== 'null' ? ', '.$invoice->issued_to_state : '' }}
					{{ !empty($invoice->issued_to_pincode) && strtolower(trim($invoice->issued_to_pincode)) !== 'null' ? ' - '.$invoice->issued_to_pincode : '' }}
				</p>
                @if(!empty($invoice->issued_to_contact_no))
                    <p class="party-desc"><strong>Contact:</strong> {{ $invoice->issued_to_contact_no }}</p>
                @endif
                @if(!empty($invoice->issued_to_gst))
                    <!--<p class="party-desc"><strong>GSTIN:</strong> {{ $invoice->issued_to_gst }}</p>-->
                @endif
            </td>
        </tr>
    </table>

    <!-- Line Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 4%; text-align: center;">#</th>
                <th style="width: 32%;">Product / Service</th>            
                <th style="width: 7%; text-align: center;">Qty</th>
                <th style="width: 14%; text-align: right;">Price</th>
                <th style="width: 17%; text-align: right;">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subTotal = 0;
                $totalCgst = 0;
                $totalSgst = 0;
                $totalIgst = 0;
            @endphp
            @if(!empty($invoiceProducts))
                @foreach($invoiceProducts as $k => $product)
                    @php
                        $itemBase = (float)$product->price * (float)$product->quantity;
                        $subTotal += $itemBase;
                        $totalCgst += (float)$product->cgst;
                        $totalSgst += (float)$product->sgst;
                        $totalIgst += (float)$product->igst;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $k + 1 }}</td>
                        <td><strong>{{ $product->product_name }}</strong></td>
                        <td class="text-center">{{ $product->quantity }}</td>
                        <td class="text-right nowrap"><span class="r-sym">&#8377;</span>{{ number_format((float)$product->price, 2) }}</td>                        
                        <td class="text-right nowrap"><strong><span class="r-sym">&#8377;</span>{{ number_format((float)$product->total_price, 2) }}</strong></td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <!-- 📌 Summary & Total Amount Layout -->
    <table class="summary-section avoid-break">
        <tr>
            <!-- Left: Amount in words Card -->
            <td class="words-card" style="width: 52%;">
                <div class="words-title">Total Amount in Words</div>
                <div class="words-text">
                    {{ !empty($invoice->total_amount_in_words) ? $invoice->total_amount_in_words : ucwords(Helper::convert_number_to_words((float)$invoice->total_amount)) . ' Rupees Only.' }}
                </div>

                @if(!empty($invoice->notes))
                    <div style="margin-top: 5px; padding-top: 3px; border-top: 1px solid #cbd5e1; font-size: 8px; color: #475569;">
                        <strong>Note:</strong> {{ $invoice->notes }}
                    </div>
                @endif
            </td>

            <!-- Right: Structured Totals Card -->
            <td class="totals-card" style="width: 48%;">
                <table class="totals-table">
                    <tr>
                        <td class="totals-row-label">Sub Total:</td>
                        <td class="totals-row-val nowrap"><span class="r-sym">&#8377;</span>{{ number_format($subTotal, 2) }}</td>
                    </tr>
                    @if((float)$invoice->discount_amount > 0)
                        <tr>
                            <td class="totals-row-label">Discount:</td>
                            <td class="totals-row-val nowrap"><span class="r-sym">&#8377;</span>{{ number_format((float)$invoice->discount_amount, 2) }}</td>
                        </tr>
                    @endif
                    @if($totalCgst > 0)
                        <tr>
                            <td class="totals-row-label">CGST:</td>
                            <td class="totals-row-val nowrap"><span class="r-sym">&#8377;</span>{{ number_format($totalCgst, 2) }}</td>
                        </tr>
                    @endif
                    @if($totalSgst > 0)
                        <tr>
                            <td class="totals-row-label">SGST:</td>
                            <td class="totals-row-val nowrap"><span class="r-sym">&#8377;</span>{{ number_format($totalSgst, 2) }}</td>
                        </tr>
                    @endif
                    @if($totalIgst > 0)
                        <tr>
                            <td class="totals-row-label">IGST:</td>
                            <td class="totals-row-val nowrap"><span class="r-sym">&#8377;</span>{{ number_format($totalIgst, 2) }}</td>
                        </tr>
                    @endif
                </table>

                <table class="grand-total-box" style="width: 100%;">
                    <tr>
                        <td style="text-align: left;">Grand Total:</td>
                        <td style="text-align: right;" class="nowrap"><span class="r-sym">&#8377;</span>{{ number_format((float)$invoice->total_amount, 2) }}</td>
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
                    $sigSrc = '';
                    if (!empty($invoice->upload_signature)) {
                        $sigFile = $invoice->upload_signature;
                        $sigPath = null;
                        if (file_exists(public_path('storage/custom_invoice_img/' . $sigFile))) {
                            $sigPath = public_path('storage/custom_invoice_img/' . $sigFile);
                        } elseif (file_exists(storage_path('app/public/custom_invoice_img/' . $sigFile))) {
                            $sigPath = storage_path('app/public/custom_invoice_img/' . $sigFile);
                        } elseif (file_exists(public_path('uploads/custom_invoice_img/' . $sigFile))) {
                            $sigPath = public_path('uploads/custom_invoice_img/' . $sigFile);
                        }
                        if (!empty($sigPath) && file_exists($sigPath)) {
                            $sigData = base64_encode(file_get_contents($sigPath));
                            $sigMime = mime_content_type($sigPath) ?: 'image/png';
                            $sigSrc = 'data:' . $sigMime . ';base64,' . $sigData;
                        }
                    }
                @endphp
                <div style="height: 52px; min-height: 52px; margin-bottom: 4px; text-align: right;">
                    @if(!empty($sigSrc))
                        <img src="{{ $sigSrc }}" style="max-height: 48px; width: auto;" alt="Signature">
                    @endif
                </div>
                <div class="sign-line">{{ !empty($invoice->signature_name) ? $invoice->signature_name : 'Authorized Signatory' }}</div>
            </td>
        </tr>
    </table>

    <!-- 📌 ROW 1: Bank Details (Full Width, avoid-break) -->
    <table class="info-table avoid-break">
        <tr>
            <td class="info-box" style="width: 100%; vertical-align: top;">
                <div class="box-title">Bank Details</div>
                @if(!empty($invoice->bank_name) || !empty($bankDetails))
                    @php
                        $bName = $invoice->bank_name ?? ($bankDetails->bank_name ?? '');
                        $bHolder = $invoice->account_holder_name ?? ($bankDetails->accholder_name ?? '');
                        $bAcNo = $invoice->account_no ?? ($bankDetails->bank_ac_no ?? '');
                        $bIfsc = $invoice->ifsc_code ?? ($bankDetails->ifsc_code ?? '');
                        $bBranch = $invoice->branch_name ?? ($bankDetails->bank_branch ?? '');
                    @endphp
                    <table style="width: 100%; font-size: 8.5px;">
                        <tr>
                            <td style="width: 50%; padding: 1.5px 0;"><strong>Account Holder:</strong> {{ $bHolder }}</td>
                            <td style="width: 50%; padding: 1.5px 0;"><strong>Account No:</strong> <span style="font-weight: bold; color: #000000;">{{ $bAcNo }}</span></td>
                        </tr>
                        <tr>
                            <td style="padding: 1.5px 0;"><strong>Bank Name:</strong> {{ $bName }}</td>
                            <td style="padding: 1.5px 0;"><strong>IFSC Code:</strong> <span style="font-weight: bold; color: #000000;">{{ $bIfsc }}</span></td>
                        </tr>
                        @if(!empty($bBranch))
                        <tr>
                            <td colspan="2" style="padding: 1.5px 0;"><strong>Branch:</strong> {{ $bBranch }}</td>
                        </tr>
                        @endif
                    </table>
                @else
                    <p style="margin: 0; color: #64748b; font-size: 8.5px;">No bank details provided.</p>
                @endif
            </td>
        </tr>
    </table>

    <!-- 📌 ROW 2: Notes & Terms & Conditions (avoid-break) -->
    <table class="info-table avoid-break" style="margin-bottom: 0;">
        <tr>
            @if(!empty($invoice->notes))
                <!-- Notes -->
                <td class="info-box" style="width: 50%; vertical-align: top;">
                    <div class="box-title">Note</div>
                    <p class="party-desc" style="line-height: 1.35; margin: 2px 0;">{{ $invoice->notes }}</p>
                </td>
                <!-- Terms & Conditions -->
                <td class="info-box" style="width: 50%; vertical-align: top;">
                    <div class="box-title">Terms & Conditions</div>
                    <p class="party-desc" style="line-height: 1.35; margin: 2px 0;">
                        {{ !empty($invoice->terms_and_conditions) ? $invoice->terms_and_conditions : 'Standard commercial terms and conditions apply.' }}
                    </p>
                </td>
            @else
                <!-- Terms & Conditions full width -->
                <td class="info-box" style="width: 100%; vertical-align: top;">
                    <div class="box-title">Terms & Conditions</div>
                    <p class="party-desc" style="line-height: 1.35; margin: 2px 0;">
                        {{ !empty($invoice->terms_and_conditions) ? $invoice->terms_and_conditions : 'Standard commercial terms and conditions apply.' }}
                    </p>
                </td>
            @endif
        </tr>
    </table>
</body>
</html>
