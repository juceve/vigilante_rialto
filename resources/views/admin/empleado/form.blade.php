   <div class="box box-info padding-1">
    <div class="box-body">

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('nombres') }}
                    {{ Form::text('nombres', $empleado->nombres, ['class' => 'form-control' . ($errors->has('nombres') ?
                    ' is-invalid' : ''), 'placeholder' => 'Nombres', 'onKeyUp'=>"this.value=this.value.toUpperCase();"])
                    }}
                    {!! $errors->first('nombres', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('apellidos') }}
                    {{ Form::text('apellidos', $empleado->apellidos, ['class' => 'form-control' .
                    ($errors->has('apellidos') ? ' is-invalid' : ''), 'placeholder' => 'Apellidos',
                    'onKeyUp'=>"this.value=this.value.toUpperCase()"]) }}
                    {!! $errors->first('apellidos', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group{{ $errors->has('tipodocumento_id') ? ' has-error' : '' }}">
                    <label for="">Tipo Documento</label>
                    {!! Form::select('tipodocumento_id', $tipodocs, $empleado->tipodocumento_id, ['id' =>
                    'tipodocumento_id', 'class' => 'form-control', 'required' => 'required','placeholder'=>'Seleccione
                    una opción']) !!}
                    <small class="text-danger">{{ $errors->first('tipodocumento_id') }}</small>
                </div>
                {{-- <div class="form-group">
                    {{ Form::label('Tipo Documento') }}
                    {{ Form::text('tipodocumento_id', $empleado->tipodocumento_id, ['class' => 'form-control' .
                    ($errors->has('tipodocumento_id') ? ' is-invalid' : ''), 'placeholder' => 'Tipo Documento']) }}
                    {!! $errors->first('tipodocumento_id', '<div class="invalid-feedback">:message</div>') !!}
                </div> --}}
            </div>
            <div class="col-12 col-md-3">
                <div class="form-group">
                    {{ Form::label('Nro. Documento') }}
                    {{ Form::text('cedula', $empleado->cedula, ['class' => 'form-control' . ($errors->has('cedula') ? '
                    is-invalid' : ''), 'placeholder' => 'Nro. Documento']) }}
                    {!! $errors->first('cedula', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="form-group">
                    {{ Form::label('Expedido') }}
                    {!! Form::select('expedido', ['LP'=>'LP','SC'=>'SC','CB'=>'CB','OR'=>'OR','PT'=>'PT','CH'=>'CH','TJ'=>'TJ','BN'=>'BN'],  $empleado->expedido, ['class' => 'form-control' . ($errors->has('expedido') ? '
                    is-invalid' : ''),'placeholder'=>'- Elija una opción -']) !!}
                    {!! $errors->first('expedido', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('Fecha Nacimiento') }}
                    {{ Form::date('fecnacimiento', $empleado->fecnacimiento, ['class' => 'form-control' . ($errors->has('fecnacimiento') ? '
                    is-invalid' : ''), 'placeholder' => 'Nro. Documento']) }}
                    {!! $errors->first('fecnacimiento', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('nacionalidad') }}
                    {{ Form::text('nacionalidad', $empleado->nacionalidad, ['class' => 'form-control' .
                    ($errors->has('nacionalidad') ? ' is-invalid' : ''), 'placeholder' => 'Nacionalidad']) }}
                    {!! $errors->first('nacionalidad', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('direccion') }}
                    {{ Form::text('direccion', $empleado->direccion, ['class' => 'form-control' .
                    ($errors->has('direccion') ? ' is-invalid' : ''), 'placeholder' => 'Direccion']) }}

                    {!! $errors->first('direccion', '<div class="invalid-feedback">:message</div>') !!}
                </div>
                <div class="d-none">
                    <input type="hidden" name="direccionlat" id="direccionlat" value="{{$empleado->direccionlat}}">
                    <input type="hidden" name="direccionlng" id="direccionlng" value="{{$empleado->direccionlng}}">
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('telefono') }}
                    {{ Form::text('telefono', $empleado->telefono, ['class' => 'form-control' .
                    ($errors->has('telefono') ? ' is-invalid' : ''), 'placeholder' => 'Telefono']) }}
                    {!! $errors->first('telefono', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('Padece Enfermedades') }}
                    {{ Form::text('enfermedades', $empleado->enfermedades, ['class' => 'form-control' . ($errors->has('enfermedades') ? '
                    is-invalid' : ''), 'placeholder' => 'Enfermedades que padece']) }}
                    {!! $errors->first('enfermedades', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('Padece Alergias') }}
                    {{ Form::text('alergias', $empleado->alergias, ['class' => 'form-control' . ($errors->has('alergias') ? '
                    is-invalid' : ''), 'placeholder' => 'Alergias que padece']) }}
                    {!! $errors->first('alergias', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('Persona Referencia') }}
                    {{ Form::text('persona_referencia', $empleado->persona_referencia, ['class' => 'form-control' . ($errors->has('persona_referencia') ? '
                    is-invalid' : ''), 'placeholder' => 'Nombre de Persona de Referencia']) }}
                    {!! $errors->first('persona_referencia', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="form-group">
                    {{ Form::label('Parentezco Referencia') }}
                    {!! Form::select('parentezco_referencia', ['ESPOSA'=>'ESPOSA','ESPOSO'=>'ESPOSO','HIJO/A'=>'HIJO/A','OTRO'=>'OTRO'],  $empleado->parentezco_referencia, ['class' => 'form-control' . ($errors->has('parentezco_referencia') ? '
                    is-invalid' : ''),'placeholder'=>'- Elija una opción -']) !!}
                    {!! $errors->first('parentezco_referencia', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="form-group">
                    {{ Form::label('Telef. Referencia') }}
                    {{ Form::text('telefono_referencia', $empleado->telefono_referencia, ['class' => 'form-control' . ($errors->has('telefono_referencia') ? '
                    is-invalid' : ''), 'placeholder' => 'Telefono referencia']) }}
                    {!! $errors->first('telefono_referencia', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('area') }}
                    {!! Form::select('area_id', $areas, $empleado->area_id, ['class' => 'form-control', 'required' =>
                    'required', 'placeholder' => 'Seleccione una opción']) !!}
                    {!! $errors->first('area_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
             <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('email') }}
                    {{ Form::text('email', $empleado->email, ['class' => 'form-control' . ($errors->has('email') ? '
                    is-invalid' : ''), 'placeholder' => 'Correo']) }}
                    {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('oficina') }}
                    {!! Form::select('oficina_id', $oficinas, 1, ['class' => 'form-control',
                    'required' => 'required', 'placeholder' => 'Seleccione una opción']) !!}
                    {!! $errors->first('oficina_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group{{ $errors->has('cubrerelevos') ? ' has-error' : '' }}">
                    {!! Form::label('cubrerelevos', 'Cubre Relevos') !!}
                    {!! Form::select('cubrerelevos',["0"=>"NO","1"=>"SI"],
                    $empleado->cubrerelevos?$empleado->cubrerelevos:null, ['id' => 'cubrerelevos', 'class' =>
                    'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('cubrerelevos') }}</small>
                </div>
            </div>
            <div class="col-12 col-md-6 ">
                <div class="form-group">
                    @can('users.create')
                    <label for="">Usuario</label>
                    @if ($empleado->user_id)
                    <div class="form-group">
                        <span class="badge bg-success">Generado</span>
                    </div>
                    @else
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="generarusuario">
                        <label class="form-check-label">Generar usuario</label> <br><small class="text-info">(Tomando
                            datos del correo
                            y Nro. Documento)</small>
                    </div>
                    @endif
                    @endcan
                </div>

                <div class="form-group d-none">
                    {{ Form::label('user_id') }}
                    {{ Form::text('user_id', $empleado->user_id, ['class' => 'form-control' . ($errors->has('user_id') ?
                    ' is-invalid' : ''), 'placeholder' => 'User Id']) }}
                    {!! $errors->first('user_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>

            </div>

            <div class="col-12 border mt-3">
                <div class="row mt-3">
                    <div class="col-12 col-md-4">
                        <label>Foto de Perfil</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupFileAddon01"><i
                                        class="fas fa-camera"></i></span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="perfil" name="perfil" capture="camera"
                                    aria-describedby="inputGroupFileAddon01"
                                    onchange="procesar('fotoperfil','perfil','perfil64')">
                                <label class="custom-file-label" id="labelAnverso">Click aqui!</label>
                            </div>
                        </div>
                        <div id="fotoperfil" class="d-flex justify-content-center">
                            @if ($empleado->imgperfil)
                            <img src="{{ asset('storage/' . $empleado->imgperfil) }}?v={{ now()->timestamp }}" class="img-thumbnail img-preview">
                            @else
                            <img src="{{asset('images/no-perfil.jpg')}}" class="img-thumbnail img-preview">
                            @endif
                        </div>
                        <input type="hidden" id="perfil64" name="perfil64">
                    </div>
                    <div class="col-12 col-md-4">
                        <label>Cedula anverso:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupFileAddon01"><i
                                        class="fas fa-camera"></i></span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="anverso"
                                    onchange="procesar('cedulaanverso','anverso','anverso64')">
                                <label class="custom-file-label" for="anverso">Click aqui!</label>


                            </div><input type="hidden" id="anverso64" name="anverso64">
                        </div>
                        <div id="cedulaanverso" class="d-flex justify-content-center mb-3">
                            @if ($empleado->imgperfil)
                            <img src="{{asset('storage/'.$empleado->cedulaanverso)}}" class="img-thumbnail img-preview">
                            @else
                            <img src="{{asset('images/anverso.png')}}" class="img-thumbnail img-preview2">
                            @endif
                        </div>

                    </div>
                    <div class="col-12 col-md-4">
                        <label>Cedula reverso:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupFileAddon01"><i
                                        class="fas fa-camera"></i></span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="reverso"
                                    onchange="procesar('cedulareverso','reverso','reverso64')">
                                <label class="custom-file-label" for="inputGroupFile01">Click aqui!</label>


                            </div><input type="hidden" id="reverso64" name="reverso64">
                        </div>
                        <div id="cedulareverso" class="d-flex justify-content-center mb-3">
                            @if ($empleado->imgperfil)
                            <img src="{{asset('storage/'.$empleado->cedulareverso)}}" class="img-thumbnail img-preview">
                            @else
                            <img src="{{asset('images/reverso.png')}}" class="img-thumbnail img-preview2">
                            @endif
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-12 border mt-3 mb-3">
                <label class="mt-2">Ubicación del Domicilio</label>
                {{-- SOLO CAMBIO: div de Leaflet por Google Maps --}}
                <input id="pac-input-empleado" class="form-control mb-2" type="text" placeholder="Buscar calles o barrios en la zona">
                <div id="google_map_empleado" class="border border-dark rounded-lg" style="width: 100%; height: 400px;"
                    class="mb-3">
                </div>
            </div>
        </div>

    </div>

    <div class="">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
    </div>

</div>
</div>

{{-- SOLO CAMBIO: JavaScript de Leaflet por Google Maps --}}
@section('js')
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('googlemaps.api_key') }}&libraries=places&callback=initMapEmpleado&loading=async">
</script>
<script>
    let mapEmpleado;
    let markerEmpleado;

    function initMapEmpleado() {
        // Mismas coordenadas que tenías en Leaflet (usar existentes o coordenadas por defecto de Santa Cruz)
        const initialLat = {{ $empleado->direccionlat ? $empleado->direccionlat : -17.7817999 }};
        const initialLng = {{ $empleado->direccionlng ? $empleado->direccionlng : -63.1825485 }};

        const initialPosition = {
            lat: initialLat,
            lng: initialLng
        };

        // Crear mapa con mismo zoom que tenías (14)
        mapEmpleado = new google.maps.Map(document.getElementById("google_map_empleado"), {
            zoom: 14,
            center: initialPosition,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        });

        // Crear marcador ARRASTRABLE (igual que en Leaflet)
        markerEmpleado = new google.maps.Marker({
            position: initialPosition,
            map: mapEmpleado,
            title: "Arrastra para ajustar ubicación del domicilio",
            draggable: true, // ¡IMPORTANTE! - Igual que en Leaflet
            icon: {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 35 35">
                        <defs>
                            <filter id="shadow" x="-50%" y="-50%" width="200%" height="200%">
                                <feDropShadow dx="2" dy="4" stdDeviation="3" flood-color="#000000" flood-opacity="0.3"/>
                            </filter>
                        </defs>
                        <path d="M17.5 3 C24 3 29 8 29 14.5 C29 21 17.5 32 17.5 32 C17.5 32 6 21 6 14.5 C6 8 11 3 17.5 3 Z"
                              fill="#E74C3C" filter="url(#shadow)"/>
                        <circle cx="17.5" cy="14.5" r="6" fill="#FFFFFF"/>
                        <circle cx="17.5" cy="14.5" r="4" fill="#C0392B"/>
                    </svg>
                `),
                scaledSize: new google.maps.Size(35, 35),
                anchor: new google.maps.Point(17.5, 35)
            }
        });

        // Crear el input de búsqueda (Places Autocomplete)
        const inputEmp = document.getElementById('pac-input-empleado');
        const searchBoxEmp = new google.maps.places.Autocomplete(inputEmp);
        // Intentar acotar al área mostrada
        try { searchBoxEmp.setBounds(mapEmpleado.getBounds()); } catch (e) {}

        searchBoxEmp.addListener('place_changed', function() {
            const place = searchBoxEmp.getPlace();
            if (!place.geometry) {
                console.log("No details available for input: '" + (place.name||'') + "'");
                return;
            }
            if (place.geometry.viewport) {
                mapEmpleado.fitBounds(place.geometry.viewport);
            } else {
                mapEmpleado.setCenter(place.geometry.location);
                mapEmpleado.setZoom(17);
            }
            markerEmpleado.setPosition(place.geometry.location);
            document.getElementById('direccionlat').value = place.geometry.location.lat();
            document.getElementById('direccionlng').value = place.geometry.location.lng();
        });

        // Evento cuando se termina de arrastrar el marcador (igual funcionalidad que Leaflet)
        markerEmpleado.addListener('dragend', function(event) {
            const position = markerEmpleado.getPosition();
            const lat = position.lat();
            const lng = position.lng();

            // Actualizar los campos ocultos (igual que en Leaflet)
            document.getElementById('direccionlat').value = lat;
            document.getElementById('direccionlng').value = lng;

            // Disparar evento keyup para cualquier validación (igual que tenías)
            const lngInput = document.getElementById('direccionlng');
            const event_keyup = new Event('keyup');
            lngInput.dispatchEvent(event_keyup);

            // Mostrar popup con nueva ubicación (opcional)
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 10px;">
                        <strong>Nueva ubicación del domicilio:</strong><br>
                        <small>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}</small>
                    </div>
                `
            });
            infoWindow.open(mapEmpleado, markerEmpleado);

            // Cerrar popup después de 3 segundos
            setTimeout(() => {
                infoWindow.close();
            }, 3000);
        });
    }
</script>

<script>
    function procesar(divName,idInput,id64){
    var srcEncoded;

    document.getElementById(divName).innerHTML='';

    const preview = document.getElementById(divName);
    const input = document.querySelector('#'+idInput);
        // const file = document.querySelector('#imageInput').files[0];
    for (let i = 0; i < input.files.length; i++) {
        const file = input.files[i];
        if (!file) {
            return;
        }

        const reader = new FileReader();

        reader.readAsDataURL(file);

        reader.onload = function (event) {
            const imgElement = document.createElement("img");
            imgElement.src = event.target.result;


            imgElement.onload = function (e) {
                const canvas = document.createElement("canvas");
                const MAX_WIDTH = 400;

                const scaleSize = MAX_WIDTH / e.target.width;
                canvas.width = MAX_WIDTH;
                canvas.height = e.target.height * scaleSize;

                const ctx = canvas.getContext("2d");

                ctx.drawImage(e.target, 0, 0, canvas.width, canvas.height);

                srcEncoded = ctx.canvas.toDataURL(e.target, "image/jpeg");

                // document.querySelector("#output").src = srcEncoded;
                // document.querySelector("#divOutput").classList.remove('d-none');

                const resizedImg = new Image();

                resizedImg.src = srcEncoded;
                if (divName=='fotoperfil') {
                    resizedImg.classList.add('img-preview');
                } else {
                    resizedImg.classList.add('img-preview2');
                }

                document.getElementById(id64).value=srcEncoded;

                resizedImg.classList.add('img-thumbnail');

                preview.appendChild(resizedImg);

            };
        };
    }

}
</script>
@endsection
@section('css')
<style>
    .img-preview {
        max-height: 250px;
    }

    .img-preview2 {
        max-height: 150px;
    }
</style>
@endsection
