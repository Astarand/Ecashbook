<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $salaryData['payslipNo']['payslip_no'] ?? '' }}</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
            color: #333;
            font-size: 12px;
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
        }

        .company-logo {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }

        .company-address {
            display: table-cell;
            width: 60%;
            text-align: right;
            vertical-align: top;
            font-size: 11px;
            line-height: 1.4;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            margin-bottom: 10px;
            border: 1px solid #dee2e6;
        }

        .header-table td {
            padding: 8px;
            vertical-align: middle;
            border: 1px solid #dee2e6;
        }

        .header-label {
            font-weight: bold;
            width: 150px;
        }

        .employee-info-table {
            margin-bottom: 10px;
            border: 1px solid #dee2e6;
        }

        .employee-info-table td {
            padding: 8px;
            vertical-align: middle;
            border: 1px solid #dee2e6;
        }

        .employee-label {
            width: 120px;
            font-weight: normal;
        }

        .employee-value {
            text-align: right;
        }

        .details-heading {
            font-weight: bold;
            margin-top: 35px;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .earnings-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .earnings-table th,
        .earnings-table td {
            padding: 8px;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        .earnings-table th {
            background-color: #e2e3e5;
            font-weight: bold;
        }

        .earnings-table .description-header {
            width: 70%;
        }

        .earnings-table .amount-header {
            width: 30%;
            text-align: left;
        }

        .earnings-table .amount-cell {
            text-align: left;
        }

        .totals-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .net-pay-row {
            background-color: #d1e7dd;
            font-weight: bold;
        }

        .signature-section {
            margin-top: 30px;
            text-align: right;
            padding-right: 50px;
        }

        .signature-line {
            display: inline-block;
            border-top: 1px solid #000;
            width: 200px;
            margin-bottom: 5px;
        }

        .signature-name {
            font-weight: bold;
        }

        .signature-title {
            font-style: italic;
            font-size: 11px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Company Logo and Address Header -->
        <div class="company-header">
            <div class="company-logo">
                @php
                $defaultLogo = 'storage/profile/e-cashbook.png';
                $logoPath = optional($company_data)->comp_logo ? 'storage/profile/' . $company_data->comp_logo : $defaultLogo;

                // Convert to absolute path
                $absolutePath = public_path($logoPath);

                // Check if file exists
                if (file_exists($absolutePath)) {
                // Convert to data URI for better PDF compatibility
                $imageData = base64_encode(file_get_contents($absolutePath));
                $src = 'data:image/' . pathinfo($absolutePath, PATHINFO_EXTENSION) . ';base64,' . $imageData;
                } else {
                // Fallback to a text placeholder if image is missing
                $src = '';
                }
                @endphp

                @if($src)
                <img src="{{ $src }}" alt="{{ $company_data->comp_name ?? 'Company' }} Logo" height="100">
                @else
                <div style="height: 60px; display: flex; align-items: center;">
                    <strong>{{ $company_data->comp_name ?? 'Company' }}</strong>
                </div>
                @endif
            </div>
            <div class="company-address">
                <div class="company-name">{{ !empty($company_data->comp_name) ? $company_data->comp_name : ' ' }}</div>
                <div>{{ !empty($company_data->comp_bill_addone) ? $company_data->comp_bill_addone : ' ' }}</div>
                <div>{{ !empty($company_data->comp_bill_addtwo) ? $company_data->comp_bill_addtwo : ' ' }}</div>
                <div>Phone: {{ !empty($company_data->comp_phone) ? $company_data->comp_phone : ' ' }} | Email: {{ !empty($company_data->comp_email) ? $company_data->comp_email : ' ' }}</div>
                <div>GST: {{ !empty($company_data->gst_no) ? $company_data->gst_no : ( !empty($company_data->comp_gst_no) ? $company_data->comp_gst_no : ' ') }} | PAN: {{ !empty($company_data->comp_pan_no) ? $company_data->comp_pan_no : ' ' }}</div>
            </div>
        </div>

        <?php
            // echo '<pre>';
            // print_r($salaryData);

        ?>

        @php
            // Shortcut variables
            $visible = $salaryData['visible_data'] ?? [];
            $employee = $visible['employee_details'] ?? [];
            $bank = $visible['bank_details'] ?? [];
            $salary = $visible['salary_details'] ?? [];
            $final = $visible['final_salary_calculation'] ?? [];

            // Attendance
            $attendance = $visible['attendance_details'] ?? [];

            // Month / payslip info
            $payslip_no = $visible['payslip_no'] ?? $salaryData['payslip_no'] ?? '';
            $monthNum = $visible['month'] ?? $salaryData['month'] ?? '';
            $financialYear = $visible['financial_year'] ?? $salaryData['financial_year'] ?? '';
            $monthName = is_numeric($monthNum)
                ? ['January','February','March','April','May','June','July','August','September','October','November','December'][$monthNum - 1]
                : $monthNum;

            // Calculations
            $perDaySalary = $salary['per_day_salary'] ?? 0;
            $lateDays = $attendance['total_present_late'] ?? 0;
            $absentDays = $attendance['total_absent'] ?? 0;
            $lateDeduction = $lateDays * $perDaySalary;
            $absentDeduction = $absentDays * $perDaySalary;

            $net_salary = $final['net_salary'] ?? 0;
            $total_deduction = $final['total_deductions'] ?? 0;
            $monthly_salary = $final['total_earnings'] ?? 0;
            $loan = $final['loan'] ?? 0;
            $overtime_payment = $final['overtime_payment'] ?? 0;
        @endphp



        <!-- Header Section with Payslip Number and Month/Year -->
        <table class="header-table">
            <tr>
                <td class="header-label">Payslip Number</td>
                <td>{{ $payslip_no }}</td>
                <td class="header-label" style="text-align: right;">Payslip Month & Year</td>
                <td style="text-align: right;">{{ $monthName }} {{ explode('-', $financialYear)[0] ?? '' }}</td>
            </tr>
        </table>

        <!-- Employee and Bank Information -->
        <table class="employee-info-table">
            <tr>
                <td>
                    <table style="width: 100%; border: none;">
                        <tr>
                            <td class="employee-label">Employee Name:</td>
                            <td class="employee-value">{{ $employee['name'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="employee-label">Employee ID:</td>
                            <td class="employee-value">{{ $employee['employee_id'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="employee-label">Department:</td>
                            <td class="employee-value">{{ $employee['dept_name'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="employee-label">Designation:</td>
                            <td class="employee-value">{{ $employee['designation_name'] ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table style="width: 100%; border: none;">
                        <tr>
                            <td class="employee-label">Bank Name:</td>
                            <td class="employee-value">{{ $bank['bank_name'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="employee-label">Account No.:</td>
                            <td class="employee-value">{{ $bank['account_number'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="employee-label">IFSC Code:</td>
                            <td class="employee-value">{{ $bank['ifsc'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="employee-label">PAN No: </td>
                            <td class="employee-value">{{ $employee['pan_number'] ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Details Section -->
        <div class="details-heading">Details</div>

        <!-- Earnings and Deductions -->
        <table class="earnings-table">
            <tr>
                <th colspan="2" style="text-align: left; background-color: #f0f0f0;">EARNINGS</th>
                <th colspan="2" style="text-align: left; background-color: #f0f0f0;">DEDUCTIONS</th>
            </tr>
            <tr>
                <th style="text-align: left; background-color: #f8f9fa;">DESCRIPTION</th>
                <th style="text-align: left; background-color: #f8f9fa;">AMOUNT</th>
                <th style="text-align: left; background-color: #f8f9fa;">DESCRIPTION</th>
                <th style="text-align: left; background-color: #f8f9fa;">AMOUNT</th>
            </tr>

            <tr>
                <td>Basic Salary</td>
                <td>Rs. {{ number_format($final['basic_salary'] ?? 0, 2) }}</td>
                <td>Employee Provident Fund (EPF)</td>
                <td>Rs. {{ number_format($final['provident_fund'] ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>House Rent Allowance (HRA)</td>
                <td>Rs. {{ number_format($final['hra'] ?? 0, 2) }}</td>
                <td>Employee State Insurance (ESI)</td>
                <td>Rs. {{ number_format($final['esi'] ?? 0, 2) }}</td>


            </tr>
            <tr>
                <td>Conveyance Allowance</td>
                <td>Rs. {{ number_format($final['conveyance'] ?? 0, 2) }}</td>
                <td>Professional Tax (PT)</td>
                <td>Rs. {{ number_format($final['ptax'] ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>Medical Allowance</td>
                <td>Rs. {{ number_format($final['medical_allowance'] ?? 0, 2) }}</td>
                <td>TDS - Tax Deducted at Source</td>
                <td>Rs. {{ number_format($final['tds'] ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>Special Allowance</td>
                <td>Rs. {{ number_format($final['special_allowance'] ?? 0, 2) }}</td>
                <td>Loan Deduction</td>
                <td>Rs. {{ number_format($final['loan'] ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>Performance Bonus</td>
                <td>Rs. {{ number_format($final['performance_bonus'] ?? 0, 2) }}</td>
                <td>Loss of Pay (LOP)</td>
                <td>Rs. {{ number_format($final['lop'] ?? 0, 2) }}</td>
            </tr>
            
            <tr>
                <td>Overtime Payment</td>
                <td>Rs. {{ number_format($final['overtime_payment'] ?? 0, 2) }}</td>
                <td>Advance</td>
                <td>Rs. {{ number_format($salary['advance_amount'] ?? 0, 2) }}</td>
            </tr>
            @if(!empty($final['lwf_applicable']) && $final['lwf_applicable'] == 1)
            <tr>
                <td></td>
                <td></td>
                <td>Labour Welfare Fund (LWF)</td>
                <td>Rs. {{ number_format($final['lwf_deduct'] ?? 0, 2) }}</td>
            </tr>
            @endif

            <tr class="net-pay-row">
                <td><strong>Total Earnings</strong></td>
                <td><strong>Rs. {{ number_format($final['total_earnings'] ?? 0, 2) }}</strong></td>
                <td>Total Deductions</td>
                <td><strong>Rs. {{ number_format($final['total_deductions'] ?? 0, 2) }}</strong></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Net Salary: Rs. {{ number_format($final['net_salary'] ?? 0, 2) }}</strong></td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top: 10px;">
                    <strong>Net Salary (In Words):</strong> {{ $final['in_words'] ?? '' }}
                </td>
            </tr>
        </table>

        @if(!empty($salaryData['notes']))
            <div style="margin-top: 10px; border: 1px solid #dee2e6; padding: 10px;">
                <strong>Notes:</strong> {{ $salaryData['notes'] }}
            </div>
        @endif

        <div class="footer">
            <p>Generated on {{ date('d M Y') }}</p>
            <p>This is a computer-generated document. No signature is required.</p>
        </div>
    </div>
</body>

</html>
