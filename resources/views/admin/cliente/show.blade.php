@extends('adminlte::page')

@section('title')
    Información Cliente
@endsection
@section('content_header')
    <h4>Información Cliente</h4>
@endsection
@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Datos Cliente
                            </span>

                            <div class="float-right">
                                <a href="javascript:history.back()" class="btn btn-info btn-sm float-right"
                                    data-placement="left">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col col-12 col-md-6">
                                <div class="form-group">
                                    <strong>Nombre:</strong>
                                    {{ $cliente->nombre }}
                                </div>
                                <div class="form-group">
                                    <strong>Tipo Documento:</strong>
                                    {{ $cliente->tipodocumento->name }}
                                </div>
                                <div class="form-group">
                                    <strong>Nro. Documento:</strong>
                                    {{ $cliente->nrodocumento }}
                                </div>
                                <div class="form-group">
                                    <strong>Dirección:</strong>
                                    {{ $cliente->direccion }}
                                </div>
                                <div class="form-group">
                                    <strong>U.V.:</strong>
                                    {{ $cliente->uv }}
                                </div>
                                <div class="form-group">
                                    <strong>Manzano:</strong>
                                    {{ $cliente->manzano }}
                                </div>
                            </div>
                            <div class="col col-12 col-md-6">
                                <div class="form-group">
                                    <strong>Persona Contacto:</strong>
                                    {{ $cliente->personacontacto }}
                                </div>
                                <div class="form-group">
                                    <strong>Teléfono Contacto:</strong>
                                    {{ $cliente->telefonocontacto }}
                                </div>
                                <div class="form-group">
                                    <strong>Oficina Vinculada:</strong>
                                    {{ $cliente->oficina->nombre }}
                                </div>
                                <div class="form-group">
                                    <strong>Observaciones:</strong>
                                    {{ $cliente->observaciones }}
                                </div>
                                <div class="form-group">
                                    <strong>Estado:</strong>
                                    @if ($cliente->status)
                                        <span class="badge badge-pill badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-pill badge-secondary">Inactivo</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group d-none">
                            <strong>Latitud:</strong>
                            {{ $cliente->latitud }}
                        </div>
                        <div class="form-group d-none">
                            <strong>Longitud:</strong>
                            {{ $cliente->longitud }}
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="mapa">Ubicación del Domicilio:</label>
                            {{-- SOLO CAMBIO: div de Leaflet por Google Maps --}}
                            <div id="google_map" class="border" style="width: 100%; height: 500px;"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

{{-- SOLO CAMBIO: JavaScript de Leaflet por Google Maps --}}
@section('js')
<script async defer 
    src="https://maps.googleapis.com/maps/api/js?key={{ config('googlemaps.api_key') }}&callback=initMap&loading=async">
</script>
<script>
    let map;

    function initMap() {
        // Mismas coordenadas del cliente que tenías en Leaflet
        const clientePosition = { 
            lat: {{ $cliente->latitud }}, 
            lng: {{ $cliente->longitud }} 
        };
        
        map = new google.maps.Map(document.getElementById("google_map"), {
            zoom: 17, // Mismo zoom que tenías en Leaflet
            center: clientePosition,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        });

        // Crear marcador con el mismo icono que tenías
        const marker = new google.maps.Marker({
            position: clientePosition,
            map: map,
            title: "{{ $cliente->nombre }}", // Título del marcador
            icon: {
                url: "{{ asset('images/punt.png') }}", // Mismo icono que tenías
                scaledSize: new google.maps.Size(39, 39), // Mismo tamaño (35x35)
                anchor: new google.maps.Point(17, 35) // Ajuste del anclaje
            }
        });

        // Popup opcional con información del cliente
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="max-width: 200px;">
                    <h6>{{ $cliente->nombre }}</h6>
                    <p><strong>Dirección:</strong><br>{{ $cliente->direccion }}</p>
                    <p><strong>Contacto:</strong><br>{{ $cliente->personacontacto }}</p>
                </div>
            `
        });

        // Mostrar popup al hacer clic en el marcador
        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });
    }
</script>
@endsection
