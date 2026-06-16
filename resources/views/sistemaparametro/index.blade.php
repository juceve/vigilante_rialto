@extends('adminlte::page')

@section('title')
    Parametros del Sistema
@endsection
@section('content_header')
    <h4>Parametros del Sistema</h4>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Listado de Parametros
                            </span>

                            <div class="float-right">

                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dataTable">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>

                                        <th>Tolerancia Ingreso</th>
                                        <th>Telf. Panico</th>
                                        <th>Desc No Marcar Salida</th>
                                        <th>Desc Inasistencia</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sistemaparametros as $sistemaparametro)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            <td>{{ $sistemaparametro->tolerancia_ingreso }}</td>
                                            <td>{{ $sistemaparametro->telefono_panico }}</td>
                                            <td>{{ $sistemaparametro->asistencia_sin_salida }}</td>
                                            <td>{{ $sistemaparametro->falta_dia_completo }}</td>

                                            <td class="text-right">
                                                @can('sistemaparametros.edit')
                                                    <a class="btn btn-sm btn-success"
                                                    href="{{ route('sistemaparametros.edit', $sistemaparametro->id) }}"
                                                    title="Editar"><i class="fa fa-fw fa-edit"></i> Editar</a>
                                                @endcan

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $sistemaparametros->links() !!}
            </div>
        </div>
    </div>
@endsection
