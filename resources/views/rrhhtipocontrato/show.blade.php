@extends('adminlte::page')

@section('title')
    Información Tipo de Contrato
@endsection
@section('content_header')
    <h4>Información Tipo de Contrato</h4>
@endsection
@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Detalles
                            </span>

                            <div class="float-right">
                                <a href="javascript:history.back()" class="btn btn-info btn-sm float-right"
                                    data-placement="left">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Codigo:</strong>
                            {{ $rrhhtipocontrato->codigo }}
                        </div>
                        <div class="form-group">
                            <strong>Nombre:</strong>
                            {{ $rrhhtipocontrato->nombre }}
                        </div>
                        <div class="form-group">
                            <strong>Descripcion:</strong>
                            {{ $rrhhtipocontrato->descripcion }}
                        </div>
                        <div class="form-group">
                            <strong>Cantidad Dias:</strong>
                            {{ $rrhhtipocontrato->cantidad_dias }}
                        </div>
                        <div class="form-group">
                            <strong>Horas Dia:</strong>
                            {{ $rrhhtipocontrato->horas_dia }}
                        </div>
                        <div class="form-group">
                            <strong>Sueldo Referencial:</strong>
                            {{ $rrhhtipocontrato->sueldo_referencial }}
                        </div>
                        <div class="form-group">
                            <strong>Activo:</strong>
                            @if ($rrhhtipocontrato->activo)
                                SI
                            @else
                                NO
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
