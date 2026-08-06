<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome to MethotX - CA Partner Invitation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', Arial, sans-serif !important;
        }
        body {
            font-family: 'Poppins', Arial, sans-serif !important;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
            color: #334155;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f7fa;
            padding: 40px 0;
        }
        .main-card {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
        }
        .top-bar {
            height: 6px;
            background: linear-gradient(90deg, #0A86C6 0%, #00C2FF 100%);
        }
        .header {
            padding: 35px 30px 20px;
            text-align: center;
            background-color: #ffffff;
        }
        .logo {
            max-width: 190px;
            height: auto;
            display: inline-block;
        }
        .content {
            padding: 10px 35px 35px;
        }
        .main-heading {
            color: #0A86C6;
            font-size: 24px;
            font-weight: 600;
            margin: 15px 0 8px;
            line-height: 1.3;
            text-align: center;
        }
        .sub-heading {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
            margin: 0 0 25px;
            text-align: center;
        }
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #0A86C6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0 25px;
        }
        .info-card-title {
            color: #0A86C6;
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-row {
            font-size: 14px;
            color: #475569;
            margin: 8px 0;
            line-height: 1.5;
        }
        .info-row strong {
            color: #1e293b;
            display: inline-block;
            width: 120px;
        }
        .btn-container {
            margin: 30px 0 25px;
            text-align: center;
        }
        .verify-btn {
            display: inline-block;
            background-color: #0A86C6;
            color: #ffffff !important;
            text-decoration: none;
            text-align: center;
            padding: 14px 34px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(10, 134, 198, 0.35);
            transition: all 0.3s ease;
        }
        .verify-btn:hover {
            background-color: #086fa4;
            box-shadow: 0 6px 20px rgba(10, 134, 198, 0.45);
        }
        .feature-box {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .feature-title {
            font-size: 15px;
            font-weight: 600;
            color: #0A86C6;
            margin: 0 0 12px;
        }
        .feature-list {
            margin: 0;
            padding-left: 20px;
            font-size: 13px;
            color: #475569;
            line-height: 1.8;
        }
        .body-text {
            font-size: 13px;
            color: #64748b;
            line-height: 1.6;
            margin: 15px 0;
        }
        .social-section {
            padding: 20px 0 15px;
            text-align: center;
            border-top: 1px solid #f1f5f9;
            margin-top: 25px;
        }
        .social-title {
            font-size: 12px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }
        .social-table {
            margin: 0 auto;
            border-collapse: collapse;
        }
        .social-icon-btn {
            display: inline-block;
            width: 38px;
            height: 38px;
            line-height: 34px;
            background-color: #ffffff;
            border: 2px solid #0A86C6;
            border-radius: 50%;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            margin: 0 6px;
            transition: all 0.2s ease;
        }
        .social-icon-btn img {
            width: 18px;
            height: 18px;
            vertical-align: middle;
            margin-top: -2px;
            display: inline-block;
        }
        .social-icon-btn:hover {
            background-color: #0A86C6;
            border-color: #0A86C6;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 4px 0;
        }
        .footer a {
            color: #0A86C6;
            text-decoration: none;
            font-weight: 500;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main-card">
            <div class="top-bar"></div>
            
            <div class="header">
                <img src="{{ asset('assets/images/logo.png') }}" alt="MethotX Logo" class="logo">
            </div>

            <div class="content">
                <h1 class="main-heading">Welcome to MethotX Platform</h1>
                <p class="sub-heading">Dear {{ $ca_name }}, you have been referred to join MethotX by <strong>{{ $companyProfile->comp_name ?? 'Referred Company/Person' }}</strong> as their designated Chartered Accountant / Finance Partner.</p>

                <div class="info-card">
                    <div class="info-card-title">Your Login Credentials</div>
                    <p class="info-row"><strong>User ID / Email:</strong> <a href="mailto:{{ $ca_email }}" style="color: #0A86C6; text-decoration: none; font-weight: 500;">{{ $ca_email }}</a></p>
                    <p class="info-row"><strong>Password:</strong> <span style="font-family: monospace; background: #e2e8f0; padding: 2px 8px; border-radius: 4px; color: #0f172a; font-weight: 600;">{{ $plainPassword }}</span></p>
                    <p class="body-text" style="font-size: 12px; color: #64748b; margin-top: 8px;">(You may change your password after your first login.)</p>
                </div>

                <div class="btn-container">
                    <a href="{{ $verifyUrl }}" class="verify-btn">VERIFY & ACTIVATE ACCOUNT</a>
                </div>

                <div class="feature-box">
                    <div class="feature-title">🚀 First-of-its-kind Accounting Aggregator Platform</div>
                    <ul class="feature-list">
                        <li>✅ Direct Integration between Businesses & Finance Experts</li>
                        <li>✅ Real-time Financial Collaboration & GST Compliance</li>
                        <li>✅ Multi-client Ledger, Payroll & Tax Filing Management</li>
                        <li>✅ Streamlined Access & Growth Opportunities for CAs</li>
                    </ul>
                </div>

                <p class="body-text" style="font-size: 12px; color: #94a3b8; text-align: center;">
                    A confirmation email has also been dispatched to <strong>{{ $companyProfile->comp_name ?? 'Client/Company' }}</strong> regarding your referral setup.
                </p>

                <!-- Social Icons Section (Center Aligned) -->
                <div class="social-section">
                    <div class="social-title">Connect With Us</div>
                    <table class="social-table" align="center" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td align="center" valign="middle">
                                <a href="#" class="social-icon-btn" title="Facebook">
                                    <img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" alt="Facebook">
                                </a>
                                <a href="#" class="social-icon-btn" title="X (Twitter)">
                                    <img src="https://cdn-icons-png.flaticon.com/512/5968/5968958.png" alt="X">
                                </a>
                                <a href="#" class="social-icon-btn" title="LinkedIn">
                                    <img src="https://cdn-icons-png.flaticon.com/512/733/733561.png" alt="LinkedIn">
                                </a>
                                <a href="#" class="social-icon-btn" title="Instagram">
                                    <img src="https://cdn-icons-png.flaticon.com/512/733/733558.png" alt="Instagram">
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="footer">
                <p>Contact Support: <a href="mailto:support@methotx.com">support@methotx.com</a> | Website: <a href="https://methotx.com" target="_blank">www.methotx.com</a></p>
                <p>© {{ date('Y') }} Clickngo Tech Service Pvt. Ltd. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
