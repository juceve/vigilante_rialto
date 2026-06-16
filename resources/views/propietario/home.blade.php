@extends('layouts.propietarios')

@section('title')
    Dashboard
@endsection

@section('header-title')
    Dashboard
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3 mb-2">

            <div class="card text-white bg-primary shadow h-100">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <div class="text-uppercase font-weight-bold">
                            <span>Residencias Verificadas</span>
                        </div>
                        <div class="h3 font-weight-bold">
                            {{ $residencias->count() }}
                        </div>
                    </div>
                    <div class="ml-auto">
                        <a href="{{route('misresidencias')}}" style="" class="btn btn-primary">
                            <i class="fas fa-home fa-2x"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-3 mb-2">

            <div class="card text-white bg-info shadow h-100">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <div class="text-uppercase font-weight-bold">
                            <span>Pases Vigentes</span>
                        </div>
                        <div class="h3 font-weight-bold">
                            {{ $paseingresos->count() }}
                        </div>
                    </div>
                    <div class="ml-auto">
                        <a href="{{route('propietarios.pases')}}" style="" class="btn btn-info">
                            <i class="fas fa-ticket-alt fa-2x"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="content mt-4">
        @livewire('propietarios.flujopases')
    </div>
@endsection
