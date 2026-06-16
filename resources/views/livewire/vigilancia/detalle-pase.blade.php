<div>
    @section('title')
    Detalles del Pase
    @endsection

    <!-- Header Corporativo -->
    <div
        style="margin-top: 85px; background: linear-gradient(135deg, #1e3a8a, #1e293b); padding: 1rem 0; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(30, 41, 59, 0.1);">
        <div class="container">
            <div
                style="display: flex; align-items: center; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 12px; padding: 1rem; box-shadow: 0 2px 8px rgba(30, 41, 59, 0.1);">
                <a href="{{ route('vigilancia.controlpases', $designacione_id) }}"
                    style="width: 50px; height: 50px; background: linear-gradient(135deg, #1e3a8a, #1e293b); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; font-size: 1.2rem; margin-right: 1rem;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div style="flex: 1; text-align: center;">
                    <h1 style="font-size: 1.2rem; font-weight: 700; color: #1e293b; margin: 0; letter-spacing: 0.5px;">
                        PASE DE ACCESO <br> N°
                        {{ $paseingreso->residencia->cliente->id . '-' . cerosIzq2($paseingreso->id) }}
                    </h1>
                    <p style="font-size: 1.1rem;  margin: 0.2rem 0 0 0; font-weight: 500;">TIPO:
                        {{ $paseingreso->motivo->nombre }}</p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="text-center text-primary">Información del Pase</h4>

    <div class="table-responsive p-3">
        <table class="table table-bordered table-striped" style="font-size: 13px;">
            <tr>
                <td colspan="2">
                    <strong>Nombre:</strong> {{ $paseingreso->nombre }}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Cedula:</strong> {{ $paseingreso->cedula }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Inicia:</strong> {{ $paseingreso->fecha_inicio }}
                </td>

                <td>
                    <strong>Expira:</strong> {{ $paseingreso->fecha_fin }}
                </td>
            </tr>

            <tr>
                <td>
                    <strong>Detalles:</strong> {{ $paseingreso->detalles ?? 'N/A' }}
                </td>


                <td>
                    <strong>Unico Uso:</strong>
                    @if ($paseingreso->usounico)
                    <span class="badge rounded-pill text-bg-primary">SI</span>
                    @else
                    <span class="badge rounded-pill text-bg-warning">NO</span>
                    @endif
                </td>

            </tr>
            @if ($flujos)
            <tr>
                <td colspan="2">
                    <strong>Ultimo registro:</strong>
                    {{ $flujos->tipo . ' | ' . $flujos->fecha . ' | ' . $flujos->hora }}
                </td>
            </tr>
            @endif

        </table>
    </div>

    <h4 class="text-center text-blue mt-4">Datos de la Residencia</h4>
    <div class="table-responsive p-3">
        <table class="table table-bordered table-striped" style="font-size: 13px;">
            <tr>
                <td colspan="2">
                    <strong>Propietario:</strong> {{ $paseingreso->residencia->propietario->nombre }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Telefono:</strong> {{ $paseingreso->residencia->propietario->telefono }}
                </td>
                <td>
                    <strong>Ciudad:</strong> {{ $paseingreso->residencia->propietario->ciudad }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Nro. Puerta:</strong> {{ $paseingreso->residencia->numeropuerta ?? '-' }}
                </td>

                <td>
                    <strong>Piso:</strong> {{ $paseingreso->residencia->piso ?? '-' }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Calle:</strong> {{ $paseingreso->residencia->calle ?? '-' }}
                </td>

                <td>
                    <strong>Nro. Lote:</strong> {{ $paseingreso->residencia->nrolote ?? '-' }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Manzano:</strong> {{ $paseingreso->residencia->manzano ?? '-' }}
                </td>

                <td>
                    <strong>Estado:</strong> {{ $paseingreso->residencia->estado }}
                </td>
            </tr>

        </table>
    </div>
    <div class="container-fluid">
        <label>
            @if ($flujos && $flujos->tipo == 'INGRESO')
            <small>Anotaciones de Salida</small>
            @else
            <small>Anotaciones de Ingreso</small>
            @endif
        </label>
        <textarea class="form-control" id="" rows="2" maxlength="255" wire:model='anotaciones'
            placeholder="Describa brevemente detalles adicionales de la visita."></textarea>
    </div>
    <div class="d-grid p-3">
        @if ($flujos && $flujos->tipo == 'INGRESO')
        <button class="btn btn-danger" onclick='marcado("SALIDA")'>Marcar Salida <br><i
                class="fas fa-sign-out-alt fs-2"></i></button>
        @else
        <button class="btn btn-success" onclick='marcado("INGRESO")'>Marcar Ingreso <br><i
                class="fas fa-sign-in-alt fs-2"></i></button>
        @endif


        {{--
        <button class="btn btn-danger" onclick='marcado("SALIDA")'>Marcar Salida <br><i
                class="fas fa-sign-out-alt fs-2"></i></button> --}}

    </div>
    <br>

</div>
@section('js')
<script>
    function marcado(tipo) {
            Swal.fire({
                title: "Marcar " + tipo,
                text: "¿Seguro de realizar esta operación?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, continuar",
                cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('marcar', tipo);
                }
            });
        }
</script>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styleVigilancia.css') }}">
@endpush