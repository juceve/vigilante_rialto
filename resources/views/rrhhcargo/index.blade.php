@extends('adminlte::page')

@section('title')
    Cargos
@endsection
@section('content_header')
    <h4>Cargos</h4>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Listado de Cargos
                            </span>

                            <div class="float-right">
                                @can('rrhhcargos.create')
                                    <a href="{{ route('rrhhcargos.create') }}" class="btn btn-info btn-sm float-right"
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
                                        <th>Descripcion</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rrhhcargos as $rrhhcargo)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            <td>{{ $rrhhcargo->nombre }}</td>
                                            <td>{{ $rrhhcargo->descripcion }}</td>

                                            <td class="text-right">
                                                <form action="{{ route('rrhhcargos.destroy', $rrhhcargo->id) }}" class="delete" onsubmit="return false"
                                                    method="POST">
                                                    <a class="btn btn-sm btn-primary "
                                                        href="{{ route('rrhhcargos.show', $rrhhcargo->id) }}" title="Ver info"><i
                                                            class="fa fa-fw fa-eye"></i></a>
                                                    <a class="btn btn-sm btn-success"
                                                        href="{{ route('rrhhcargos.edit', $rrhhcargo->id) }}" title="Editar"><i
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
