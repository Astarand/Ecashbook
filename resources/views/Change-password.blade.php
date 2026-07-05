<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Register - E-Cashbook</title>

    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="E-Cashbook - Intelligent Accounting & GST Management Platform for Chartered Accountants, Tax Professionals, and Small Businesses. Professional financial management for the digital age." />
    <meta name="author" content="360 Business & Services" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon" />

    <!-- [Google Font: Public Sans] -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- [Tabler Icons] -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">

    <!-- [Feather Icons] -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">

    <!-- [Font Awesome Icons] -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">

    <!-- [Material Icons] -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">
</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light">
<!-- [ Pre-loader ] start -->
<div class="loader-bg">
    <div class="loader-track">
        <div class="loader-fill"></div>
    </div>
</div>
<!-- [ Pre-loader ] End -->

<div class="auth-main v1">
    <div class="auth-wrapper">
        <div class="auth-form">
            <div class="card my-5">
                <div class="card-body">
                    <div class="text-center">
                        <img src="../assets/images/authentication/img-auth-reset-password.png" alt="images" class="img-fluid mb-3">
                        <h4 class="f-w-500 mb-1">Reset password</h4>
                        <p class="mb-3">Back to <a href="../pages/login-v1.html" class="link-primary ms-1">Log in</a></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="currentPassword" placeholder="Current Password">
                            <div class="input-group-text toggle-password" style="cursor: pointer;" data-target="currentPassword">
                                <i class="ti ti-eye"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="newPassword" placeholder="New Password">
                            <div class="input-group-text toggle-password" style="cursor: pointer;" data-target="newPassword">
                                <i class="ti ti-eye"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm New Password">
                            <div class="input-group-text toggle-password" style="cursor: pointer;" data-target="confirmPassword">
                                <i class="ti ti-eye"></i>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="button" class="btn btn-primary">Reset Password</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="auth-sidefooter">
                <img src="{{ asset('assets/images/logo.png') }}" class="img-brand img-fluid" alt="images" style="width: 150px;"/>
            <hr class="mb-3 mt-4" />
            <div class="row">
                <div class="col my-1">
                    <p class="m-0">Made with ♥ by Team <a href="https://360bizservice.com/" target="_blank"> 360 Business & Services</a></p>
                </div>
                <div class="col-auto my-1">
                    <ul class="list-inline footer-link mb-0">
                        <li class="list-inline-item"><a href="https://ecashbook.in">Home</a></li>
                        <li class="list-inline-item"><a href="https://ecashbook.in/help-center" target="_blank">Help Center</a></li>
                        <li class="list-inline-item"><a href="https://ecashbook.in/support" target="_blank">Support</a></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- [ Main Content ] end -->
<!-- Required Js -->
<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
<script src="{{ asset('assets/js/pcoded.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const inputField = document.getElementById(targetId);
                const icon = this.querySelector('i');

                // Toggle input type between password and text
                if (inputField.type === 'password') {
                    inputField.type = 'text';
                    icon.classList.replace('ti-eye', 'ti-eye-off'); // Change icon
                } else {
                    inputField.type = 'password';
                    icon.classList.replace('ti-eye-off', 'ti-eye'); // Change icon
                }
            });
        });
    });
</script>
</body>
<!-- [Body] end -->

</html>
