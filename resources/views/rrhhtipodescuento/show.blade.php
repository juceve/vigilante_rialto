@extends('layouts.app')

@section('template_title')
    Tipo Descuento
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Rrhhtipodescuento</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('rrhhtipodescuentos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="form-group">
                            <strong>Nombre:</strong>
                            {{ $rrhhtipodescuento->nombre }}
                        </div>
                        <div class="form-group">
                            <strong>Nombre Corto:</strong>
                            {{ $rrhhtipodescuento->nombre_corto }}
                        </div>
                        <div class="form-group">
                            <strong>Monto:</strong>
                            {{ $rrhhtipodescuento->monto }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
