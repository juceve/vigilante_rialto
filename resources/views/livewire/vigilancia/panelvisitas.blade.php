{{-- filepath: c:\laragon\www\vigilantev2\resources\views\livewire\vigilancia\panelvisitas.blade.php --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styleVigilancia.css') }}">
@endpush

<div>
    @section('title')
        VISITAS
    @endsection

    <!-- Header Corporativo -->
    <div class="patrol-header">
        <div class="container">
            <div class="header-navigation">
                <a href="{{ route('home') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="header-title">
                    <h1 class="title-text">CONTROL DE VISITAS</h1>
                    <p class="subtitle-text">Registro electrónico de ingresos y salidas</p>
                </div>
                <div class="header-status">
                    <div class="status-indicator"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="patrol-content">
        <!-- Logo Corporativo -->
        <div class="company-logo">
            <img src="{{ asset(config('adminlte.auth_logo.img.path')) }}" alt="Logo" class="logo-image">
        </div>

        <!-- Información de Asignación -->
        <div class="assignment-card">
            <div class="assignment-header">
                <i class="fas fa-building"></i>
                <span>Cliente Asignado</span>
            </div>
            <div class="assignment-body">
                <div class="assignment-item">
                    <div class="assignment-label">Ubicación</div>
                    <div class="assignment-value">{{ $designacion->turno->cliente->nombre }}</div>
                </div>
            </div>
        </div>

        <!-- Panel de Control de Visitas -->
        <div class="visits-control-panel">
            <div class="row g-4">
                <!-- Botón de Ingresos -->
                <div class="col-md-6">
                    <div class="visit-action-card ingreso-card">
                        <div class="action-card-header">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>INGRESOS</span>
                        </div>
                        <div class="action-card-body">
                            <div class="counter-section">
                                <span class="counter-label">Ingresos Hoy</span>
                                <div class="counter-value">{{$visitas->count()}}</div>
                            </div>
                            <a href="{{route('vigilancia.regingreso', $designacion->id)}}"
                                class="action-button ingreso-btn">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>REGISTRAR INGRESO</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Botón de Salidas -->
                <div class="col-md-6">
                    <div class="visit-action-card salida-card">
                        <div class="action-card-header">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>SALIDAS</span>
                        </div>
                        <div class="action-card-body">
                            <div class="counter-section">
                                <span class="counter-label">Salidas Hoy</span>
                                <div class="counter-value">{{$visitas->where('estado', 0)->count()}}</div>
                            </div>
                            <a href="{{route('vigilancia.regsalida', $designacion->id)}}"
                                class="action-button salida-btn">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>REGISTRAR SALIDA</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Cierre patrol-content -->
</div>

@section('css')
    <style>
        /* Variables CSS - Paleta Empresarial de Seguridad */
        :root {
            --primary-color: #1e3a8a;
            /* Azul naval profundo */
            --primary-dark: #1e293b;
            /* Azul oscuro casi negro */
            --primary-light: #3b82f6;
            /* Azul corporativo */
            --secondary-color: #334155;
            /* Gris azulado */
            --secondary-dark: #1e293b;
            /* Gris oscuro */
            --accent-color: #d97706;
            /* Dorado corporativo */
            --accent-light: #f59e0b;
            /* Dorado claro */
            --success-color: #059669;
            /* Verde profesional */
            --warning-color: #d97706;
            /* Naranja dorado */
            --error-color: #dc2626;
            /* Rojo corporativo */
            --info-color: #0891b2;
            /* Azul información */
            --surface-color: #ffffff;
            --background-color: #f8fafc;
            /* Gris muy claro */
            --on-surface: #1e293b;
            /* Texto principal */
            --text-secondary: #64748b;
            /* Texto secundario */
            --shadow-light: 0 2px 8px rgba(30, 41, 59, 0.1);
            --shadow-medium: 0 4px 16px rgba(30, 41, 59, 0.15);
            --shadow-heavy: 0 8px 24px rgba(30, 41, 59, 0.2);
            --border-radius: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Forzar modo claro permanente */
        * {
            color-scheme: light !important;
        }

        html,
        body {
            background-color: #f8fafc !important;
            color: #1e293b !important;
        }

        /* Estilos Generales */
        body {
            background-color: var(--background-color);
            font-family: 'Montserrat', sans-serif;
        }

        /* Header Corporativo */
        .patrol-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-medium);
        }

        .header-navigation {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 1rem 1.5rem;
            box-shadow: var(--shadow-light);
        }

        .back-button {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--accent-color), var(--accent-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            transition: var(--transition);
            box-shadow: var(--shadow-light);
        }

        .back-button:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow-medium);
            color: white;
        }

        .header-title {
            text-align: center;
            flex: 1;
            margin: 0 1rem;
        }

        .title-text {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--on-surface);
            margin: 0;
            letter-spacing: 0.5px;
        }

        .subtitle-text {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin: 0.2rem 0 0 0;
            font-weight: 500;
        }

        .header-status {
            width: 50px;
            display: flex;
            justify-content: center;
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            background: var(--success-color);
            border-radius: 50%;
            animation: pulse-patrol 2s infinite;
        }

        /* Contenido Principal */
        .patrol-content {
            padding: 0 1rem;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Logo Corporativo */
        .company-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-image {
            width: 120px;
            height: auto;
            filter: drop-shadow(var(--shadow-light));
        }

        /* Card de Asignación */
        .assignment-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            overflow: hidden;
            margin-bottom: 2rem;
            border: 2px solid var(--primary-color);
        }

        .assignment-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 1rem;
        }

        .assignment-header i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }

        .assignment-body {
            padding: 1.5rem;
        }

        .assignment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
        }

        .assignment-label {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .assignment-value {
            font-weight: 700;
            color: var(--on-surface);
            font-size: 1rem;
            text-align: right;
        }

        /* Panel de Control de Visitas */
        .visits-control-panel {
            margin-top: 2rem;
        }

        .visit-action-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            overflow: hidden;
            transition: var(--transition);
            border: 2px solid transparent;
            height: 100%;
        }

        .visit-action-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-medium);
        }

        .ingreso-card {
            border-color: var(--success-color);
        }

        .salida-card {
            border-color: var(--error-color);
        }

        .action-card-header {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 1rem;
            color: white;
        }

        .ingreso-card .action-card-header {
            background: linear-gradient(135deg, var(--success-color), #10b981);
        }

        .salida-card .action-card-header {
            background: linear-gradient(135deg, var(--error-color), #ef4444);
        }

        .action-card-header i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }

        .action-card-body {
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .counter-section {
            margin-bottom: 2rem;
        }

        .counter-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .counter-value {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1;
            padding: 1rem;
            background: var(--background-color);
            border-radius: calc(var(--border-radius) / 2);
            border: 2px solid #e2e8f0;
            min-width: 100px;
        }

        .ingreso-card .counter-value {
            color: var(--success-color);
            border-color: var(--success-color);
            background: #f0fdf4;
        }

        .salida-card .counter-value {
            color: var(--error-color);
            border-color: var(--error-color);
            background: #fef2f2;
        }

        .action-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1rem 2rem;
            border-radius: calc(var(--border-radius) / 2);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            transition: var(--transition);
            box-shadow: var(--shadow-light);
            width: 100%;
            color: white;
            letter-spacing: 0.5px;
        }

        .ingreso-btn {
            background: linear-gradient(135deg, var(--success-color), #10b981);
        }

        .ingreso-btn:hover {
            background: linear-gradient(135deg, #047857, var(--success-color));
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
            color: white;
        }

        .salida-btn {
            background: linear-gradient(135deg, var(--error-color), #ef4444);
        }

        .salida-btn:hover {
            background: linear-gradient(135deg, #b91c1c, var(--error-color));
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
            color: white;
        }

        .action-button i {
            font-size: 1.1rem;
        }

        /* Animaciones */
        @keyframes pulse-patrol {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.7;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-navigation {
                padding: 0.8rem 1rem;
            }

            .title-text {
                font-size: 1.1rem;
            }

            .subtitle-text {
                font-size: 0.75rem;
            }

            .back-button {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }

            .patrol-content {
                padding: 0 0.5rem;
            }

            .logo-image {
                width: 100px;
            }

            .assignment-body {
                padding: 1rem;
            }

            .action-card-body {
                padding: 1.5rem 1rem;
            }

            .counter-value {
                font-size: 2.5rem;
                padding: 0.75rem;
                min-width: 80px;
            }

            .action-button {
                padding: 0.875rem 1.5rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .header-navigation {
                padding: 0.7rem 0.8rem;
            }

            .title-text {
                font-size: 1rem;
            }

            .subtitle-text {
                font-size: 0.7rem;
            }

            .back-button {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .header-title {
                margin: 0 0.5rem;
            }

            .logo-image {
                width: 80px;
            }

            .assignment-header {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .assignment-body {
                padding: 0.75rem;
            }

            .action-card-header {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .action-card-body {
                padding: 1rem 0.75rem;
            }

            .counter-value {
                font-size: 2rem;
                padding: 0.5rem;
                min-width: 70px;
            }

            .action-button {
                padding: 0.75rem 1rem;
                font-size: 0.85rem;
                gap: 0.5rem;
            }

            .visits-control-panel .row {
                gap: 1rem;
            }
        }
    </style>
@endsection
