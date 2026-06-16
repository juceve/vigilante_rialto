@extends('adminlte::page')

@section('title')
    Tipo de Descuentos
@endsection
@section('content_header')
    <h4>Tipo de Descuentos</h4>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Listado de Tipos de Descuentos
                            </span>

                            <div class="float-right">
                                @can('rrhhtipodescuentos.create')
                                    <a href="{{ route('rrhhtipodescuentos.create') }}" class="btn btn-info btn-sm float-right"
                                        data-placement="left">
                                        Nuevo <i class="fas fa-plus"></i>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dataTable">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>

                                        <th>Nombre</th>
                                        <th>Nombre Corto</th>
                                        <th>Monto</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rrhhtipodescuentos as $rrhhtipodescuento)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            <td>{{ $rrhhtipodescuento->nombre }}</td>
                                            <td>{{ $rrhhtipodescuento->nombre_corto }}</td>
                                            <td>{{ $rrhhtipodescuento->monto }}</td>

                                            <td class="text-right">
                                                <form
                                                    action="{{ route('rrhhtipodescuentos.destroy', $rrhhtipodescuento->id) }}"
                                                    class="delete" onsubmit="return false;" method="POST">
                                                    @can('rrhhtipodescuentos.edit')
                                                        <a class="btn btn-sm btn-success"
                                                            href="{{ route('rrhhtipodescuentos.edit', $rrhhtipodescuento->id) }}"
                                                            title="Editar"><i class="fa fa-fw fa-edit"></i> </a>
                                                    @endcan

                                                    @csrf
                                                    @method('DELETE')
                                                    @can('rrhhtipodescuentos.destroy')
                                                       <button type="submit" class="btn btn-danger btn-sm" title="Eliminar"><i
                                                            class="fa fa-fw fa-trash"></i> </button>
                                                    @endcan

                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
