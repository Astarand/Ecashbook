<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $letter->subject ?? 'HR Letter' }}</title>
    <style>
        @page {
            size: A4;
            margin: 12mm;
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

        .container {
            max-width: 100%;
        }

        .header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .logo-box {
            display: table-cell;
            width: 25%;
            vertical-align: middle;
        }

        .logo-box img {
            max-width: 120px;
            max-height: 80px;
        }

        .company-box {
            display: table-cell;
            width: 75%;
            text-align: right;
            vertical-align: middle;
        }

        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 4px;
        }

        .company-meta {
            font-size: 11px;
            color: #374151;
            line-height: 1.5;
        }

        .letter-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0 14px;
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
    <div class="container">
        <div class="header">
            <div class="logo-box">
                @if(!empty($companyLogo))
                    <img src="{{ $companyLogo }}" alt="{{ $companyData->comp_name ?? 'Company Logo' }}">
                @endif
            </div>
            <div class="company-box">
                <div class="company-name">{{ $companyData->comp_name ?? 'Company Name' }}</div>
                <div class="company-meta">
                    @if(!empty($companyData->comp_email))
                        <div>Email: {{ $companyData->comp_email }}</div>
                    @endif
                    @if(!empty($companyData->comp_phone))
                        <div>Phone: {{ $companyData->comp_phone }}</div>
                    @endif
                    @if($showGst && !empty($companyData->gst_no))
                        <div>GST: {{ $companyData->gst_no }}</div>
                    @endif
                    @if(!empty($companyData->comp_pan_no))
                        <div>PAN: {{ $companyData->comp_pan_no }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="letter-title">{{ $letter->subject ?? 'HR Letter' }}</div>
        <div class="letter-content">
            {!! $letter->content ?? '' !!}
        </div>
    </div>
</body>
</html>
