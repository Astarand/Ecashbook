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
                        <h4 class="f-w-500 mb-1">Welcome to Ecashbook</h4>
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
                            <!--<div class="d-flex mt-1 justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" required>
                                    <label class="form-check-label text-muted" for="customCheckc1">
                                        I agree to all the
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms & Conditions</a>
                                    </label>
                                </div>
                            </div>-->

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
    <!-- Improved Terms & Conditions Modal -->
    <!--<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable modal-lg">
			<div class="modal-content border-0 shadow-lg">
			<div class="modal-header text-white">
				<h5 class="modal-title" id="termsModalLabel"><i class="bi bi-file-earmark-text me-2"></i>TERMS & CONDITIONS:</h5>
				<button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body px-4 py-3" style="font-size: 0.95rem; line-height: 1.7;">
				<div class="mb-4">
				<p><strong>Effective Date:</strong> 1st June’2025</p>
				<p><strong>Company:</strong> Clickngo Tech Service Pvt. Ltd.</p>
				<p><strong>Registered Office:</strong> Srijan Park, Tower-1, Sector-V, Salt Lake, Kolkata – 700091</p>
				<p><strong>Email:</strong> <a href="mailto:contact@clickngotech.in">contact@clickngotech.in</a></p>
				</div>

				<hr>

				<div class="mb-3">
				<h6 class="fw-bold">1. Acceptance of Terms</h6>
				<p>By accessing or using this application, you agree to be bound by these Terms & Conditions, the Privacy Policy, and any legal notices issued by Clickngo Tech Service Pvt. Ltd.</p>
				</div>

				<div class="mb-3">
				<h6 class="fw-bold">2. User Responsibilities</h6>
				<ul>
					<li>Maintain confidentiality of your login credentials.</li>
					<li>Do not share login information with third parties.</li>
					<li>Ensure that your registration data is accurate and current.</li>
				</ul>
				</div>

				<div class="mb-3">
				<h6 class="fw-bold">3. Permissible Use</h6>
				<ul>
					<li>Use the platform solely for lawful, professional, and authorized purposes such as accounting and GST compliance.</li>
					<li>Misuse may result in account suspension or legal action.</li>
				</ul>
				</div>

				<div class="mb-3">
				<h6 class="fw-bold">4. Data Security & Confidentiality</h6>
				<ul>
					<li>Your data is protected with industry-standard security.</li>
					<li>We only disclose data when legally required or with written consent.</li>
				</ul>
				</div>

				<div class="mb-3">
				<h6 class="fw-bold">5. Legal Compliance</h6>
				<ul>
					<li>We comply with Indian IT Act, GST Act, and Companies Act.</li>
					<li>Users are responsible for adhering to applicable laws.</li>
				</ul>
				</div>

				<div class="mb-3">
				<h6 class="fw-bold">6. Intellectual Property Rights</h6>
				<p>All platform content belongs to Clickngo Tech Service Pvt. Ltd. Unauthorized use may lead to legal consequences.</p>
				</div>

				<div class="mb-3">
				<h6 class="fw-bold">7. Limitation of Liability</h6>
				<p>We are not liable for data loss, interruptions, or damages arising from use of the platform.</p>
				</div>

				<div class="mb-3">
				<h6 class="fw-bold">8. Account Suspension & Termination</h6>
				<p>We may suspend or terminate accounts for violations without prior notice.</p>
				</div>

				<div class="mb-3">
				<h6 class="fw-bold">9. Modifications to Terms</h6>
				<p>Terms may be updated periodically. Continued use implies acceptance.</p>
				</div>

				<div>
				<h6 class="fw-bold">10. Contact Information</h6>
				<p>Clickngo Tech Service Pvt. Ltd.<br>Srijan Park, Tower-1, Sector-V, Salt Lake, Kolkata – 700091<br>Email: <a href="mailto:contact@clickngotech.in">contact@clickngotech.in</a></p>
				</div>
				
				<p class="mt-4">
					By clicking <strong>Accept & Continue</strong>, I confirm that I have read, understood, and agree to all the above terms and conditions.
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" id="userCancel" class="btn btn-secondary" data-bs-dismiss="modal">
					Cancel
				</button>

				<button type="button" id="userAccept" class="btn btn-primary">
					Accept & Continue
				</button>
			</div>
			</div>
		</div>
    </div>-->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header text-white">
                <h5 class="modal-title" id="termsModalLabel">
                    <i class="bi bi-file-earmark-text me-2"></i>TERMS & CONDITIONS
                </h5>
                <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-4 py-3" style="font-size:0.95rem; line-height:1.7;">

                <div class="mb-4">
                    <p><strong>Platform:</strong> eCashbook ERP</p>
                    <p><strong>Company:</strong> ClicknGo Tech Service Pvt. Ltd.</p>
                    <p><strong>CIN:</strong> U85500WB2025PTC276321</p>
                    <p><strong>Registered Office:</strong> PS Srijan Corporate Park, EP & GP Block, Sector-V, Salt Lake, Kolkata – 700091</p>
                    <p><strong>Last Updated:</strong> 20 January 2026</p>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="fw-bold">Legal Nature of Agreement</h6>
                    <ul>
                        <li>Information Technology Act, 2000</li>
                        <li>IT (Intermediary Guidelines & Digital Media Ethics Code) Rules, 2021</li>
                    </ul>
                    <p>
                        By clicking <strong>“I Agree”</strong>, registering, logging in or using the platform,
                        you accept these Terms & Conditions as a User or CA/Accounting Professional.
                    </p>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">1. Platform Identity & Role</h6>
                    <p>
                        eCashbook is a compliance-first business control platform providing
                        accounting systems, payroll systems, statutory tracking, documentation
                        management and system-generated financial & GST-ready reports.
                    </p>
                    <ul>
                        <li>Does NOT act as a Chartered Accountant</li>
                        <li>Does NOT provide tax or legal opinions</li>
                        <li>Does NOT certify or audit accounts</li>
                    </ul>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">2. Scope of Services</h6>
                    <ul>
                        <li>Accounting, HR & Payroll systems</li>
                        <li>GST-ready registers & return preparation</li>
                        <li>Compliance tracking dashboards</li>
                        <li>Audit trails & document management</li>
                        <li>Government API integrations (GSTN, EPFO, ITD, State portals)</li>
                    </ul>
                    <p>All outputs are system-generated and preparatory in nature.</p>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">3. Statutory Filings – Boundary Clause</h6>
                    <ul>
                        <li>Platform enables filing but does NOT file on behalf of users</li>
                        <li>Returns are filed only by the business owner or authorized professional</li>
                        <li>Platform does not sign, certify or authenticate returns</li>
                    </ul>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">4. CA & Professional Collaboration</h6>
                    <p>
                        Chartered Accountants and professionals act independently.
                        The platform does not recommend or endorse any professional.
                    </p>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">5. Reports & Output Disclaimer</h6>
                    <p>
                        All financial statements and compliance outputs are for internal
                        management and preparation purposes only.
                        Final responsibility rests with the User and engaged professional.
                    </p>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">6. User Responsibilities</h6>
                    <ul>
                        <li>Provide accurate and complete data</li>
                        <li>Review filings before submission</li>
                        <li>Use only authorized credentials</li>
                        <li>Seek independent professional advice when required</li>
                    </ul>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">7. Limitation of Liability</h6>
                    <p>
                        Company shall not be liable for penalties, incorrect data,
                        professional advice by third parties, or government system failures.
                        Maximum liability shall not exceed fees paid in the last 12 months.
                    </p>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">8. Payroll, Inventory & Tax Disclaimer</h6>
                    <ul>
                        <li>Payroll & HR disputes are solely between employer and employees</li>
                        <li>Inventory values are indicative and user-dependent</li>
                        <li>Tax penalties due to incorrect inputs are user responsibility</li>
                    </ul>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">9. Cyber Risk & Data Security</h6>
                    <p>
                        Industry-standard security is followed; however absolute security
                        cannot be guaranteed against cyber threats or force majeure events.
                    </p>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">10. Payments & Subscriptions</h6>
                    <ul>
                        <li>All fees are non-refundable</li>
                        <li>Chargebacks may lead to service termination</li>
                        <li>GST applicable as per law</li>
                    </ul>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">11. Suspension & Termination</h6>
                    <p>
                        Access may be suspended or terminated for violations,
                        fraud, non-payment, or misuse of the platform.
                    </p>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="fw-bold">Privacy Policy (DPDP Act, 2023)</h6>
                    <ul>
                        <li>Company acts as Data Fiduciary</li>
                        <li>Data collected only for accounting, payroll & compliance</li>
                        <li>Shared only with government APIs or authorized professionals</li>
                        <li>Role-based access, encryption & audit logs maintained</li>
                    </ul>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">Governing Law & Jurisdiction</h6>
                    <p>
                        Governed by laws of India.  
                        Jurisdiction: Courts of Kolkata, West Bengal.  
                        Arbitration under Arbitration & Conciliation Act, 1996.
                    </p>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">Contact Information</h6>
                    <p>
                        ClicknGo Tech Service Pvt. Ltd.<br>
                        Email: <a href="mailto:contact@ecashbook.in">contact@ecashbook.in</a><br>
                        Website: <a href="https://www.ecashbook.in" target="_blank">www.ecashbook.in</a>
                    </p>
                </div>

                <p class="mt-4">
                    By clicking <strong>Accept & Continue</strong>, I confirm that I have read,
                    understood and agree to all Terms, Conditions and Privacy Policy.
                </p>

            </div>

            <div class="modal-footer">
                <button type="button" id="userCancel" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button" id="userAccept" class="btn btn-primary">
                    Accept & Continue
                </button>
            </div>

        </div>
    </div>
</div>

	
	<!-- CA Firm / Accountant Terms Modal -->
	<div class="modal fade" id="caTermsModal" tabindex="-1">
		<div class="modal-dialog modal-dialog-scrollable modal-lg">
			<div class="modal-content">

				<div class="modal-header">
					<h5 class="modal-title"><u><b>TERMS & CONDITIONS</b> CA Firm / Accountant Agreement</u></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>

				<div class="modal-body">

					<p><strong>Important:</strong> This agreement applies to Chartered Accountants, Accounting Firms, and Accounting Professionals registering on this platform.</p>

					<ol class="mt-3">
						<li>
							I confirm that I am registering as a <strong>Chartered Accountant / Accounting Professional</strong> and that I am legally authorized to practice under applicable laws.
						</li>

						<li>
							I understand and acknowledge that this platform provides only <strong>technology, automation, self-service tools, and process-management support</strong> and does <strong>not</strong> provide tax advice, legal advice, statutory certification, professional opinions, or representation before any statutory authority.
						</li>

						<li>
							I acknowledge that all accounting records, financial statements, MIS reports, and statutory returns (including GST, PF, ESI, TDS, and other compliances) generated or processed through this platform are either <strong>system-generated or user-initiated self-service outputs</strong>, based entirely on data and credentials provided by me or my clients.  
							All professional judgment, statutory compliance decisions, filing responsibility, certification, and advisory responsibility remain solely with me or the appointed professional.
						</li>

						<li>
							I further acknowledge that the platform does <strong>not</strong> independently file statutory returns, does <strong>not</strong> act as an intermediary or authorized representative before any government or statutory authority, and does <strong>not</strong> assume responsibility for statutory accuracy, correctness, timeliness, or compliance outcomes.
						</li>

						<li>
							I understand that registration on this platform does <strong>not</strong> create any exclusivity, agency, employment, partnership, fiduciary relationship, or transfer of statutory responsibility between myself and the platform.
						</li>

						<li>
							I acknowledge that the listing of professionals on the platform is provided only for <strong>client choice and convenience</strong>.  
							The platform does <strong>not</strong> recommend, certify, guarantee, or assume responsibility for professional services rendered by any listed professional.
						</li>

						<li>
							I also acknowledge that any commercial arrangement such as referral fees, lead-sharing fees, or commissions offered by the platform is purely a <strong>technology promotion and business facilitation arrangement</strong> and does not constitute any endorsement, certification, guarantee, or assumption of professional responsibility by the platform.
						</li>
					</ol>

					<p class="mt-4">
						By clicking <strong>Accept & Continue</strong>, I confirm that I have read, understood, and agree to all the above terms and conditions.
					</p>

				</div>

				<div class="modal-footer">
					<button type="button" id="caCancel" class="btn btn-secondary" data-bs-dismiss="modal">
						Cancel
					</button>

					<button type="button" id="caAccept" class="btn btn-primary">
						Accept & Continue
					</button>
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
	let termsAccepted = false;
	let pendingFormData = null;   // store form until user accepts
	
    document.addEventListener('DOMContentLoaded', function() {
        const togglePasswordElements = document.querySelectorAll('#togglePassword1, #togglePassword2');

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

        let registeredEmail = "";  //------- Global variable -----------
        let registeredName = "";   //------- Global variable -----------
        let formData = {};         //------- Store form data -----------
        let verificationTimer = null; //--- Timer reference -----------

        // Test function to check if elements exist
        function testElements() {
            console.log('Testing elements...');
            console.log('Create account section exists:', $('#create-account-section').length);
            console.log('Verification section exists:', $('#verification-section').length);
            console.log('Success container exists:', $('#success-container').length);
        }

        // Call test function on page load
        testElements();

        // Show Bootstrap alert-style message
        function showMessage(message, type = "danger", duration = 3000) {
            const msgHtml = `<div class="alert alert-${type}" role="alert">${message}</div>`;
            $("#signupform .message-container").html(msgHtml);

            setTimeout(() => {
                $("#signupform .message-container").fadeOut(300, function () {
                    $(this).html("").show();
                });
            }, duration);
        }

        // Function to make all form fields readonly except email
        function makeFieldsReadonly(exceptEmail = false) {
            const formElements = $('#signupform input, #signupform select');
            formElements.each(function() {
                if (exceptEmail && (this.id === 'email')) {
                    $(this).prop('readonly', false).prop('disabled', false);
                } else {
                    $(this).prop('readonly', true).prop('disabled', true);
                }
            });

            // Keep checkbox disabled
            //$('#customCheckc1').prop('disabled', true);
        }

        // Function to make all form fields editable
        function makeFieldsEditable() {
            const formElements = $('#signupform input, #signupform select');
            formElements.prop('readonly', false).prop('disabled', false);
            //$('#customCheckc1').prop('disabled', false);
        }

                // Function to show verification section
        function showVerificationSection() {
            console.log('Showing verification section, hiding create account section');
            console.log('Create account section before:', $('#create-account-section').css('display'));
            console.log('Verification section before:', $('#verification-section').css('display'));

            // Use attr to set inline style directly
            $('#create-account-section').attr('style', 'display: none !important;');
            $('#verification-section').attr('style', 'display: block !important;');

            console.log('Create account section after:', $('#create-account-section').css('display'));
            console.log('Verification section after:', $('#verification-section').css('display'));

            makeFieldsReadonly(true); // Make all readonly except email
        }

        // Function to show create account section
        function showCreateAccountSection() {
            console.log('Showing create account section, hiding verification section');
            $('#verification-section').attr('style', 'display: none !important;');
            $('#create-account-section').attr('style', 'display: block !important;');
            makeFieldsEditable();
        }

        // Function to show success section
        function showSuccessSection(name, email) {
            // Hide all form elements completely
            $('#signupform .row').attr('style', 'display: none !important;'); // Hide all rows containing form fields
            $('#signupform .col-sm-12').attr('style', 'display: none !important;'); // Hide radio buttons section
            $('#signupform .col-sm-6').attr('style', 'display: none !important;'); // Hide name and password fields
            $('#signupform .mb-3').attr('style', 'display: none !important;'); // Hide all form groups
            $('#signupform .d-flex').attr('style', 'display: none !important;'); // Hide terms checkbox
            $('#verification-section').attr('style', 'display: none !important;');
            $('#create-account-section').attr('style', 'display: none !important;');
            $('#success-name').text(name);
            $('#success-email').text(email);
            $('#success-container').attr('style', 'display: block !important;');
        }

        // Function to start countdown timer
        function startCountdownTimer() {
            let timeLeft = 120; // 2 minutes in seconds
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

        // Watch for email changes
        $('#email').on('input', function() {
            const currentEmail = $(this).val();
            if (registeredEmail && currentEmail !== registeredEmail) {
                // Email changed, show create account button again
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

			// ---------------- SAVE FORM ----------------
			pendingFormData = formDataReg;

			// ---------------- SHOW CORRECT MODAL ----------------
			if (userType == "2") {
				$('#termsModal').modal('show');      // Company/User
			} else {
				$('#caTermsModal').modal('show');    // CA Firm
			}
		});
		
		function submitRegistration() 
		{
			if (!pendingFormData) return;

			const submitBtn = $("#create-account-section button");
			const originalText = submitBtn.text();
			submitBtn.prop('disabled', true).text('Creating Account...');

			$.ajax({
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
				},
				url: "/register/user",
				type: "POST",
				data: pendingFormData,
				success: function (response) {
					submitBtn.prop('disabled', false).text(originalText);

					if (response.class === "succ") {
						registeredEmail = pendingFormData.email;
						registeredName = pendingFormData.name;

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

		

		// User Accept button
		$("#userAccept").on("click", function () {
			$('#termsModal').modal('hide');
			submitRegistration();
		});
		
		// CA Firm Accept button
		$("#caAccept").on("click", function () {
			$('#caTermsModal').modal('hide');
			submitRegistration();
		});

		// User Cancel
		$("#userCancel").on("click", function () {
			termsAccepted = false;
		});
		// CA Firm Cancel
		$("#caCancel").on("click", function () {
			termsAccepted = false;
		});


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
