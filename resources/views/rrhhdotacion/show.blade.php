@extends('layouts.app')

@section('template_title')
    {{-- {{ $rrhhdotacion->name ?? "{{ __('Show') Rrhhdotacion" }} --}}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Rrhhdotacion</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('rrhhdotacions.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Rrhhcontrato Id:</strong>
                            {{ $rrhhdotacion->rrhhcontrato_id }}
                        </div>
                        <div class="form-group">
                            <strong>Empleado Id:</strong>
                            {{ $rrhhdotacion->empleado_id }}
                        </div>
                        <div class="form-group">
                            <strong>Fecha:</strong>
                            {{ $rrhhdotacion->fecha }}
                        </div>
                        <div class="form-group">
                            <strong>Responsable Entrega:</strong>
                            {{ $rrhhdotacion->responsable_entrega }}
                        </div>
                        <div class="form-group">
                            <strong>Estado:</strong>
                            {{ $rrhhdotacion->estado }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
