<div>
    <p>Dear {{ $customerName }},</p>

    <p>
        Please find attached your invoice
        <strong>{{ $invoiceNumber }}</strong> in PDF format.
    </p>

    <p>
        Thank you for your business.
    </p>

    <p>
        Regards,<br>
        {{ $fromName }}
    </p>
</div>
