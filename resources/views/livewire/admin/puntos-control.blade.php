<div>
    @section('title')
        Puntos de Control
    @endsection
    @section('content_header')
        <h4>Puntos de Control</h4>
    @endsection

    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-info">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span id="card_title">
                        CLIENTE: <strong>{{ $turno->cliente->nombre }}</strong>
                    </span>
                    <div class="float-right">
                        <a href="javascript:history.back()" class="btn btn-info btn-sm float-right" data-placement="left">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                TURNO: <strong>{{ $turno->nombre }}</strong>
                <hr>
                <div class="form-group">
                    <label>Listado de Puntos de Control</label>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-md-6">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-info">
                                    <tr>
                                        <th>Nro.</th>
                                        <th>Nombre</th>
                                        <th>Hora</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($puntos as $punto)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $punto->nombre }}</td>
                                            <td>{{ $punto->hora }}</td>
                                            <td align="right">
                                                <button class="btn btn-outline-danger btn-sm" title="Eliminar de la DB"
                                                    onclick="eliminar({{ $punto->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- Botón actualizar ya no es necesario con la actualización automática --}}
                            <button type="button" onClick="window.location.reload()" class="btn btn-success">
                                <i class="fas fa-sync"></i> Actualizar Manual
                            </button>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        {{-- SOLO CAMBIO: div de Leaflet por Google Maps --}}
                        <div id="google_map2" style="width: 100%; height: 400px" wire:ignore></div>
                        <strong>Haga Click en el mapa para registrar un Nuevo Punto</strong>
                    </div>
                </div>
                <div style="display: none" id="nuevopt" wire:ignore.self>
                    <h5>Registrar Nuevo Punto</h5>
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control" id="nombre" placeholder="Nombre de Punto"
                                    wire:model='nombre' required>
                                @error('nombre')
                                    <small class="text-danger">El campo Nombre es requerido.</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <input type="time" class="form-control" id="hora" required wire:model='hora'>
                                @error('hora')
                                    <small class="text-danger">El campo Hora es requerido.</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <button class="btn btn-primary btn-block" wire:click='registrarPunto'>Registrar</button>
                        </div>
                        <div class="col-12 col-md-3">
                            <a href="javascript:location.reload();" class="btn btn-secondary btn-block">Cancelar</a>
                        </div>
                        <div class="col-12 col-md-3 d-none">
                            <div class="form-group">
                                <label>Latitud:</label>
                                <input type="text" class="form-control" id="latitud" required
                                    placeholder="Seleccione un punto en el Mapa" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 d-none">
                            <div class="form-group">
                                <label>Longitud:</label>
                                <input type="text" class="form-control" id="longitud" required
                                    placeholder="Seleccione un punto en el Mapa" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SOLO CAMBIO: JavaScript de Leaflet por Google Maps + mejoras --}}
@section('css')
<style>
    /* Estilos para el efecto de destello verde */
    .input-flash-green {
        animation: flashGreen 1.5s ease-in-out;
        transition: all 0.8s ease;
    }

    @keyframes flashGreen {
        0% { background-color: #ffffff; border-color: #ced4da; }
        25% { background-color: #d4edda; border-color: #28a745; box-shadow: 0 0 10px rgba(40, 167, 69, 0.5); }
        50% { background-color: #c8e6c9; border-color: #28a745; box-shadow: 0 0 15px rgba(40, 167, 69, 0.7); }
        75% { background-color: #d4edda; border-color: #28a745; box-shadow: 0 0 10px rgba(40, 167, 69, 0.5); }
        100% { background-color: #ffffff; border-color: #ced4da; box-shadow: none; }
    }
</style>
@endsection

@section('js')
<script async defer 
    src="https://maps.googleapis.com/maps/api/js?key={{ config('googlemaps.api_key') }}&callback=initMap2&loading=async">
</script>
<script>
    let map2;
    let auxMarker = null;
    let puntosMarkers = [];

    function initMap2() {
        // Misma ubicación centrada en el cliente que tenías en Leaflet
        const clientePosition = { 
            lat: {{ $cliente->latitud }}, 
            lng: {{ $cliente->longitud }} 
        };
        
        // Crear mapa con mismo zoom que tenías (18)
        map2 = new google.maps.Map(document.getElementById("google_map2"), {
            zoom: 18,
            center: clientePosition,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        });

        // Cargar puntos existentes
        loadExistingPoints();

        // Evento click en el mapa (igual funcionalidad que Leaflet)
        map2.addListener('click', function(event) {
            onMapClick(event);
        });
    }

    function loadExistingPoints() {
        // Limpiar marcadores anteriores
        puntosMarkers.forEach(marker => marker.setMap(null));
        puntosMarkers = [];

        // Cargar puntos desde la variable PHP - ACTUALIZADA DINÁMICAMENTE
        var arr1 = "{{ $pnts }}";
        if (!arr1) return;
        
        arr1 = arr1.split('$');

        if (arr1[0] != "") {
            for (let i = 0; i < arr1.length; i++) {
                const pt = arr1[i].split("|");
                
                // Crear marcador azul para puntos existentes
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(pt[1]), lng: parseFloat(pt[2]) },
                    map: map2,
                    title: pt[0],
                    draggable: false, // NO DRAGGABLE para puntos guardados
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                        scaledSize: new google.maps.Size(32, 32),
                        anchor: new google.maps.Point(16, 32)
                    }
                });

                // Popup con información del punto
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 8px; text-align: center;">
                            <strong style="color: #0066cc;">${pt[0]}</strong><br>
                            <small style="color: #666;">Hora: ${pt[3] || 'No definida'}</small><br>
                            <small style="color: #888;">Punto de Control</small>
                        </div>
                    `
                });

                marker.addListener('click', () => {
                    infoWindow.open(map2, marker);
                });

                puntosMarkers.push(marker);
            }
        }
    }

    // NUEVA FUNCIÓN: Cargar puntos usando AJAX para obtener datos actualizados
    function loadExistingPointsFromServer() {
        // Hacer petición AJAX para obtener puntos actualizados
        fetch(`{{ url()->current() }}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Extraer la variable $pnts del HTML actualizado
            const pntsMatch = html.match(/"([^"]*\$[^"]*)"/);
            if (pntsMatch) {
                const updatedPnts = pntsMatch[1];
                loadPuntosFromString(updatedPnts);
            }
        })
        .catch(error => {
            console.error('Error actualizando puntos:', error);
            // Fallback: recargar página si hay error
            window.location.reload();
        });
    }

    // FUNCIÓN AUXILIAR: Cargar puntos desde string
    function loadPuntosFromString(pntsString) {
        // Limpiar marcadores anteriores
        puntosMarkers.forEach(marker => marker.setMap(null));
        puntosMarkers = [];

        if (!pntsString) return;
        
        const arr1 = pntsString.split('$');

        if (arr1[0] != "") {
            for (let i = 0; i < arr1.length; i++) {
                const pt = arr1[i].split("|");
                
                // Crear marcador azul para puntos existentes
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(pt[1]), lng: parseFloat(pt[2]) },
                    map: map2,
                    title: pt[0],
                    draggable: false, // NO DRAGGABLE
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                        scaledSize: new google.maps.Size(32, 32),
                        anchor: new google.maps.Point(16, 32)
                    }
                });

                // Popup con información del punto
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 8px; text-align: center;">
                            <strong style="color: #0066cc;">${pt[0]}</strong><br>
                            <small style="color: #666;">Hora: ${pt[3] || 'No definida'}</small><br>
                            <small style="color: #888;">Punto de Control</small>
                        </div>
                    `
                });

                marker.addListener('click', () => {
                    infoWindow.open(map2, marker);
                });

                puntosMarkers.push(marker);
            }
        }
    }

    function onMapClick(event) {
        // Eliminar marcador temporal anterior
        if (auxMarker) {
            auxMarker.setMap(null);
        }

        // Mostrar formulario
        $('#nuevopt').css("display", "block");
        
        // Enfocar campo nombre CON EFECTO DE DESTELLO VERDE
        const nombreInput = $('#nombre');
        nombreInput.focus();
        
        // MEJORA: Efecto de destello verde en el input
        nombreInput.removeClass('input-flash-green').addClass('input-flash-green');
        
        // Remover la clase después de la animación
        setTimeout(() => {
            nombreInput.removeClass('input-flash-green');
        }, 1500);

        // Obtener coordenadas del click
        const lat = event.latLng.lat();
        const lng = event.latLng.lng();

        // Actualizar campos ocultos
        $('#latitud').val(lat);
        $('#longitud').val(lng);
        
        // Emitir evento Livewire
        const data = [lat, lng];
        Livewire.emit('cargaLatLng', data);

        // Crear marcador verde temporal (DRAGGABLE mientras no se guarde)
        auxMarker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map2,
            title: "Nuevo Punto de Control - Haga clic para guardar",
            draggable: true, // DRAGGABLE solo para el temporal
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
                scaledSize: new google.maps.Size(32, 32),
                anchor: new google.maps.Point(16, 32)
            },
            animation: google.maps.Animation.BOUNCE
        });

        // Permitir mover el marcador verde antes de guardar
        auxMarker.addListener('dragend', function(event) {
            const newLat = event.latLng.lat();
            const newLng = event.latLng.lng();
            $('#latitud').val(newLat);
            $('#longitud').val(newLng);
            
            // Actualizar evento Livewire con nueva posición
            const newData = [newLat, newLng];
            Livewire.emit('cargaLatLng', newData);
        });

        // Detener animación después de 2 segundos
        setTimeout(() => {
            if (auxMarker) {
                auxMarker.setAnimation(null);
            }
        }, 2000);

        // Popup temporal para el nuevo punto
        const tempInfoWindow = new google.maps.InfoWindow({
            content: `
                <div style="padding: 8px; text-align: center;">
                    <strong style="color: #28a745;">📍 Nuevo Punto</strong><br>
                    <small style="color: #666;">Arrastra para ajustar posición</small><br>
                    <small style="color: #666;">Complete el formulario para guardarlo</small>
                </div>
            `
        });
        tempInfoWindow.open(map2, auxMarker);

        // Cerrar popup después de 4 segundos
        setTimeout(() => {
            tempInfoWindow.close();
        }, 4000);
    }

    // CORREGIDO: Evento para actualización automática después de guardar
    Livewire.on('puntoRegistrado', () => {
        console.log('Punto registrado, actualizando mapa...');
        
        // Ocultar formulario
        $('#nuevopt').css("display", "none");
        
        // Eliminar marcador temporal verde
        if (auxMarker) {
            auxMarker.setMap(null);
            auxMarker = null;
        }
        
        // ACTUALIZACIÓN REAL: Recargar puntos desde el servidor
        setTimeout(() => {
            // Opción 1: Recargar con Livewire (recomendado)
            Livewire.emit('refreshComponent');
            
            // Opción 2: Si Opción 1 no funciona, usar AJAX
            // loadExistingPointsFromServer();
            
            // Opción 3: Fallback - recargar página completa
            // window.location.reload();
        }, 500);
        
        // Mostrar notificación de éxito
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¡Punto Registrado!',
                text: 'El punto de control se ha guardado correctamente',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
    });

    // Evento para ocultar formulario (al cancelar)
    Livewire.on('ocultar', () => {
        $('#nuevopt').css("display", "none");
        // Eliminar marcador temporal al cancelar
        if (auxMarker) {
            auxMarker.setMap(null);
            auxMarker = null;
        }
    });

    // Evento para actualización automática después de eliminar
    Livewire.on('puntoEliminado', () => {
        console.log('Punto eliminado, actualizando mapa...');
        // Recargar puntos automáticamente
        setTimeout(() => {
            Livewire.emit('refreshComponent');
        }, 500);
    });

    // NUEVO: Evento para refrescar solo el mapa cuando Livewire se actualiza
    document.addEventListener('livewire:load', function () {
        Livewire.on('refreshMap', () => {
            setTimeout(() => {
                loadExistingPoints();
            }, 200);
        });
    });
</script>

<script src="{{ asset('vendor/jquery/scripts.js') }}"></script>
@include('vendor.mensajes')
<script>
    function registrar() {
        var nombre = document.getElementById('nombre');
        var hora = document.getElementById('hora');
        var latitud = document.getElementById('latitud');
        var longitud = document.getElementById('longitud');
        if (nombre.value != "" && hora.value != "" && latitud.value != "" && longitud.value != "") {
            const data = [nombre.value, hora.value, latitud.value, longitud.value];
            Livewire.emit('registrarPunto', data);
            document.getElementById('nombre').value = "";
            document.getElementById('hora').value = "";
            document.getElementById('latitud').value = "";
            document.getElementById('longitud').value = "";
        }
    }

    function eliminar(id) {
        Swal.fire({
            title: 'Eliminar Punto',
            text: "Esta seguro de realizar esta operación?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'No, cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('delete', id);
            }
        })
    }
</script>
@endsection
