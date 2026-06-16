<div>
    <form action="" onsubmit="return false">
        <div class="modal-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" class="form-control" id="nombre" placeholder="Nombre de Punto" required>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label>Hora:</label>
                        <input type="time" class="form-control" id="hora" required>
                    </div>
                </div>
                <div class="col-12 col-md-6 ">
                    <div class="form-group">
                        <label>Latitud:</label>
                        <input type="text" class="form-control" id="latitud" required
                            placeholder="Seleccione un punto en el Mapa">
                    </div>
                </div>
                <div class="col-12 col-md-6 ">
                    <div class="form-group">
                        <label>Longitud:</label>
                        <input type="text" class="form-control" id="longitud" required
                            placeholder="Seleccione un punto en el Mapa">
                    </div>
                </div>
            </div>
            <div class="form-group" wire:ignore>
                <div class="border" id="mi_mapa" style="width: 100%; height: 400px"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times-circle"></i>
                Cerrar</button>

            <button class="btn btn-info" onclick='registrar()'>
                <i class="fas fa-save"></i> Registrar
            </button>
        </div>
    </form>
</div>
@section('plugins.OpenStreetMap', true) 
@section('js')
{{-- --}}
<script>
    let map = L.map('mi_mapa').setView([{{ $cliente->latitud }}, {{ $cliente->longitud }}], 17)

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy;'
    }).addTo(map);

    var marker = L.marker([{{ $cliente->latitud }}, {{ $cliente->longitud }}], {
        "draggable": true
    }).addTo(map);

    marker.on('dragend', function(event) {
        var position = marker.getLatLng();
        marker.setLatLng(position, {
            draggable: 'true'
        }).bindPopup(position).update();
        $("#latitud").val(position.lat);
        $("#longitud").val(position.lng).keyup();
    });
</script>
@endsection
