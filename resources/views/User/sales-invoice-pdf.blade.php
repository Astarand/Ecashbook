<!doctype html>
<html>

<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.5;
            background-color: #fff;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 1140px;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        table th {
            background-color: #f9f9f9;
            font-weight: 600;
            color: #333;
            font-size: 13px;
            text-transform: uppercase;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 30px;
        }

        .company-logo {
            margin-bottom: 10px;
        }

        .company-logo img {
            max-height: 50px;
        }

        .invoice-details {
            text-align: right;
        }

        .invoice-details h2 {
            margin: 0 0 10px;
            color: #333;
        }

        .invoice-details p {
            margin: 0 0 5px;
            color: #666;
        }

        /* Party details container - vertical layout */
        .party-section {
            margin-bottom: 30px;
        }

        /* Individual party block - full width */
        .party-block {
            width: 100%;
            background-color: #fff;
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Party block title */
        .party-block h4 {
            margin: 0 0 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
            color: #333;
            font-size: 15px;
            font-weight: bold;
        }

        /* Party block content */
        .party-block p {
            margin: 0 0 5px;
            font-size: 14px;
            color: #555;
        }

        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .totals-table {
            width: 350px;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 8px 15px;
            border: none;
        }

        .totals-table .total-row td {
            border-top: 1px solid #ddd;
            font-weight: bold;
            font-size: 16px;
            padding-top: 12px;
        }

        .notes-section {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .notes-section h4 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #333;
        }

        /* Price text styling */
        .price-text {
            text-align: right;
        }
    </style>
    <meta charset="utf-8">
    <title>Invoice {{ $inv_num }}</title>
</head>

<body>
    <div class="container">
        <div class="header-section">
            <div class="company-info">
                <div class="company-logo">
                    <img src="{{ asset('public/assets/img/logo2.png') }}" alt="e-Cashbook">
                </div>
            </div>
            <div class="invoice-details">
                <h2>Invoice No: {{ $inv_num }}</h2>
                <p>Date: {{ date('Y-m-d', strtotime($sales->inv_date)) }}</p>
            </div>
        </div>

        <div class="party-section">
            <div class="party-block">
                <h4>Issued By:</h4>
                <p><strong>{{ $compDetails->comp_name }}</strong></p>
                <p>{{ $compDetails->comp_bill_addone }}</p>
                <p>{{ $compDetails->comp_bill_addtwo ?? '' }}</p>
                <p>Kolkata-700004, Kolkata, West Bengal, 700004</p>
                <p>{{ $sales->seller_contact ?? '9477040800' }}</p>
                <p>{{ $sales->seller_email ?? 't&ampt@gmail.com' }}</p>
                <p>{{ $sales->seller_pan ?? '19BHRCS4542Ijgtetx' }}</p>
            </div>

            <div class="party-block">
                <h4>Issued To:</h4>
                <p><strong>{{ $custDetails->cust_name ?? 'ATTENTIX CONSULTING PRIVATE LIMITED' }}</strong></p>
                <p>{{ $custDetails->cust_bill_addone ?? 'undefined' }}, {{ $custDetails->cust_bill_addtwo ?? 'undefined' }}</p>
                <p>{{ $cityBill->first()->name ?? '5583, null 743503' }}</p>
                <p>{{ $custDetails->cust_phone ?? '8017130586' }}</p>
                <p>{{ $custDetails->cust_email ?? 'attcpvt@gmail.com' }}</p>
                <p>{{ $custDetails->cust_gst_no ?? '19AAZCA7098N1ZB' }}</p>
            </div>

            <div class="party-block">
                <h4>Bank Details:</h4>
                <p><strong>{{ $compDetails->bank_name ?? 'State Bank of India' }}</strong></p>
                <p>{{ $compDetails->account_no ?? '13478925467@' }}</p>
                <p>{{ $compDetails->branch_name ?? 'R. kundu' }}</p>
                <p>{{ $compDetails->branch_address ?? 'Shyambazar' }}</p>
                <p>{{ $compDetails->ifsc_code ?? 'SBIN0004673' }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>PRODUCT / SERVICE</th>
                    <th>HSN/SAC CODE</th>
                    <th>QTY</th>
                    <th>GST MODE</th>
                    <th>CGST</th>
                    <th>SGST</th>
                    <th>IGST/UGST</th>
                    <th>TOTAL AMOUNT</th>
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
                            <td>{{ ($value->tax_type=="intrastate")?"Intra State":(($value->tax_type=="interstate")?"Inter State":"Union Territory") }}</td>
                            <td class="price-text">{{ ($value->tax_type=="intrastate")?'₹'.$value->tax_amt/2:'₹0.00' }}</td>
                            <td class="price-text">{{ ($value->tax_type=="intrastate")?'₹'.$value->tax_amt/2:'₹0.00' }}</td>
                            <td class="price-text">{{ ($value->tax_type!="intrastate")?'₹'.$value->tax_amt:'₹0.00' }}</td>
                            <td class="price-text">₹{{ $value->amount }}</td>
                        </tr>
                <?php
                        $taxableAmt += ($value->rate * $value->quantity);
                        $totalDisc += $value->disc_amt;
                        $totalTax += $value->tax_amt;
                        $totalAmount += $value->amount;
                    }
                } ?>
            </tbody>
        </table>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Sub Total:</td>
                    <td class="price-text">₹ {{ number_format($taxableAmt, 2) }}</td>
                </tr>
                <tr>
                    <td>Discount:</td>
                    <td class="price-text">₹ {{ number_format($totalDisc, 2) }}</td>
                </tr>
                <tr>
                    <td>Taxes:</td>
                    <td class="price-text">₹{{ number_format($totalTax, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Grand Total:</td>
                    <td class="price-text">₹ {{ number_format($totalAmount + $totalTax, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="notes-section">
            <h4>Note</h4>
            <p>It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance projects. Thank You!</p>
        </div>
    </div>
</body>

</html>