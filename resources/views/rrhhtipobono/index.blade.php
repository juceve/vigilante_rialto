@extends('adminlte::page')

@section('title')
    Tipo de Bonos
@endsection
@section('content_header')
    <h4>Tipo de Bonos</h4>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Listado de Tipos de Bono
                            </span>

                            <div class="float-right">
                                @can('rrhhtipobonos.create')
                                    <a href="{{ route('rrhhtipobonos.create') }}" class="btn btn-info btn-sm float-right"
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
                                        <th class="text-right">Monto</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rrhhtipobonos as $rrhhtipobono)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            <td>{{ $rrhhtipobono->nombre }}</td>
                                            <td>{{ $rrhhtipobono->nombre_corto }}</td>
                                            <td class="text-right">{{ number_format($rrhhtipobono->monto,2,'.') }}</td>

                                            <td class="text-right">
                                                <form action="{{ route('rrhhtipobonos.destroy', $rrhhtipobono->id) }}" class="delete" onsubmit="return false"
                                                    method="POST">

                                                    <a class="btn btn-sm btn-success"
                                                        href="{{ route('rrhhtipobonos.edit', $rrhhtipobono->id) }}" title="Editar"><i
                                                            class="fa fa-fw fa-edit"></i></a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar DB"><i
                                                            class="fa fa-fw fa-trash"></i></button>
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
