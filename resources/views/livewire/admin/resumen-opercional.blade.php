<div>
    @section('title')
    Resumen Operacional
    @endsection
    @section('content_header')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Resumen Operacional</h4>
            {{-- <div class="">
                <a href="{{ route('clientes.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i>
                    Volver</a>

            </div> --}}
        </div>
    </div>
    @endsection
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <label for="">Filtrar:</label>
                <div class="row">
                    <div class="col-12 col-md-3 mb-3">
                        {!! Form::select('cliente_id', $clientes, null,
                        ['class'=>'form-control','placeholder'=>'- Todos los Clientes -','wire:model'=>'cliente_id']) !!}
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Desde</span>
                            </div>
                            <input type="date" class="form-control" wire:model='fechaInicio' aria-label="fechaInicio">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Hasta</span>
                            </div>
                            <input type="date" class="form-control" wire:model='fechaFin' aria-label="fechaFin">
                        </div>
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        <button class="btn btn-primary btn-block" wire:click='generarResumen'>Generar <i
                                class="fas fa-cogs"></i></button>
                    </div>
                </div>
                <hr>
                <div class="row mb-2">
                    <div class="col-12 col-md-9">
                    </div>
                    <div class="col-12 col-md-3">
                        @if ($resultados)
                        <button class="btn btn-sm btn-success btn-block" wire:click='exportarExcel'>
                            <i class="fas fa-file-excel"></i> Exportar
                        </button>
                        @endif
                    </div>

                </div>
                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-sm " style="vertical-align: middle; font-size: 12px;">
                        <thead>
                            <tr class="table-info">
                                <th></th>
                                <th colspan="7" class="text-center">
                                    CANTIDAD DE REGISTROS
                                </th>
                            </tr>
                            <tr class="table-info text-center">
                                <th>CLIENTE</th>
                                <th>RONDAS</th>
                                <th>VISITAS</th>
                                <th>PASES QR</th>
                                <th>PANICOS</th>
                                <th>TAREAS</th>
                                <th>NOVEDADES</th>
                                <th>HOMBRE VIVO</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @if ($resultados) --}}
                            @forelse ($resultados as $item)
                            <tr>
                                <td>{{$item['cliente']}}</td>
                                <td class="text-center align-middle">{{$item['rondas']}}</td>
                                <td class="text-center align-middle">{{$item['visitas']}}</td>
                                <td class="text-center align-middle">{{$item['flujopases']}}</td>
                                <td class="text-center align-middle">{{$item['panicos']}}</td>
                                <td class="text-center align-middle">{{$item['tareas']}}</td>
                                <td class="text-center align-middle">{{$item['novedades']}}</td>
                                <td class="text-center align-middle">{{$item['hombrevivos']}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No existen datos para mostrar</td>
                            </tr>
                            @endforelse
                            {{-- @endif --}}

                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        {{-- @if (!is_null($rows))
                        {{ $rows->links() }}
                        @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>