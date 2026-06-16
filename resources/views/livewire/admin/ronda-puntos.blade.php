<div>
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0">Puntos de Control</h4>
            <a href="{{ route('clientes.rondas', $cliente_id) }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info ">
                    <h3 class="card-title">{{$cliente->nombre}}</h3>
                </div>
                <div class="card-body">
                    <div wire:ignore id="map" style="height: 500px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title text-white">Registrar Punto</h3>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="guardarPunto">
                        <div class="form-group">
                            <label>Nombre del punto</label>
                            <input type="text" id="descripcion-punto" maxlength="30" class="form-control" wire:model.defer="descripcion" required>
                            @error('descripcion')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Coordenadas</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" wire:model.defer="latitud" readonly
                                    placeholder="Latitud">
                                <input type="text" class="form-control" wire:model.defer="longitud" readonly
                                    placeholder="Longitud">
                            </div>
                            @error('latitud')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @error('longitud')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Punto</button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-secondary">
                    <h3 class="card-title text-white">Puntos Registrados</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach ($puntos as $punto)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    {{-- <strong>{{ $punto->nombre }}</strong><br> --}}
                                    <span class="text-muted">{{ $punto->descripcion }}</span>
                                </div>
                                <button class="btn btn-danger btn-sm"
                                    onclick="confirmarEliminar({{ $punto->id }}, '{{ $punto->descripcion }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@section('css')
    <style>
        #map {
            height: 500px;
            width: 100%;
            position: relative !important;
        }
    </style>
@endsection
@section('js')
    <script>
        let map;
        let tempMarker;
        let puntosMarkers = {};

        // Función para cargar el API de Google Maps
        function loadGoogleMapsApi() {
            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key={{ config('googlemaps.api_key') }}&libraries=places&loading=async&callback=initMap`;
            script.async = true;
            document.head.appendChild(script);
        }

        // Definir la función de inicialización que será llamada como callback
        window.initMap = function() {
            try {
                const clienteLat = {{ is_numeric($cliente_latitud) ? $cliente_latitud : -17.7833 }};
                const clienteLng = {{ is_numeric($cliente_longitud) ? $cliente_longitud : -63.1821 }};
                const centro = {
                    lat: parseFloat(clienteLat),
                    lng: parseFloat(clienteLng)
                };

                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 18,
                    center: centro,
                    mapTypeId: 'roadmap',
                    gestureHandling: 'greedy'
                });

                map.addListener('click', function(e) {
                    placeMarker(e.latLng);
                });

                cargarPuntosExistentes();
            } catch (error) {
                console.error('Error al inicializar el mapa:', error);
            }
        }

        // Cargar el API cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', loadGoogleMapsApi);

        function cargarPuntosExistentes() {
            @foreach ($puntos as $punto)
                if ({{ $punto->latitud }} && {{ $punto->longitud }}) {
                    const position = {
                        lat: parseFloat({{ $punto->latitud }}),
                        lng: parseFloat({{ $punto->longitud }})
                    };
                    crearMarcadorPunto({{ $punto->id }}, position, "{{ $punto->descripcion }}");
                }
            @endforeach
        }

        function crearMarcadorPunto(id, position, descripcion) {
            const marker = new google.maps.Marker({
                position: position,
                map: map,
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png', // Cambiado a HTTPS
                    scaledSize: new google.maps.Size(35, 35),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34)
                },
                title: descripcion,
                animation: google.maps.Animation.DROP
            });

            marker.addListener('click', function() {
                Swal.fire({
                    title: '¿Eliminar punto?',
                    text: descripcion,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('eliminarPunto', id);
                    }
                });
            });

            puntosMarkers[id] = marker;
        }

        function placeMarker(location) {
            if (tempMarker) {
                tempMarker.setPosition(location);
            } else {
                tempMarker = new google.maps.Marker({
                    position: location,
                    map: map,
                    draggable: true
                });
            }

            @this.set('latitud', location.lat());
            @this.set('longitud', location.lng());

            // Agregar focus al textarea de descripción
            document.getElementById('descripcion-punto').focus();
        }

        function confirmarEliminar(id, descripcion) {
            Swal.fire({
                title: '¿Eliminar punto?',
                text: descripcion,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('eliminarPunto', id);
                }
            });
        }

        window.addEventListener('punto-guardado', event => {
            if (tempMarker) {
                tempMarker.setMap(null);
                tempMarker = null;
            }
            crearMarcadorPunto(
                event.detail.punto.id, {
                    lat: parseFloat(event.detail.punto.latitud),
                    lng: parseFloat(event.detail.punto.longitud)
                },
                event.detail.punto.descripcion
            );
            Swal.fire('¡Guardado!', 'El punto se ha registrado correctamente.', 'success');
        });

        window.addEventListener('punto-eliminado', event => {
            if (puntosMarkers[event.detail.puntoId]) {
                puntosMarkers[event.detail.puntoId].setMap(null);
                delete puntosMarkers[event.detail.puntoId];
            }
            Swal.fire('¡Eliminado!', 'El punto se ha eliminado correctamente.', 'success');
        });
    </script>
@endsection


