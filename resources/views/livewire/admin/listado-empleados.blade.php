<div>
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <input type="search" class="form-control" placeholder="Ingrese su busqueda..."
                    wire:model.debounce.500ms='busqueda'>
            </div>
        </div>
        <div class="col-12 col-md-2">
            <button class="btn btn-success btn-block" wire:click='exporExcel'><i class="fas fa-file-excel"></i>
                Exportar</button>
        </div>
        <div class="col-12 col-md-2">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><small>Filas: </small></span>
                </div>
                <select class="form-control text-center" wire:model='filas'>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="thead table-info">
                <tr>
                    <th>ID</th>

                    <th>Nombres</th>
                    <th>Apellidos</th>

                    <th>Area</th>
                    <th>Usuario</th>
                    {{-- <th>Oficina</th> --}}
                    <th></th>
                </tr>
            </thead>
            <tbody>
               
                @foreach ($empleados as $empleado)
                    <tr>
                        <td>{{ $empleado->id }}</td>

                        <td>{{ $empleado->nombres }}</td>
                        <td>{{ $empleado->apellidos }}</td>
                        <td>{{ $empleado->area->nombre }}</td>
                        <td>
                            @if (!is_null($empleado->user_id))
                                @if ($empleado->user->status)
                                    <span class="badge badge-pill badge-primary">Activo</span>
                                @else
                                    <span class="badge badge-pill badge-secondary">Inactivo</span>
                                @endif
                            @else
                                N/A
                            @endif

                        </td>
                        <td align="right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                    data-toggle="dropdown">Opciones</button>
                                {{-- <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                aria-expanded="false"> --}}
                                <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu" style="">
                                    <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST"
                                        class="delete" onsubmit="return false">
                                       
                                        @can('empleados.edit')
                                            {{-- <a class="dropdown-item" href="{{ route('empleados.edit', $empleado->id) }}"><i
                                                    class="fa fa-fw fa-edit text-secondary"></i> Editar</a> --}}
                                            <a class="dropdown-item" href="{{ route('rrhh.kardex', $empleado->id) }}"><i
                                                    class="fa fa-fw fa-folder text-secondary"></i> Kardex</a>
                                        @endcan

                                        @csrf
                                        @method('DELETE')
                                        @can('empleados.destroy')
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
        <div class="d-flex justify-content-end">
            {{ $empleados->links() }}
        </div>
    </div>
</div>
