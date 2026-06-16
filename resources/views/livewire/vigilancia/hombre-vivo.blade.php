{{-- filepath: c:\laragon\www\vigilantev2\resources\views\livewire\vigilancia\hombre-vivo.blade.php --}}
<div>
    @section('title')
        Hombre Vivo
    @endsection

    <!-- Header Corporativo -->
    <div style="margin-top: 85px; background: linear-gradient(135deg, #1e3a8a, #1e293b); padding: 1rem 0; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(30, 41, 59, 0.1);">
        <div class="container">
            <div style="display: flex; align-items: center; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 12px; padding: 1rem; box-shadow: 0 2px 8px rgba(30, 41, 59, 0.1);">
                <a href="{{ route('home') }}" style="width: 50px; height: 50px; background: linear-gradient(135deg, #1e3a8a, #1e293b); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; font-size: 1.2rem; margin-right: 1rem;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div style="flex: 1; text-align: center;">
                    <h1 style="font-size: 1.3rem; font-weight: 700; color: #1e293b; margin: 0; letter-spacing: 0.5px;">HOMBRE VIVO</h1>
                    <p style="font-size: 0.85rem; color: #64748b; margin: 0.2rem 0 0 0; font-weight: 500;">Control de Estado Activo</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container px-3">
        @if ($intervalo)
            <div style="background: white; border-radius: 16px; box-shadow: 0 4px 16px rgba(30, 41, 59, 0.15); overflow: hidden; border: 2px solid #059669;">
                <div style="background: linear-gradient(135deg, #059669, #047857); color: white; padding: 1.5rem; text-align: center;">
                    <div style="display: flex; align-items: center; justify-content: center; gap: 0.8rem; margin-bottom: 0.5rem;">
                        <i class="fas fa-heartbeat" style="font-size: 1.5rem; animation: pulse 2s infinite;"></i>
                        <h4 style="margin: 0; font-weight: 600;">HORA PROGRAMADA</h4>
                    </div>
                    <h3 style="margin: 0; font-size: 1.5rem; font-weight: 700;">{{ $intervalo->hora }}</h3>
                </div>
                <div style="padding: 2rem;">
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; color: #1e293b; margin-bottom: 0.8rem; font-size: 0.9rem;">
                            <i class="fas fa-edit" style="color: #d97706; width: 16px; text-align: center;"></i>
                            Anotaciones:
                        </label>
                        <textarea class="form-control" wire:model='anotaciones' rows="4"
                                placeholder="Ingrese observaciones sobre su estado y situación actual..."
                                style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 0.8rem 1rem; font-size: 0.9rem; transition: all 0.3s ease; background: white; min-height: 100px; resize: vertical; font-family: inherit; line-height: 1.5;"
                                onfocus="this.style.borderColor='#d97706'; this.style.boxShadow='0 0 0 3px rgba(217, 119, 6, 0.1)'"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="button" onclick="activarGeolocalizacionYReportarse()"
                                style="background: linear-gradient(135deg, #059669, #047857); color: white; border: none; border-radius: 12px; padding: 1rem 2rem; font-size: 1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; gap: 0.8rem; box-shadow: 0 4px 16px rgba(5, 150, 105, 0.3); min-height: 60px;"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 24px rgba(5, 150, 105, 0.4)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(5, 150, 105, 0.3)'"
                                onmousedown="this.style.transform='translateY(0)'"
                                onmouseup="this.style.transform='translateY(-2px)'">
                            <i class="fas fa-heartbeat" style="font-size: 1.2rem; animation: pulse 2s infinite;"></i>
                            <span>REPORTARSE ACTIVO</span>
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 2px 8px rgba(30, 41, 59, 0.1); margin: 2rem 0; display: flex; align-items: center; gap: 1rem; border-left: 4px solid #059669;">
                <div style="font-size: 2.5rem; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #059669, #047857); color: white;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h5 style="font-weight: 700; margin-bottom: 0.5rem; color: #1e293b;">Estado Activo</h5>
                    <p style="margin: 0; color: #64748b; font-weight: 500;">NO CUENTA CON PETICIONES ACTIVAS</p>
                </div>
            </div>
        @endif
    </div>

    <style>
        @media (max-width: 768px) {
            .container {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
        }
    </style>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styleVigilancia.css') }}">
@endpush

@section('js')
    @if ($intervalo)
        <script>
            // Función que se ejecuta cuando el usuario hace clic en "Reportarse Activo"
            function activarGeolocalizacionYReportarse() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(success2, function(error) {
                        console.error('Error obteniendo ubicación:', error);
                        // Reportarse sin ubicación si falla la geolocalización
                        reportarseActivo();
                    });
                } else {
                    console.warn('El navegador no soporta geolocalización');
                    // Reportarse sin ubicación si no hay soporte
                    reportarseActivo();
                }
            }

            function success2(geoLocationPosition) {
                // console.log(geoLocationPosition.timestamp);
                let data = [
                    geoLocationPosition.coords.latitude,
                    geoLocationPosition.coords.longitude,
                ];

                // Emitir evento y esperar respuesta usando promesa
                @this.call('ubicacionAprox', data).then(() => {
                    // Ahora que las coordenadas están guardadas, reportarse
                    reportarseActivo();
                });
            }

            function reportarseActivo() {
                @this.reportarse();
            }
        </script>
    @endif
@endsection
