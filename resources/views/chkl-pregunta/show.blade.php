@extends('layouts.app')

@section('template_title')
    {{ $chklPregunta->name ?? "{{ __('Show') Chkl Pregunta" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Chkl Pregunta</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('chkl-preguntas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Chkl Listaschequeo Id:</strong>
                            {{ $chklPregunta->chkl_listaschequeo_id }}
                        </div>
                        <div class="form-group">
                            <strong>Descripcion:</strong>
                            {{ $chklPregunta->descripcion }}
                        </div>
                        <div class="form-group">
                            <strong>Tipoboleta Id:</strong>
                            {{ $chklPregunta->tipoboleta_id }}
                        </div>
                        <div class="form-group">
                            <strong>Requerida:</strong>
                            {{ $chklPregunta->requerida }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
