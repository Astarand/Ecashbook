@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/sales-invoice') }}">Sales</a></li>
                        <li class="breadcrumb-item" aria-current="page">Payment Received</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Payment Received</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="text-center mb-4">Payment Received</h1>

                    <!-- Invoice and Payment Info -->
                    <div class="details-section mb-4">
                        <h4 class="text-primary">Invoice Information</h4>
                        <p><strong>Invoice Number:</strong> {{ $payment->invoice_num }}</p>
                        <p><strong>Received Date:</strong> {{ $payment->payment_date }}</p>
                        <p><strong>Payment Amount:</strong> ₹{{ $payment->pay_amount }}</p>
                        <p><strong>Payment Mode:</strong> {{ $payment->payment_mode }}</p>
                    </div>

                    <!-- Company Details -->
                    <div class="details-section mb-4">
                        <h4 class="text-primary">Company Details</h4>
                        <p><strong>Company Name:</strong> {{ $payment->comp_name }}</p>
                        <p><strong>GST Number:</strong> {{ $payment->gst_no }}</p>
                        <p><strong>GST Registration Status:</strong> {{ $payment->gst_reg }}</p>
                        <p><strong>Company Type:</strong> {{ $payment->comp_type }}</p>
                        <p><strong>CIN:</strong> {{ $payment->cin }}</p>
                        <p><strong>Udyam Registration Number:</strong> {{ $payment->udyam_reg_no }}</p>
                        <p><strong>Company Email:</strong> {{ $payment->comp_email }}</p>
                        <p><strong>Company Phone:</strong> {{ $payment->comp_phone }}</p>
                    </div>

                    <!-- Sales Details -->
                    <div class="details-section mb-4">
                        <h4 class="text-primary">Sales Information</h4>
                        <p><strong>Seller Name:</strong> {{ $salesDetails->seller_name }}</p>
                        <p><strong>Billing Address:</strong> {{ $salesDetails->seller_addone }}, {{ $salesDetails->seller_city }}, {{ $salesDetails->seller_state }}</p>
                        <p><strong>Shipping Address:</strong> {{ $salesDetails->seller_addtwo }}, {{ $salesDetails->seller_city }}, {{ $salesDetails->seller_state }}</p>
                    </div>

                    <!-- Print Button -->
                    <div class="d-flex justify-content-center">
                        <button id="printBtn" class="btn btn-success">Print Payment Received</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

@endsection

@section('scripts')
<script>
    document.getElementById('printBtn').addEventListener('click', function() {
        // Get the content from the card-body
        const content = document.querySelector('.card-body').innerHTML;

        // Create an iframe element to hold the content for printing
        const iframe = document.createElement('iframe');
        iframe.style.position = 'absolute';
        iframe.style.width = '0px';
        iframe.style.height = '0px';
        iframe.style.border = 'none';
        document.body.appendChild(iframe);

        // Write the content into the iframe's document
        const doc = iframe.contentWindow.document;
        doc.open();
        doc.write('<html><head><title>Payment Received</title><style>');
        doc.write('body { font-family: Arial, sans-serif; margin: 20px; }');
        doc.write('h1 { text-align: center; font-size: 2em; }');
        doc.write('p { font-size: 1.2em; margin: 10px 0; }');
        doc.write('.details-section { margin-bottom: 20px; }');
        doc.write('.text-primary { color: #007bff; font-size: 1.5em; margin-bottom: 10px; }');
        doc.write('</style></head><body>');
        doc.write(content);  // Write the content to the iframe
        doc.write('</body></html>');
        doc.close();

        // Print the content
        iframe.contentWindow.focus();
        iframe.contentWindow.print();

        // Remove the iframe after printing
        document.body.removeChild(iframe);
    });
</script>
@endsection
