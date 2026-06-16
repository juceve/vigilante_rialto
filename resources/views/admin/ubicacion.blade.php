<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ubicacion en el Mapa</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css">
</head>

<body>
    <div id="mi_mapa" style="width: 100%; height: 330px"></div>


    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    {{-- <script>
        function initMap() {
            var latitud = {{ $lat }};
            var longitud = {{ $lng }};

            coordenadas = {
                lng: longitud,
                lat: latitud,
            }

            generarMapa(coordenadas);
        }

        function generarMapa() {
            var mapa = new google.maps.Map(document.getElementById('mapa'), {
                zoom: 21,
                center: new google.maps.LatLng(coordenadas.lat, coordenadas.lng)
            });

            var marcador = new google.maps.Marker({
                map: mapa,
                draggable: false,
                position: new google.maps.LatLng(coordenadas.lat, coordenadas.lng)
            })

            // marcador.addListener('dragend', function(event) {
            //     document.getElementById('latitud').value = this.getPosition().lat();

            //     document.getElementById('longitud').value = this.getPosition().lng();
            // })
        }
    </script> --}}

    <script>
        let map = L.map('mi_mapa').setView([{{ $lat }}, {{ $lng }}], 18)

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy;'
        }).addTo(map);

       

        var myIcon = L.icon({
            iconUrl: "{{ asset('images/punt.png') }}",
            iconSize: [35, 35],
            iconAnchor: [35, 35],
            popupAnchor: [-15, -30],
        });

        L.marker([{{ $lat }}, {{ $lng }}]).addTo(map);
        // map.on('click', onMapClick)

        // function onMapClick(e) {
        //     alert("Posición: " + e.latlng)
        // }
    </script>
</body>

</html>

