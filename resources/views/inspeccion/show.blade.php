@extends('layouts.app')

@section('template_title')
    {{ $inspeccion->name ?? "{{ __('Show') Inspeccion" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Inspeccion</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('inspeccions.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Designacionsupervisor Id:</strong>
                            {{ $inspeccion->designacionsupervisor_id }}
                        </div>
                        <div class="form-group">
                            <strong>Cliente Id:</strong>
                            {{ $inspeccion->cliente_id }}
                        </div>
                        <div class="form-group">
                            <strong>Inicio:</strong>
                            {{ $inspeccion->inicio }}
                        </div>
                        <div class="form-group">
                            <strong>Fin:</strong>
                            {{ $inspeccion->fin }}
                        </div>
                        <div class="form-group">
                            <strong>Status:</strong>
                            {{ $inspeccion->status }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
