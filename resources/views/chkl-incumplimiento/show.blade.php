@extends('layouts.app')

@section('template_title')
    {{ $chklIncumplimiento->name ?? "{{ __('Show') Chkl Incumplimiento" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Chkl Incumplimiento</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('chkl-incumplimientos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Chkl Respuesta Id:</strong>
                            {{ $chklIncumplimiento->chkl_respuesta_id }}
                        </div>
                        <div class="form-group">
                            <strong>Empleado Id:</strong>
                            {{ $chklIncumplimiento->empleado_id }}
                        </div>
                        <div class="form-group">
                            <strong>Observaciones:</strong>
                            {{ $chklIncumplimiento->observaciones }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
