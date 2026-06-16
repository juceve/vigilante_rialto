@extends('layouts.clientes')

@section('page_header')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Inicio</h1>

    {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
</div>
@endsection

@section('content')

<div class="row g-1">
    <div class="col-12 col-md-7 mb-3">
        <div class="card">
            <div class="card-header font-weight-bold bg-info text-white">Datos de Cliente</div>
            <div class="card-body">
                <div class="row">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>
                                <strong>Nombre:</strong>
                            </td>
                            <td>
                                {{ $cliente->nombre }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Dirección:</strong>
                            </td>
                            <td>
                                {{ $cliente->direccion }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Persona Contacto:</strong>
                            </td>
                            <td>
                                {{ $cliente->personacontacto }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Teléfono Contacto:</strong>
                            </td>
                            <td>
                                {{ $cliente->telefonocontacto }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Estado:</strong>
                            </td>
                            <td>
                                @if ($cliente->status)
                                <span class="badge badge-pill badge-success">Activo</span>
                                @else
                                <span class="badge badge-pill badge-secondary">Inactivo</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>
        <hr>
        @livewire('customer.resumen-operacional', ['cliente_id' => $cliente->id])
    </div>
    <div class="col-12 col-md-5 mb-3">
        <div class="card">
            <div class="card-header font-weight-bold bg-info text-white">Personal de Seguridad Asignado</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-secondary text-white">
                            <th>
                                NOMBRE
                            </th>
                            <th>
                                DATOS
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($designaciones as $item)
                        <tr>
                            <td>{{$item->empleado}}</td>
                            <td class="text-right">
                                <a href="{{route('customer.perfilguardia',$item->empleado_id)}}"
                                    class="btn btn-success btn-sm" title="Ver datos"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 mb-3">
        {{-- @livewire('ctrlairbnb') --}}
    </div>
</div>


@endsection