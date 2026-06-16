<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_shield.png') }}">
    <title>@yield('title', 'Registros')</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Fuente moderna --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
        }

        .navbar {
            background: linear-gradient(90deg, #0d6efd, #0dcaf0);
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            background: #0d6efd;
            border: none;
        }

        .btn-success {
            background: #198754;
            border: none;
        }

        footer {
            margin-top: 2rem;
            padding: 1rem 0;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>

    @livewireStyles
</head>

<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <div class="d-flex justify-content-center align-items-center">
                    <img src="{{ asset('images/logo_shield.png') }}" alt="Logo" style="width: 40px">
                    {{ config('app.name') }}
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                {{-- <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="#">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Propietarios</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Residencias</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Configuración</a></li>
                </ul> --}}
            </div>
        </div>
    </nav>

    {{-- Contenido principal --}}
    <main class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                @yield('content')
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer>
        <div class="container">
            <p class="mb-0">© {{ date('Y') }} Mi Condominio — Todos los derechos reservados</p>
        </div>
    </footer>
    @livewireScripts
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        Livewire.on('toast-success', msg => {
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: msg,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
        Livewire.on('toast-warning', msg => {
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'warning',
                title: msg,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
        Livewire.on('toast-error', msg => {
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'error',
                title: msg,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
</body>

</html>
