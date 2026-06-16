@extends('layouts.app')
@section('title', 'Perfil del Empleado')
@section('content')

<div class="mb-3">&nbsp;</div>

<div class="container-fluid px-3 py-2">
    <!-- Header / back -->
    <div class="d-flex align-items-center mb-3">
        <button class="btn btn-link p-0 me-2" onclick="history.back()" aria-label="Volver">
            <i class="fas fa-arrow-left fa-lg"></i>
        </button>
        <h4 class="mb-0">Perfil del Empleado</h4>
    </div>

    <!-- Profile card -->
    <div class="card shadow-sm mb-3">
        <div class="card-body p-3">
            <div class="d-flex align-items-center">
                <!-- Avatar -->
                <div class="flex-shrink-0 me-3">
                    @php
                    $avatar = $empleado->avatar ?? null;
                    $placeholder = 'https://ui-avatars.com/api/?name=' . urlencode($empleado->nombres ?? 'Nombre'.'
                    '.$empleado->apellidos ?? 'Empleado') . '&background=0D6EFD&color=fff&rounded=true&size=128';
                    $avatarUrl = $empleado->imgperfil ? asset('storage/'.$empleado->imgperfil) : $placeholder;
                    @endphp
                    <img src="{{ $avatarUrl }}" alt="Avatar" class="rounded-circle"
                        style="width:84px;height:84px;object-fit:cover;">
                </div>

                <!-- Info -->
                <div class="flex-grow-1">
                    <h5 class="mb-0 text-primary">{{ $empleado->nombres ?? 'Nombre' }} {{ $empleado->apellidos ??
                        'Apellidos' }}</h5>
                    <small class="text-blue d-block mb-2"><strong>{{ $empleado->area->nombre ?? 'Cargo / Puesto'
                            }}</strong></small>

                    <!-- Actions (mobile-friendly) -->
                    {{-- <div class="d-flex gap-2">
                        <a href="tel:{{ $empleado->telefono ?? '' }}" class="btn btn-sm btn-outline-primary flex-fill"
                            @if(empty($empleado->telefono)) disabled @endif>
                            <i class="fas fa-phone"></i> Llamar
                        </a>
                        <a href="mailto:{{ $empleado->email ?? '' }}" class="btn btn-sm btn-outline-success flex-fill"
                            @if(empty($empleado->email)) disabled @endif>
                            <i class="fas fa-envelope"></i> Email
                        </a>
                    </div> --}}
                </div>

                <!-- Edit button -->
                {{-- <div class="ms-2 d-none d-sm-block">
                    <a href="{{ route('empleados.edit', $empleado->id ?? 0) }}" class="btn btn-sm btn-primary"
                        title="Editar">
                        <i class="fas fa-edit me-1"></i> Editar
                    </a>
                </div> --}}
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-2" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details"
                type="button" role="tab" aria-controls="details" aria-selected="true">Detalles</button>
        </li>
        {{-- <li class="nav-item" role="presentation">
            <button class="nav-link" id="docs-tab" data-bs-toggle="tab" data-bs-target="#docs" type="button" role="tab"
                aria-controls="docs" aria-selected="false">Documentos</button>
        </li> --}}
        {{-- <li class="nav-item" role="presentation">
            <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button"
                role="tab" aria-controls="activity" aria-selected="false">Actividad</button>
        </li> --}}
    </ul>

    <div class="tab-content">
        <!-- Details -->
        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
            <div class="card mb-3">
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-12">
                            <h6 class="mb-1 text-muted">INFORMACIÓN DE CONTACTO</h6>
                            <hr>
                            <p class="mb-1"><strong>Teléfono:</strong> {{ $empleado->telefono ?? '—' }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $empleado->email ?? '—' }}</p>
                            <p class="mb-0"><strong>Dirección:</strong> {{ $empleado->direccion ?? '—' }}</p>
                        </div>

                        <div class="col-12 mt-2">
                            <h6 class="mb-1 text-success">Información adicional</h6>
                            <small>
                                <p class="mb-0"><strong>Persona Ref.:</strong> {{ $empleado->persona_referencia ?? 'Sin
                                    información adicional.' }}</p>
                                <p class="mb-0"><strong>Telf Ref.:</strong> {{ $empleado->telefono_referencia ?? 'Sin
                                    información adicional.' }}</p>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents -->
        <div class="tab-pane fade" id="docs" role="tabpanel" aria-labelledby="docs-tab">
            <div class="card mb-3">
                <div class="card-body p-2">
                    <ul class="list-group list-group-flush">
                        @forelse($empleado->documentos ?? [] as $doc)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="small">{{ $doc->nombre ?? 'Documento' }}</span>
                            <a href="{{ asset('storage/'.$doc->ruta) }}" target="_blank"
                                class="badge bg-primary text-decoration-none">Ver</a>
                        </li>
                        @empty
                        <li class="list-group-item text-center small text-muted">No hay documentos.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Activity -->
        <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
            <div class="card mb-3">
                <div class="card-body p-2">
                    <ul class="list-unstyled mb-0">
                        @forelse($employee->actividades ?? [] as $act)
                        <li class="py-2 border-bottom">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="small fw-semibold">{{ $act->titulo ?? 'Actividad' }}</div>
                                    <div class="small text-muted">{{ $act->descripcion ?? '' }}</div>
                                </div>
                                <div class="small text-muted">{{ \Carbon\Carbon::parse($act->created_at ??
                                    now())->diffForHumans() }}</div>
                            </div>
                        </li>
                        @empty
                        <li class="text-center small text-muted py-3">No hay actividad reciente.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick stats -->
    <div class="row g-2 mb-2">
        <div class="col-12 col-sm-4 mb-1">
            <a href="{{ route('vigilancia.vacaciones') }}"
                class="btn btn-info w-100 text-secondary d-flex flex-column align-items-center justify-content-center py-3">
                <i class="fas fa-calendar-check fa-2x mb-2"></i>
                <span class="fw-semibold">Permisos y Vacaciones</span>
            </a>
        </div>
        <div class="col-12 col-sm-4 mb-1">
            <a href="{{ route('vigilancia.adelantos') }}"
                class="btn btn-primary w-100 text-secondary d-flex flex-column align-items-center justify-content-center py-3">
                <i class="fas fa-hand-holding-usd fa-2x mb-2"></i>
                <span class="fw-semibold">Adelantos</span>
            </a>
        </div>
        <div class="col-12 col-sm-4 mb-1">
            <a href="{{ route('vigilancia.boletas') }}"
                class="btn w-100 text-secondary d-flex flex-column align-items-center justify-content-center py-3"
                style="background-color: #ff6c6c">
                <i class="fas fa-receipt fa-2x mb-2"></i>
                <span class="fw-semibold">Mis Boletas</span>
            </a>
        </div>
        <div class="col-12 col-sm-4 mb-1">
            <a  href="{{ route('vigilancia.asistencias') }}"
            class="btn btn-warning text-secondary w-100 d-flex flex-column align-items-center justify-content-center py-3">
                <i class="fas fa-user-clock fa-2x mb-2"></i>
                <span class="fw-semibold">Asistencias</span>
            </a>
        </div>
    </div>





</div>

@endsection

@push('styles')
<style>
    /* Mobile-first avatar sizing */
    @media (max-width: 576px) {
        #imagenPreviewLocal img {
            max-height: 120px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Inicializar tooltips (si se usan) y asegurar tabs en mobile funcionan bien
	document.addEventListener('DOMContentLoaded', function () {
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		tooltipTriggerList.forEach(function (tooltipTriggerEl) {
			new bootstrap.Tooltip(tooltipTriggerEl)
		})
	});
</script>
@endpush
