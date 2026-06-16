@extends('layouts.app')

@section('template_title')
    {{-- {{ $rrhhestado->name ?? "{{ __('Show') Rrhhestado" }} --}}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Rrhhestado</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('rrhhestados.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Nombre:</strong>
                            {{ $rrhhestado->nombre }}
                        </div>
                        <div class="form-group">
                            <strong>Factor:</strong>
                            {{ $rrhhestado->factor }}
                        </div>
                        <div class="form-group">
                            <strong>Color:</strong>
                            {{ $rrhhestado->color }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
