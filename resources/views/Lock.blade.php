<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Lock Screen - E-Cashbook</title>

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
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
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
            <div class="card user-card">
                <div class="card-body position-relative">
                    <div class="row d-flex justify-content-center align-items-center text-center">
                        <div class="chat-avtar d-inline-flex mx-auto">
                            <img class="rounded-circle img-fluid wid-90 img-thumbnail mx-auto" src="../assets/images/user/avatar-1.jpg" alt="User image">
                        </div>
                        <div class="d-flex flex-column align-items-center mt-2">
                            <h2 class="mb-3 userName"></h2>
                        </div>
                        <h4 class="f-w-500 mb-1">Please Enter Password to Login</h4>
                    </div>

                    <div class="saprator my-3">
                        <span>Login</span>
                    </div>
                    <div class="text-center">
						<input type="hidden" id="lock_email">
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                                <div class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                    <i class="ti ti-eye"></i>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-primary loginBtn">Login</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="auth-sidefooter">
            <img src="{{ asset('assets/images/logo.png') }}" class="img-brand img-fluid" alt="images" style="width: 150px;"/>
            <hr class="mb-3 mt-4" />
            <div class="row">
                <div class="col my-1">
                    <p class="m-0">Made with ♥ by Team <a href="https://clickngotech.com/" target="_blank"> Clickngo Tech Service Pvt Ltd.</a></p>
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
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>

	// Toast Notification Function
	function showToast(message, type) {
		Toastify({
			text: message,
			duration: 3000, // Show for 3 seconds
			close: true,
			gravity: "top", // Position: Top
			position: "right", // Align: Right
			backgroundColor: type === "success" ? "#28a745" : "#dc3545", // Green for success, Red for error
			stopOnFocus: true, // Stop on hover
			style: {
				fontSize: "18px", // Larger Font
				padding: "16px 24px", // More Padding
				borderRadius: "8px", // Smooth Edges
				background: type === "success" ? "#28a745" : "#dc3545", // Green for success, Red for error
				color: "#fff", // White text
				boxShadow: "0px 5px 15px rgba(0, 0, 0, 0.2)", // Nice Shadow
			},
		}).showToast();
	}
	
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            // Toggle input type between password and text
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle the eye icon class
            const icon = this.querySelector('i');
            icon.classList.toggle('ti-eye');
            icon.classList.toggle('ti-eye-off');
        });
    });
	
	$(document).ready(function () {
		
		let name = localStorage.getItem('nm');
		$('.userName').text(name);

		let email = localStorage.getItem('lock_email');
		if (!email) {
			showToast('Session expired. Please login again.', 'error');
			window.location.href = "/login";
		}
		$('#lock_email').val(email);
		// LOGIN CLICK
		$('.loginBtn').click(function () {

				let btn = $(this); // current button
				let password = $('#password').val();
				if (!password) {
					showToast('Please enter password', 'error');
					return;
				}

				 btn.prop('disabled', true).text('Please wait...');
				$.ajax({
					url: '/unlock-user',
					type: 'POST',
					data: {
						_token: $('meta[name="csrf-token"]').attr('content'),
						email: email,
						password: password
					},
					success: function (res) {
						if (res.status === 'success') {
							let redirectUrl = localStorage.getItem('lock_redirect');
							localStorage.removeItem('lock_email');
							localStorage.removeItem('lock_redirect');
							if (redirectUrl) {
								window.location.href = redirectUrl;
							} else {
								window.location.href = res.redirect;
							}
						} else {
							showToast(res.message, 'error');
							btn.prop('disabled', false).text('Login');
						}
					},
					error: function () {
						 btn.prop('disabled', false).text('Login');
						showToast('Session expired. Please login again.', 'error');
						window.location.href = "/login";
					}
				});

			});

	});
</script>
</body>
<!-- [Body] end -->

</html>
