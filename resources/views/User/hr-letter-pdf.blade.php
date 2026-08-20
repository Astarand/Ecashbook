<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $letter->subject ?? 'HR Letter' }}</title>
    <style>
        @page {
            size: A4;
            margin: 30mm 12mm 22mm 12mm; /* top room for header, bottom room for footer */
        }

        body {
            font-family: Arial, sans-serif;
            color: #1f2937;
            background: #ffffff;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            font-size: 12px;
        }

        /* ============================================================
           REPEATING HEADER — fixed at the top of every page
           DomPDF renders position:fixed elements on every page.
        ============================================================ */
        #pdf-header {
            position: fixed;
            top: -28mm;           /* pull into the @page top margin */
            left: 0;
            right: 0;
            height: 26mm;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 4px;
        }

        #pdf-header .header-inner {
            display: table;
            width: 100%;
        }

        #pdf-header .logo-box {
            display: table-cell;
            width: 25%;
            vertical-align: middle;
        }

        #pdf-header .logo-box img {
            max-width: 120px;
            max-height: 60px;
        }

        #pdf-header .company-box {
            display: table-cell;
            width: 75%;
            text-align: right;
            vertical-align: middle;
        }

        #pdf-header .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 3px;
        }

        #pdf-header .company-meta {
            font-size: 10px;
            color: #374151;
            line-height: 1.5;
        }

        /* ============================================================
           REPEATING FOOTER — fixed at the bottom of every page
        ============================================================ */
        #pdf-footer {
            position: fixed;
            bottom: -20mm;        /* pull into the @page bottom margin */
            left: 0;
            right: 0;
            height: 18mm;
            border-top: 1px solid #e5e7eb;
            padding-top: 4px;
        }

        #pdf-footer .footer-inner {
            display: table;
            width: 100%;
        }

        #pdf-footer .footer-left {
            display: table-cell;
            font-size: 9px;
            color: #6b7280;
            font-style: italic;
            vertical-align: middle;
        }

        #pdf-footer .footer-right {
            display: table-cell;
            text-align: right;
            font-size: 9px;
            color: #6b7280;
            vertical-align: middle;
        }

        /* ============================================================
           Page body content
        ============================================================ */
        .container {
            max-width: 100%;
        }

        .letter-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0 14px;
            color: #111827;
        }

        .letter-content {
            font-size: 12px;
            color: #1f2937;
            white-space: normal;
        }

        .letter-content p {
            margin: 0 0 10px;
        }

        .letter-content ul,
        .letter-content ol {
            margin: 0 0 10px 18px;
            padding-left: 18px;
        }

        .letter-content strong,
        .letter-content b {
            font-weight: bold;
        }

        .letter-content table {
            border-collapse: collapse;
            width: 100%;
            margin: 8px 0 12px;
        }

        .letter-content td,
        .letter-content th {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
        }
    </style>
</head>
<body>

    {{-- ============================================================
         HEADER — DomPDF repeats position:fixed on every page
    ============================================================ --}}
    <div id="pdf-header">
        <div class="header-inner">
            <div class="logo-box">
                @if(!empty($companyLogo))
                    <img src="{{ $companyLogo }}" alt="{{ $companyData->comp_name ?? 'Company Logo' }}">
                @endif
            </div>
            <div class="company-box">
                <div class="company-name">{{ $companyData->comp_name ?? 'Company Name' }}</div>
                <div class="company-meta">
                    @if(!empty($companyData->comp_email))
                        <span>Email: {{ $companyData->comp_email }}</span>
                    @endif
                    @if(!empty($companyData->comp_phone))
                        &nbsp;&nbsp;|&nbsp;&nbsp;<span>Phone: {{ $companyData->comp_phone }}</span>
                    @endif
                    @if($showGst && !empty($companyData->gst_no))
                        &nbsp;&nbsp;|&nbsp;&nbsp;<span>GST: {{ $companyData->gst_no }}</span>
                    @endif
                    @if(!empty($companyData->comp_pan_no))
                        &nbsp;&nbsp;|&nbsp;&nbsp;<span>PAN: {{ $companyData->comp_pan_no }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         FOOTER — DomPDF repeats position:fixed on every page
    ============================================================ --}}
    <div id="pdf-footer">
        <div class="footer-inner">
            <div class="footer-left">
                System generated PDF — no signature required
            </div>
            <div class="footer-right">
                {{ $companyData->comp_name ?? '' }}
            </div>
        </div>
    </div>

    {{-- ============================================================
         BODY CONTENT
    ============================================================ --}}
    <div class="container">
        <div class="letter-title">{{ $letter->subject ?? 'HR Letter' }}</div>
        <div class="letter-content">
            {!! $letter->content ?? '' !!}
        </div>
    </div>

</body>
</html>
