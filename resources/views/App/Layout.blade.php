<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Meta Data -->
  <meta charset="utf-8" lang="eng" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="E-Cashbook - Intelligent Accounting & GST Management Platform for Chartered Accountants, Tax Professionals, and Small Businesses. Professional financial management for the digital age." />
  <meta name="author" content="ClicknGo Tech Service Pvt. Ltd." />
  <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="robots" content="noindex, nofollow">
	<meta name="googlebot" content="noindex">
	<meta name="bingbot" content="noindex">
	<title>E-Cashbook OneSuite | Intelligent Accounting, Tax & Compliance Portal</title>

  <!-- [Favicon] icon -->
  <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon" />

  <!-- [Google Font : Public Sans] - High Priority -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <!-- Core CSS files - High Priority -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
  <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">

  <!-- Icons - Medium Priority -->
  <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

  <!-- Plugin CSS - Medium Priority -->
  <link rel="stylesheet" href="{{ asset('assets/css/plugins/jsvectormap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

  <!-- Third-party libraries - Lower Priority -->
  <link href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/dropzone.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

  <!-- Custom CSS - Lower Priority -->
  <link rel="stylesheet" href="{{asset('assets/css/custom-css.css') }}">
  <!-- Ajax File-->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

  <input type="hidden" id="base_url" value="{{URL::to('/')}}">
</head>

<!-- [Head] end -->

<body data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-caption="true" data-pc-direction="ltr" data-pc-theme="light">

  @include('App.Sidebar')

  <!-- Subscription Reminder Banner -->
  @if(Auth::check() && Auth::user()->u_type == 2)
  <!-- Subscription Reminder Modal -->
	<div class="modal fade" id="subscriptionModal" tabindex="-1" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content" style="border-radius: 12px; overflow: hidden;">

		  <!-- Header -->
		  <div style="background: #FF2C55; padding: 22px; color: #fff;">
			<div class="d-flex align-items-center gap-3">
			  <div style="background-color: rgba(255,255,255,0.15); border-radius: 8px; width: 48px; height: 48px;"
				   class="d-flex align-items-center justify-content-center">
				<i class="ti ti-alarm fs-4 text-white"></i>
			  </div>
			  <div>
				<h4 class="mb-0 fw-semibold text-white">Subscription Expiring Soon</h4>
				<p class="mb-0 opacity-75 small text-white">
				  Your free trial period is ending
				</p>
			  </div>
			</div>
		  </div>

		  <!-- Body -->
		  <div class="modal-body p-4">

			<!-- Days Left -->
			<div class="d-flex align-items-center mb-3 p-3 rounded"
				 style="background-color:#fff5f7;border-left:4px solid #FF2C55;">
			  <div class="me-3 d-flex align-items-center justify-content-center"
				   style="width:60px;height:60px;background:#ffe5ea;border-radius:8px;">
				<span class="fs-4 fw-bold text-danger" id="days-left">0</span>
			  </div>
			  <div>
				<h6 class="mb-1 fw-semibold">Days Remaining in Trial</h6>
				<p class="mb-0 small text-muted">
				  Access to premium features will be limited after trial expiration
				</p>
			  </div>
			</div>

			<!-- Benefits -->
			<div class="p-3 rounded bg-light mb-4">
			  <h6 class="fw-semibold mb-2">
				<i class="ti ti-check-circle text-danger me-1"></i>
				Benefits of Premium Subscription
			  </h6>
			  <ul class="list-unstyled small mb-0">
				<li><i class="ti ti-check text-danger me-1"></i> Unlimited access to all financial tools</li>
				<li><i class="ti ti-check text-danger me-1"></i> Priority customer support</li>
				<li><i class="ti ti-check text-danger me-1"></i> Advanced reporting features</li>
				<li><i class="ti ti-check text-danger me-1"></i> Data export capabilities</li>
			  </ul>
			</div>

			<!-- Actions -->
			<div class="d-flex gap-2">
			  <button id="close-reminder" class="btn btn-light flex-fill" data-bs-dismiss="modal">Remind Me Later</button>
			  <a href="{{ route('user.Plans') }}" class="btn flex-fill text-white" style="background:#FF2C55;">View Plans</a>
			</div>

		  </div>
		</div>
	  </div>
	</div>
	
	<div class="modal fade" id="expiredModal" tabindex="-1">
	  <div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content" style="border-radius:12px">

		  <div style="background:#111;padding:22px;color:#fff">
			<p class="mb-0 opacity-75">Subscription Expired</p>
		  </div>

		  <div class="modal-body p-4 text-center">
			<h5 class="mb-3 text-danger">
			  Your access is now limited
			</h5>
			<p class="text-muted">
			  Please upgrade your plan to continue using all features.
			</p>

			<div class="d-flex gap-2">
			  <button id="close-reminder" class="btn btn-light flex-fill" data-bs-dismiss="modal">Remind Me Later</button>
			  <a href="{{ route('user.Plans') }}" class="btn flex-fill text-white" style="background:#FF2C55;">View Plans</a>
			</div>
		  </div>

		</div>
	  </div>
	</div>
  @endif

  @include('App.Header')
  @include('partials.chat-modal')
  
  <div class="pc-container">
    @yield('container')
  </div>

  <!-- Core JS - Highest Priority -->

  <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>

  <!-- Essential Framework JS - High Priority -->
  <script src="{{ asset('assets/js/pcoded.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
  <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>

  <!-- Datatable JS - High Priority -->
  <script src="{{ asset('assets/js/plugins/simple-datatables.js') }}"></script>
  <script src="{{ asset('assets/js/datatable.js') }}"></script>

  <!-- Chart and Visualization - Medium Priority -->
  <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/js/pages/w-chart.js') }}"></script>
  <script src="{{ asset('assets/js/charts_custom.js') }}"></script>

  <!-- Form Validation - Medium Priority -->
  <script src="{{ asset('assets/js/jquery.validate.js') }}"></script>

  <!-- UI Enhancements - Medium Priority -->
  <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

  <!-- Additional Functionality - Lower Priority -->
  <script src="{{ asset('assets/js/plugins/index.global.min.js') }}"></script>
  <script src="{{ asset('assets/js/pages/calendar.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/wizard.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/uppy.min.js') }}"></script>

  <!-- XLSX Library for Excel Export -->
  <script src="https://cdn.sheetjs.com/xlsx-0.19.3/package/dist/xlsx.full.min.js"></script>

  <!-- Custom JS - Low Priority -->
  <script src="{{ asset('assets/js/message.js') }}?v=1.1"></script>
  <script src="{{ asset('assets/js/ca.js') }}?v=1.1"></script>
  <script src="{{ asset('assets/js/tickets.js') }}?v=1.1"></script>
  <script src="{{ asset('assets/js/custom.js') }}?v=1.1"></script>
  <script src="{{ asset('assets/js/ca-comp-chat.js') }}?v=1.1"></script>

  <!-- Subscription Reminder Script -->
  @if(Auth::check() && Auth::user()->u_type == 2)
  <script>
	document.addEventListener('DOMContentLoaded', function () {
		const ACCESS_TYPE = "{{ $accessType ?? '' }}";
		const TRIAL_DAYS = {{ (int) ($trialDaysLeft ?? 0) }};

		const today = new Date().toDateString();
		const lastShown = localStorage.getItem('subscription_popup_last');

		// ========== TRIAL ACTIVE ==========
		if (ACCESS_TYPE === 'trial' && TRIAL_DAYS > 0) {
			if(lastShown !== today) {

				document.getElementById('days-left').innerText = TRIAL_DAYS;

				const modal = new bootstrap.Modal(
					document.getElementById('subscriptionModal'),
					{ backdrop: 'static', keyboard: false }
				);

				modal.show();
				localStorage.setItem('subscription_popup_last', today);
			}
		}

		// ========== TRIAL EXPIRED ==========
		if (ACCESS_TYPE === 'expired') {
				if(lastShown !== today) {
				const modal = new bootstrap.Modal(
					document.getElementById('expiredModal'),
					{ backdrop: 'static', keyboard: false }
				);

				modal.show();
				localStorage.setItem('subscription_popup_last', today);
			}
		}
	});

    $(document).ready(function() {		

    });
  </script>
  @endif

  <!-- Page specific scripts -->
  @yield('page-script')

  @include('App.Footer')
  <div id="loader" class="loader2" style="display:none;"></div>
</body>

</html>
