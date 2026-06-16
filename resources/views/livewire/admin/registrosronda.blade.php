<div>
    @section('title')
        Registro de Rondas
    @endsection
    @section('content_header')
        <div class="container-fluid">
            <h4>Registro de Rondas</h4>
        </div>
    @endsection

    <div class="container-fluid">
        <div class="card">

            <div class="card-body">
                <label for="">Filtrar:</label>
                <div class="row">
                    <div class="col-12 col-md-3 mb-3">
                        {!! Form::select('cliente_id', $clientes, null, [
                            'class' => 'form-control',
                            'placeholder' => 'Seleccione un cliente',
                            'wire:model' => 'cliente_id',
                        ]) !!}
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Desde</span>
                            </div>
                            <input type="date" class="form-control" wire:model.debounce.999ms='inicio' aria-label="inicio"
                                aria-describedby="basic-addon1">
                        </div>

                    </div>
                    <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Hasta</span>
                            </div>
                            <input type="date" class="form-control" wire:model.debounce.999ms='final' aria-label="final"
                                aria-describedby="basic-addon1">
                        </div>

                    </div>
                    <div class="col-12 col-md-3">
                        {{-- Nuevo filtro por empleados (se llena desde el componente Livewire) --}}
                        {!! Form::select('empleado_id', $empleados ?? [], null, [
                            'class' => 'form-control',
                            'placeholder' => 'Todos los empleados',
                            'wire:model' => 'empleado_id',
                        ]) !!}
                    </div>
                </div>
                <hr>

                @if (!is_null($resultados))
                    <div class="row w-100">
                        <div class="col-12 col-md-6 mb-3">
                            <div class="input-group ">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            class="fas fa-search"></i></span>
                                </div>
                                <input type="search" class="form-control" placeholder="Busqueda..."
                                    aria-label="Busqueda..." aria-describedby="basic-addon1"
                                    wire:model.debounce.500ms='search'>
                            </div>
                        </div>
                        {{-- <div class="col-12 col-md-2 mb-3">
                            <button class="btn btn-success btn-block" wire:click='exporExcel'><i
                                    class="fas fa-file-excel"></i>
                                Exportar</button>
                        </div>--}}
                       @if ($resultados->count() > 0)
                           <div class="col-12 col-md-2 mb-3">
                               <button class="btn btn-danger btn-block" wire:click='exportarPDF'><i
                                       class="fas fa-file-pdf"></i>
                                   Exportar PDF</button>

                       @endif
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="vertical-align: middle">
                        <thead>
                            <tr class="table-info">
                                <th>ID</th>
                                <th>CLIENTE</th>
                                <th>GUARDIA</th>
                                <th class="text-center">DESCRIPCIÓN</th>
                                <th class="text-center">INICIO</th>
                                <th class="text-center">FINAL</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!is_null($resultados))
                                @forelse ($resultados as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->cliente->nombre }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td class="text-center">{{ $item->ronda->descripcion }}</td>
                                        <td class="text-center">
                                            <i class="fas fa-calendar text-primary"></i>
                                            {{ \Carbon\Carbon::parse($item->inicio)->format('d/m/Y') }} |
                                            <i class="fas fa-clock text-info"></i>
                                            {{ \Carbon\Carbon::parse($item->inicio)->format('H:i:s') }}
                                        </td>
                                        <td class="text-center">
                                            @if ($item->fin)
                                                <i class="fas fa-calendar text-warning "></i>
                                                {{ \Carbon\Carbon::parse($item->fin)->format('d/m/Y') }} |
                                                <i class="fas fa-clock text-secondary"></i>
                                                {{ \Carbon\Carbon::parse($item->fin)->format('H:i:s') }}
                                            @elseif ($item->status === 'EN_PROGRESO')
                                                <span class="badge badge-success">En progreso</span>
                                            @else
                                                <span class="badge badge-secondary">Sin registro</span>
                                            @endif

                                        </td>
                                        <td class="text-right">
                                            <button class="btn btn-info btn-sm" title="Mas detalles"
                                                wire:click="openModal({{ $item->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="7">No se econtraron resultados.</td>
                                    </tr>
                                @endforelse
                            @else
                                <tr>
                                    <td class="text-center" colspan="7">No se econtraron resultados.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                @if (!is_null($resultados))
                    {{ $resultados->links() }}
                @endif

            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="modalIframe" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Detalles del Recorrido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    @if ($modalUrl)
                        <iframe id="iframeModal" src="{{ $modalUrl }}"
                            style="width:100%; height:80vh; border:none;"></iframe>
                    @endif

                </div>
            </div>
        </div>
    </div>



</div>
@section('js')
    <script>
        // Escuchar evento de Livewire para abrir modal
        window.addEventListener('openModal', event => {
            $('#modalIframe').modal('show');
        });

        // Limpiar iframe al cerrar para liberar memoria
        $('#modalIframe').on('hidden.bs.modal', function() {
            document.getElementById('iframeModal').src = '';
            @this.call('closeModal');
        });
    </script>

    <script>
    Livewire.on('renderizarpdf', () => {
            var win = window.open("../pdf/rondasejecutadas/", '_blank');
            win.focus();
        });
</script>
@endsection
