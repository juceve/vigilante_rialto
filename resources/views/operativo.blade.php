@extends('layouts.app')

@section('title', 'Panel Operativo')

@push('head')
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="color-scheme" content="light only">
@endpush

@section('content')
    {{-- Header Minimalista --}}
    <div class="minimal-header" style="margin-top: 85px;">
        <div class="container">
            <div class="header-content">
                <div class="user-section">
                    <div class="user-avatar-mini">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="user-details">
                        <h4 class="user-name-mini">{{ Auth::user()->name }}</h4>
                        <div class="status-badge">
                            <i class="fas fa-circle"></i>
                            <span>En Servicio</span>
                        </div>
                    </div>
                </div>
                @if ($designaciones)
                    <div class="assignment-compact">
                        <div style="font-size: 12px">
                            <i class="fas fa-building"></i>
                            <span>{{ $designaciones->turno->cliente->nombre ?? 'No asignada' }}</span>
                        </div>
                        <div style="font-size: 12px">
                            <i class="fas fa-clock"></i>
                            <span>{{ $designaciones->turno->nombre ?? 'No asignado' }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Lógica Principal de Designaciones --}}
    @if ($designaciones)
        @php
            // Optimización: Calcular una sola vez los estados del empleado
            $esDiaLibreHoy = esDiaLibre($designaciones->id);
            $estadoMarcado = yaMarque($designaciones->id);

            $tieneTareasPendientes = verificaTareas($designaciones->id);
            $intervaloHV = verificaHV($designaciones->id);
        @endphp

        @if ($esDiaLibreHoy)
            {{-- Día Libre --}}
            <div class="alert-modern alert-success text-center">
                <div class="alert-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="alert-content">
                    <h5>Día Libre</h5>
                    <p>Hoy es tu día de descanso</p>
                </div>
            </div>
        @else
            @if ($estadoMarcado > 0)
                @if ($estadoMarcado == 1)

                    {{-- Panel de Funciones Material Design --}}
                    <div class="functions-container">
                        <div class="container-fluid px-3">
                            <div class="functions-grid">
                                {{-- Botón de Pánico --}}
                                <div class="function-card emergency-card">
                                    <a href="{{ route('vigilancia.panico') }}" class="card-link">
                                        <div class="card-icon">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="card-content">
                                            <h6 class="card-title">PÁNICO</h6>
                                            <p class="card-subtitle">Emergencia</p>
                                        </div>
                                    </a>
                                </div>

                                {{-- Ronda --}}
                                <div class="function-card primary-card">
                                    <a href="{{ route('vigilancia.ronda') }}" class="card-link">
                                        <div class="card-icon">
                                            <i class="fas fa-walking"></i>
                                        </div>
                                        <div class="card-content">
                                            <h6 class="card-title">RONDA</h6>
                                            <p class="card-subtitle">Vigilancia</p>
                                        </div>
                                    </a>
                                </div>

                                {{-- Tareas --}}
                                <div
                                    class="function-card warning-card {{ $tieneTareasPendientes ? 'has-notification' : '' }}">
                                    <a href="{{ route('vigilancia.tareas', $designaciones->id) }}" class="card-link">
                                        <div class="card-icon">
                                            <i class="fas fa-tasks"></i>
                                            @if ($tieneTareasPendientes)
                                                <span class="notification-badge pulse"></span>
                                            @endif
                                        </div>
                                        <div class="card-content">
                                            <h6 class="card-title">TAREAS</h6>
                                            <p class="card-subtitle">
                                                {{ $tieneTareasPendientes ? 'Pendientes' : 'Asignadas' }}</p>
                                        </div>
                                    </a>
                                </div>

                                {{-- Hombre Vivo --}}
                                <div class="function-card success-card {{ $intervaloHV ? 'has-notification' : '' }}">
                                    @if ($intervaloHV)
                                        <a href="{{ route('vigilancia.hombre-vivo', $intervaloHV->id) }}" class="card-link">
                                        @else
                                            <a href="{{ route('vigilancia.hombre-vivo', 0) }}" class="card-link">
                                    @endif
                                    <div class="card-icon">
                                        <i class="fas fa-heartbeat"></i>
                                        @if ($intervaloHV)
                                            <span class="notification-badge pulse"></span>
                                        @endif
                                    </div>
                                    <div class="card-content">
                                        <h6 class="card-title">HOMBRE VIVO</h6>
                                        <p class="card-subtitle">{{ $intervaloHV ? 'Pendiente' : 'Disponible' }}</p>
                                    </div>
                                    </a>
                                </div>

                                {{-- Visitas --}}
                                <div class="function-card info-card">
                                    <a href="{{ route('vigilancia.panelvisitas', $designaciones->id) }}" class="card-link">
                                        <div class="card-icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="card-content">
                                            <h6 class="card-title">VISITAS</h6>
                                            <p class="card-subtitle">Registro</p>
                                        </div>
                                    </a>
                                </div>
                                {{-- Pases --}}
                                <div class="function-card primary-card">
                                    <a href="{{ route('vigilancia.controlpases', $designaciones->id) }}" class="card-link">
                                        <div class="card-icon">
                                            <i class="far fa-id-card"></i>
                                        </div>
                                        <div class="card-content">
                                            <h6 class="card-title">CONTROL PASES</h6>
                                            <p class="card-subtitle">Registro</p>
                                        </div>
                                    </a>
                                </div>
                                {{-- Novedades --}}
                                <div class="function-card secondary-card">
                                    <a href="{{ route('vigilancia.novedades', $designaciones->id) }}" class="card-link">
                                        <div class="card-icon">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                        <div class="card-content">
                                            <h6 class="card-title">NOVEDADES</h6>
                                            <p class="card-subtitle">Reportes</p>
                                        </div>
                                    </a>
                                </div>

                                {{-- Airbnb --}}
                                {{-- <div class="function-card airbnb-card">
                                    <a href="{{ route('vigilancia.airbnb', $designaciones->id) }}" class="card-link">
                                        <div class="card-icon">
                                            <i class="fab fa-airbnb"></i>
                                        </div>
                                        <div class="card-content">
                                            <h6 class="card-title">AIRBNB</h6>
                                            <p class="card-subtitle">Gestión</p>
                                        </div>
                                    </a>
                                </div> --}}
                            </div>
                        </div>

                        {{-- Componente de Marca Salida Estilizado --}}
                        <div class="checkout-section">
                            <div class="checkout-card">
                                <div class="checkout-header">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <h5>Finalizar Turno</h5>
                                </div>
                                <p class="checkout-description">
                                    Marca tu salida cuando hayas completado todas las tareas asignadas
                                </p>
                                <div class="checkout-component">
                                    @livewire('vigilancia.marca-salida', ['designacione_id' => $designaciones->id])
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Ya registró salida --}}
                    <div class="alert-modern alert-completed text-center">
                        <div class="alert-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="alert-content">
                            <h5>Turno Completado</h5>
                            <p>Ya registró su salida exitosamente</p>
                        </div>
                    </div>
                @endif
            @else
                {{-- Componente de Marca Ingreso --}}
                <div class="check-in-container">
                    @livewire('vigilancia.marca-ingreso', ['designacione_id' => $designaciones->id])
                </div>
            @endif
        @endif
    @else
        {{-- Sin Designaciones --}}
        <div class="alert-modern alert-error text-center">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <h5>Sin Asignaciones</h5>
                <p>No existen designaciones habilitadas</p>
            </div>
        </div>

        {{-- Opción de Relevo Temporal --}}
        @if (Auth::user()->empleados[0]->cubrerelevos ?? false)
            <div class="relief-option">
                <a href="{{ route('vigilancia.cubrerelevos') }}" class="relief-button">
                    <div class="relief-icon">
                        <i class="fas fa-power-off"></i>
                    </div>
                    <div class="relief-content">
                        <h5>Activar Relevo Temporal</h5>
                        <p>Cubrir turno de emergencia</p>
                    </div>
                </a>
            </div>
        @endif
    @endif

    {{-- Estilos Material Design --}}
    @push('styles')
       <link rel="stylesheet" href="{{asset('css/styleHomeVS.css')}}">
    @endpush

    {{-- JavaScript Optimizado Material Design --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-refresh optimizado con visibilidad de página
                let refreshInterval;

                function startAutoRefresh() {
                    refreshInterval = setTimeout(() => {
                        // Solo refrescar si la página está visible
                        if (!document.hidden) {
                            window.location.reload();
                        } else {
                            // Si la página no está visible, reintentar en 10 segundos
                            startAutoRefresh();
                        }
                    }, 60000); // 60 segundos
                }

                // Manejar cambios de visibilidad de la página - passive
                document.addEventListener('visibilitychange', function() {
                    if (document.hidden) {
                        // Página oculta, pausar el refresh
                        clearTimeout(refreshInterval);
                    } else {
                        // Página visible, reanudar el refresh
                        startAutoRefresh();
                    }
                }, {
                    passive: true
                });

                // Efectos de interacción mejorados - optimizado para passive events
                const functionCards = document.querySelectorAll('.function-card');

                functionCards.forEach(card => {
                    // Efecto de tap en móvil - passive para mejor rendimiento
                    card.addEventListener('touchstart', function() {
                        this.style.transform = 'scale(0.95)';
                    }, {
                        passive: true
                    });

                    // Touch end para efecto visual - passive
                    card.addEventListener('touchend', function() {
                        this.style.transform = '';
                    }, {
                        passive: true
                    });

                    // Separar la lógica de prevención de zoom doble tap
                    let touchTime = 0;
                    card.addEventListener('touchstart', function(event) {
                        // Solo prevenir zoom si hay múltiples toques rápidos
                        if (event.touches.length > 1) return;

                        const currentTime = new Date().getTime();
                        const tapLength = currentTime - touchTime;

                        if (tapLength < 300 && tapLength > 0) {
                            event.preventDefault();
                        }
                        touchTime = currentTime;
                    }, {
                        passive: false
                    }); // NO passive solo para prevenir zoom
                });

                // Feedback háptico en dispositivos compatibles
                function provideFeedback() {
                    if ('vibrate' in navigator) {
                        navigator.vibrate(50); // Vibración corta
                    }
                }

                // Agregar feedback a las tarjetas importantes - passive para mejor rendimiento
                const emergencyCard = document.querySelector('.emergency-card');
                const notificationCards = document.querySelectorAll('.has-notification');

                if (emergencyCard) {
                    emergencyCard.addEventListener('click', provideFeedback, {
                        passive: true
                    });
                }

                notificationCards.forEach(card => {
                    card.addEventListener('click', provideFeedback, {
                        passive: true
                    });
                });

                // Iniciar el auto-refresh
                startAutoRefresh();

                // Manejar errores de red para reintentar después - passive
                window.addEventListener('offline', function() {
                    clearTimeout(refreshInterval);
                    console.log('Aplicación sin conexión - pausando auto-refresh');
                }, {
                    passive: true
                });

                window.addEventListener('online', function() {
                    startAutoRefresh();
                    console.log('Conexión restaurada - reanudando auto-refresh');
                }, {
                    passive: true
                });

                // Mejora de accesibilidad - keydown requiere preventDefault así que NO passive
                const cards = document.querySelectorAll('.card-link');
                cards.forEach(card => {
                    card.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            this.click();
                        }
                    }); // Sin passive porque usamos preventDefault
                });

                // Animación de entrada progresiva
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry, index) => {
                        if (entry.isIntersecting) {
                            setTimeout(() => {
                                entry.target.style.opacity = '1';
                                entry.target.style.transform = 'translateY(0)';
                            }, index * 100);
                        }
                    });
                });

                // Aplicar animación de entrada a las tarjetas
                functionCards.forEach(card => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    observer.observe(card);
                });
            });
        </script>
    @endpush
@endsection
