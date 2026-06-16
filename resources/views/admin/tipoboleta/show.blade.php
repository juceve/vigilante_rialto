@extends('adminlte::page')

@section('title')
    Información Tipo de Boleta
@endsection
@section('content_header')
    <h4>Información Tipo de Boleta</h4>
@endsection
@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Datos Tipo de Boleta
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
                            <strong>Nombre:</strong>
                            {{ $tipoboleta->nombre }}
                        </div>
                        <div class="form-group">
                            <strong>Descripcion:</strong>
                            {{ $tipoboleta->descripcion }}
                        </div>
                        <div class="form-group">
                            <strong>Tipo de Descuento:</strong>
                            {{ $tipoboleta->rrhhtipodescuento->nombre ?? 'Sin descuento' }}
                        </div>
                        <div class="form-group">
                            <strong>Monto:</strong>
                            {{ $tipoboleta->monto_descuento?? '0.00' }}
                        </div>
                        <div class="form-group">
                            <strong>Estado:</strong>
                            {{ $tipoboleta->status ? 'Activo' : 'Inactivo' }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
