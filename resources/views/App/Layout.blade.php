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
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

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
    <link rel="stylesheet" href="{{ asset('assets/css/custom-css.css') }}">
    <!-- Intro.js Tour CSS & Custom Style -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/introjs.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tour.css') }}">

    <!-- Ajax File-->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <input type="hidden" id="base_url" value="{{ URL::to('/') }}">
</head>

<!-- [Head] end -->

<body data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-caption="true" data-pc-direction="ltr"
    data-pc-theme="light">

    @include('App.Sidebar')

    <!-- Subscription Reminder -->
    @include('partials.subscription-reminder')

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
    <!-- Intro.js Tour JS & Custom Script -->
    <script src="{{ asset('assets/js/plugins/intro.min.js') }}"></script>
    <script src="{{ asset('assets/js/tour.js') }}"></script>

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



    <!-- Page specific scripts -->
    @yield('page-script')

    @include('App.Footer')
    <div id="loader" class="loader2" style="display:none;"></div>
</body>

</html>
