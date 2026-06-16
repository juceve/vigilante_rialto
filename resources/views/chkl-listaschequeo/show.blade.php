@extends('layouts.app')

@section('template_title')
    {{ $chklListaschequeo->name ?? "{{ __('Show') Chkl Listaschequeo" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Chkl Listaschequeo</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('chkl-listaschequeos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Cliente Id:</strong>
                            {{ $chklListaschequeo->cliente_id }}
                        </div>
                        <div class="form-group">
                            <strong>Titulo:</strong>
                            {{ $chklListaschequeo->titulo }}
                        </div>
                        <div class="form-group">
                            <strong>Descripcion:</strong>
                            {{ $chklListaschequeo->descripcion }}
                        </div>
                        <div class="form-group">
                            <strong>Activo:</strong>
                            {{ $chklListaschequeo->activo }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
