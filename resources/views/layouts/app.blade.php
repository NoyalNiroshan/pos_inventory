<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8" />
        <title>Dashboard | Minia - Minimal Admin & Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">

        <!-- plugin css -->
        <link href="{{asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />

        {{-- <!-- preloader css -->
        <link rel="stylesheet" href="{{asset('assets/css/preloader.min.css')}}" type="text/css" /> --}}

        <!-- Bootstrap Css -->
        <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

    </head>
<body>
<!-- Begin page -->
<div id="layout-wrapper">
    @include('layouts.partials.header')

    <div class="vertical-menu">
        @include('layouts.partials.sidebar')
    </div>
    <!-- Left Sidebar End -->

    <div class="main-content">
        @yield('content')
    </div>
    <!-- end main content-->

      <!-- Footer Section -->
      @include('layouts.partials.footer')
</div>
<!-- END layout-wrapper -->

<!-- Right Sidebar -->
@include('layouts.partials.rightbar')
<!-- /Right-bar -->

<!-- Right bar overlay-->
<div class="rightbar-overlay"></div><!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

<!-- JAVASCRIPT -->
<script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
<script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>
<script src="{{asset('assets/libs/feather-icons/feather.min.js')}}"></script>
<!-- pace js -->
<script src="{{asset('assets/libs/pace-js/pace.min.js')}}"></script>

<!-- apexcharts -->
<script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>

<!-- Plugins js-->
<script src="{{asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- dashboard init -->
<script src="{{asset('assets/js/pages/dashboard.init.js')}}"></script>

<script src="{{asset('assets/js/app.js')}}"></script>

</body>


<!-- Mirrored from themesbrand.com/minia/layouts/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 23 Aug 2024 06:39:38 GMT -->
</html>

