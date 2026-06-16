@extends('adminlte::page')

@section('title')
Oficinas
@endsection
@section('content_header')
<h4>Oficinas</h4>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-info">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            Listado de Oficinas
                        </span>

                        <div class="float-right">
                            @can('oficinas.create')
                            <a href="{{ route('oficinas.create') }}" class="btn btn-info btn-sm float-right"
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
                                    <th>Direccion</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($oficinas as $oficina)
                                <tr>
                                    <td>{{ ++$i }}</td>

                                    <td>{{ $oficina->nombre }}</td>
                                    <td>{{ $oficina->direccion }}</td>

                                    <td align="right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                                data-toggle="dropdown">Opciones</button>
                                            <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu" style="">
                                                <form action="{{ route('oficinas.destroy', $oficina->id) }}"
                                                    method="POST" class="delete" onsubmit="return false">
                                                    <a class="dropdown-item"
                                                        href="{{ route('oficinas.show', $oficina->id) }}"><i
                                                            class="fa fa-fw fa-eye text-secondary"></i> Info</a>
                                                    @can('oficinas.edit')
                                                    <a class="dropdown-item"
                                                        href="{{ route('oficinas.edit', $oficina->id) }}"><i
                                                            class="fa fa-fw fa-edit text-secondary"></i> Editar</a>
                                                    @endcan

                                                    @csrf
                                                    @method('DELETE')
                                                    @can('oficinas.destroy')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-fw fa-trash text-secondary"></i>
                                                        Eliminar de la DB
                                                    </button>
                                                    @endcan
                                                </form>
                                            </div>
                                        </div>
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
@section('js')
<script src="{{ asset('vendor/jquery/scripts.js') }}"></script>
@include('vendor.mensajes')
@endsection