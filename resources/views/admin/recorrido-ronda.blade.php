<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detalle Ronda Ejecutada</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

</head>

<body>
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-12 col-md-3">
                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><strong>ID:</strong></span>
                    </div>
                    <input type="text" class="form-control bg-white" value="{{ $rondaejecutada->id }}"
                        readonly>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><strong>Inicio:</strong></span>
                    </div>
                    <input type="text" class="form-control bg-white" value="{{ $rondaejecutada->inicio }}" readonly>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><strong>Final:</strong></span>
                    </div>
                    <input type="text" class="form-control bg-white" value="{{ $rondaejecutada->fin ?? '--' }}"
                        readonly>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><strong>Estado:</strong></span>
                    </div>
                    <input type="text" class="form-control bg-white"
                        value="{{ str_replace('_', ' ', $rondaejecutada->status) }}" readonly>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><strong>Establecimiento:</strong></span>
                    </div>
                    <input type="text" class="form-control bg-white" value="{{ $rondaejecutada->cliente->nombre }}"
                        readonly>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><strong>Guardia:</strong></span>
                    </div>
                    <input type="text" class="form-control bg-white" value="{{ $rondaejecutada->user->name }}"
                        readonly>
                </div>
            </div>

        </div>
    </div>
    <div class="container-fluid">
        <div id="map" style="height: 80vh; width: 100%;" class="border"></div>
    </div>

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('googlemaps.api_key') }}"></script>
    <script>
        let ubicaciones = @json($rondaejecutada->rondaejecutadaubicaciones);
    </script>
  
    <script>
        function initMap() {
            // if (ubicaciones.length === 0) return;

            // Coordenadas de inicio y fin
            const start = {
                lat: parseFloat({{ $rondaejecutada->latitud_inicio }}),
                lng: parseFloat({{ $rondaejecutada->longitud_inicio }})
            };
            const end = {
                lat: parseFloat({{ $rondaejecutada->latitud_fin }}),
                lng: parseFloat({{ $rondaejecutada->longitud_fin }})
            };

            // Crear mapa centrado en el inicio
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 21,
                center: start,
            });

            // Íconos personalizados
            const iconInicio = {
                url: "https://cdn-icons-png.flaticon.com/512/190/190411.png",
                scaledSize: new google.maps.Size(35, 35)
            };
            const iconFin = {
                url: "https://cdn-icons-png.flaticon.com/512/190/190406.png",
                scaledSize: new google.maps.Size(35, 35)
            };
            const iconPunto = {
                url: "{{ asset('images/point.png') }}",
                scaledSize: new google.maps.Size(40, 40)
            };

            // Crear infoWindow global
            const infoWindow = new google.maps.InfoWindow();


            // Marcador de inicio con popup
            const markerInicio = new google.maps.Marker({
                position: start,
                map,
                title: "Inicio",
                icon: iconInicio
            });
            markerInicio.addListener("click", () => {
                infoWindow.setContent(
                    `<b>INICIO RONDA</b><br>Fecha Hora: {{ $rondaejecutada->inicio }}<br>Latitud: {{ $rondaejecutada->latitud_inicio }}<br>Longitud: {{ $rondaejecutada->longitud_inicio }}`
                );
                infoWindow.open(map, markerInicio);
            });

            // Marcador de fin con popup
            const markerFin = new google.maps.Marker({
                position: end,
                map,
                title: "Fin",
                icon: iconFin
            });
            markerFin.addListener("click", () => {
                infoWindow.setContent(
                    `<b>FIN DE RONDA</b><br>Fecha Hora: {{ $rondaejecutada->fin }}<br>Latitud: {{ $rondaejecutada->latitud_fin ?? '-' }}<br>Longitud: {{ $rondaejecutada->longitud_fin ?? '-' }}`
                );
                infoWindow.open(map, markerFin);
            });

            // Puntos intermedios
            if (ubicaciones.length > 0) {
                const pathCoords = ubicaciones.map((u, index) => ({
                    lat: parseFloat(u.latitud),
                    lng: parseFloat(u.longitud),
                    numero: index + 1, // numeración correlativa comenzando en 1
                    info: `<b>PUNTO ${index + 1}</b><br>Fecha Hora: ${u.fecha_hora}<br>Lat: ${u.latitud}<br>Lng: ${u.longitud}`
                }));

                pathCoords.forEach(coord => {
                    const marker = new google.maps.Marker({
                        position: coord,
                        map,
                        icon: iconPunto
                    });
                    marker.addListener("click", () => {
                        infoWindow.setContent(coord.info);
                        infoWindow.open(map, marker);
                    });
                });
                // Dibujar polilínea del recorrido

                const recorridoCoords = [start, ...pathCoords, end];
                const recorrido = new google.maps.Polyline({
                    path: recorridoCoords,
                    geodesic: true,
                    strokeColor: "#00BFFF", // Celeste
                    strokeOpacity: 1.0,
                    strokeWeight: 6 // Grosor
                });
                recorrido.setMap(map);
            }



            // Ajustar bounds para mostrar todo el recorrido
            const bounds = new google.maps.LatLngBounds();
            recorridoCoords.forEach(pt => bounds.extend(pt));
            map.fitBounds(bounds);

            // Crear recorrido completo (inicio → intermedios → fin)

        }

        // Inicializar mapa al cargar la página
        window.onload = initMap;
    </script>
</body>

</html>
