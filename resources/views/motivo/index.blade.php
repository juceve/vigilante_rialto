@extends('adminlte::page')
@section('title')
    Motivo de Vista
@endsection
@section('content_header')
    <h4>Motivo de Vista</h4>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Listado de Motivos
                            </span>

                            <div class="float-right">
                                @can('motivos.create')
                                    <a href="{{ route('motivos.create') }}" class="btn btn-primary btn-sm float-right"
                                        data-placement="left">
                                        Nuevo <i class="fas fa-plus"></i>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>


                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr class="table-info">
                                        <th>No</th>

                                        <th>Nombre</th>
                                        <th>Estado</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($motivos as $motivo)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            <td>{{ $motivo->nombre }}</td>
                                            <td>{{ $motivo->estado ? 'Activo' : 'Inactivo' }}</td>

                                            <td class="text-right">
                                                <form class="delete" onsubmit="return false;"
                                                    action="{{ route('motivos.destroy', $motivo->id) }}" method="POST">

                                                    @can('motivos.edit')
                                                        <a class="btn btn-sm btn-success"
                                                            href="{{ route('motivos.edit', $motivo->id) }}" title="Editar"><i
                                                                class="fa fa-fw fa-edit"></i></a>
                                                    @endcan
                                                    @csrf
                                                    @method('DELETE')
                                                    @can('motivos.destroy')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar"><i
                                                                class="fa fa-fw fa-trash"></i></button>
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
                {{-- {!! $motivos->links() !!} --}}
            </div>
        </div>
    </div>
@endsection
