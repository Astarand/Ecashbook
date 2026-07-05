<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">

    <title>
        Bank Reconciliation Report
    </title>

    <style>

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:10px;
        }

        table, th, td{
            border:1px solid #000;
        }

        th, td{
            padding:6px;
            text-align:left;
        }

        .text-center{
            text-align:center;
        }

        .summary-table td{
            font-weight:bold;
        }

        .matched{
            background:#d4edda;
        }

        .unmatched{
            background:#f8d7da;
        }

        .heading{
            margin-bottom:10px;
        }

    </style>

</head>

<body>

    <div class="heading">

        <h2 class="text-center">
            Bank Reconciliation Report
        </h2>

        <table>

            <tr>
                <td>
                    <strong>Financial Year :</strong>
                    {{ $financialYear }}
                </td>

                <td>
                    <strong>Report Type :</strong>
                    {{ $reportType }}
                </td>

                <td>
                    <strong>From :</strong>
                    {{ $from }}
                </td>

                <td>
                    <strong>To :</strong>
                    {{ $to }}
                </td>

            </tr>

        </table>

    </div>

    <!-- ===================================== -->
    <!-- SUMMARY -->
    <!-- ===================================== -->

    <table class="summary-table">

        <thead>

            <tr>

                <th>Particulars</th>

                <th>Amount</th>

                <th>Amount</th>

            </tr>

        </thead>

        <tbody>

            <tr>

                <td>
                    Opening Balance
                </td>

                <td>
                    {{ number_format($openingCash,2) }}
                </td>

                <td></td>

            </tr>

            <tr>

                <td>
                    Deposit In Transit
                </td>

                <td></td>

                <td>
                    {{ number_format($depositAmt,2) }}
                </td>

            </tr>

            <tr>

                <td>
                    Unrepresented Cheques
                </td>

                <td></td>

                <td>
                    {{ number_format($chequeAmt,2) }}
                </td>

            </tr>

            <tr>

                <td>
                    Bank Charges
                </td>

                <td></td>

                <td>
                    {{ number_format($charges,2) }}
                </td>

            </tr>

            <tr>

                <td>
                    Closing Balance
                </td>

                <td></td>

                <td>
                    {{ number_format($closingBalance,2) }}
                </td>

            </tr>

            <tr style="background:#ffe082">

                <td>
                    Reconciled Balance
                </td>

                <td>
                    {{ number_format($matchedBalance1,2) }}
                </td>

                <td>
                    {{ number_format($matchedBalance2,2) }}
                </td>

            </tr>

        </tbody>

    </table>

    <!-- ===================================== -->
    <!-- MATCHED RECORDS -->
    <!-- ===================================== -->

    <h3>
        Matched Records
    </h3>

    <table>

        <thead>

            <tr>

                <th>Bank Name</th>
                <th>Trans. Date</th>
                <th>Trans. Type</th>
                <th>Bank Amount</th>
                <th>Voucher No</th>
                <th>Voucher Date</th>
                <th>Voucher Amount</th>
                <th>Status</th>
                <th>Score</th>
            </tr>

        </thead>

        <tbody>

            @forelse($matchedRows as $row)

                <tr class="matched">

                    <td>
                        {{ $row['bank_name'] }}
                    </td>

                    <td>
                        {{ $row['bank_date'] }}
                    </td>
					
					<td>
                        {{ $row['tran_type'] }}
                    </td>

                    <td>
                        {{ number_format($row['bank_amount'],2) }}
                    </td>

                    <td>
                        {{ $row['voucher_no'] }}
                    </td>

                    <td>
                        {{ $row['voucher_date'] }}
                    </td>

                    <td>
                        {{ number_format($row['voucher_amount'],2) }}
                    </td>

                    <td>
                        {{ $row['status'] }}
                    </td>

                    <td>
                        {{ $row['score'] }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="8" class="text-center">
                        No matched records found
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

    <!-- ===================================== -->
    <!-- UNMATCHED RECORDS -->
    <!-- ===================================== -->

    <h3 style="margin-top:20px;">
        Unmatched Records
    </h3>

    <table>

        <thead>

            <tr>

                <th>Bank Name</th>

                <th>Trans. Date</th>
				
                <th>Trans. Type</th>

                <th>Bank Amount</th>

                <th>Voucher No</th>

                <th>Voucher Date</th>

                <th>Voucher Amount</th>

                <th>Status</th>

                <th>Score</th>

            </tr>

        </thead>

        <tbody>

            @forelse($unmatchedRows as $row)

                <tr class="unmatched">

                    <td>
                        {{ $row['bank_name'] }}
                    </td>

                    <td>
                        {{ $row['bank_date'] }}
                    </td>
					
					 <td>
                        {{ $row['tran_type'] }}
                    </td>

                    <td>
                        {{ number_format($row['bank_amount'],2) }}
                    </td>

                    <td>
                        {{ $row['voucher_no'] }}
                    </td>

                    <td>
                        {{ $row['voucher_date'] }}
                    </td>

                    <td>
                        {{ $row['voucher_amount'] }}
                    </td>

                    <td>
                        {{ $row['status'] }}
                    </td>

                    <td>
                        {{ $row['score'] }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="8" class="text-center">
                        No unmatched records found
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</body>
</html>