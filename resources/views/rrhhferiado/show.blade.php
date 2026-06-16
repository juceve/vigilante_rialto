@extends('layouts.app')

@section('template_title')
    Feriados
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Rrhhferiado</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('rrhhferiados.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="form-group">
                            <strong>Nombre:</strong>
                            {{ $rrhhferiado->nombre }}
                        </div>
                        <div class="form-group">
                            <strong>Fecha:</strong>
                            {{ $rrhhferiado->fecha }}
                        </div>
                        <div class="form-group">
                            <strong>Fecha Inicio:</strong>
                            {{ $rrhhferiado->fecha_inicio }}
                        </div>
                        <div class="form-group">
                            <strong>Fecha Fin:</strong>
                            {{ $rrhhferiado->fecha_fin }}
                        </div>
                        <div class="form-group">
                            <strong>Recurrente:</strong>
                            {{ $rrhhferiado->recurrente }}
                        </div>
                        <div class="form-group">
                            <strong>Factor:</strong>
                            {{ $rrhhferiado->factor }}
                        </div>
                        <div class="form-group">
                            <strong>Activo:</strong>
                            {{ $rrhhferiado->activo }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
