@extends('adminlte::page')

@section('template_title')
    Tipo Permisos
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Rrhhtipopermiso</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('rrhhtipopermisos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="form-group">
                            <strong>Nombre:</strong>
                            {{ $rrhhtipopermiso->nombre }}
                        </div>
                        <div class="form-group">
                            <strong>Nombre Corto:</strong>
                            {{ $rrhhtipopermiso->nombre_corto }}
                        </div>
                        <div class="form-group">
                            <strong>Factor:</strong>
                            {{ $rrhhtipopermiso->factor }}
                        </div>
                        <div class="form-group">
                            <strong>Color:</strong>
                            {{ $rrhhtipopermiso->color }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
