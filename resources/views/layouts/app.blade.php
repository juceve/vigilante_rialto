<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>@yield('title') | {{ strtoupper(config('app.name')) }}</title>

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#1e3a8a">

    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('images/logo_shield120x120.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/logo_shield152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo_shield180x180.png') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_shield.png') }}" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet"
        type="text/css" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
        integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('web/css/styles.css') }}" rel="stylesheet" />
    @yield('css')
    @stack('styles')

    <!-- Material Design Styles -->
    <style>
        /* Forzar modo claro permanente */
        :root {
            color-scheme: light only !important;
            supported-color-schemes: light !important;
        }

        * {
            color-scheme: light !important;
        }

        html,
        body {
            background-color: #f8fafc !important;
            color: #1e293b !important;
            color-scheme: light !important;
        }

        /* Variables CSS Material Design - Paleta Empresarial de Seguridad */
        :root {
            --primary-color: #1e3a8a;
            --primary-dark: #1e293b;
            --primary-light: #3b82f6;
            --secondary-color: #334155;
            --secondary-dark: #1e293b;
            --accent-color: #d97706;
            --accent-light: #f59e0b;
            --success-color: #059669;
            --warning-color: #d97706;
            --error-color: #dc2626;
            --surface-color: #ffffff;
            --background-color: #f8fafc;
            --card-background: #ffffff;
            --on-surface: #1e293b;
            --on-primary: #ffffff;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --shadow-1: 0 1px 3px rgba(30, 41, 59, 0.12), 0 1px 2px rgba(30, 41, 59, 0.24);
            --shadow-2: 0 3px 6px rgba(30, 41, 59, 0.16), 0 3px 6px rgba(30, 41, 59, 0.23);
            --shadow-3: 0 10px 20px rgba(30, 41, 59, 0.19), 0 6px 6px rgba(30, 41, 59, 0.23);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Body y Typography */
        body {
            font-family: 'Montserrat', 'Roboto', sans-serif !important;
            background-color: var(--background-color) !important;
            color: var(--on-surface) !important;
            line-height: 1.6;
        }

        /* Material Navbar */
        .material-navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)) !important;
            box-shadow: var(--shadow-2);
            backdrop-filter: blur(10px);
            border: none;
            padding: 0.8rem 0;
            transition: var(--transition);
        }

        .material-navbar.scrolled {
            padding: 0.5rem 0;
            box-shadow: var(--shadow-3);
        }

        /* Brand Container */
        .brand-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .brand-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--accent-color), var(--accent-light));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
        }

        .brand-icon:hover {
            background: linear-gradient(135deg, var(--accent-light), var(--accent-color));
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(217, 119, 6, 0.4);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: white;
            line-height: 1;
            letter-spacing: 0.5px;
        }

        .brand-subtitle {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 400;
            margin-top: 0.2rem;
        }

        .material-brand {
            text-decoration: none;
            transition: var(--transition);
        }

        .material-brand:hover {
            text-decoration: none;
        }

        /* Material Toggler */
        .material-toggler {
            border: none;
            background: none;
            padding: 0.5rem;
            border-radius: 8px;
            transition: var(--transition);
        }

        .material-toggler:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .material-toggler:focus {
            box-shadow: none;
        }

        .toggler-icon {
            display: flex;
            flex-direction: column;
            width: 24px;
            height: 18px;
            justify-content: space-between;
        }

        .toggler-icon span {
            width: 100%;
            height: 2px;
            background: white;
            border-radius: 2px;
            transition: var(--transition);
        }

        .material-toggler[aria-expanded="true"] .toggler-icon span:first-child {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .material-toggler[aria-expanded="true"] .toggler-icon span:nth-child(2) {
            opacity: 0;
        }

        .material-toggler[aria-expanded="true"] .toggler-icon span:last-child {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Material Nav Links */
        .material-nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.8rem 1.5rem !important;
            border-radius: var(--border-radius);
            transition: var(--transition);
            display: flex;
            align-items: center;
            margin: 0 0.25rem;
            text-decoration: none;
        }

        .material-nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white !important;
            transform: translateY(-1px);
        }

        .material-nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white !important;
        }

        .logout-link {
            background: linear-gradient(135deg, var(--error-color), #2878d4);
            color: white !important;
            margin-left: 0.5rem;
            box-shadow: 0 3px 10px rgba(220, 38, 38, 0.3);
        }

        .logout-link:hover {
            background: linear-gradient(135deg, #216f94, #216f94);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 38, 38, 0.4);
        }

        /* Main Content */
        .main-content {
            margin-top: 90px;
            min-height: calc(100vh - 90px);
            background: var(--background-color);
        }

        /* Responsive Design */
        @media (max-width: 991.98px) {
            .brand-text {
                display: none;
            }

            .brand-container {
                gap: 0;
            }

            .material-navbar {
                padding: 0.6rem 0;
            }

            .main-content {
                margin-top: 76px;
            }

            .navbar-collapse {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: var(--border-radius);
                margin-top: 1rem;
                padding: 1rem;
                box-shadow: var(--shadow-2);
            }

            .material-nav-link {
                color: var(--on-surface) !important;
                padding: 1rem !important;
                margin: 0.25rem 0;
            }

            .material-nav-link:hover {
                background: var(--primary-color);
                color: white !important;
            }

            .logout-link {
                margin-left: 0;
                margin-top: 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .brand-name {
                font-size: 1rem;
            }

            .material-navbar {
                padding: 0.5rem 0;
            }

            .main-content {
                margin-top: 70px;
            }
        }

        /* Scroll Behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Loading States */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        /* Focus States for Accessibility */
        .material-nav-link:focus,
        .material-brand:focus,
        .material-toggler:focus {
            outline: 2px solid rgba(255, 255, 255, 0.5);
            outline-offset: 2px;
        }

        /* SweetAlert2 Material Design */
        .material-popup {
            border-radius: 16px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2) !important;
            border: none !important;
        }

        .material-title {
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 600 !important;
            font-size: 1.5rem !important;
            margin-bottom: 0.5rem !important;
        }

        .material-content {
            font-family: 'Montserrat', sans-serif !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
        }

        .material-button {
            background-color: #1abc9c;
            color: white;
            border-radius: 8px !important;
            padding: 0.75rem 2rem !important;
            font-weight: 600 !important;
            font-family: 'Montserrat', sans-serif !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
        }

        .material-button:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2) !important;
        }

        /* DataTables Material Design */
        .dataTables_wrapper {
            font-family: 'Montserrat', sans-serif;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.5rem;
            font-family: 'Montserrat', sans-serif;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px;
            margin: 0 2px;
            transition: var(--transition);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
            color: white !important;
        }
    </style>

    @livewireStyles
    @yield('recaptcha')
    @stack('head')
</head>

<body id="page-top">
    <!-- Navigation Material Design -->
    <nav class="navbar navbar-expand-lg material-navbar fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand material-brand" href="{{ route('home') }}">
                <div class="brand-container">
                    <div class="brand-icon">
                        <img src="{{ asset('images/logo_shield.png') }}" alt="logo" width="40" height="44">

                    </div>

                    <div class="brand-text">
                        <span class="brand-name">{{ strtoupper(config('app.name')) }}</span>
                        <small class="brand-subtitle">Sistema de Vigilancia</small>
                    </div>
                </div>
            </a>

            @livewire('vigilancia.ronda-en-progreso')
            @livewire('vigilancia.alerta-hv')

            @auth
                <button class="navbar-toggler material-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="toggler-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link material-nav-link" href="{{ route('home') }}">
                                <i class="fas fa-home me-2"></i>
                                <span>Inicio</span>
                            </a>
                        </li>
                        {{-- @if (session('cliente_id-oper')) --}}
                        <li class="nav-item">
                            <a class="nav-link material-nav-link" href="{{ route('vigilancia.profile') }}">
                                <i class="fas fa-user me-2"></i>
                                <span>Mi Perfil</span>
                            </a>
                        </li>
                        {{-- @endif --}}
                        @if (session('designacion-super'))
                            @if (session('designacion-control') == 1)
                                <li class="nav-item">
                                    <a class="nav-link material-nav-link" href="{{ route('supervisores.cajachica') }}">
                                        <i class="fas fa-wallet"></i> &nbsp;
                                        <span>Caja Chica</span>
                                    </a>
                                </li>
                            @endif
                        @endif

                        <li class="nav-item">
                            <a href="{{ route('logout') }}" class="nav-link material-nav-link logout-link"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                <span>Cerrar Sesión</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="main-content">

        @yield('content')
    </main>


    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="{{ asset('web/js/scripts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{--
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script> --}}
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
        integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    @livewireScripts
    @yield('js')

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Excelente',
                text: '{{ session('success') }}',
                showConfirmButton: true,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#059669',
                background: '#ffffff',
                color: '#1e293b',
                borderRadius: '12px',
                customClass: {
                    popup: 'material-popup',
                    title: 'material-title',
                    confirmButton: 'material-button'
                }
            })
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                showConfirmButton: true,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#dc2626',
                background: '#ffffff',
                color: '#1e293b',
                borderRadius: '12px',
                customClass: {
                    popup: 'material-popup',
                    title: 'material-title',
                    confirmButton: 'material-button'
                }
            })
        </script>
    @endif

    @if (session('warning'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: '{{ session('warning') }}',
                showConfirmButton: true,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#d97706',
                background: '#ffffff',
                color: '#1e293b',
                borderRadius: '12px',
                customClass: {
                    popup: 'material-popup',
                    title: 'material-title',
                    confirmButton: 'material-button'
                }
            })
        </script>
    @endif

    <script>
        // Material Design Configuration for DataTables and Livewire
        Livewire.on('dataTableRender', () => {
            $(".dataTable").dataTable({
                "destroy": true,
                order: [
                    [0, 'desc']
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                },
                // Material Design styling for DataTables
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                pagingType: 'simple_numbers',
                responsive: true
            })
        });

        // Material Design SweetAlert2 configurations
        const materialSwalConfig = {
            background: '#ffffff',
            color: '#333333',
            showConfirmButton: true,
            confirmButtonText: 'Entendido',
            customClass: {
                popup: 'material-popup',
                title: 'material-title',
                confirmButton: 'material-button',
                content: 'material-content'
            },
            buttonsStyling: false
        };

        Livewire.on('success', message => {
            Swal.fire({
                ...materialSwalConfig,
                icon: 'success',
                title: 'Excelente',
                text: message,
                confirmButtonColor: '#059669',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        });

        Livewire.on('error', message => {
            Swal.fire({
                ...materialSwalConfig,
                icon: 'error',
                title: 'Error',
                text: message,
                confirmButtonColor: '#dc2626'
            });
        });

        Livewire.on('warning', message => {
            Swal.fire({
                ...materialSwalConfig,
                icon: 'warning',
                title: 'Atención',
                text: message,
                confirmButtonColor: '#1abc9c'
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.material-navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth transitions on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.3s ease-in-out';

            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
        });
    </script>

    @stack('scripts')
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(reg => console.log('Loaded App'))
                    .catch(err => console.error('0'));
            });
        }
    </script>
    @yield('js2')

    @if (session('designacion-super'))

    @endif
</body>

</html>
