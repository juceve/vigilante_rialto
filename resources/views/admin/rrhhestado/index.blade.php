@extends('adminlte::page')

@section('title')
    Estado de Asistencia
@endsection
@section('content_header')
    <h4>Estado de Asistencia</h4>
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
                                @can('rrhhestados.create')
                                    <a href="{{ route('rrhhestados.create') }}" class="btn btn-info btn-sm float-right"
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
                                        <th>Factor</th>
                                        <th>Color</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rrhhestados as $rrhhestado)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            <td>{{ $rrhhestado->nombre }}</td>
                                            <td>{{ $rrhhestado->nombre_corto }}</td>
                                            <td>{{ $rrhhestado->factor }}</td>
                                            <td>
                                                {{-- <div style="width: 24px; height: 24px; border-radius: 50%; background-color: {{ $rrhhestado->color }}; border: 1px solid #ccc;"></div> --}}
                                                <div
                                                    style="width: 24px; height: 24px; background-color: {{ $rrhhestado->color }}; border: 1px solid #ccc;">
                                                </div>
                                            </td>

                                            <td class="text-right">
                                                <form action="{{ route('rrhhestados.destroy', $rrhhestado->id) }}"
                                                    method="POST">
                                                    <a class="btn btn-sm btn-primary "
                                                        href="{{ route('rrhhestados.show', $rrhhestado->id) }}"
                                                        title="Ver Info"><i class="fa fa-fw fa-eye"></i></a>
                                                    @can('rrhhestados.edit')
                                                        <a class="btn btn-sm btn-success"
                                                            href="{{ route('rrhhestados.edit', $rrhhestado->id) }}"
                                                            title="Editar"><i class="fa fa-fw fa-edit"></i></a>
                                                    @endcan

                                                    @csrf
                                                    @method('DELETE')
                                                    @can('rrhhestados.destroy')
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
                
            </div>
        </div>
    </div>
@endsection
