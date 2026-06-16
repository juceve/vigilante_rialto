@extends('adminlte::page')

@section('title')
    Estado Dotaciones
@endsection
@section('content_header')
    <h4>Estado Dotaciones</h4>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Listado de Estados
                            </span>

                            <div class="float-right">
                                @can('rrhhestadodotaciones.create')
                                    <a href="{{ route('rrhhestadodotacions.create') }}" class="btn btn-info btn-sm float-right"
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

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rrhhestadodotacions as $rrhhestadodotacion)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            <td>{{ $rrhhestadodotacion->nombre }}</td>

                                            <td class="text-right">
                                                <form
                                                    action="{{ route('rrhhestadodotacions.destroy', $rrhhestadodotacion->id) }}"
                                                    class="delete" onsubmit="return false" method="POST">
                                                    @can('rrhhestadodotaciones.edit')
                                                        <a class="btn btn-sm btn-success"
                                                            href="{{ route('rrhhestadodotacions.edit', $rrhhestadodotacion->id) }}"
                                                            title="Editar"><i class="fa fa-fw fa-edit"></i></a>
                                                    @endcan

                                                    @csrf
                                                    @method('DELETE')
                                                    @can('rrhhestadodotaciones.destroy')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            title="Eliminar DB"><i class="fa fa-fw fa-trash"></i></button>
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
