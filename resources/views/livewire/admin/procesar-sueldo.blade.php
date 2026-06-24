<div>
    @section('title', 'Procesar Sueldos')

    @section('content_header')
        <div class="container-fluid" style="display: flex; justify-content: space-between; align-items: center;">

            <span id="card_title">
                <h1 class="m-0 text-dark">Procesar Sueldos</h1>
            </span>

            <div class="float-right">
                <a href="{{route('admin.sueldos')}}" class="btn btn-info  float-right"
                    data-placement="left">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>


    @endsection


    <div class="container-fluid">

        {{-- Info de mes y feriados --}}

        <div class="card bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <h5 class="card-title font-weight-bold">Datos a procesar:</h5> <br>
                        <small>
                            Gestión: <strong>{{ $gestion }}</strong><br>
                            Mes: <strong>{{ str_pad($mes, 2, '0', STR_PAD_LEFT) }}</strong>
                        </small>

                    </div>
                    <div class="col-12 col-md-6">
                        <h5 class="card-title font-weight-bold">Feriados del mes</h5> <br>
                        <ul class="mb-0" style="max-height: 150px; overflow-y: auto; padding-left: 1.2rem;">
                            @forelse($feriados as $feriado)
                                <li class="mb-1">
                                    <small>
                                        <strong>{{ $feriado->nombre }}</strong>
                                        @if ($feriado->fecha)
                                            ({{ \Carbon\Carbon::parse($feriado->fecha)->format('d/m/Y') }})
                                        @elseif($feriado->fecha_inicio && $feriado->fecha_fin)
                                            ({{ \Carbon\Carbon::parse($feriado->fecha_inicio)->format('d/m/Y') }} -
                                            {{ \Carbon\Carbon::parse($feriado->fecha_fin)->format('d/m/Y') }})
                                        @elseif($feriado->recurrente)
                                            @php
                                                $anioActual = now()->year;
                                            @endphp
                                            ({{ \Carbon\Carbon::createFromDate($anioActual, \Carbon\Carbon::parse($feriado->fecha)->month, \Carbon\Carbon::parse($feriado->fecha)->day)->format('d/m/Y') }})
                                        @endif
                                    </small>
                                </li>
                            @empty
                                <li>No hay feriados</li>
                            @endforelse
                        </ul>
                    </div>

                </div>

            </div>
        </div>
        {{-- @dump($contratosSeleccionados) --}}
        {{-- Tabla de contratos --}}
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="row">
                    <div class="col-8">
                        <h3 class="card-title">Contratos listos para proceso <span
                                class="badge {{ count($contratosSeleccionados) > 0 ? 'badge-success' : 'badge-secondary' }}">
                                {{ count($contratosSeleccionados) }}
                            </span>
                        </h3>
                    </div>
                    <div class="col-4">
                        <div class="float-right p-0">
                            @if ($procesado)
                                <a href="" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-sync"></i> Cancelar
                                </a>
                            @else
                                <button type="button" class="btn btn-success btn-sm" wire:click="procesarSueldos"
                                    wire:loading.attr="disabled" wire:target="procesar">
                                    <i class="fas fa-cogs"></i> Procesar
                                </button>
                            @endif
                        </div>
                    </div>
                </div>


            </div>
            <div class="card-body table-responsive">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                    </div>
                    <input type="search" class="form-control" placeholder="Buscar por Empleado"
                        wire:model.debounce.800ms="search">
                </div>

                <table class="table table-bordered table-hover text-center table-sm mb-0" id="tablaContratos">
                    <thead
                        class="
                    @if ($procesado) table-success
                    @else
                        table-secondary @endif
                    ">
                        <tr>
                            @if (!$procesado)
                                <th style="vertical-align: middle"><input type="checkbox" title="Seleccionar todos"
                                        wire:model="seleccionarTodos">
                                </th>
                            @endif
                            <th style="vertical-align: middle">#</th>
                            <th style="vertical-align: middle">Empleado</th>
                            <th style="vertical-align: middle">Salario Base</th>
                            <th style="vertical-align: middle">Detalles</th>
                            <th style="vertical-align: middle">Inasistencias</th>
                            <th style="vertical-align: middle">No Marcó Salida</th>
                            <th style="vertical-align: middle">Sanciones</th>
                            <th style="vertical-align: middle">Adelantos</th>
                            <th style="vertical-align: middle">Bonos</th>
                            <th style="vertical-align: middle">Liquido Pagable</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($contratosP as $index => $contrato)
                            @if ($contrato != null)
                                <tr>
                                    @if (!$procesado)
                                        <td style="vertical-align: middle">
                                            <input type="checkbox" value="{{ $contrato['id'] }}"
                                                wire:model="contratosSeleccionados" class="cb-contrato">
                                        </td>
                                    @endif
                                    <td style="vertical-align: middle">{{ $index + 1 }}</td>
                                    <td class="text-left" style="vertical-align: middle">
                                        <strong>{{ $contrato['nombres'] }} {{ $contrato['apellidos'] }}</strong>
                                        <br>
                                        <small>
                                            Inicio:
                                            {{ \Carbon\Carbon::parse($contrato['fecha_inicio'])->format('d/m/Y') }}
                                            |
                                            Fin:
                                            {{ $contrato['fecha_fin'] == 'Indefinido' ? 'Indefinido' : \Carbon\Carbon::parse($contrato['fecha_fin'])->format('d/m/Y') }}<br>
                                            Tipo: {{ $contrato['tipo_contrato'] ?? 'N/A' }}
                                        </small>
                                    </td>

                                    {{-- Salario Mes --}}
                                    <td style="vertical-align: middle">{{ number_format($contrato['salario_mes'], 2) }}
                                    </td>

                                    <td style="vertical-align: middle">

                                        @if (isset($contrato['calendario_laboral']))
                                            @if ($procesado)
                                                <button class="btn btn-xs btn-info mt-1" type="button"
                                                    wire:click="verDetalles({{ $contrato['id'] }})">
                                                    Ver Detalles
                                                </button>
                                            @else
                                                --
                                            @endif
                                        @endif
                                    </td>


                                    {{-- INASISTENCIAS: días y ajuste --}}
                                    <td style="vertical-align: middle">
                                        <span
                                            class="badge {{ $contrato['total_ctrlasistencias'] == 0 ? 'badge-secondary' : 'badge-danger' }}"
                                            title="Cant.: {{ $contrato['cant_inasistencias'] }} Inasistencias">
                                            {{ number_format($contrato['total_ctrlasistencias'], 2) }}
                                        </span>
                                        {{-- <br>
                                        <small>Cant.: {{$contrato['cant_inasistencias']}}</small> --}}
                                    </td>

                                    {{-- Marcado sin salida --}}
                                    <td style="vertical-align: middle">
                                        <span
                                            class="badge {{ $contrato['total_marcaciones_incompletas'] > 0 ? 'badge-danger' : 'badge-secondary' }}"
                                            title="Cant.: {{ $contrato['cant_marcaciones_incompletas'] }} Asistencia sin Salida">
                                            {{ number_format($contrato['total_marcaciones_incompletas'], 2) }}
                                        </span>
                                        {{-- <br>
                                        <small>Cant.: {{$contrato['cant_marcaciones_incompletas']}}</small> --}}
                                    </td>

                                    {{-- Descuentos --}}
                                    <td style="vertical-align: middle">

                                        <span
                                            class="badge {{ $contrato['total_descuentos'] > 0 ? 'badge-danger' : 'badge-secondary' }}"
                                            title="Descuentos por Sanciones">
                                            {{ number_format(abs($contrato['total_descuentos']), 2) }}
                                        </span>
                                    </td>

                                    {{-- Adelantos --}}
                                    <td style="vertical-align: middle">
                                        <span
                                            class="badge {{ $contrato['total_adelantos'] > 0 ? 'badge-danger' : 'badge-secondary' }}"
                                            title="Adelantos descontados en el mes">
                                            {{ number_format($contrato['total_adelantos'] ?? 0, 2) }}
                                        </span>
                                    </td>
                                    {{-- Bonos --}}
                                    <td style="vertical-align: middle">

                                        <span
                                            class="badge {{ $contrato['total_bonos'] > 0 ? 'badge-success' : 'badge-secondary' }}"
                                            title="Bonos sin pago efectuado">
                                            {{ number_format(abs($contrato['total_bonos']), 2) }}
                                        </span>
                                    </td>

                                    {{-- Liquido Pagable --}}
                                    <td style="vertical-align: middle">
                                        @php

                                            $liquido_color =
                                                $contrato['liquido_pagable'] >= $contrato['salario_basico']
                                                    ? 'badge-success'
                                                    : 'badge-secondary';
                                            $liquido_tooltip =
                                                $contrato['liquido_pagable'] >= $contrato['salario_basico']
                                                    ? 'El líquido pagable es igual o mayor al salario del mes (sin descuentos extraordinarios).'
                                                    : 'El líquido pagable es menor al salario del mes debido a descuentos por inasistencias u otros';
                                        @endphp
                                        <span class="badge {{ $liquido_color }}" title="{{ $liquido_tooltip }}">
                                            {{ number_format($contrato['liquido_pagable'], 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="9">No hay contratos activos para este mes</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="float-right mt-2">{{ $contratosP->links() }}</div>
            </div>


            <div class="card-footer">
                <div class="col-12 col-md-4 float-right">
                    <button type="button"
                        class="btn btn-success btn-block ml-2  @if (!$procesado) d-none @endif"
                        id="btn-registrar-resultados" wire:loading.attr="disabled">
                        <i class="fas fa-save"></i> Registrar Resultados
                    </button>
                </div>
            </div>

        </div>

    </div>

    {{-- modal de detalles --}}
    <!-- Modal -->
    @if ($contratoSeleccionado)
        <div class="modal fade" id="modalDetalles" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="modalDetallesLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        DETALLES GENERALES
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 class="text-secondary"><strong>CONTRATO NRO.:
                                {{ str_pad($contratoSeleccionado['id'], 5, '0', STR_PAD_LEFT) }}</strong></h4>
                            <div class="row text-secondary">
                                <div class="col-6">
                                    <label for="">EMPLEADO: </label> {{ $contratoSeleccionado['nombres'] }}
                                    {{ $contratoSeleccionado['apellidos'] }}
                                </div>
                                <div class="col-3">
                                    <label for="">Inicio: </label> {{ $contratoSeleccionado['fecha_inicio'] }}
                                </div>
                                <div class="col-3">
                                    <label for="">Fin: </label>
                                    {{ $contratoSeleccionado['fecha_fin'] ?? 'Indefinido' }}
                                </div>
                                <div class="col-6">
                                    <label for="">Tipo Contrato: </label>
                                    {{ $contratoSeleccionado['tipo_contrato'] ?? 'Indefinido' }}
                                </div>
                                <div class="col-3">
                                    <label for="">Salario Base: </label>
                                    {{ number_format($contratoSeleccionado['salario_basico'], 2) ?? 'Indefinido' }}
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-6">
                                    <div class="accordion" id="accordionExample">
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left text-secondary"
                                                        type="button" data-toggle="collapse"
                                                        data-target="#collapseOne" aria-expanded="false"
                                                        aria-controls="collapseOne">
                                                        <strong>BONOS</strong>
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <table class="table table-sm table-striped">
                                                        <thead>
                                                            <tr class="table-success">
                                                                <th>Fecha</th>
                                                                <th>Tipo</th>
                                                                <th class="text-right">Monto</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $total_bonos = 0;
                                                            @endphp
                                                            @forelse ($contratoSeleccionado['bonos'] as $bono)
                                                                <tr>
                                                                    <td>{{ $bono['fecha'] }}</td>
                                                                    @php
                                                                        $tipobono = App\Models\Rrhhtipobono::find(
                                                                            $bono['rrhhtipobono_id'],
                                                                        );
                                                                    @endphp
                                                                    <td>{{ $tipobono->nombre }}</td>
                                                                    <td class="text-right">
                                                                        {{ number_format($bono['cantidad'] * $bono['monto'], 2) }}
                                                                    </td>

                                                                </tr>
                                                                @php
                                                                    $total_bonos += $bono['cantidad'] * $bono['monto'];
                                                                @endphp
                                                            @empty
                                                                <tr>
                                                                    <td class="text-center" colspan=3>No se encontraron
                                                                        resultados</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="table-secondary">
                                                                <td colspan="2" class="text-right">TOTAL</td>
                                                                <td class="text-right">
                                                                    {{ number_format($total_bonos, 2) }}
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="headingTwo">
                                                <h2 class="mb-0">
                                                    <button
                                                        class="btn btn-link btn-block text-left text-secondary collapsed"
                                                        type="button" data-toggle="collapse"
                                                        data-target="#collapseTwo" aria-expanded="false"
                                                        aria-controls="collapseTwo">
                                                        <strong>DESCUENTOS / SANCIONES</strong>
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                                data-parent="#accordionExample">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-sm table-striped">
                                                        <thead>
                                                            <tr class="table-danger">
                                                                <th>Fecha</th>
                                                                <th>Tipo</th>
                                                                <th class="text-right">Monto</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $total_descuentos = 0;
                                                            @endphp
                                                            @forelse ($contratoSeleccionado['descuentos'] as $descuento)
                                                                <tr>
                                                                    <td>{{ $descuento['fecha'] }}</td>
                                                                    @php
                                                                        $tipodescuento = App\Models\Rrhhtipodescuento::find(
                                                                            $descuento['rrhhtipodescuento_id'],
                                                                        );
                                                                    @endphp
                                                                    <td>{{ $tipodescuento->nombre }}</td>
                                                                    <td class="text-right">
                                                                        {{ number_format($descuento['cantidad'] * $descuento['monto'], 2) }}
                                                                    </td>

                                                                </tr>
                                                                @php
                                                                    $total_descuentos +=
                                                                        $descuento['cantidad'] * $descuento['monto'];
                                                                @endphp
                                                            @empty
                                                                <tr>
                                                                    <td class="text-center" colspan=3>No se encontraron
                                                                        resultados</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="table-secondary">
                                                                <td colspan="2" class="text-right">TOTAL</td>
                                                                <td class="text-right">
                                                                    {{ number_format($total_descuentos, 2) }}
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="headingThree">
                                                <h2 class="mb-0">
                                                    <button
                                                        class="btn btn-link btn-block text-left text-secondary collapsed"
                                                        type="button" data-toggle="collapse"
                                                        data-target="#collapseThree" aria-expanded="false"
                                                        aria-controls="collapseThree">
                                                        <strong>ADELANTOS</strong>
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <table class="table table-sm table-striped">
                                                        <thead>
                                                            <tr class="table-warning">
                                                                <th>Fecha</th>
                                                                <th>Motivo</th>
                                                                <th class="text-right">Monto</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $total_adelantos = 0;
                                                            @endphp
                                                            @forelse ($contratoSeleccionado['adelantos'] as $adelanto)
                                                                <tr>
                                                                    <td>{{ $adelanto['fecha'] }}</td>

                                                                    <td>{{ $adelanto['motivo'] }}</td>
                                                                    <td class="text-right">
                                                                        {{ number_format($adelanto['monto'], 2) }}</td>

                                                                </tr>
                                                                @php
                                                                    $total_adelantos += $adelanto['monto'];
                                                                @endphp
                                                            @empty
                                                                <tr>
                                                                    <td class="text-center" colspan=3>No se encontraron
                                                                        resultados</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="table-secondary">
                                                                <td colspan="2" class="text-right">TOTAL</td>
                                                                <td class="text-right">
                                                                    {{ number_format($total_adelantos, 2) }}
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <strong class="text-secondary">CONTROL DE ASISTENCIA</strong>
                                    @if ($contratoSeleccionado)
                                        @if ($contratoSeleccionado['tipo_designacion'] == '')
                                            <div class="bg-success disabled color-palette p-2">
                                                <strong>EL TIPO DE DESIGNACION NO GENERA EVALUACIÓN.</strong>
                                            </div>
                                        @else
                                            <table class="table table-sm" style="font-size: 0.9rem;">

                                                <thead>
                                                    <tr class="bg-primary text-white text-center">
                                                        <th>Fecha</th>
                                                        <th>Tipo de Día</th>
                                                        <th>Estado de Asistencia</th>
                                                        <th class="text-right">Descuento</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $total_descuentos = 0;
                                                    @endphp
                                                    @foreach ($contratoSeleccionado['calendario_laboral'] as $dia)
                                                        <tr
                                                            class="text-center
                                            @switch($dia['estado_asistencia'])
                                                @case('completa')
                                                    table-success
                                                    @break
                                                @case('media_jornada')
                                                    table-warning
                                                    @break
                                                @case('sin_marca')
                                                    table-danger
                                                    @break
                                                @case('completa')
                                                    @break
                                                @default
                                                    table-secondary
                                            @endswitch

                                            ">
                                                            <td>{{ \Carbon\Carbon::parse($dia['fecha'])->format('d/m/Y') }}
                                                            </td>
                                                            <td>
                                                                {{ $dia['tipo_dia'] }}
                                                            </td>
                                                            <td>
                                                                @switch($dia['estado_asistencia'])
                                                                    @case('completa')
                                                                        COMPLETA
                                                                    @break

                                                                    @case('media_jornada')
                                                                        NO MARCÓ SALIDA
                                                                    @break

                                                                    @default
                                                                        SIN MARCACIÓN
                                                                @endswitch
                                                            </td>
                                                            <td class="text-right">
                                                                {{ number_format($dia['descuento'], 2) }}
                                                                @php
                                                                    $total_descuentos += $dia['descuento'];
                                                                @endphp
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr class="bg-primary text-white">
                                                        <th colspan="3" class="text-right">TOTAL</th>
                                                        <th class="text-right">
                                                            {{ number_format($total_descuentos, 2) }}
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        @endif
                                    @endif
                                </div>
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

    @endif
    {{-- fin Modal detalles --}}

    {{-- Cortina de procesamiento --}}
    <div wire:loading.flex>
        <div
            style="position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:1050; display:flex; align-items:center; justify-content:center;">
            <div class="text-center">
                <div class="spinner-border text-primary" style="width:4rem; height:4rem;"></div>
                <h4 class="mt-3 text-dark">Procesando...</h4>
            </div>
        </div>
    </div>


</div>
@section('css')
    <style>
        .btn-back {
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            width: 45px;
            height: 45px;
            z-index: 1050;
        }
    </style>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        Livewire.on('abrirModalDetalle', () => {
            $('#modalDetalles').modal('show');
        });
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirmación antes de guardar
            document.getElementById('btn-registrar-resultados').addEventListener('click', function() {
                Swal.fire({
                    title: '¿Está seguro?',
                    text: '¿Desea registrar los resultados de sueldos? Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, registrar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('guardarSueldos');
                    }
                });
            });


        });
    </script>
@endsection
