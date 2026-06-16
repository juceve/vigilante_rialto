<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') | Propietarios</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_shield.png') }}">

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#1e3a8a">

    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('images/logo_shield120x120.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/logo_shield152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo_shield180x180.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">


    <!-- Bootstrap -->
    <link href="{{ asset('customers/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" />
    <!-- NProgress -->
    <link href="{{ asset('customers/vendors/nprogress/nprogress.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('customers/build/css/custom.min.css') }}" rel="stylesheet">

    @yield('css')
    @livewireStyles
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="/" class="site_title">
                            <img class="img-profile rounded-circle" src="{{ asset('images/logo_shield.png') }}"
                                style="width: 40px;">
                            <span>{{ config('app.name') }}</span>
                        </a>
                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <ul class="nav side-menu">
                                <li>
                                    <a href="/home" class="nav-link">

                                        <i class="fas fa-tachometer-alt"></i>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/propietarios/mis-residencias" class="nav-link">

                                        <i class="fas fa-home"></i>

                                        <span>Mis Residencias</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/propietarios/pases" class="nav-link">

                                        <i class="fas fa-ticket-alt"></i>

                                        <span>Pases</span>
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>
                    <!-- /sidebar menu -->
                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                    <nav class="nav navbar-nav">
                        <ul class=" navbar-right">
                            <li class="nav-item dropdown open" style="padding-left: 15px;">
                                <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true"
                                    id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                                    <strong>Cliente: </strong>{{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-usermenu pull-right"
                                    aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"><i
                                            class="fa fa-sign-out pull-right"></i>
                                        Cerrar Sesión</a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>


                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->

            <div class="right_col" role="main">

                <div class="container-fluid">
                    <div class="mb-3" style="margin-top: 60px">
                        <h4>@yield('header-title')</h4>
                    </div>

                    @yield('content')
                </div>

            </div>
            <!-- /page content -->

            <!-- footer content -->
            <footer>
                <div class="pull-right">

                </div>
                <div class="clearfix"></div>
            </footer>
            <!-- /footer content -->
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('customers/vendors/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('customers/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('customers/vendors/fastclick/lib/fastclick.js') }}"></script>
    <!-- NProgress -->
    <script src="{{ asset('customers/vendors/nprogress/nprogress.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom Theme Scripts -->
    <script src="{{ asset('customers/build/js/custom.js') }}"></script>
    @livewireScripts
    <script>
        Livewire.on('success', message => {
            // Swal.fire('Excelente!',message,'success');
            Swal.fire({
                icon: 'success',
                title: 'Excelente',
                text: message,
            })
        });
        Livewire.on('error', message => {
            Swal.fire('Error!', message, 'error');

        });
        Livewire.on('warning', message => {
            Swal.fire('Atención!', message, 'warning');
        });
    </script>
    @yield('js')
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(reg => console.log('Service Worker registrado:', reg))
                    .catch(err => console.error('Error al registrar SW:', err));
            });
        }
    </script>
</body>

</html>
