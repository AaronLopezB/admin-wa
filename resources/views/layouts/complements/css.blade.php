    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fontawesome.css') }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/icofont.css') }}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/themify.css') }}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/flag-icon.css') }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/feather-icon.css') }}">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/slick-theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/scrollbar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/flatpickr/flatpickr.min.css')}}">
    <!-- Plugins css Ends-->
    @stack('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/toastr.min.css')}}">
    <style>
        .freeze-ui4 {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            background-color: rgba(255, 255, 255, .8);
        }
        .freeze-ui4:before {
            content: attr(data-text);
            display: block;
            max-width: 125px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #343a40;
            text-align: center;
        }
        .freeze-ui4:after {
            content: '';
            display: block;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border-width: 2px;
            border-style: solid;
            border-color: transparent var(--recent-dashed-border) var(--recent-dashed-border) var(--recent-dashed-border);
            position: absolute;
            top: 50%;
            left: 50%;
            animation: load .85s infinite linear;
        }
    </style>
    @livewireStyles
