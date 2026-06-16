@extends('adminlte::page')
@section('content')
@livewire('admin.registrosdesignacion')
@endsection

{{-- @section('title')
Designaciones
@endsection
@section('content_header')
<h4>Designaciones</h4>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            Listado de Designaciones
                        </span>

                        <div class="float-right">
                            @can('designaciones.create')
                            <a href="{{ route('designaciones.create') }}" class="btn btn-primary btn-sm float-right"
                                data-placement="left">
                                Nuevo <i class="fas fa-plus"></i>
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered dataTable" style="font-size: 13px;">
                            <thead class="table-info">
                                <tr>
                                    <th>No</th>

                                    <th>EMPLEADO</th>
                                    <th>CLIENTE</th>
                                    <th>TURNO</th>
                                    <th style="width: 45px;">INICIO</th>
                                    <th style="width: 45px;">
                                        FINAL
                                    </th>
                                    <th>ESTADO</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i = 0;
                                @endphp
                                @foreach ($designaciones as $designacione)
                                <tr>
                                    <td>{{ ++$i }}</td>

                                    <td>{{ $designacione->empleado->nombres . ' ' . $designacione->empleado->apellidos
                                        }}
                                    </td>
                                    <td>{{ $designacione->turno->cliente->nombre }}
                                    <td>{{ $designacione->turno->nombre }}</td>
                                    <td>{{ $designacione->fechaInicio }}</td>
                                    <td>
                                        {{ $designacione->fechaFin }}

                                    </td>
                                    <td class="text-center">
                                        @if (!$designacione->estado) <span
                                            class="badge badge-pill badge-warning">Finalizado</span>
                                        @else
                                        <span class="badge badge-pill badge-success">Activo</span>
                                        @endif
                                    </td>

                                    <td align="right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                                data-toggle="dropdown">Opciones</button>
                                            <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu" style="">
                                                <form action="{{ route('designaciones.destroy', $designacione->id) }}"
                                                    method="POST" onsubmit="return false" class="delete">
                                                    @can('admin.registros.hombrevivo')
                                                    <a class="dropdown-item"
                                                        href="{{ route('designaciones.show', $designacione->id) }}"
                                                        title="">
                                                        <i class="fas fa-fw fa-street-view text-secondary"></i>
                                                        Rondas
                                                    </a>
                                                    @endcan
                                                    @can('admin.registros.hombrevivo')
                                                    <a class="dropdown-item"
                                                        href="{{ route('registroshv', $designacione->id) }}" title="">
                                                        <i class="fas fa-fw fa-user-check text-secondary"></i>
                                                        Hombre Vivo
                                                    </a>
                                                    @endcan
                                                    @can('admin.registros.asistencia')
                                                    <a class="dropdown-item"
                                                        href="{{ route('marcaciones', $designacione->id) }}" title="">
                                                        <i class="fas fa-user-clock text-secondary"></i>
                                                        Asistencias
                                                    </a>
                                                    @endcan
                                                    @can('admin.registros.novedades')
                                                    <a class="dropdown-item"
                                                        href="{{ route('regnovedades', $designacione->id) }}" title="">
                                                        <i class="fas fa-book text-secondary"></i>
                                                        Novedades
                                                    </a>
                                                    @endcan

                                                    @can('admin.registros.diaslibres')
                                                    <a class="dropdown-item"
                                                        href="{{ route('designaciones.diaslibres', $designacione->id) }}">
                                                        <i class="fas fa-fw fa-calendar-alt text-secondary"></i>
                                                        Días libres</a>
                                                    @endcan

                                                    @can('designaciones.edit')
                                                    <a class="dropdown-item"
                                                        href="{{ route('designaciones.edit', $designacione->id) }}"
                                                        title=""><i class="fa fa-fw fa-edit text-secondary"></i> Editar
                                                    </a>
                                                    @endcan

                                                    @csrf
                                                    @method('DELETE')
                                                    @can('designaciones.destroy')
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
@endsection --}}