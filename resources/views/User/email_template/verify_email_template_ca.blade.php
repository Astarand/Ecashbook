<!DOCTYPE html>
<html>
<head>
    <title>Welcome to e-Cashbook</title>
    <meta charset="UTF-8">
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: 'Segoe UI', Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; min-height:100vh;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#fff; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.07); margin:40px 0; padding:0 0 24px 0;">
                    <!-- Header -->
                    <tr>
                        <td style="background:#2d3e50; border-radius:10px 10px 0 0; padding:32px 0; text-align:center;">
                            <span style="color:#fff; font-size:28px; font-weight:bold; letter-spacing:1px;">e-Cashbook</span>
                        </td>
                    </tr>
                    <!-- Body -->
                    <tr>
                        <td style="padding:32px 40px 0 40px; color:#222;">
                            <h2 style="margin-top:0; font-size:24px; color:#28a745;">Dear {{ $ca_name }},</h2>
                            <p style="font-size:16px; line-height:1.6;">
                                We are pleased to inform you that you have been referred to join the <strong>e-Cashbook Accounting Platform</strong> by <strong>{{ $companyProfile->comp_name ?? 'Referred Company/Person' }}</strong> as their designated Chartered Accountant/Accounting Partner.
                            </p>
                            <p style="font-size:16px; color:#28a745; font-weight:bold;">Welcome to e-Cashbook!</p>
                            <p style="font-size:16px; line-height:1.6;">e-Cashbook is a digital aggregator platform that seamlessly connects companies and CAs, simplifying financial collaboration, GST filings, ledger management, payroll, compliance, and real-time accounting.</p>

                            <div style="background:#f8f9fa; border-radius:6px; padding:16px 24px; margin:24px 0;">
                                <p style="margin:0 0 8px 0; font-weight:bold;">Your Login Credentials</p>
                                <ul style="padding-left:20px; margin:0; font-size:15px;">
                                    <li><strong>User ID:</strong> {{ $ca_email }}</li>
                                    <li><strong>Password:</strong> {{ $plainPassword }}</li>
                                </ul>
                                <p style="margin-top:8px; font-size:14px; color:#555;">(You may change your password after your first login.)</p>
                            </div>

                            <p style="font-size:16px; font-weight:bold; margin-bottom:8px;">Please verify your account to activate access:</p>
                            <p style="text-align:center;">
                                <a href="{{ $verifyUrl }}"
                                    style="display:inline-block; background-color:#28a745; color:#fff; padding:14px 36px; font-size:18px; text-decoration:none; border-radius:6px; font-weight:bold; box-shadow:0 2px 4px rgba(40,167,69,0.08);">
                                    Click Here to Verify Your Account
                                </a>
                            </p>
                            <p style="font-size:14px; color:#888; text-align:center; margin:16px 0 0 0;">Or copy and paste this link in your browser:</p>
                            <p style="word-break:break-all; text-align:center; margin:0 0 24px 0;"><a href="{{ $verifyUrl }}" style="color:#28a745;">{{ $verifyUrl }}</a></p>

                            <hr style="border:none; border-top:1px solid #eee; margin:32px 0;">
                            <h3 style="font-size:20px; color:#2d3e50; margin-bottom:12px;">🚀 Our Innovative Solution – The First Accounting Aggregator Model</h3>
                            <ul style="font-size:15px; line-height:1.7; color:#444; padding-left:22px;">
                                <li>✅ First-of-its-kind Aggregator in Accounting Software</li>
                                <li>✅ Bridging the Gap Between Businesses & Finance Experts</li>
                                <li>✅ One-Stop Platform for Financial & Compliance Needs</li>
                                <li>✅ Simplifying & Automating Business Financial Management</li>
                                <li>✅ Empowering CAs & Accountants with New Growth Opportunities</li>
                            </ul>
                            <p style="font-size:15px; color:#555;">This next-generation aggregator model is poised to disrupt the traditional accounting software industry by bringing technology, professionals, and businesses together under one powerful platform.</p>

                            <hr style="border:none; border-top:1px solid #eee; margin:32px 0;">
                            <p style="font-size:15px; color:#888;">Notification:</p>
                            <p style="background:#f8f9fa; border-radius:6px; padding:12px 20px; color:#222; font-size:15px;">
                                A confirmation has also been sent to <strong>{{ $companyProfile->comp_name ?? 'Client/Company Name' }}</strong> regarding your successful referral and account creation.
                            </p>

                            <p style="font-size:16px; color:#222; margin-bottom:0;">If you have any queries or need onboarding support, feel free to contact our support team.</p>
                            <p style="margin-top:16px; font-size:16px;"><strong>Warm Regards,</strong><br>
                            Team e-Cashbook<br>
                            <strong>ClicknGo Tech Service Pvt. Ltd.</strong></p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background:#f4f6f8; border-radius:0 0 10px 10px; padding:24px 40px 0 40px; text-align:center; color:#888; font-size:13px;">
                            <p style="margin:0 0 8px 0;">Contact us: <a href="mailto:contact@ecashbook.in" style="color:#28a745; text-decoration:none;">contact@ecashbook.in</a></p>
                            <p style="margin:0 0 8px 0;">🌐 <a href="https://www.ecashbook.in" style="color:#28a745; text-decoration:none;">www.ecashbook.in</a></p>
                            <p style="margin:0;">&copy; {{ date('Y') }} e-Cashbook. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
