<div>
    @section('title', 'Puntos de Control - Moderno')
    @section('content_header')
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="m-0 text-primary">
                    <i class="fas fa-map-marked-alt"></i> Puntos de Control
                </h1>
                <small class="text-muted">Gestión inteligente de puntos de control</small>
            </div>
            <a href="javascript:history.back()" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    @endsection

    {{-- Información del Cliente --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body bg-gradient-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1">
                                <i class="fas fa-building"></i> {{ $cliente->nombre }}
                            </h4>
                            <p class="mb-0">
                                <i class="fas fa-clock"></i> Turno: <strong>{{ $turno->nombre }}</strong>
                                <span class="ml-3">
                                    <i class="fas fa-map-marker-alt"></i> {{ $cliente->direccion }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="badge badge-light badge-lg p-3">
                                <i class="fas fa-map-pin text-primary"></i>
                                <strong class="ml-2">{{ count($puntos) }} puntos</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Panel Principal --}}
    <div class="row">
        {{-- Mapa Interactivo --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 text-secondary">
                        <i class="fas fa-map"></i> Mapa Interactivo
                    </h5>
                    <small class="text-muted">Haga clic en el mapa para agregar un punto de control</small>
                </div>
                <div class="card-body p-0">
                    <div id="mapa_moderno" style="height: 500px; border-radius: 0 0 8px 8px;" wire:ignore></div>
                </div>
                <div class="card-footer bg-light border-0">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="bg-success rounded-circle p-2 mr-2">
                                    <i class="fas fa-plus text-white"></i>
                                </div>
                                <small><strong>Verde:</strong> Nuevo punto</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="bg-primary rounded-circle p-2 mr-2">
                                    <i class="fas fa-map-pin text-white"></i>
                                </div>
                                <small><strong>Azul:</strong> Guardado</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="bg-warning rounded-circle p-2 mr-2">
                                    <i class="fas fa-edit text-white"></i>
                                </div>
                                <small><strong>Amarillo:</strong> Editando</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel de Control --}}
        <div class="col-lg-4">
            {{-- Formulario --}}
            @if($showForm)
                <div class="card border-0 shadow-sm mb-4" id="form_panel">
                    <div class="card-header bg-gradient-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-{{ $editingId ? 'edit' : 'plus' }}"></i>
                            {{ $editingId ? 'Editar Punto' : 'Nuevo Punto' }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="registrarPunto">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    <i class="fas fa-tag text-primary"></i> Nombre del Punto
                                </label>
                                <input type="text" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       wire:model="nombre" 
                                       placeholder="Ej: Entrada Principal"
                                       id="input_nombre">
                                @error('nombre') 
                                    <div class="invalid-feedback">{{ $message }}</div> 
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">
                                    <i class="fas fa-clock text-primary"></i> Hora de Control
                                </label>
                                <input type="time" 
                                       class="form-control @error('hora') is-invalid @enderror" 
                                       wire:model="hora">
                                @error('hora') 
                                    <div class="invalid-feedback">{{ $message }}</div> 
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted small">Latitud</label>
                                        <input type="text" 
                                               class="form-control form-control-sm" 
                                               wire:model="latitud" 
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted small">Longitud</label>
                                        <input type="text" 
                                               class="form-control form-control-sm" 
                                               wire:model="longitud" 
                                               readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" 
                                        class="btn btn-{{ $editingId ? 'warning' : 'success' }} btn-block">
                                    <i class="fas fa-{{ $editingId ? 'save' : 'plus' }}"></i>
                                    {{ $editingId ? 'Actualizar' : 'Registrar' }} Punto
                                </button>
                                <button type="button" 
                                        wire:click="cancelar" 
                                        class="btn btn-secondary btn-block mt-2">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Lista de Puntos --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-secondary">
                            <i class="fas fa-list"></i> Puntos Registrados
                        </h5>
                        <span class="badge badge-primary badge-pill">{{ count($puntos) }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(count($puntos) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($puntos as $index => $punto)
                                <div class="list-group-item border-0 punto-item {{ $editingId == $punto->id ? 'bg-warning bg-opacity-10' : '' }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="badge badge-primary badge-pill mr-2">{{ $index + 1 }}</span>
                                                <h6 class="mb-0 font-weight-bold">{{ $punto->nombre }}</h6>
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> {{ $punto->hora }}
                                                <span class="ml-2">
                                                    <i class="fas fa-map-marker-alt"></i> 
                                                    {{ number_format($punto->latitud, 6) }}, {{ number_format($punto->longitud, 6) }}
                                                </span>
                                            </small>
                                        </div>
                                        <div class="btn-group-vertical btn-group-sm">
                                            <button class="btn btn-outline-warning btn-sm" 
                                                    wire:click="editarPunto({{ $punto->id }})"
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" 
                                                    onclick="confirmarEliminacion({{ $punto->id }})"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay puntos registrados</h5>
                            <p class="text-muted">Haga clic en el mapa para agregar el primer punto</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@section('css')
<style>
    .input-flash-green {
        animation: flashGreen 1.5s ease-in-out;
    }

    @keyframes flashGreen {
        0% { background-color: #ffffff; border-color: #ced4da; }
        25% { background-color: #d4edda; border-color: #28a745; box-shadow: 0 0 10px rgba(40, 167, 69, 0.5); }
        50% { background-color: #c8e6c9; border-color: #28a745; box-shadow: 0 0 15px rgba(40, 167, 69, 0.7); }
        75% { background-color: #d4edda; border-color: #28a745; box-shadow: 0 0 10px rgba(40, 167, 69, 0.5); }
        100% { background-color: #ffffff; border-color: #ced4da; box-shadow: none; }
    }

    /* REMOVIDO: Efectos hover que causaban temblor */
    .card {
        transition: none; /* Eliminado hover effect */
    }

    .punto-item {
        transition: none; /* Eliminado hover effect */
    }

    /* REMOVIDO: Hover effects problemáticos */
    /* .card:hover { transform: translateY(-2px); } */
    /* .list-group-item:hover { background-color: #f8f9fa !important; } */

    .badge-lg {
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }

    .bg-gradient-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
    }

    .bg-gradient-success {
        background: linear-gradient(45deg, #28a745, #1e7e34);
    }
</style>
@endsection
@section('js')
<script async defer 
    src="https://maps.googleapis.com/maps/api/js?key={{ config('googlemaps.api_key') }}&callback=initMapModerno&loading=async">
</script>
<script>
    let mapaModerno;
    let marcadorTemporal = null;
    let marcadorEdicion = null;
    let marcadoresPuntos = [];

    function initMapModerno() {
        const clientePosition = { 
            lat: {{ (float) $cliente->latitud }}, // CORREGIDO: Asegurar que sea float
            lng: {{ (float) $cliente->longitud }} // CORREGIDO: Asegurar que sea float
        };
        
        mapaModerno = new google.maps.Map(document.getElementById("mapa_moderno"), {
            zoom: 18,
            center: clientePosition,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [
                {
                    featureType: "poi",
                    elementType: "labels",
                    stylers: [{ visibility: "off" }]
                }
            ]
        });

        cargarPuntosExistentes();

        // Click en el mapa
        mapaModerno.addListener('click', function(event) {
            agregarNuevoPunto(event);
        });
    }

    // CORREGIDO: Función que carga puntos con validación de tipos
    function cargarPuntosExistentes() {
        // Limpiar marcadores
        marcadoresPuntos.forEach(marker => marker.setMap(null));
        marcadoresPuntos = [];

        // OBTENER PUNTOS DIRECTAMENTE DEL ARRAY PHP (no del string estático)
        const puntosArray = @json($puntos);
        
        puntosArray.forEach((punto, index) => {
            // CORREGIDO: Validar que lat y lng sean números válidos
            const lat = parseFloat(punto.latitud);
            const lng = parseFloat(punto.longitud);
            
            // Verificar que los valores sean números válidos
            if (isNaN(lat) || isNaN(lng)) {
                console.error('Coordenadas inválidas para punto:', punto.nombre, lat, lng);
                return; // Saltar este punto si las coordenadas no son válidas
            }

            const marker = new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: mapaModerno,
                title: punto.nombre,
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                    scaledSize: new google.maps.Size(32, 32),
                    anchor: new google.maps.Point(16, 32)
                }
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 10px; min-width: 200px;">
                        <h6 class="text-primary mb-2">
                            <i class="fas fa-map-pin"></i> ${punto.nombre}
                        </h6>
                        <p class="mb-1">
                            <small><i class="fas fa-clock"></i> <strong>Hora:</strong> ${punto.hora}</small>
                        </p>
                        <p class="mb-0">
                            <small><i class="fas fa-map-marker-alt"></i> <strong>Punto #${index + 1}</strong></small>
                        </p>
                    </div>
                `
            });

            marker.addListener('click', () => {
                infoWindow.open(mapaModerno, marker);
            });

            marcadoresPuntos.push(marker);
        });
    }

    function agregarNuevoPunto(event) {
        // Limpiar marcador temporal anterior
        if (marcadorTemporal) {
            marcadorTemporal.setMap(null);
        }

        // Limpiar marcador de edición
        if (marcadorEdicion) {
            marcadorEdicion.setMap(null);
            marcadorEdicion = null;
        }

        const lat = event.latLng.lat();
        const lng = event.latLng.lng();

        // Crear marcador verde temporal
        marcadorTemporal = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: mapaModerno,
            title: "Nuevo Punto de Control",
            draggable: true,
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
                scaledSize: new google.maps.Size(32, 32),
                anchor: new google.maps.Point(16, 32)
            },
            animation: google.maps.Animation.BOUNCE
        });

        // Detener animación
        setTimeout(() => {
            if (marcadorTemporal) {
                marcadorTemporal.setAnimation(null);
            }
        }, 2000);

        // Permitir arrastrar
        marcadorTemporal.addListener('dragend', function(event) {
            const newLat = event.latLng.lat();
            const newLng = event.latLng.lng();
            @this.setLocation(newLat, newLng);
        });

        // Popup informativo
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="padding: 10px; text-align: center;">
                    <h6 class="text-success">📍 Nuevo Punto</h6>
                    <small>Arrastra para ajustar la posición<br>Complete el formulario para guardar</small>
                </div>
            `
        });
        infoWindow.open(mapaModerno, marcadorTemporal);

        setTimeout(() => {
            infoWindow.close();
        }, 4000);

        // Activar formulario y efecto
        @this.setLocation(lat, lng);
        
        setTimeout(() => {
            const input = document.getElementById('input_nombre');
            if (input) {
                input.focus();
                input.classList.add('input-flash-green');
                setTimeout(() => {
                    input.classList.remove('input-flash-green');
                }, 1500);
            }
        }, 300);
    }

    // CORREGIDO: Eventos Livewire - SOLO recargar página
    Livewire.on('puntoRegistrado', () => {
        window.location.reload();
    });

    Livewire.on('puntoActualizado', () => {
        window.location.reload();
    });

    Livewire.on('puntoEliminado', () => {
        window.location.reload();
    });

    // CORREGIDO: Validar coordenadas antes de usar
    Livewire.on('editarPuntoEnMapa', (lat, lng) => {
        console.log('Recibido para editar:', lat, lng, typeof lat, typeof lng);
        
        // VALIDAR que las coordenadas sean números válidos
        const latFloat = parseFloat(lat);
        const lngFloat = parseFloat(lng);
        
        if (isNaN(latFloat) || isNaN(lngFloat)) {
            console.error('Coordenadas inválidas recibidas:', lat, lng);
            alert('Error: Coordenadas inválidas para editar este punto');
            return;
        }

        // Limpiar marcadores temporales
        if (marcadorTemporal) {
            marcadorTemporal.setMap(null);
            marcadorTemporal = null;
        }

        // Crear marcador de edición (amarillo)
        marcadorEdicion = new google.maps.Marker({
            position: { lat: latFloat, lng: lngFloat },
            map: mapaModerno,
            title: "Editando Punto",
            draggable: true,
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png',
                scaledSize: new google.maps.Size(32, 32),
                anchor: new google.maps.Point(16, 32)
            }
        });

        // Actualizar posición al arrastrar
        marcadorEdicion.addListener('dragend', function(event) {
            const newLat = event.latLng.lat();
            const newLng = event.latLng.lng();
            @this.set('latitud', newLat);
            @this.set('longitud', newLng);
        });

        // Centrar mapa en el punto
        mapaModerno.setCenter({ lat: latFloat, lng: lngFloat });
    });

    Livewire.on('cancelarEdicion', () => {
        if (marcadorTemporal) {
            marcadorTemporal.setMap(null);
            marcadorTemporal = null;
        }
        if (marcadorEdicion) {
            marcadorEdicion.setMap(null);
            marcadorEdicion = null;
        }
    });

    function confirmarEliminacion(id) {
        Swal.fire({
            title: '¿Eliminar punto?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.eliminarPunto(id);
            }
        });
    }
</script>
@endsection