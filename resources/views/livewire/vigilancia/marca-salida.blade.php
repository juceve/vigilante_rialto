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
        var marcaAntes = false;

        function prepararMarcado() {
            // 1. Obtener hora actual
            const ahora = new Date();

            // 2. Obtener hora del span
            const horaTexto = '{{ $designacione->turno->horafin }}';

            // 3. Separar horas y minutos
            const [hora, minutos] = horaTexto.split(":").map(Number);

            // 4. Crear fecha con esa hora (hoy)
            const horaSpan = new Date();
            horaSpan.setHours(hora, minutos, 0, 0);

            // 5. Comparar
            if (ahora < horaSpan) {
                marcaAntes=true;
                Swal.fire({
                    icon: 'error',
                    title: '🚨 ¿FINALIZAR TURNO?',
                    html: `
    <strong>MARCACIÓN ANTES DE TIEMPO</strong><br><br>
    ¿Está seguro de registrar su salida?<br> <strong>Se aplicará sanciones.</strong>
  `,
                    showCancelButton: true,
                    confirmButtonText: 'Sí Entiendo, Marcar Salida',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#ff0000',
                    backdrop: `
    rgba(0,0,0,0.9)
  `
                }).then((result) => {
                    if (result.isConfirmed) {
                        localize();
                    }
                });
            } else if (ahora >= horaSpan) {
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


        }

        function localize() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(enviar, function(error) {
                    console.error('Error obteniendo ubicación:', error);
                    Swal.fire('Advertencia', 'No se pudo obtener la ubicación. Se marcará sin coordenadas.',
                        'warning');
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
            Livewire.emit('cargaPosicion', [latitud, longitud,marcaAntes]);
        }
    </script>
@endsection
