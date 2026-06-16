@extends('layouts.app')

@section('template_title')
    {{-- {{ $rrhhbono->name ?? "{{ __('Show') Rrhhbono" }} --}}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Rrhhbono</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('rrhhbonos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Rrhhcontrato Id:</strong>
                            {{ $rrhhbono->rrhhcontrato_id }}
                        </div>
                        <div class="form-group">
                            <strong>Fecha:</strong>
                            {{ $rrhhbono->fecha }}
                        </div>
                        <div class="form-group">
                            <strong>Rrhhtipobono Id:</strong>
                            {{ $rrhhbono->rrhhtipobono_id }}
                        </div>
                        <div class="form-group">
                            <strong>Empleado Id:</strong>
                            {{ $rrhhbono->empleado_id }}
                        </div>
                        <div class="form-group">
                            <strong>Cantidad:</strong>
                            {{ $rrhhbono->cantidad }}
                        </div>
                        <div class="form-group">
                            <strong>Monto:</strong>
                            {{ $rrhhbono->monto }}
                        </div>
                        <div class="form-group">
                            <strong>Estado:</strong>
                            {{ $rrhhbono->estado }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
