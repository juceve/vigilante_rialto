@extends('adminlte::page')
<div>
    @section('title')
        HISTORIAL DE DESIGNACIONES
    @endsection
    @section('content_header')
        <h4>HISTORIAL DE DESIGNACIONES</h4>
    @endsection

    @section('content')
        <div class="container-fluid">
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header " id="headingOne">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left " type="button" data-toggle="collapse"
                                data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Resumen por Guardia
                                <span class="float-right"><i class="fas fa-plus"></i></span>
                            </button>
                        </h2>
                    </div>

                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="table-responsive" wire:ignore>
                                <table class="table table-bordered table-striped" id="tablaPrincipal">
                                    <thead class="table-primary">
                                        <tr class="text-center">
                                            <th class="text-left">GUARDIA</th>
                                            <th>CUBRE-RELEVOS</th>
                                            <th>ACTIVAS</th>
                                            <th>INACTIVAS</th>
                                            <th>CONDICIÓN</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $item)
                                            <tr class="text-center">
                                                <td class="text-left">{{ $item->empleado }}</td>
                                                <td>{{ $item->cubrerelevos }}</td>
                                                <td>{{ $item->activos }}</td>
                                                <td>{{ $item->inactivos }}</td>
                                                <td>
                                                    @if ($item->activos == 0)
                                                        <span class="badge badge-pill badge-success">LIBRE</span>
                                                    @else
                                                        <span class="badge badge-pill badge-warning">EN
                                                            FUNCIONES</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" title="Mas detalles"
                                                        onclick='selEmpleado({{ $item->id }},"{{ $item->empleado }}")'
                                                        data-toggle="modal" data-target="#modalDesignaciones">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" style="overflow: visible !important;">
                    <div class="card-header " id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button"
                                data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                                aria-controls="collapseTwo">
                                Exportar Historial Completo <span class="float-right"><i
                                        class="fas fa-plus"></i></span>
                            </button>
                        </h2>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            <form id="formExport" action="{{ route('designaciones-historial.exportar') }}" method="POST"
                                target="_blank">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-4 col-xl-3">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">Inicio</span>
                                            </div>
                                            <input type="date" class="form-control" name="inicio">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-xl-3">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">Final</span>
                                            </div>
                                            <input type="date" class="form-control" name="final">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-xl-6">
                                        <div class="form-group">
                                            <select name="empleados[]" id="empleados" multiple>
                                                @foreach ($empleados as $empleado)
                                                    <option value="{{ $empleado['id'] }}">{{ $empleado['nombre'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success btn-block">
                                        Exportar Historial <i class="fas fa-file-excel"></i>
                                    </button>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    <!-- Modal -->
    <div class="modal fade" id="modalDesignaciones" tabindex="-1" aria-labelledby="modalDesignacionesLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
               
                <div class="modal-body">
                    <h4 class="text-center"><strong>Historial de Designaciones</strong></h4>
                    <h5 class="text-center"><span id="nombreGuardia"></span></h5>
                    <table class="table table-striped" id="tablaDatos" style="font-size: 15px">
                        <thead class="table-info">
                            <tr class="text-center">
                                <th class="text-left">EMPRESA</th>
                                <th>INICIO</th>
                                <th>FIN</th>
                                <th>TURNO</th>
                                <th>ESTADO</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary col-12 col-md-4 col-xl-2" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <style>
        /* Ajusta el contenedor del input de Choices para igualar altura Bootstrap */
        .choices__inner {
            min-height: calc(2.25rem + 2px) !important;
            padding: .375rem .75rem !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
            border-radius: .25rem !important;
            border: 1px solid #ced4da !important;
            /* borde igual que Bootstrap */
            background-color: #fff !important;
        }

        /* Ajusta el input interno para que quede alineado */
        .choices__input {
            height: 1.5rem !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
            padding: 0 !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            width: auto !important;
            min-width: 2rem !important;
        }
    </style>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#tablaPrincipal').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Listado de Guardias con sus Designaciones',
                    text: '<i class="fas fa-file-excel"></i> Exportar a Excel',
                    titleAttr: 'Exportar a Excel',
                    className: 'btn btn-success'
                }],
                language: {
                    url: '{{ asset('plugins/es-ES.json') }}',
                }
            });

            const selectElement = document.getElementById('empleados');
            const choices = new Choices(selectElement, {
                removeItemButton: true, // botón para quitar items seleccionados
                searchEnabled: true, // activar buscador
                shouldSort: false, // que no ordene automáticamente
                placeholderValue: 'Selecciona empleados',
                searchPlaceholderValue: 'Busca un empleado',
                itemSelectText: '', // quita texto "Press to select"
                maxItemCount: -1, // sin límite
            });
        });
    </script>



    <script>
        function selEmpleado(id, nombre) {
            $('#nombreGuardia').html(nombre);
            $('#tablaDatos').DataTable({
                destroy: true,
                processing: true,
                serverSide: false, // O true si tenés backend con paginación
                ajax: {
                    url: '/admin/designaciones/selEmpleado/' + id,
                    type: 'GET',
                    dataSrc: ''
                },
                dom: 'Bfrtip', // "B" = Botones, "f" = filtro, "r" = procesando, "t" = tabla, "p" = paginación
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Reporte de Designaciones',
                    text: '<i class="fas fa-file-excel"></i> Exportar a Excel',
                    titleAttr: 'Exportar a Excel',
                    className: 'btn btn-success'
                }, ],
                columns: [{
                        data: 'cliente'
                    },
                    {
                        data: 'fechaInicio',
                        className: 'text-center'
                    },
                    {
                        data: 'fechaFin',
                        className: 'text-center'
                    },
                    {
                        data: 'turno',
                        className: 'text-center'
                    },
                    {
                        data: 'estado',
                        className: 'text-center',
                        render: function(data) {
                            return data == 1 ?
                                '<span class="badge badge-primary">Activo</span>' :
                                '<span class="badge badge-secondary">Inactivo</span>';
                        }
                    }
                ],
                language: {
                    url: '{{ asset('plugins/es-ES.json') }}',
                }
            });


        }
    </script>
@endsection
