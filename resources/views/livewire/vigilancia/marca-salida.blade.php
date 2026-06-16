<div class="container mt-3">
    <div class="row">
        <div class="col-12 text-center d-grid">
            <button class="btn btn-secondary btn-sm d-grid py-4" onclick="prepararMarcado()">
                <div class="row">
                    <div class="col-6 text-start">Marcar Salida</div>
                    <div class="col-6 text-end">
                        <small class="text-danger"><b>{{ $designacione->turno->horafin }} Hrs.</b></small>
                    </div>
                </div>
            </button>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styleVigilancia.css') }}">
@endpush

@section('js')
<script>
    function prepararMarcado() {
        Swal.fire({
            title: "FINALIZAR TURNO",
            text: "¿Está seguro de realizar el marcado de salida? Se obtendrá su ubicación actual.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "SI, marcar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                localize();
            }
        });
    }

    function localize() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(enviar, function(error) {
                console.error('Error obteniendo ubicación:', error);
                Swal.fire('Advertencia', 'No se pudo obtener la ubicación. Se marcará sin coordenadas.', 'warning');
                Livewire.emit('cargaPosicion', [null, null]);
            });
        } else {
            Swal.fire('Error', 'Tu navegador no soporta geolocalización.', 'error');
            Livewire.emit('cargaPosicion', [null, null]);
        }
    }

    function enviar(pos) {
        let latitud = pos.coords.latitude;
        let longitud = pos.coords.longitude;
        Livewire.emit('cargaPosicion', [latitud, longitud]);
    }
</script>
@endsection
