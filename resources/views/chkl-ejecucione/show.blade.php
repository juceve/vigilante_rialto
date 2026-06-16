@extends('layouts.app')

@section('template_title')
    {{ $chklEjecucione->name ?? "{{ __('Show') Chkl Ejecucione" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Chkl Ejecucione</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('chkl-ejecuciones.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Chkl Listaschequeo Id:</strong>
                            {{ $chklEjecucione->chkl_listaschequeo_id }}
                        </div>
                        <div class="form-group">
                            <strong>Fecha:</strong>
                            {{ $chklEjecucione->fecha }}
                        </div>
                        <div class="form-group">
                            <strong>Inspector Id:</strong>
                            {{ $chklEjecucione->inspector_id }}
                        </div>
                        <div class="form-group">
                            <strong>Notas:</strong>
                            {{ $chklEjecucione->notas }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
