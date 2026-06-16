@extends('adminlte::page')

@section('title')
Tareas
@endsection
@section('content_header')
<h4>Tareas</h4>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-info">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            Listado de Tareas
                        </span>

                        <div class="float-right">
                            @can('oficinas.create')
                            <button class="btn btn-info btn-sm float-right" data-placement="left" data-toggle="modal"
                                data-target="#modalTarea">
                                Nuevo <i class="fas fa-plus"></i>
                            </button>
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

                                    <th>Fecha</th>

                                    <th>Cliente</th>
                                    <th>Empleado</th>
                                    <th>Contenido</th>
                                    <th>Estado</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tareas as $tarea)
                                <tr>
                                    <td>{{ ++$i }}</td>

                                    <td>{{ $tarea->fecha }}</td>

                                    <td>{{ $tarea->cliente->nombre }}</td>
                                    <td>{{ $tarea->empleado->nombres." ".$tarea->empleado->apellidos }}</td>
                                    <td>{{ $tarea->contenido }}</td>
                                    <td>
                                        @if ($tarea->estado)
                                        <span class="badge badge-pill badge-success">Pendiente</span>
                                        @else
                                        <span class="badge badge-pill badge-secondary">Finalizado</span>
                                        @endif
                                    </td>

                                    <td class="text-right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                                data-toggle="dropdown">Opciones</button>
                                            {{-- <button type="button" class="btn btn-success dropdown-toggle"
                                                data-toggle="dropdown" aria-expanded="false"> --}}
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu" style="">
                                                <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST"
                                                    class="delete" onsubmit="return false">
                                                    <a class="dropdown-item"
                                                        href="{{ route('tareas.show', $tarea->id) }}"><i
                                                            class="fa fa-fw fa-eye text-secondary"></i> Info</a>
                                                    {{-- @can('tareas.edit') --}}
                                                    <button class="dropdown-item"
                                                        wire:click="$emitTo('{{$tarea->id}}', 'postAdded')"><i
                                                            class="fa fa-fw fa-edit text-secondary"></i> Editar</button>
                                                    {{-- @endcan --}}

                                                    @csrf
                                                    @method('DELETE')
                                                    {{-- @can('tareas.destroy') --}}
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-fw fa-trash text-secondary"></i>
                                                        Eliminar de la DB
                                                    </button>
                                                    {{-- @endcan --}}
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
    <!-- Modal -->
    <div class="modal fade" id="modalTarea" tabindex="-1" aria-labelledby="modalTareaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTareaLabel">Tareas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @livewire('admin.nuevatarea')
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