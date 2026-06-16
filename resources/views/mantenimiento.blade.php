@extends('adminlte::page')

@section('title')
MANTENIMIENTO
@endsection
{{-- @section('content_header')
<h4>MODULO EN MANTENIMIENTO</h4>
@endsection --}}
@section('content')
    <div class="content-wrapper d-flex align-items-center justify-content-center" style="min-height: 90vh;">
        <div class="text-center">

            <!-- Icono -->
            <div class="mb-4">
                <i class="fas fa-tools fa-5x text-warning"></i>
            </div>

            <!-- Mensaje -->
            <h2 class="mb-3">Módulo en mantenimiento</h2>
            <p class="text-muted">
                Estamos realizando mejoras en este módulo.<br>
                Por favor, intenta nuevamente más tarde.
            </p>

            <!-- Botón opcional -->
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                <i class="fas fa-arrow-left"></i> Volver al inicio
            </a>

        </div>
    </div>
@endsection
