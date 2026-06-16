<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            <div class="col col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('nombre') }}
                    {{ Form::text('nombre', $cliente->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre']) }}
                    {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col col-12 col-md-3">
                <div class="form-group{{ $errors->has('tipodocumento_id') ? ' has-error' : '' }}">
                    {!! Form::label('tipodocumento_id', 'Tipo Documento') !!}
                    {!! Form::select('tipodocumento_id', $tipodocs, $cliente->tipodocumento_id, [
                    'id' => 'tipodocumento_id',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Seleccione una opción',
                    ]) !!}
                    <small class="text-danger">{{ $errors->first('tipodocumento_id') }}</small>
                </div>
            </div>
            <div class="col col-12 col-md-3">
                <div class="form-group">
                    {{ Form::label('Nro. Doc.') }}
                    {{ Form::text('nrodocumento', $cliente->nrodocumento, ['class' => 'form-control' . ($errors->has('nrodocumento') ? ' is-invalid' : ''), 'placeholder' => 'Nrodocumento']) }}
                    {!! $errors->first('nrodocumento', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('dirección') }}
                    {{ Form::text('direccion', $cliente->direccion, ['class' => 'form-control' . ($errors->has('direccion') ? ' is-invalid' : ''), 'placeholder' => 'Direccion']) }}
                    {!! $errors->first('direccion', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col col-12 col-md-3">
                <div class="form-group">
                    {{ Form::label('U.V.') }}
                    {{ Form::text('uv', $cliente->uv, ['class' => 'form-control' . ($errors->has('uv') ? ' is-invalid' : ''), 'placeholder' => 'Uv']) }}
                    {!! $errors->first('uv', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col col-12 col-md-3">
                <div class="form-group">
                    {{ Form::label('manzano') }}
                    {{ Form::text('manzano', $cliente->manzano, ['class' => 'form-control' . ($errors->has('manzano') ? ' is-invalid' : ''), 'placeholder' => 'Manzano']) }}
                    {!! $errors->first('manzano', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col col-12 col-md-6 d-none">
                <div class="form-group">
                    {{ Form::label('latitud') }}
                    {{ Form::text('latitud', $cliente->latitud, ['class' => 'form-control' . ($errors->has('latitud') ? ' is-invalid' : ''), 'placeholder' => 'Latitud', 'id' => 'latitud']) }}
                    {!! $errors->first('latitud', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col col-12 col-md-6 d-none">
                <div class="form-group">
                    {{ Form::label('longitud') }}
                    {{ Form::text('longitud', $cliente->longitud, ['class' => 'form-control' . ($errors->has('longitud') ? ' is-invalid' : ''), 'placeholder' => 'Longitud', 'id' => 'longitud']) }}
                    {!! $errors->first('longitud', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('Persona Contacto') }}
                    {{ Form::text('personacontacto', $cliente->personacontacto, ['class' => 'form-control' . ($errors->has('personacontacto') ? ' is-invalid' : ''), 'placeholder' => 'Personacontacto']) }}
                    {!! $errors->first('personacontacto', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('Teléfono Contacto') }}
                    {{ Form::text('telefonocontacto', $cliente->telefonocontacto, ['class' => 'form-control' . ($errors->has('telefonocontacto') ? ' is-invalid' : ''), 'placeholder' => 'Telefonocontacto']) }}
                    {!! $errors->first('telefonocontacto', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col col-12 col-md-6">
                <div class="form-group{{ $errors->has('oficina_id') ? ' has-error' : '' }}">
                    {!! Form::label('oficina_id', 'Oficina vinculada') !!}
                    {!! Form::select('oficina_id', $oficinas, $cliente->oficina_id, [
                    'id' => 'oficina_id',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Seleccione una opción',
                    ]) !!}
                    <small class="text-danger">{{ $errors->first('oficina_id') }}</small>
                </div>
            </div>

            <div class="col col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('observaciones') }}
                    {{ Form::text('observaciones', $cliente->observaciones, ['class' => 'form-control' . ($errors->has('observaciones') ? ' is-invalid' : ''), 'placeholder' => 'Observaciones']) }}
                    {!! $errors->first('observaciones', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            <div class="col col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('fecha inicio') }}
                    {{ Form::date('fecha_inicio', $cliente->fecha_inicio, ['class' => 'form-control' . ($errors->has('fecha_inicio') ? ' is-invalid' : ''), 'placeholder' => 'Fecha inicio']) }}
                    {!! $errors->first('fecha_inicio', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('fecha fin') }}
                    {{ Form::date('fecha_fin', $cliente->fecha_fin, ['class' => 'form-control' . ($errors->has('fecha_fin') ? ' is-invalid' : ''), 'placeholder' => 'Fecha fin']) }}
                    {!! $errors->first('fecha_fin', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
             <div class="col col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('email') }}
                    {{ Form::text('email', $cliente->email, ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => 'Email']) }}
                    {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col col-12 col-md-6">
                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                    {!! Form::label('status', 'Estado') !!}
                    {!! Form::select('status', ['1' => 'Activo', '0' => 'Inactivo'], $cliente->status, [
                    'id' => 'status',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Seleccione una opcion',
                    ]) !!}
                    <small class="text-danger">{{ $errors->first('status') }}</small>
                </div>
            </div>
            <div class="col col-12 mb-2">
                <label for="mapa">Ubicación del Domicilio</label>
                <p class="text-muted small">
                    <i class="fas fa-info-circle"></i>
                    Arrastra el marcador para ajustar la ubicación exacta
                </p>
                <input id="pac-input" class="form-control mb-2" type="text" placeholder="Buscar calles o barrios en la zona">
                {{-- SOLO CAMBIO: div de Leaflet por Google Maps --}}
                <div id="google_map" style="width: 100%; height: 500px; border: 1px solid #ddd;"></div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row mt-3">
        <div class="col-12 col-md-6">
            <div class="box-footer mt20">
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>

{{-- SOLO CAMBIO: JavaScript de Leaflet por Google Maps --}}
@section('js')
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('googlemaps.api_key') }}&libraries=places&callback=initMap&loading=async">
</script>
<script>
    let map;
    let marker;

    function initMap() {
        // Mismas coordenadas que tenías en Leaflet (usar existentes o coordenadas por defecto de Santa Cruz)
        const initialLat = {{ $cliente->latitud ? $cliente->latitud : -17.7817999 }};
        const initialLng = {{ $cliente->longitud ? $cliente->longitud : -63.1825485 }};

        const initialPosition = {
            lat: initialLat,
            lng: initialLng
        };

        // Crear mapa con mismo zoom que tenías (17)
        map = new google.maps.Map(document.getElementById("google_map"), {
            zoom: 17,
            center: initialPosition,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        });

        // Crear marcador ARRASTRABLE (igual que en Leaflet)
        marker = new google.maps.Marker({
            position: initialPosition,
            map: map,
            title: "Arrastra para ajustar ubicación",
            draggable: true, // ¡IMPORTANTE! - Igual que en Leaflet
            icon: {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="50" viewBox="0 0 40 50">
                        <defs>
                            <filter id="shadow" x="-50%" y="-50%" width="200%" height="200%">
                                <feDropShadow dx="2" dy="4" stdDeviation="3" flood-color="#000000" flood-opacity="0.3"/>
                            </filter>
                        </defs>
                        <path d="M20 5 C28 5 35 12 35 20 C35 28 20 45 20 45 C20 45 5 28 5 20 C5 12 12 5 20 5 Z"
                              fill="#E74C3C" filter="url(#shadow)"/>
                        <circle cx="20" cy="20" r="8" fill="#FFFFFF"/>
                        <circle cx="20" cy="20" r="5" fill="#C0392B"/>
                    </svg>
                `),
                scaledSize: new google.maps.Size(40, 50),
                anchor: new google.maps.Point(20, 50)
            }
        });

        // Crear el input de búsqueda
        const input = document.getElementById('pac-input');
        const searchBox = new google.maps.places.Autocomplete(input);
        searchBox.setBounds(map.getBounds());

        // Cuando se selecciona un lugar
        searchBox.addListener('place_changed', function() {
            const place = searchBox.getPlace();
            if (!place.geometry) {
                console.log("No details available for input: '" + place.name + "'");
                return;
            }
            // Centrar el mapa en el lugar
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
            // Mover el marker
            marker.setPosition(place.geometry.location);
            // Actualizar campos
            document.getElementById('latitud').value = place.geometry.location.lat();
            document.getElementById('longitud').value = place.geometry.location.lng();
        });

        // Evento cuando se termina de arrastrar el marcador (igual funcionalidad que Leaflet)
        marker.addListener('dragend', function(event) {
            const position = marker.getPosition();
            const lat = position.lat();
            const lng = position.lng();

            // Actualizar los campos ocultos (igual que en Leaflet)
            document.getElementById('latitud').value = lat;
            document.getElementById('longitud').value = lng;

            // Disparar evento keyup para cualquier validación (igual que tenías)
            const longitudInput = document.getElementById('longitud');
            const event_keyup = new Event('keyup');
            longitudInput.dispatchEvent(event_keyup);

            // Mostrar coordenadas en popup (opcional)
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 10px;">
                        <strong>Nueva ubicación:</strong><br>
                        <small>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}</small>
                    </div>
                `
            });
            infoWindow.open(map, marker);

            // Cerrar popup después de 3 segundos
            setTimeout(() => {
                infoWindow.close();
            }, 3000);
        });

        // Popup inicial con instrucciones
        const initialInfoWindow = new google.maps.InfoWindow({
            content: `
                <div style="padding: 10px; text-align: center;">
                    <strong>📍 Ubicación del Cliente</strong><br>
                    <small>Arrastra el marcador para ajustar la posición</small>
                </div>
            `
        });

        // Mostrar popup inicial al hacer clic
        marker.addListener('click', () => {
            initialInfoWindow.open(map, marker);
        });
    }
</script>
@endsection
