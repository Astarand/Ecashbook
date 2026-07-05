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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
                                <li class="list-inline-item"><a href="#">Home</a></li>
                                <li class="list-inline-item"><a href="#" target="_blank">Documentation</a></li>
                                <li class="list-inline-item"><a href="#" target="_blank">Support</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <div class="auth-form">
                <div class="card my-5 mx-3">
                    <div class="card-body">
                        <form>
                            <h4 class="f-w-500 mb-1">Reset password</h4>
                            <p class="mb-3">Back to <a href="{{ route('login') }}" class="link-primary">Log in</a></p>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" id="newpwd" placeholder="Password">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="conf_new_pwd" placeholder="Confirm Password">
                            </div>
                            <div id="message_show"></div>
                            <div class="d-grid mt-4">
                                <button type="button" id="reset_pwd" class="btn btn-primary">Reset Password</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#reset_pwd').on('click', function () {
                // Get the new password and confirm password values
                const newPwd = $('#newpwd').val();
                const confirmPwd = $('#conf_new_pwd').val();

                // Clear any previous messages
                $('#message_show').empty();

                // Check if fields are empty
                if (newPwd === "" || confirmPwd === "") {
                    $('#message_show').html('<div class="alert alert-danger">Both fields are required.</div>');
                    setTimeout(function () {
                        $('#message_show').empty();
                    }, 3000);
                    return;
                }

                if (newPwd.length < 6) {
                    $('#message_show').html('<div class="alert alert-danger">Password must be at least 6 characters long.</div>');
                    setTimeout(function () {
                        $('#message_show').empty();
                    }, 3000);
                    return;
                }

                // Check if passwords match
                if (newPwd !== confirmPwd) {
                    $('#message_show').html('<div class="alert alert-danger">Passwords do not match.</div>');
                    setTimeout(function () {
                        $('#message_show').empty();
                    }, 3000);
                    return;
                }


                $.ajax({
                    url: '/update-password',
                    method: 'POST',
                    data: {
                        email: '{{ session('email') }}',
                        password: newPwd,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status === 'success') {

                            $('#message_show').html('<div class="alert alert-success">Password updated successfully!</div>');
                            setTimeout(function () {
                                window.location.href = '{{ route('login') }}';
                            }, 3000);
                        } else {

                            $('#message_show').html('<div class="alert alert-danger">' + response.message + '</div>');
                            setTimeout(function () {
                                $('#message_show').empty();
                            }, 3000);
                        }
                    },
                    error: function (xhr, status, error) {

                        $('#message_show').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                        setTimeout(function () {
                            $('#message_show').empty();
                        }, 3000);
                    }
                });
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

</body>
<!-- [Body] end -->


</html>
