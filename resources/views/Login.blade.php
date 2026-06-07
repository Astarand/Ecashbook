<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Register / Login - E-Cashbook OneSuite | Intelligent Accounting, Tax & Compliance Portal</title>

    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="E-Cashbook - Intelligent Accounting & GST Management Platform for Chartered Accountants, Tax Professionals, and Small Businesses. Professional financial management for the digital age." />
    <meta name="author" content="360 Business & Services" />
    <meta name="robots" content="noindex, nofollow">
	<meta name="googlebot" content="noindex">
	<meta name="bingbot" content="noindex">
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

    <div class="auth-main v2">
        <div class="bg-overlay bg-dark"></div>
        <div class="auth-wrapper">
            <div class="auth-sidecontent">
                <div class="auth-sidefooter">
                    <img src="{{ asset('assets/images/white-logo.png') }}" class=" img-fluid" alt="images" />
                    <hr class="mb-3 mt-4" />
                    <div class="row">
                        <div class="col-auto my-1">
                            <ul class="list-inline footer-link mb-0">
                                <li class="list-inline-item"><a href="https://ecashbook.in" target="_blank">Home</a></li>
                                <li class="list-inline-item"><a href="https://www.ecashbook.in/coming-soon" target="_blank">Documentation</a></li>
                                <li class="list-inline-item"><a href="https://ecashbook.in/contact" target="_blank">Support</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <div class="auth-form">
                <div class="card my-5 mx-3">
                    <div class="card-body">
                        <h4 class="f-w-500 mb-1">Login with your email</h4>
                        <p class="mb-3">Don't have an Account? <a href="{{ route('signup') }}" class="link-primary ms-1">Create Account</a></p>
                        <form class="needs-validation" action="javascript:void(0);" name="loginform" id="loginform">
                            @csrf

                            <div class="mb-3">
                                <input type="email" class="form-control" id="username" name="username"
                                    placeholder="Email Address"
                                    value="{{ Cookie::get('loginId') ?? '' }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter Password"
                                        value="{{ Cookie::get('loginPass') ?? '' }}"
                                        required>
                                    <div class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                        <i class="ti ti-eye"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex mt-1 justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox"
                                        id="customCheckc1" name="remember"
                                        {{ Cookie::get('loginId') ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted" for="customCheckc1">Remember me?</label>
                                </div>
                                <a href="{{route('forgetPassword')}}">
                                    <h6 class="text-secondary f-w-400 mb-0">Forgot Password?</h6>
                                </a>
                            </div>

                            <div class="message-container"></div>

                            <div class="d-grid mt-4">
                                <button class="btn btn-lg btn-block btn-primary w-100" type="submit">Login</button>
                            </div>
                        </form>

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
</body>
<!-- [Body] end -->
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const togglePasswordElements = document.querySelectorAll('#togglePassword');

        togglePasswordElements.forEach(toggle => {
            toggle.addEventListener('click', function() {
                // Find the sibling input field relative to the clicked toggle
                const passwordInput = this.previousElementSibling;

                // Toggle the password visibility
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle the eye icon class
                const icon = this.querySelector('i');
                icon.classList.toggle('ti-eye');
                icon.classList.toggle('ti-eye-off');
            });
        });




        $("form#loginform").on("submit", function (e) {
            e.preventDefault();

            var username = $("#loginform #username").val();
            var password = $("#loginform #password").val();
            var remember = $("#customCheckc1").is(":checked"); // Fixed selector

            var formData = {
                email: username,
                password: password,
                remember: remember,
            };

            $("#loginLoader").show();
            $("#username, #password").prop("disabled", true);
            $("button[type=submit]").prop("disabled", true);

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/login/user",
                type: "POST",
                data: formData,
                success: function (response) {
                    $("#username, #password").prop("disabled", false);
                    $("button[type=submit]").prop("disabled", false);
                    $("#loginLoader").hide();

                    if (response.class === "succ") {
                        window.location = response.redirect;
                    } else {
                        $("#loginform .message-container").html(
                            `<div class="alert alert-danger" role="alert">${response.message}</div>`
                        );
                        setTimeout(function () {
                            $("#loginform .message-container div").fadeOut(300, function () {
                                $("#loginform .message-container").html("");
                            });
                        }, 10000);
                    }
                },
                error: function (xhr, status, error) {
                    $("#username, #password").prop("disabled", false);
                    $("button[type=submit]").prop("disabled", false);
                    $("#loginLoader").hide();

                    console.error("Error occurred:", error);
                    $("#loginform .message-container").html(
                        '<div class="alert alert-danger">An unexpected error occurred. Please try again.</div>'
                    );
                },
            });
        });




    });





</script>

</html>
