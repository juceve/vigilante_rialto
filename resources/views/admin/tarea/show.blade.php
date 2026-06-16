@extends('layouts.app')

@section('template_title')
    {{-- {{ $tarea->name ?? "{{ __('Show') Tarea" }} --}}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Tarea</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('tareas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Fecha:</strong>
                            {{ $tarea->fecha }}
                        </div>
                        <div class="form-group">
                            <strong>Contenido:</strong>
                            {{ $tarea->contenido }}
                        </div>
                        <div class="form-group">
                            <strong>Cliente Id:</strong>
                            {{ $tarea->cliente_id }}
                        </div>
                        <div class="form-group">
                            <strong>Empleado Id:</strong>
                            {{ $tarea->empleado_id }}
                        </div>
                        <div class="form-group">
                            <strong>Estado:</strong>
                            {{ $tarea->estado }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
