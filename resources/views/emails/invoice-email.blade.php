<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Invoice {{ $invoiceNumber }}</title>
    <style>
        /* Email client resets */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100% !important; height: 100% !important; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
        
        @media only screen and (max-width: 620px) {
            .email-container { width: 100% !important; padding: 10px !important; }
            .card-body { padding: 24px 20px !important; }
            .header-content { padding: 24px 20px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b;">
    
    <!-- Outer Wrapper -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f1f5f9; min-height: 100vh;">
        <tr>
            <td align="center" style="padding: 35px 15px 40px 15px;">
                
                <!-- Main Email Container -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="email-container" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08); border: 1px solid #e2e8f0;">
                    
                    <!-- Header Section -->
                    <tr>
                        <td class="header-content" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 32px 36px; text-align: left;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td>
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="background-color: rgba(255, 255, 255, 0.12); border-radius: 8px; padding: 8px 12px; display: inline-block;">
                                                    <span style="color: #38bdf8; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">TAX INVOICE</span>
                                                </td>
                                            </tr>
                                        </table>
                                        <h1 style="color: #ffffff; font-size: 24px; font-weight: 700; margin: 14px 0 4px 0; line-height: 1.3;">
                                            Invoice #{{ $invoiceNumber }}
                                        </h1>
                                        <p style="color: #94a3b8; font-size: 14px; margin: 0;">
                                            Generated and sent via MethotX Portal
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td class="card-body" style="padding: 36px 36px 30px 36px;">
                            
                            <!-- Greeting -->
                            <p style="font-size: 16px; font-weight: 600; color: #0f172a; margin: 0 0 16px 0;">
                                Dear {{ $customerName }},
                            </p>
                            
                            <p style="font-size: 14px; color: #475569; line-height: 1.65; margin: 0 0 24px 0;">
                                We appreciate your business. Please find attached the official copy of your tax invoice <strong>#{{ $invoiceNumber }}</strong> for your reference and records.
                            </p>

                            <!-- Invoice Summary Card -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 20px 24px;">
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                                                    <span style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Invoice Number</span><br>
                                                    <strong style="font-size: 16px; color: #0f172a;">#{{ $invoiceNumber }}</strong>
                                                </td>
                                                <td align="right" style="padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                                                    <span style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Attachment</span><br>
                                                    <span style="background-color: #e0f2fe; color: #0284c7; font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 6px; display: inline-block;">PDF Attached</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="padding-top: 12px;">
                                                    <p style="font-size: 13px; color: #64748b; margin: 0;">
                                                        📄 The detailed PDF invoice contains all line items, tax breakdown, and payment details.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Important Note Box -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 4px 8px 8px 4px; margin-bottom: 28px;">
                                <tr>
                                    <td style="padding: 14px 18px;">
                                        <p style="font-size: 13px; color: #1e40af; line-height: 1.5; margin: 0;">
                                            <strong>Notice:</strong> Please review the attached PDF document. If you have any questions or require any clarification regarding this invoice, feel free to reply directly to this email.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Sign Off -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-top: 1px solid #f1f5f9; padding-top: 20px;">
                                <tr>
                                    <td>
                                        <p style="font-size: 14px; color: #475569; margin: 0 0 4px 0;">
                                            Warm regards,
                                        </p>
                                        <p style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">
                                            {{ $fromName }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer Section -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 22px 36px; text-align: center;">
                            <p style="font-size: 12px; color: #94a3b8; margin: 0 0 6px 0; line-height: 1.5;">
                                This is an automated email sent from <strong>{{ $fromName }}</strong>.
                            </p>
                            <p style="font-size: 11px; color: #cbd5e1; margin: 0;">
                                © {{ date('Y') }} All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- End Email Container -->

            </td>
        </tr>
    </table>
</body>
</html>
