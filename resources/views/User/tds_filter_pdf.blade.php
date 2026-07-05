<!DOCTYPE html>
<html>
<head>
    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:9px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            table-layout:auto;
        }

        th,td{
            border:1px solid #000;
            padding:4px;
            word-wrap:break-word;
            overflow-wrap:break-word;
        }

        th{
            background:#cbcbcb;
            font-size:9px;
        }

        td{
            font-size:8px;
        }

        h3{
            text-align:center;
            margin-bottom:10px;
        }
    </style>
</head>
<body>

<h3>TDS Returns Report</h3>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Vendor/Employee ID</th>
            <th>Name</th>
            <th>Pan</th>
            <th>Section</th>
            <th>Nature Of Payment</th>
            <th>Gross Amount</th>
            <th>TDS Rate (%)</th>
            <th>TDS Deduction</th>
            <th>Challan No</th>
            <th>Payment Date</th>
            <th>Return Quarter</th>
            <th>Remarks</th>
        </tr>
    </thead>

    <tbody>
        
        @foreach($rows as $row)
        <tr>
            <td>{{ $row['#'] ?? '' }}</td>
            <td>{{ $row['Vendor/ Employee ID'] ?? 'N/A' }}</td>
            <td>{{ $row['Name'] ?? 'N/A' }}</td>
            <td>{{ $row['Pan'] ?? 'N/A' }}</td>
            <td>{{ $row['Section'] ?? 'N/A' }}</td>
            <td>{{ $row['Nature Of Payment'] ?? 'N/A' }}</td>
            <td>{{ $row['Gross Amount'] ?? 0 }}</td>
            <td>{{ $row['TDS Rate (%)'] ?? 0 }}</td>
            <td>{{ $row['TDS Deduction'] ?? 0 }}</td>
            <td>{{ $row['Challan No'] ?? 'N/A' }}</td>
            <td>{{ $row['Payment Date'] ?? 'N/A' }}</td>
            <td>{{ $row['Return Quarter'] ?? 'N/A' }}</td>
            <td>{{ $row['Remarks'] ?? 'Paid' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
