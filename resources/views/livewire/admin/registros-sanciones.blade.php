<div>
    @section('title')
        Registro de Sanciones
    @endsection
    @section('content_header')
        <div class="container-fluid">

            <div style="display: flex; justify-content: space-between; align-items: center;" class="mb-2 mt-2">
                <h4>Registro de Sanciones</h4>


            </div>
        </div>
    @endsection

    <div class="container-fluid">
        <div class="card">

            <div class="card-body">
                <label for="">Filtrar:</label>
                <div class="row">
                    {{-- <div class="col-12 col-md-3 mb-3">
                        <input type="search" class="form-control" wire:model='search'>
                    </div> --}}
                    <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Desde</span>
                            </div>
                            <input type="date" class="form-control" wire:model='inicio' aria-label="inicio"
                                aria-describedby="basic-addon1">
                        </div>

                    </div>
                    <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Hasta</span>
                            </div>
                            <input type="date" class="form-control" wire:model='final' aria-label="final"
                                aria-describedby="basic-addon1">
                        </div>

                    </div>
                    <div class="col-12 col-md-3">
                        {!! Form::select('estado', ['' => 'Todos', '1' => 'Activos', '0' => 'Inactivos'], null, [
                            'class' => 'form-control',
                            'wire:model' => 'estado',
                        ]) !!}
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    @if ($resultados->count() > 0)
                        <div class="row w-100">
                            <div class="col-12 col-md-8 mb-3">
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
                            <div class="col-12 col-md-2 mb-3">
                                <button class="btn btn-success btn-block" wire:click='exporExcel'><i
                                        class="fas fa-file-excel"></i>
                                    Exportar</button>
                            </div>
                            <div class="col-12 col-md-2 mb-3">
                                <a href="{{ route('pdf.sanciones') }}" class="btn btn-danger btn-block"
                                    target="_blank"><i class="fas fa-file-pdf"></i> Exportar</a>
                            </div>
                        </div>

                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="vertical-align: middle">
                        <thead>
                            <tr class="table-info">
                                <th class="text-center">ID</th>
                                <th class="text-center">FECHA</th>
                                <th>EMPLEADO</th>
                                <th>TIPO</th>
                                <th class="text-right">MONTO</th>
                                <th class="text-center">ESTADO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!is_null($resultados))
                                @forelse ($resultados as $item)
                                    <tr>
                                        <td class="text-center">{{ $item->id }}</td>
                                        <td class="text-center">{{ $item->fecha }}</td>

                                        <td>{{ $item->empleado?->nombres . ' ' . $item->empleado?->apellidos }}</td>
                                        <td>{{ $item->rrhhtipodescuento ? $item->rrhhtipodescuento->nombre : 'NULL' }}
                                        </td>
                                        <td class="text-right">{{ number_format($item->cantidad * $item->monto, 2) }}
                                        </td>
                                        <td class="text-center">
                                            @if ($item->estado)
                                                <span class="badge badge-pill badge-success">ACTIVO</span>
                                            @else
                                                <span class="badge badge-pill badge-secondary">INACTIVO</span>
                                            @endif
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


</div>
@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/ekko-lightbox/ekko-lightbox.css') }}">
@endsection
@section('js')
    <script src="{{ asset('vendor/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
    <script>
        $(function() {
            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox({
                    alwaysShowClose: true
                });
            });
        })
    </script>
@endsection
