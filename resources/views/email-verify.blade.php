<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Email Verified - E-Cashbook</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{asset('public/assets/img/favicon.png')}}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('public/assets/plugins/fontawesome/css/all.min.css')}}">
    <!-- Font Awesome CDN Fallback -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .verification-container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
            overflow: hidden;
            position: relative;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .colored-bar {
            height: 6px;
            background: linear-gradient(90deg, #673de6 0%, #50b4f2 100%);
        }

        .verification-header {
            padding: 40px 30px 20px;
            position: relative;
        }

        .logo {
            max-width: 160px;
            height: auto;
            margin-bottom: 30px;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            animation: checkmark 0.6s ease-in-out 0.3s both;
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3);
        }

        @keyframes checkmark {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-icon i {
            color: #ffffff;
            font-size: 35px;
            animation: checkmarkIcon 0.3s ease-in-out 0.6s both;
        }

        @keyframes checkmarkIcon {
            from {
                opacity: 0;
                transform: scale(0);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .verification-content {
            padding: 0 30px 40px;
        }

        .main-title {
            color: #313363;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .sub-title {
            color: #5a5a73;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
            font-weight: 400;
        }

        .info-card {
            background: #f7f9fc;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border-left: 4px solid #673de6;
        }

        .info-card h4 {
            color: #313363;
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .info-card p {
            color: #5a5a73;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #673de6, #5331c9);
            color: #ffffff;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(103, 61, 230, 0.3);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(103, 61, 230, 0.4);
            text-decoration: none;
            color: #ffffff;
        }

        .btn-secondary {
            background: #ffffff;
            color: #673de6;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.3s ease;
            border: 2px solid #673de6;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary:hover {
            background: #673de6;
            color: #ffffff;
            transform: translateY(-2px);
            text-decoration: none;
        }

        .features-list {
            text-align: left;
            margin: 20px 0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            color: #5a5a73;
            font-size: 14px;
        }

        .feature-item i {
            color: #4CAF50;
            margin-right: 10px;
            font-size: 16px;
        }

        .footer-text {
            color: #8a8a8a;
            font-size: 13px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e8eaf1;
        }

        .social-icons {
            margin: 25px 0;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-icon {
            display: inline-flex;
            width: 40px;
            height: 40px;
            background-color: #ffffff;
            border: 2px solid #673de6;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background-color: #673de6;
            transform: translateY(-2px);
        }

        .social-icon img {
            width: 18px;
            height: 18px;
        }

        .social-icon:hover img {
            filter: brightness(0) invert(1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .verification-container {
                margin: 10px;
                border-radius: 15px;
            }

            .verification-header {
                padding: 30px 20px 15px;
            }

            .verification-content {
                padding: 0 20px 30px;
            }

            .main-title {
                font-size: 24px;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                justify-content: center;
            }
        }

        /* Loading animation for buttons */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }

        .btn-loading::after {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="verification-container">
        <div class="colored-bar"></div>

        <div class="verification-header">
            <img src="{{ asset('assets/images/logo.png') }}" alt="E-Cashbook Logo" class="logo" onerror="this.style.display='none'; document.querySelector('.logo-fallback').style.display='block';">
            <div class="logo-fallback" style="display: none; font-size: 32px; font-weight: 700; color: #673de6; margin-bottom: 30px;">E-Cashbook</div>

            <div class="success-icon">
                <i class="fas fa-check"></i>
                <span class="checkmark-fallback" style="display: none; font-size: 35px; color: white; font-weight: bold;">✓</span>
            </div>
        </div>

        <div class="verification-content">
            @if($flag == 1)
                <h1 class="main-title">Email Successfully Verified!</h1>
                <p class="sub-title">Congratulations! Your email address has been verified successfully. You can now access all features of E-Cashbook.</p>
            @else
                <h1 class="main-title">Email Already Verified!</h1>
                <p class="sub-title">Your email address was already verified. You can continue using all features of E-Cashbook.</p>
            @endif

            <div class="info-card">
                <h4><i class="fas fa-info-circle" style="color: #673de6; margin-right: 8px;"></i>What's Next?</h4>
                <div class="features-list">
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Access your personalized dashboard</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Manage your financial records</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Generate reports and analytics</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Sync across all your devices</span>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ url('/') }}" class="btn-primary" id="homeBtn">
                    <i class="fas fa-home"></i>
                    Go to Dashboard
                </a>
                <a href="{{ url('/login') }}" class="btn-secondary" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    Login Now
                </a>
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

            <div class="footer-text">
                <p>Need help? <a href="mailto:support@ecashbook.in" style="color: #673de6; text-decoration: none;">Contact Support</a></p>
                <p>Copyright © {{ date('Y') }} E-Cashbook. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        // Add loading animation to buttons when clicked
        document.addEventListener('DOMContentLoaded', function() {
            // Check if Font Awesome loaded, if not show fallback checkmark
            setTimeout(function() {
                const checkIcon = document.querySelector('.fas.fa-check');
                const fallbackIcon = document.querySelector('.checkmark-fallback');

                if (checkIcon && fallbackIcon) {
                    const computedStyle = window.getComputedStyle(checkIcon, ':before');
                    if (!computedStyle.content || computedStyle.content === 'none' || computedStyle.content === '""') {
                        checkIcon.style.display = 'none';
                        fallbackIcon.style.display = 'inline';
                    }
                }
            }, 500);
            const buttons = document.querySelectorAll('.btn-primary, .btn-secondary');

            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Add loading class
                    this.classList.add('btn-loading');

                    // Remove loading class after 2 seconds (in case navigation is slow)
                    setTimeout(() => {
                        this.classList.remove('btn-loading');
                    }, 2000);
                });
            });

            // Add entrance animation to elements
            const animateElements = document.querySelectorAll('.info-card, .action-buttons, .social-icons');
            animateElements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    element.style.transition = 'all 0.6s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, 800 + (index * 200));
            });
        });
    </script>
</body>
</html>





