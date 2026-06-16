@extends('layouts.app')

@section('template_title')
    {{ $chklRespuesta->name ?? "{{ __('Show') Chkl Respuesta" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Chkl Respuesta</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('chkl-respuestas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Chkl Ejecucione Id:</strong>
                            {{ $chklRespuesta->chkl_ejecucione_id }}
                        </div>
                        <div class="form-group">
                            <strong>Chkl Pregunta Id:</strong>
                            {{ $chklRespuesta->chkl_pregunta_id }}
                        </div>
                        <div class="form-group">
                            <strong>Ok:</strong>
                            {{ $chklRespuesta->ok }}
                        </div>
                        <div class="form-group">
                            <strong>Observacion:</strong>
                            {{ $chklRespuesta->observacion }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
