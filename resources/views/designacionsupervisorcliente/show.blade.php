@extends('layouts.app')

@section('template_title')
    {{ $designacionsupervisorcliente->name ?? "{{ __('Show') Designacionsupervisorcliente" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Designacionsupervisorcliente</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('designacionsupervisorclientes.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Designacionsupervisor Id:</strong>
                            {{ $designacionsupervisorcliente->designacionsupervisor_id }}
                        </div>
                        <div class="form-group">
                            <strong>Cliente Id:</strong>
                            {{ $designacionsupervisorcliente->cliente_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
