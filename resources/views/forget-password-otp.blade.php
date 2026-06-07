<div>
    <!-- Be present above all else. - Naval Ravikant -->
</div>
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
                        <h4 class="f-w-500 mb-1">Please confirm with OTP</h4>
                        <p class="mb-0">We`ve sent you a code on {{ session('maskedEmail') }}</p>
                        <p class="mb-3">Did not receive the email?
                            <a href="#" class="link-primary ms-1" id="resend-otp">Resend code</a>
                            <span id="timer" style="display:none; color: darkorchid;"></span></p>

                        <div class="row my-4 text-center">
                            <div class="col">
                                <input type="text" class="form-control text-center otp-input" data-index="0" maxlength="1" placeholder="0">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control text-center otp-input" data-index="1" maxlength="1" placeholder="0">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control text-center otp-input" data-index="2" maxlength="1" placeholder="0">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control text-center otp-input" data-index="3" maxlength="1" placeholder="0">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control text-center otp-input" data-index="4" maxlength="1" placeholder="0">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control text-center otp-input" data-index="5" maxlength="1" placeholder="0">
                            </div>
                        </div>
                        <div class="message-container mt-3" style="display:none;">
                            <div class="alert alert-danger" role="alert"></div>
                        </div>
                        <div class="message-container-success mt-3" style="display:none;">
                            <div class="alert alert-success" role="alert"></div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-primary" id="verify-otp">Verify</button>
                        </div>

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
        $(document).ready(function() {
            // Function to distribute OTP digits across inputs
            function distributeOTP(otpString) {
                const digits = otpString.replace(/\D/g, '').split('').slice(0, 6);

                // Clear all inputs first
                $('.otp-input').val('');

                // Fill inputs with digits
                digits.forEach((digit, index) => {
                    $('.otp-input[data-index="' + index + '"]').val(digit);
                });

                // Focus on next empty field or last field
                const nextIndex = Math.min(digits.length, 5);
                $('.otp-input[data-index="' + nextIndex + '"]').focus();

                console.log('OTP distributed:', digits.join(''));
            }

            // Handle input events
            $('.otp-input').on('input', function(e) {
                const input = $(this);
                let value = input.val();
                const index = parseInt(input.data('index'));

                console.log('Input event:', value, 'at index:', index);

                // If multiple characters (paste detection)
                if (value.length > 1) {
                    console.log('Multiple characters detected, distributing...');
                    distributeOTP(value);
                    return;
                }

                // Allow only single digit
                if (!/^\d$/.test(value)) {
                    input.val('');
                    return;
                }

                // Move to next input
                if (value.length === 1 && index < 5) {
                    $('.otp-input[data-index="' + (index + 1) + '"]').focus();
                }
            });

                        // Handle paste events - Method 1: Traditional paste event
            $('.otp-input').on('paste', function(e) {
                console.log('Paste event triggered');

                // Try to get clipboard data immediately
                let pastedData = '';
                if (e.originalEvent && e.originalEvent.clipboardData) {
                    pastedData = e.originalEvent.clipboardData.getData('text/plain') ||
                               e.originalEvent.clipboardData.getData('text');
                } else if (window.clipboardData) {
                    pastedData = window.clipboardData.getData('Text');
                }

                if (pastedData) {
                    e.preventDefault();
                    console.log('Pasted data from event:', pastedData);
                    distributeOTP(pastedData);
                } else {
                    // Allow default paste and catch in input event
                    console.log('No paste data found, allowing default paste');
                    setTimeout(() => {
                        const currentValue = $(this).val();
                        if (currentValue.length > 1) {
                            console.log('Caught pasted data in input:', currentValue);
                            distributeOTP(currentValue);
                        }
                    }, 10);
                }
            });

                        // Handle keyboard events
            $('.otp-input').on('keydown', function(e) {
                const input = $(this);
                const index = parseInt(input.data('index'));

                // Handle Ctrl+V manually (check multiple key combinations)
                if ((e.ctrlKey && e.key.toLowerCase() === 'v') || (e.metaKey && e.key.toLowerCase() === 'v')) {
                    console.log('Ctrl+V detected');
                    e.preventDefault();

                    // Try modern clipboard API first
                    if (navigator.clipboard && navigator.clipboard.readText) {
                        navigator.clipboard.readText().then(function(text) {
                            console.log('Clipboard API text:', text);
                            distributeOTP(text);
                        }).catch(function(err) {
                            console.log('Clipboard API failed:', err);
                            // Fallback: allow default paste and catch in input event
                            setTimeout(() => {
                                const currentValue = input.val();
                                if (currentValue.length > 1) {
                                    distributeOTP(currentValue);
                                }
                            }, 10);
                        });
                    } else {
                        // For older browsers, allow default paste and catch in input event
                        setTimeout(() => {
                            const currentValue = input.val();
                            if (currentValue.length > 1) {
                                distributeOTP(currentValue);
                            }
                        }, 10);
                    }
                    return;
                }

                // Handle backspace
                if (e.key === 'Backspace') {
                    if (input.val() === '' && index > 0) {
                        $('.otp-input[data-index="' + (index - 1) + '"]').focus();
                    }
                    return;
                }

                // Handle arrow keys
                if (e.key === 'ArrowLeft' && index > 0) {
                    e.preventDefault();
                    $('.otp-input[data-index="' + (index - 1) + '"]').focus();
                    return;
                }

                if (e.key === 'ArrowRight' && index < 5) {
                    e.preventDefault();
                    $('.otp-input[data-index="' + (index + 1) + '"]').focus();
                    return;
                }

                // Allow only digits and control keys
                if (!/[\d]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                    e.preventDefault();
                }
            });

            // Additional global paste handler
            $(document).on('keydown', function(e) {
                // Check if any OTP input is focused
                if ($('.otp-input:focus').length > 0) {
                    if ((e.ctrlKey && e.key.toLowerCase() === 'v') || (e.metaKey && e.key.toLowerCase() === 'v')) {
                        console.log('Global Ctrl+V detected on OTP input');
                        e.preventDefault();

                        if (navigator.clipboard && navigator.clipboard.readText) {
                            navigator.clipboard.readText().then(function(text) {
                                console.log('Global clipboard text:', text);
                                distributeOTP(text);
                            }).catch(function(err) {
                                console.log('Global clipboard failed:', err);
                            });
                        }
                    }
                }
            });

            // Auto-focus first input on page load
            $('.otp-input[data-index="0"]').focus();

            // Simple fallback: Monitor for any multi-character input
            $('.otp-input').on('propertychange input textInput', function(e) {
                const input = $(this);
                const value = input.val();

                if (value.length > 1) {
                    console.log('Multi-character input detected:', value);
                    distributeOTP(value);
                }
            });

            // Verify button functionality
            $('#verify-otp').on('click', function() {
                let otp = '';
                $('.otp-input').each(function() {
                    otp += $(this).val();
                });

                // Check if OTP length is 6
                if (otp.length === 6) {
                    // Send OTP to server for verification
                    $.ajax({
                        url: '/verify-otp',  // Update with the actual server route for OTP verification
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: { otp: otp },
                        success: function(response) {
                            if (response.status === 'success') {

                                $('.message-container-success').show();
                                $('.message-container-success .alert').text("");  // Clear any previous message
                                $('.message-container-success .alert').text(response.message);

                                // Hide the error message after 3 seconds
                                setTimeout(function() {
                                    window.location.href = response.redirect;
                                }, 3000); // 3 seconds


                            } else {
                                // Show error message if OTP does not match
                                $('.message-container').show();
                                $('.message-container .alert').text(response.message);

                                // Hide the error message after 3 seconds
                                setTimeout(function() {
                                    $('.message-container').fadeOut(300);  // Fade out smoothly after 3 seconds
                                }, 3000); // 3 seconds
                            }
                        },
                        error: function() {
                            $('.message-container').show();
                            $('.message-container .alert').text('An error occurred. Please try again.');

                            // Hide the error message after 3 seconds
                            setTimeout(function() {
                                $('.message-container').fadeOut(300);  // Fade out smoothly after 3 seconds
                            }, 3000); // 3 seconds
                        }
                    });
                } else {
                    // Show error message if OTP is not complete
                    $('.message-container .alert').text('Please enter complete 6-digit OTP');
                    $('.message-container').show();

                    // Hide the error message after 3 seconds
                    setTimeout(function() {
                        $('.message-container').fadeOut(300);  // Fade out smoothly after 3 seconds
                    }, 3000); // 3 seconds
                }
            });

            //------ Resend OTP --------
            let countdownTimer;
            let remainingTime = 120; // 2 minutes in seconds

            // Resend OTP when the "Resend code" link is clicked
            $('#resend-otp').on('click', function(e) {
                e.preventDefault();  // Prevent the default link behavior

                // Disable the resend link and show the timer
                $('#resend-otp').hide(); // Hide the "Resend code" link
                $('#timer').show(); // Show the timer span

                // Send AJAX request to resend OTP
                $.ajax({
                    url: '/resend-otp',  // Add the correct route for resending OTP
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Show success message
                        $('.message-container').hide();
                        $('.message-container-success').show();
                        $('.message-container-success .alert').text(response.message);

                        // Optionally, hide the success message after a few seconds
                        setTimeout(function() {
                            $('.message-container-success').fadeOut(300);
                        }, 3000); // 3 seconds

                        // Start the countdown timer for button re-enable
                        startCountdown();
                        call_time_exp();
                    },
                    error: function(xhr, status, error) {
                        // Hide the success message
                        $('.message-container-success').hide();
                        $('.message-container').show();

                        // Show error message
                        $('.message-container .alert').text('An error occurred. Please try again.');

                        // Optionally, hide the error message after a few seconds
                        setTimeout(function() {
                            $('.message-container').fadeOut(300);
                        }, 3000); // 3 seconds
                    }
                });
            });

            // Countdown function to update the timer text
            function startCountdown() {
                countdownTimer = setInterval(function() {
                    let minutes = Math.floor(remainingTime / 60);
                    let seconds = remainingTime % 60;

                    // Update the timer text with the remaining time
                    $('#timer').text('Resend code in ' + formatTime(minutes) + ':' + formatTime(seconds));

                    // Decrease the time
                    remainingTime--;

                    // When the countdown reaches 0, re-enable the link
                    if (remainingTime < 0) {
                        clearInterval(countdownTimer);
                        $('#timer').hide(); // Hide the timer
                        $('#resend-otp').show().text('Resend code'); // Show the link again
                        remainingTime = 120; // Reset the timer for next time
                    }
                }, 1000);
            }

            // Helper function to format time as two digits
            function formatTime(time) {
                return time < 10 ? '0' + time : time;
            }

            //----- OTP Time check -------
                call_time_exp();

                function call_time_exp() {
                    const otpExpiration = '{{ session('otp_expiration') }}'; // This value is passed from the server-side

                    if (otpExpiration) {
                        const expirationTime = new Date(otpExpiration).getTime(); // Convert expiration time to Date object
                        let currentTime = new Date().getTime(); // Get current time
                        let remainingTime = expirationTime - currentTime; // Calculate remaining time in milliseconds

                        // console.log('Remaining time in milliseconds:', remainingTime);

                        if (remainingTime > 0) {

                            setInterval(function() {
                                // Calculate the remaining time in seconds
                                remainingTime -= 1000;

                                // // Calculate hours, minutes, and seconds
                                // let hours = Math.floor(remainingTime / (1000 * 60 * 60));
                                // let minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
                                // let seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

                                // // Format time as HH:MM:SS
                                // let formattedTime = formatTime(hours) + ':' + formatTime(minutes) + ':' + formatTime(seconds);

                                // console.log('Remaining time:', formattedTime);

                                // If OTP has expired, stop the timer and call the expiration function
                                if (remainingTime <= 0) {
                                    clearInterval(this); // Stop the interval
                                    handleOtpExpiration();
                                }
                            }, 1000);
                        } else {
                            // If OTP has already expired, call the function immediately
                            handleOtpExpiration();
                        }
                    }
                }



                function handleOtpExpiration() {
                    $.ajax({
                        url: '/forget-password-otp-destroy',
                        method: 'GET',
                        success: function(response) {
                            showToast("OTP Expired", error);
                            if (response.status === 'redirect') {
                               showToast("OTP Expired", "error");

                                setTimeout(function() {
                                    window.location.href = response.redirect;  // Redirect to login page
                                }, 2000); // Redirect to the login page after 2 seconds
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error checking OTP expiration:', error);
                        }
                    });
                }


        });




    </script>
</body>
<!-- [Body] end -->


</html>
