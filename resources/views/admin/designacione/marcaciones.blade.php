@extends('adminlte::page')

@section('title')
Marcación de Asistencia
@endsection
@section('content_header')
<h4>Marcación de Asistencia</h4>
@endsection
@section('content')
<section class="content container-fluid">
    <div class="">
        <div class="col-md-12">

            @includeif('partials.errors')

            <div class="card">
                <div class="card-header bg-info">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            Detalles
                        </span>

                        <div class="float-right">
                            <a href="{{route('designaciones.index')}}" class="btn btn-info btn-sm float-right"
                                data-placement="left">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col col-12 col-md-4">
                            <div class="form-group">
                                <strong>Operador:</strong>
                                {{ $designacione->empleado->nombres . ' ' . $designacione->empleado->apellidos }}
                            </div>
                        </div>
                        <div class="col col-12 col-md-4">
                            <div class="form-group">
                                <strong>Empresa:</strong>
                                {{ $designacione->turno->cliente->nombre }}
                            </div>
                        </div>
                        <div class="col col-12 col-md-4">
                            <div class="form-group">
                                <strong>Turno:</strong>
                                {{ $designacione->turno->nombre }}
                            </div>
                        </div>
                        <div class="col col-12 col-md-4">
                            <div class="form-group">
                                <strong>Fecha inicio:</strong>
                                {{ $designacione->fechaInicio }}
                            </div>
                        </div>
                        <div class="col col-12 col-md-4">
                            <div class="form-group">
                                <strong>Fecha fin:</strong>
                                {{ $designacione->fechaFin }}
                            </div>
                        </div>
                        <div class="col col-12 col-md-4">
                            <div class="form-group">
                                <strong>Estado:</strong>
                                @if ($designacione->fechaFin >= date('Y-m-d'))
                                <span class="badge badge-pill badge-success">Activo</span>
                                @else
                                <span class="badge badge-pill badge-secondary">Inactivo</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col col-6">
                            <h3 class="card-title">REGISTROS DE MARCACIONES</h3>
                        </div>
                        <div class="col col-6 text-right">
                            {{-- <a href="{{ route('marcaciones.pdf', $designacione->id) }}" target="_blank"
                                class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a> --}}
                            {{-- <button class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> XLS</button>
                            --}}
                        </div>
                    </div>

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @livewire('admin.marcaciones',['designacione_id'=>$designacione->id])

                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>

</section>
@endsection
@section('js')
<script src="{{ asset('vendor/jquery/scripts.js') }}"></script>
@include('vendor.mensajes')
<script>

</script>
@endsection