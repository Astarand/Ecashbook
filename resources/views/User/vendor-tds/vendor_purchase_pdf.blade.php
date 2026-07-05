<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vendor Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px 25px;
        }

        /* ================= HEADER ================= */
        .header {
            width: 100%;
            border-bottom: 2px solid #444;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .header-table {
            width: 100%;
        }

        .header-table td {
            vertical-align: top;
        }

        .logo img {
            height: 45px;
        }

        .report-title {
            text-align: right;
        }

        .report-title h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .report-title p {
            margin: 2px 0;
            font-size: 11px;
        }

        /* ================= INFO ================= */
        .info {
            margin: 15px 0 20px 0;
            font-size: 11px;
        }

        .info strong {
            font-weight: bold;
        }

        /* ================= TABLE ================= */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background-color: #f1f1f1;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            border: 1px solid #333;
            font-size: 11px;
        }

        table td {
            padding: 7px;
            border: 1px solid #333;
            font-size: 11px;
        }

        table tr:nth-child(even) td {
            background-color: #fafafa;
        }

        .text-right {
            text-align: right;
        }

		td.logo {
			vertical-align: middle;
		}

		img {
			image-rendering: auto;
		}
        
        .footer {
            margin-top: 25px;
            font-size: 10px;
            text-align: center;
            color: #666;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- HEADER -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo" style="width:120px; padding:5px;">
					<img src="file://{{ public_path('assets/images/logo-small.png') }}"
						 alt="E-cashbook"
						 style="width:110px; height:40px; object-fit:contain;">
				</td>

                <td class="report-title">
                    <h2>Vendor Report</h2>
                    <p>Period: {{ date('d-m-Y', strtotime($from)) }} to {{ date('d-m-Y', strtotime($to)) }}</p>
                    <p><strong>Buyer Turnover:</strong> &#8377;{{ number_format($buyer_turnover,2) }}</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th>Vendor Name</th>
                <th>PAN</th>
                <th class="text-right">Total Purchase (&#8377;)</th>
                <th class="text-right">TDS Rate (%)</th>
                <th class="text-right">TDS Amount (&#8377;)</th>
				<th>TDS Applicable</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $row['vendor_name'] }}</td>
                    <td>{{ $row['pan_no'] }}</td>
                    <td class="text-right">&#8377;{{ number_format($row['fy_purchase'],2) }}</td>
                    <td class="text-right">{{ $row['tds_rate'] }}%</td>
                    <td class="text-right">&#8377;{{ number_format($row['tds_amount'],2) }}</td>
					<td>
						<span style="
							display:inline-block;
							padding:4px 8px;
							font-size:8px;
							color:#fff;
							border-radius:4px;
							background-color: {{ strtoupper($row['tds_applicable']) === 'YES' ? '#dc3545' : '#6c757d' }};">
							{{ strtoupper($row['tds_applicable']) }}
						</span>
					</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        This is a system generated report. No signature required.
    </div>

</div>

</body>
</html>
