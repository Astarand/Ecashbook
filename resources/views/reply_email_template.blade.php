<!DOCTYPE html>
<html lang="en">
<head>
    <title>Verify Your E-Cashbook Account</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Poppins", Arial, sans-serif !important;
            font-weight: normal;
        }
        body {
            font-family: "Poppins", Arial, sans-serif !important;
            margin: 0;
            padding: 0;
            background-color: #f7f9fc;
            color: #333333;
            font-weight: normal;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 0 0 8px 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            font-family: "Poppins", Arial, sans-serif !important;
        }
        .header {
            padding: 30px 0;
            text-align: center;
            background-color: #ffffff;
        }
        .logo {
            max-width: 180px;
            height: auto;
            margin: 0 auto;
            display: block;
        }
        .main-heading {
            text-align: center;
            color: #313363;
            font-size: 24px;
            font-weight: 500;
            margin: 40px 0 20px;
            padding: 0 20px;
            font-family: "Poppins", Arial, sans-serif !important;
        }
        .sub-heading {
            text-align: center;
            color: #5a5a73;
            font-size: 15px;
            line-height: 1.5;
            margin: 0 0 40px;
            padding: 0 30px;
            font-family: "Poppins", Arial, sans-serif !important;
            font-weight: normal;
        }
        .content {
            padding: 0 30px 40px;
            font-family: "Poppins", Arial, sans-serif !important;
        }
        .verify-button-container {
            text-align: center;
            margin: 30px 0;
        }
        .verify-button {
            display: inline-block;
            background-color: #673de6;
            color: #ffffff !important;
            text-decoration: none;
            text-align: center;
            padding: 12px 28px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 15px;
            transition: background 0.3s;
            box-shadow: 0 4px 10px rgba(103, 61, 230, 0.3);
            font-family: "Poppins", Arial, sans-serif !important;
        }
        .verify-button:hover {
            background-color: #5331c9;
        }
        .info-block {
            background-color: #f7f9fc;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
            font-family: "Poppins", Arial, sans-serif !important;
        }
        .info-block-title {
            color: #313363;
            font-weight: 500;
            margin-top: 0;
            margin-bottom: 15px;
            font-family: "Poppins", Arial, sans-serif !important;
            font-size: 16px;
        }
        .info-text {
            color: #5a5a73;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            font-family: "Poppins", Arial, sans-serif !important;
            font-weight: normal;
        }
        .footer {
            background-color: #f7f9fc;
            padding: 25px;
            text-align: center;
            font-size: 13px;
            color: #5a5a73;
            border-top: 1px solid #e8eaf1;
            font-family: "Poppins", Arial, sans-serif !important;
        }
        .footer p {
            margin: 5px 0;
            font-family: "Poppins", Arial, sans-serif !important;
            font-weight: normal;
        }
        .colored-bar {
            height: 5px;
            background: linear-gradient(90deg, #673de6 0%, #50b4f2 100%);
        }
        .support-link {
            color: #673de6;
            text-decoration: none;
            font-family: "Poppins", Arial, sans-serif !important;
        }
        .support-link:hover {
            text-decoration: underline;
        }
        .social-icons {
            margin: 20px 0;
            text-align: center;
        }
        .social-icon {
            display: inline-block;
            width: 36px;
            height: 36px;
            background-color: #ffffff !important;
            border: 2px solid #673de6;
            border-radius: 50%;
            margin: 0 8px;
            text-align: center;
            line-height: 36px;
            text-decoration: none;
            color: #ffffff;
            font-size: 16px;
            padding: 10px;
            box-sizing: border-box;
            vertical-align: middle;
        }
        .social-icon:hover {
            background-color: #f0f0f0;
            border-color: #5331c9;
        }
        .social-icon img {
            width: 16px;
            height: 16px;
            display: block;
            margin: 0 auto;
        }
        .account-info {
            margin: 10px 0;
        }
        .account-info strong {
            color: #313363;
        }
    </style>
</head>
<body>
    <div class="colored-bar"></div>
    <div class="container">
        <div class="header">
            <img src="{{ asset('assets/images/logo.png') }}" alt="E-Cashbook Logo" class="logo">
        </div>

        <h1 class="main-heading">Ticket Status</h1>
        <p class="sub-heading">Thank you for creating an E-Cashbook account. Please verify your ticket Status.</p>

        <div class="content">
            <div class="info-block">
                <h3 class="info-block-title">Account Information</h3>
                <div class="account-info">
                    <p class="info-text"><strong>Name:</strong> {{ $name }}</p>
                    <p class="info-text"><strong>Email/Username:</strong> <a href="mailto:{{ $email }}" style="color: #673de6; text-decoration: none;">{{ $email }}</a></p>
                
					<p class="info-text"><strong>Ticket Status:</strong> {{ $status_text }}</p>
					<p class="info-text"><strong>Reply:</strong> {{ $replyMsg }}</p>
				</div>
            </div>

            <p class="info-text">
                E-Cashbook works on all your devices and email clients including Outlook, Gmail, Apple Mail, and Thunderbird.
                You can access your account from your smartphone, tablet, or computer.
            </p>

            

            <p class="info-text">
                If you did not create this account, please disregard this email or contact our support team if you have concerns.
            </p>
        </div>

        <div class="social-icons">
                  <a href="#" class="social-icon" title="Facebook">
                    <img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" alt="Facebook">
                  </a>
                  <a href="#" class="social-icon" title="X (Twitter)">
                    <img src="https://cdn-icons-png.flaticon.com/512/5968/5968958.png" alt="X Twitter">
                  </a>
                  <a href="#" class="social-icon" title="LinkedIn">
                    <img src="https://cdn-icons-png.flaticon.com/512/733/733561.png" alt="LinkedIn">
                  </a>
                  <a href="#" class="social-icon" title="Instagram">
                    <img src="https://cdn-icons-png.flaticon.com/512/733/733558.png" alt="Instagram">
                  </a>
                </div>

        <div class="footer">
            <p>Need help? <a href="mailto:support@ecashbook.in" class="support-link">Contact Support</a></p>
            <p>Copyright © {{ date('Y') }} E-Cashbook. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
