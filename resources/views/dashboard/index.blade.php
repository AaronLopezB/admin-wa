@extends('layouts.app')
@section('title', 'Dashboard')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select.bootstrap5.css') }}">
@endpush
@section('main_content')

<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Reservaciones </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href=""> <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                            </svg></a></li>
                    <li class="breadcrumb-item">Dashboard</li>
                    {{-- <li class="breadcrumb-item active">Default </li> --}}
                </ol>
            </div>
        </div>
    </div>
</div><!-- Container-fluid starts-->
<div class="container-fluid default-dashboard">
    <div class="row widget-grid">
        <div class="col-xl-5 col-md-6 ord-xl-4 ord-md-5 box-ord-4">
            <div class="card profile-box">
                <div class="card-body">
                    <div class="d-flex media-wrapper justify-content-between">
                        <div class="flex-grow-1">
                            <div class="greeting-user">
                                <h2 class="f-w-600">Bienvenido
                                    {{ \Illuminate\Support\Str::title(auth()->user()->name ?? '') }}!</h2>
                                <p>Que tenga un buen día</p>
                                <div class="whatsnew-btn"><a class="btn btn-outline-white" href="{{ route('profile.edit') }}" target="_blank">Ver
                                        Perfil</a></div>
                            </div>
                        </div>
                        <div>
                            <div class="clockbox"><svg id="clock" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 600 600">
                                    <g id="face">
                                        <circle class="circle" cx="300" cy="300" r="253.9"></circle>
                                        <path class="hour-marks"
                                            d="M300.5 94V61M506 300.5h32M300.5 506v33M94 300.5H60M411.3 107.8l7.9-13.8M493 190.2l13-7.4M492.1 411.4l16.5 9.5M411 492.3l8.9 15.3M189 492.3l-9.2 15.9M107.7 411L93 419.5M107.5 189.3l-17.1-9.9M188.1 108.2l-9-15.6">
                                        </path>
                                        <circle class="mid-circle" cx="300" cy="300" r="16.2"></circle>
                                    </g>Here whats happing in your account today
                                    <g id="hour">
                                        <path class="hour-hand" d="M300.5 298V142"></path>
                                        <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                                    </g>
                                    <g id="minute">
                                        <path class="minute-hand" d="M300.5 298V67"> </path>
                                        <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                                    </g>
                                    <g id="second">
                                        <path class="second-hand" d="M300.5 350V55"></path>
                                        <circle class="sizing-box" cx="300" cy="300" r="253.9">
                                        </circle>
                                    </g>
                                </svg></div>
                            <div class="badge f-10 p-0" id="txt"></div>
                        </div>
                    </div>
                    <div class="cartoon"><img class="img-fluid" src="{{ asset('assets/images/dashboard/cartoon.svg') }}"
                            alt="vector women with leptop">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-7 col-lg-8 ord-xl-6 ord-md-6 box-ord-6 box-col-8e">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card compare-order">
                        <div class="card-header card-no-border">
                            <div class="header-top">
                                <div class="compare-icon shadow-primary"><i data-feather="dollar-sign"></i></div>
                                <div class="dropdown icon-dropdown"><button class="btn dropdown-toggle"
                                        id="dealDropdown1" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false"><i class="icon-more-alt"></i></button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dealDropdown1">
                                        <a class="dropdown-item" href="#">Ver Reservaciones</a>
                                        <a class="dropdown-item"href="#">Descargar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0"> <span class="f-w-500 c-o-light">Ventas de Hoy</span>
                            <h4 class="mb-2"> $<span class="counter"
                                    data-target="{{ $grafic['day']['total'] }}">0</span></h4>
                            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="58"
                                aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-primary" style="width: 58%"></div>
                            </div><span class="user-growth f-12 f-w-500">
                                {{-- <i class="icon-arrow-down txt-danger"></i> --}}
                                <span class="txt-danger">{{ $grafic['day']['count'] }}</span></span><span
                                class="user-text">last
                                month</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card compare-order">
                        <div class="card-header card-no-border">
                            <div class="header-top">
                                <div class="compare-icon shadow-primary"><i data-feather="dollar-sign"></i></div>
                                <div class="dropdown icon-dropdown"><button class="btn dropdown-toggle"
                                        id="dealDropdown1" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false"><i class="icon-more-alt"></i></button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dealDropdown1">
                                        <a class="dropdown-item" href="#">Ver Reservaciones</a>
                                        <a class="dropdown-item"href="#">Descargar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0"> <span class="f-w-500 c-o-light">Ventas de ayer</span>
                            <h4 class="mb-2"> $<span class="counter"
                                    data-target="{{ $grafic['yesterday']['total'] }}">0</span></h4>
                            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="58"
                                aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-primary" style="width: 58%"></div>
                            </div><span class="user-growth f-12 f-w-500">
                                {{-- <i class="icon-arrow-down txt-danger"></i> --}}
                                <span class="txt-danger">{{ $grafic['yesterday']['count'] }}</span></span><span
                                class="user-text">ventas</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card compare-order">
                        <div class="card-header card-no-border">
                            <div class="header-top">
                                <div class="compare-icon shadow-primary"><i data-feather="dollar-sign"></i></div>
                                <div class="dropdown icon-dropdown">
                                    <button class="btn dropdown-toggle" id="dealDropdown1" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false"><i
                                            class="icon-more-alt"></i></button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dealDropdown1">
                                        <a class="dropdown-item" href="#">Ver Reservaciones</a>
                                        <a class="dropdown-item"href="#">Descargar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0"> <span class="f-w-500 c-o-light">Ventas del mes</span>
                            <h4 class="mb-2"> $<span class="counter"
                                    data-target="{{ $grafic['week']['total'] }}">0</span></h4>
                            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="58"
                                aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-primary" style="width: 58%"></div>
                            </div><span class="user-growth f-12 f-w-500">
                                {{-- <i class="icon-arrow-down txt-danger"></i> --}}
                                <span class="txt-danger">{{ $grafic['week']['count'] }}</span>
                            </span><span class="user-text">ventas</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <livewire:dash-board.dashboard />

    </div>
</div><!-- Container-fluid Ends-->

@endsection

@push('scripts')
<script>
    startTime();
    function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        // var s = today.getSeconds();
        var ampm = h >= 12 ? "PM" : "AM";
        h = h % 12;
        h = h ? h : 12;
        m = checkTime(m);
        // s = checkTime(s);
        document.getElementById("txt").innerHTML = h + ":" + m + " " + ampm;
        var t = setTimeout(startTime, 500);
        }
        function checkTime(i) {
        if (i < 10) {
            i = "0" + i;
        } // add zero in front of numbers < 10
        return i;
    }
</script>

    <script  src="{{ asset('assets/js/clock.js') }}" ></script>

    <script  src="{{ asset('assets/js/counter/counter-custom.js') }}"   ></script>
    <script  src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"   ></script>
    {{-- <script  src="{{ asset('assets/js/dashboard/default.js') }}"   ></script> --}}
    <script  src="{{ asset('assets/js/notify/index.js') }}"   ></script>
    {{-- <script  src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"   ></script>
    <script  src="{{ asset('assets/js/datatable/datatables/dataTables.js') }}"   ></script>
    <script  src="{{ asset('assets/js/datatable/datatables/dataTables.select.js') }}"   ></script>
    <script  src="{{ asset('assets/js/datatable/datatables/select.bootstrap5.js') }}"  ></script>
    <script  src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"   ></script> --}}
    <script  src="{{ asset('assets/js/typeahead/handlebars.js') }}"   ></script>
    <script  src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"   ></script>
    <script  src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"  ></script>
    <script  src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"   ></script>
    <script  src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"   ></script>
    <script  src="{{  asset('assets/js/flat-pickr/flatpickr.js')}}"  ></script>

    {{-- <script  src="{{  asset('assets/js/flat-pickr/custom-flatpickr.js')}}"  ></script> --}}
@endpush
