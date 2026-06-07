<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Forget Password - E-Cashbook</title>

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                                <li class="list-inline-item"><a href="https://ecashbook.in/features/dashboard" target="_blank">Documentation</a></li>
                                <li class="list-inline-item"><a href="https://ecashbook.in/contact" target="_blank">Support</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <div class="auth-form">
                <div class="card my-5 mx-3">
                    <div class="card-body">
                        <h4 class="f-w-500 mb-1">Forgot Password</h4>
                        <p class="mb-3">Back to <a href="{{ route('login') }}" class="link-primary">Log in</a></p>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" required class="form-control" id="email" name="email" placeholder="Email Address">
                        </div>

                        <div class="message-container" style="display: none;"></div>
                        <div class="d-grid mt-3">
                            <button type="button" class="btn btn-primary" id="send-otp-btn">
                                Send Reset E-mail
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
       $('#send-otp-btn').on('click', function() {
            var email = $('#email').val();
            var messageContainer = $('.message-container');
            var sendOtpBtn = $('#send-otp-btn');

            // Reset the message container and hide any previous message
            messageContainer.hide().html('');

            // Check if the email is empty
            if (!email) {
                messageContainer.show().html('<div class="alert alert-danger" role="alert">Email is required.</div>');
                return;
            }

            // Disable the button and show the loading state
            sendOtpBtn.prop('disabled', true).text('Sending...');

            var formData = { email: email };

            // Perform AJAX request
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/check-email-existence",
                type: "POST",
                data: JSON.stringify(formData),
                contentType: "application/json",
                success: function(response) {
                    console.log('Response Data:', response);

                    // Re-enable the button and reset the text
                    sendOtpBtn.prop('disabled', false).text('Send Reset E-mail');

                    if (response.status === 'success') {
                        // Email exists, redirect to OTP verification page
                        window.location.href = response.redirect;
                    } else {
                        // Show error message if email does not exist
                        messageContainer.show().html(
                            `<div class="alert alert-danger" role="alert">${response.message}</div>`
                        );

                        // Hide the error message after 3 seconds
                        setTimeout(function() {
                            messageContainer.fadeOut(300, function() {
                                messageContainer.html('');
                            });
                        }, 3000);
                    }
                },
                error: function(xhr, status, error) {
                    // Re-enable the button and reset the text
                    sendOtpBtn.prop('disabled', false).text('Send Reset E-mail');

                    // Show generic error message
                    console.error("Error occurred:", error);
                    messageContainer.show().html(
                        '<div class="alert alert-danger" role="alert">An unexpected error occurred. Please try again later.</div>'
                    );

                    // Hide the error message after 3 seconds
                    setTimeout(function() {
                        messageContainer.fadeOut(300, function() {
                            messageContainer.html('');
                        });
                    }, 3000);
                }
            });
        });




    </script>

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


</html>
