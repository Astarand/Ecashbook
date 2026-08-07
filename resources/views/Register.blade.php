<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Register - {{ config('app.name') }}</title>

    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="{{ config('app.name') }} - Intelligent Accounting & GST Management Platform for Chartered Accountants, Tax Professionals, and Small Businesses. Professional financial management for the digital age." />
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

    <style>
        .err {
            color: red;
            font-weight: bold;
        }
        .succ {
            color: green;
            font-weight: bold;
        }
        .message-container {
            margin-top: 10px;
        }
        .force-hide {
            display: none !important;
        }
        .force-show {
            display: block !important;
        }
    </style>

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
                                <li class="list-inline-item"><a href="https://methotx.com" target="_blank">Home</a></li>
                                <li class="list-inline-item"><a href="https://www.methotx.com/coming-soon" target="_blank">Documentation</a></li>
                                <li class="list-inline-item"><a href="https://methotx.com/contact" target="_blank">Support</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <div class="auth-form">
                <div class="card my-5 mx-3">
                    <div class="card-body">
                        <h4 class="f-w-500 mb-1">Welcome to {{ config('app.name') }}</h4>
                        <div>
                            <p class="mb-3">Already have an Account? <a href="{{ route('login') }}" class="link-primary">Log in</a></p>
                        </div>

                        <form class="needs-validation" action="javascript:void(0);" name="signupform" id="signupform">
                            @csrf
                            <div class="row">

                                <div class="col-sm-12">
                                    <label class="mb-3">Want to Register as?</label>
                                    <div class="mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="u_type" value="2" id="customCheckinlh1">
                                            <label class="form-check-label" for="customCheckinlh1"> Company / User </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="u_type" value="1" id="customCheckinlh2">
                                            <label class="form-check-label" for="customCheckinlh2"> CA Firm /Accountant </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="fname" id="fname" placeholder="First Name">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="lname" id="lname" placeholder="Last Name">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email Address">
                            </div>
                            <div class="mb-3">
                                <input type="number" class="form-control" name="phone" id="phone" placeholder="Phone number">
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        {{-- <input type="text" class="form-control" placeholder="State" name="state_id" id="state"> --}}
                                        <select class="form-control" name="state_id" id="state">
                                            <option value="">Select State</option>
                                            @foreach($states as $k => $state)
                                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        {{-- <input type="text" class="form-control" placeholder="City" name="city_id" id="city"> --}}
                                        <select class="form-control" name="city_id" id="city" data-selected="{{ old('city_id', $user->city_id ?? '') }}" >
                                            <option value="">Select City</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password" id="Password" placeholder="Enter Password">
                                            <div class="input-group-text" id="togglePassword1" style="cursor: pointer;">
                                                <i class="ti ti-eye"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_Password" name="confirm_Password" placeholder="Confirm Password">
                                            <div class="input-group-text" id="togglePassword2" style="cursor: pointer;">
                                                <i class="ti ti-eye"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 mb-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input input-primary" type="checkbox" id="termsCheck" name="terms_accept">
                                    <label class="form-check-label text-muted" for="termsCheck">
                                        I accept <a href="https://methotx.com/terms-and-condition" target="_blank" class="link-primary fw-semibold">Terms & Condition</a> & <a href="https://methotx.com/privacy-policy" target="_blank" class="link-primary fw-semibold">Privacy Policy</a> <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" id="newsletterCheck" name="subscribe_newsletter" value="1" checked>
                                    <label class="form-check-label text-muted" for="newsletterCheck">
                                        I want to Subscribe to Newsletter & Offers
                                    </label>
                                </div>
                            </div>

                            <div class="message-container mt-3"></div>

                            <!-- Success Message Container (Hidden by default) -->
                            <div id="success-container" class="text-center mt-4" style="display: none !important;">
                                <div class="alert alert-success">
                                    <h4>🎉 Congratulations <span id="success-name"></span>!</h4>
                                    <p>Your email <strong><span id="success-email"></span></strong> has been verified successfully.</p>
                                    <p>Now you can login to your account.</p>
                                </div>
                                <div class="d-grid mt-3">
                                    <a href="{{ route('login') }}" class="btn btn-lg btn-success w-100">Login Now</a>
                                </div>
                            </div>

                            <!-- Verification Section (Hidden by default) -->
                            <div id="verification-section" style="display: none !important;">
                                <div class="d-grid mt-4">
                                    <button type="button" id="resend-verification-btn" class="btn btn-lg btn-primary w-100" disabled>Resend Verification Email</button>
                                    <div id="countdown-timer" class="text-muted mt-2 text-center"></div>
                                    <small class="text-muted mt-2 text-center">
                                        Wrong email? You can edit your email address above and click "Create Account" again.
                                    </small>
                                </div>
                            </div>

                            <!-- Create Account Button -->
                            <div class="d-grid mt-4" id="create-account-section" style="display: block !important;">
                                <button class="btn btn-lg btn-primary w-100" type="submit">Create Account</button>
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
        const togglePasswordElements = document.querySelectorAll('#togglePassword1, #togglePassword2');

        togglePasswordElements.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const passwordInput = this.previousElementSibling;
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                const icon = this.querySelector('i');
                icon.classList.toggle('ti-eye');
                icon.classList.toggle('ti-eye-off');
            });
        });

        let registeredEmail = "";
        let registeredName = "";
        let verificationTimer = null;

        function showMessage(message, type = "danger", duration = 3000) {
            const msgHtml = `<div class="alert alert-${type}" role="alert">${message}</div>`;
            $("#signupform .message-container").html(msgHtml);
            setTimeout(() => {
                $("#signupform .message-container").fadeOut(300, function () {
                    $(this).html("").show();
                });
            }, duration);
        }

        function makeFieldsReadonly(exceptEmail = false) {
            const formElements = $('#signupform input, #signupform select');
            formElements.each(function() {
                if (exceptEmail && (this.id === 'email')) {
                    $(this).prop('readonly', false).prop('disabled', false);
                } else {
                    $(this).prop('readonly', true).prop('disabled', true);
                }
            });
        }

        function makeFieldsEditable() {
            const formElements = $('#signupform input, #signupform select');
            formElements.prop('readonly', false).prop('disabled', false);
        }

        function showVerificationSection() {
            $('#create-account-section').attr('style', 'display: none !important;');
            $('#verification-section').attr('style', 'display: block !important;');
            makeFieldsReadonly(true);
        }

        function showCreateAccountSection() {
            $('#verification-section').attr('style', 'display: none !important;');
            $('#create-account-section').attr('style', 'display: block !important;');
            makeFieldsEditable();
        }

        function showSuccessSection(name, email) {
            $('#signupform .row').attr('style', 'display: none !important;');
            $('#signupform .col-sm-12').attr('style', 'display: none !important;');
            $('#signupform .col-sm-6').attr('style', 'display: none !important;');
            $('#signupform .mb-3').attr('style', 'display: none !important;');
            $('#signupform .mt-2').attr('style', 'display: none !important;');
            $('#verification-section').attr('style', 'display: none !important;');
            $('#create-account-section').attr('style', 'display: none !important;');
            $('#success-name').text(name);
            $('#success-email').text(email);
            $('#success-container').attr('style', 'display: block !important;');
        }

        function startCountdownTimer() {
            let timeLeft = 120;
            const $resendBtn = $("#resend-verification-btn");
            const $countdown = $("#countdown-timer");
            $resendBtn.prop("disabled", true);
            verificationTimer = setInterval(() => {
                timeLeft--;
                let minutes = Math.floor(timeLeft / 60);
                let seconds = timeLeft % 60;
                $countdown.text(`You can resend verification in ${minutes}:${seconds < 10 ? "0" : ""}${seconds}`);
                if (timeLeft <= 0) {
                    clearInterval(verificationTimer);
                    $resendBtn.prop("disabled", false).text("Resend Verification Email");
                    $countdown.text("You can now resend the verification email.");
                }
            }, 1000);
        }

        $('#email').on('input', function() {
            const currentEmail = $(this).val();
            if (registeredEmail && currentEmail !== registeredEmail) {
                showCreateAccountSection();
                if (verificationTimer) {
                    clearInterval(verificationTimer);
                    verificationTimer = null;
                }
                $("#signupform .message-container").html("");
            }
        });

		$("#signupform").on("submit", function (e) {
			e.preventDefault();

			let userType = $('input[name="u_type"]:checked').val();

			if (!userType) {
				showMessage("Please select account type", "danger");
				return;
			}

			// Validate Terms & Conditions Checkbox
			if (!$("#termsCheck").is(":checked")) {
				showMessage("Please accept Terms & Condition & Privacy Policy to proceed.", "danger");
				return;
			}

			// Build form object
			let formDataReg = {
				u_type: userType,
				name: $("#fname").val() + " " + $("#lname").val(),
				phone: $("#phone").val(),
				email: $("#email").val(),
				state_id: $("#state").val(),
				city_id: $("#city").val(),
				password: $("#Password").val(),
				confirm_password: $("#confirm_Password").val(),
				subscribe_newsletter: $("#newsletterCheck").is(":checked") ? 1 : 0
			};

			// ---------------- VALIDATION ----------------
			if (
				!formDataReg.name.trim() ||
				!formDataReg.phone ||
				!formDataReg.email ||
				!formDataReg.state_id ||
				!formDataReg.city_id ||
				!formDataReg.password ||
				!formDataReg.confirm_password
			) {
				showMessage("All fields are required", "danger");
				return;
			}

			if (!/^\d{10}$/.test(formDataReg.phone)) {
				showMessage("Phone number must be 10 digits", "danger");
				return;
			}

			if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formDataReg.email)) {
				showMessage("Invalid email address", "danger");
				return;
			}

			if (formDataReg.password.length < 6) {
				showMessage("Password must be at least 6 characters", "danger");
				return;
			}

			if (formDataReg.password !== formDataReg.confirm_password) {
				showMessage("Passwords do not match", "danger");
				return;
			}

			// Directly submit registration
			submitRegistration(formDataReg);
		});

		function submitRegistration(formDataReg)
		{
			if (!formDataReg) return;

			const submitBtn = $("#create-account-section button");
			const originalText = submitBtn.text();
			submitBtn.prop('disabled', true).text('Creating Account...');

			$.ajax({
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
				},
				url: "/register/user",
				type: "POST",
				data: formDataReg,
				success: function (response) {
					submitBtn.prop('disabled', false).text(originalText);

					if (response.class === "succ") {
						registeredEmail = formDataReg.email;
						registeredName = formDataReg.name;

						 // If CA Firm
						if (response.user_type == 1) {
							showMessage(
								"Thank you for registering. Our team will guide you through a structured onboarding designed specifically for professionals.",
								"success",
								7000
							);
						} else {
							showMessage(response.message, "success", 5000);
						}

						setTimeout(function () {
							showVerificationSection();
							startCountdownTimer();
						}, 200);

					} else {
						showMessage(response.message, "danger", 5000);
					}
				},
				error: function () {
					submitBtn.prop('disabled', false).text(originalText);
					showMessage("Server error. Please try again later.", "danger");
				}
			});
		}


        //------- Resend Verify email ---------
        $("#resend-verification-btn").on("click", function () {
            if (!registeredEmail) {
                showMessage("Email not available for resend.", "danger");
                return;
            }

            const $resendBtn = $(this);
            const originalText = $resendBtn.text();
            $resendBtn.prop('disabled', true).text('Sending...');

            $.ajax({
                url: "/resend-verification-email",
                method: "POST",
                data: {
                    email: registeredEmail,
                    _token: $('meta[name="csrf-token"]').attr("content")
                },
                success: function (response) {
                    $resendBtn.prop('disabled', false).text(originalText);
                    if (response.status === "success") {
                        showMessage(response.message, "success");
                        // Restart the countdown timer
                        if (verificationTimer) {
                            clearInterval(verificationTimer);
                        }
                        startCountdownTimer();
                    } else {
                        showMessage("Something went wrong. Please try again later.", "danger");
                    }
                },
                error: function () {
                    $resendBtn.prop('disabled', false).text(originalText);
                    showMessage("Server error. Please try again later.", "danger");
                }
            });
        });

        // Function to check verification status (you can call this periodically or when user comes back to the page)
        function checkVerificationStatus() {
            if (!registeredEmail) return;

            $.ajax({
                url: "/check-verification-status",
                method: "POST",
                data: {
                    email: registeredEmail,
                    _token: $('meta[name="csrf-token"]').attr("content")
                },
                success: function (response) {
                    if (response.verified === true) {
                        // User is verified, show success message
                        if (verificationTimer) {
                            clearInterval(verificationTimer);
                            verificationTimer = null;
                        }
                        showSuccessSection(registeredName, registeredEmail);
                    }
                },
                error: function () {
                    // Silently fail - don't show error for this background check
                }
            });
        }

        // Check verification status every 10 seconds when verification section is visible
        setInterval(function() {
            if ($('#verification-section').is(':visible')) {
                checkVerificationStatus();
            }
        }, 10000);



        //----------- Fetch city -----------
		const stateDropdown = document.getElementById('state');

		function loadCities(stateId, selectedCity = null)
		{
			if (stateId) {
				$.ajaxSetup({
					headers: {
						"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
					},
				});

				$.ajax({
					url: "/getCity",
					dataType: "json",
					data: { id: stateId },
					success: function (data) {
						$("#city").empty();

						let str = '<option value="">Select City</option>';

						$.each(data, function (idx, item) {
							let selected = (selectedCity == item.id) ? "selected" : "";
							str += `<option value="${item.id}" ${selected}>${item.name}</option>`;
						});

						$("#city").html(str);
					},
					error: function () {
						showMessage("Failed to load cities. Please try again.", "danger");
					}
				});
			} else {
				$("#city").html('<option value="">Select City</option>');
			}
		}

		// When state changes
		stateDropdown.addEventListener('change', function () {
			loadCities(this.value);
		});


    });


	//Auto-load on page open
	$(document).ready(function () {
		let stateId = $("#state").val();
		let selectedCity = $("#city").data("selected");

		if (stateId) {
			loadCities(stateId, selectedCity);
		}
	});



</script>

</html>
