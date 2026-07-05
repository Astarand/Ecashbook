<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance Report - {{ $employee->name ?? '' }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
            color: #333;
            font-size: 10px;
            line-height: 1.3;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 0;
        }

        .company-header {
            width: 100%;
            display: table;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .company-logo {
            display: table-cell;
            width: 30%;
            vertical-align: top;
        }

        .company-address {
            display: table-cell;
            width: 40%;
            text-align: center;
            vertical-align: top;
            font-size: 11px;
            line-height: 1.4;
        }

        .report-title {
            display: table-cell;
            width: 30%;
            text-align: right;
            vertical-align: top;
            font-size: 14px;
            font-weight: bold;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .report-header {
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
        }

        .employee-info {
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-collapse: collapse;
        }

        .employee-info td {
            padding: 8px;
            border: 1px solid #dee2e6;
        }

        .info-label {
            font-weight: bold;
            width: 150px;
            background-color: #f8f9fa;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 6px;
            border: 1px solid #dee2e6;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #e9ecef;
            font-weight: bold;
            font-size: 9px;
        }

        td {
            font-size: 8px;
        }

        .status-present {
            background-color: #d4edda;
            color: #155724;
        }

        .status-absent {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-late {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-leave {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .status-holiday {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-office-off {
            background-color: #f8f9fa;
            color: #6c757d;
        }

        .status-scheduled {
            background-color: #e9ecef;
            color: #495057;
        }

        .summary-section {
            margin-top: 20px;
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }

        .summary-title {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            padding: 10px;
            background-color: #e9ecef;
            border-bottom: 1px solid #dee2e6;
        }

        .summary-grid {
            display: table;
            width: 100%;
        }

        .summary-item {
            display: table-cell;
            width: 14.28%;
            text-align: center;
            padding: 10px;
            border-right: 1px solid #dee2e6;
        }

        .summary-item:last-child {
            border-right: none;
        }

        .summary-label {
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #495057;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }

        .page-break {
            page-break-before: always;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Company Header -->
        <div class="company-header">
            <div class="company-logo">
                @php
                $defaultLogo = 'storage/profile/e-cashbook.png';
                $logoPath = 'storage/profile/e-cashbook.png'; // You may need to adjust this based on your company data

                $absolutePath = public_path($logoPath);
                if (file_exists($absolutePath)) {
                    $imageData = base64_encode(file_get_contents($absolutePath));
                    $src = 'data:image/' . pathinfo($absolutePath, PATHINFO_EXTENSION) . ';base64,' . $imageData;
                } else {
                    $src = '';
                }
                @endphp

                @if($src)
                <img src="{{ $src }}" alt="Company Logo" height="60">
                @else
                <div style="height: 40px; display: flex; align-items: center;">
                    <strong>E-Cashbook</strong>
                </div>
                @endif
            </div>
            <div class="company-address">
                <div class="company-name">E-Cashbook</div>
                <div>Employee Attendance Report</div>
                <div>Period: {{ $period }}</div>
            </div>
            <div class="report-title">
                ATTENDANCE REPORT
            </div>
        </div>

        <!-- Employee Information -->
        <table class="employee-info">
            <tr>
                <td class="info-label">Employee Name:</td>
                <td>{{ $employee->name ?? 'N/A' }}</td>
                <td class="info-label">Department:</td>
                <td>{{ $employee->dept_name ?? 'N/A' }}</td>
                <td class="info-label">Designation:</td>
                <td>{{ $employee->designation_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="info-label">Employee ID:</td>
                <td>{{ $employee->empId ?? 'N/A' }}</td>
                <td class="info-label">Period:</td>
                <td colspan="3">{{ $period }}</td>
            </tr>
        </table>

        <!-- Attendance Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">Date</th>
                    <th style="width: 6%;">Day</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 10%;">Check In</th>
                    <th style="width: 10%;">Check Out</th>
                    <th style="width: 12%;">Working Hours</th>
                    <th style="width: 44%;">Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendanceData as $record)
                <tr>
                    <td>{{ $record['date'] }}</td>
                    <td>{{ $record['day'] }}</td>
                    <td class="status-{{ strtolower(str_replace(' ', '-', $record['status'])) }}">
                        {{ $record['status'] }}
                    </td>
                    <td>{{ $record['check_in'] }}</td>
                    <td>{{ $record['check_out'] }}</td>
                    <td>{{ $record['working_hours'] }}</td>
                    <td style="text-align: left;">{{ $record['notes'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="summary-title">Monthly Attendance Summary</div>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Present Days</div>
                    <div class="summary-value">{{ $summary['present'] }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Late Days</div>
                    <div class="summary-value">{{ $summary['late'] }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Absent Days</div>
                    <div class="summary-value">{{ $summary['absent'] }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Leave Days</div>
                    <div class="summary-value">{{ $summary['leave'] }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Holidays</div>
                    <div class="summary-value">{{ $summary['holiday'] }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Office Off</div>
                    <div class="summary-value">{{ $summary['office_off'] }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Scheduled</div>
                    <div class="summary-value">{{ $summary['scheduled'] }}</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is a computer-generated report. Generated on {{ date('d-m-Y H:i:s') }}</p>
        </div>
    </div>
</body>

</html>