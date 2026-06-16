@extends('layouts.app')

@section('template_title')
    {{-- {{ $designacionsupervisor->name ?? "{{ __('Show') Designacionsupervisor" }} --}}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Designacionsupervisor</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('designacionsupervisors.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Empleado Id:</strong>
                            {{ $designacionsupervisor->empleado_id }}
                        </div>
                        <div class="form-group">
                            <strong>Fechainicio:</strong>
                            {{ $designacionsupervisor->fechaInicio }}
                        </div>
                        <div class="form-group">
                            <strong>Fechafin:</strong>
                            {{ $designacionsupervisor->fechaFin }}
                        </div>
                        <div class="form-group">
                            <strong>Observaciones:</strong>
                            {{ $designacionsupervisor->observaciones }}
                        </div>
                        <div class="form-group">
                            <strong>Estado:</strong>
                            {{ $designacionsupervisor->estado }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
