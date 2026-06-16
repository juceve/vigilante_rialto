@extends('adminlte::page')

@section('title')
Clientes
@endsection
@section('content_header')
<h4>Clientes</h4>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-info">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            Listado de Clientes
                        </span>

                        <div class="float-right">
                            @can('clientes.create')
                            <a href="{{ route('clientes.create') }}" class="btn btn-info btn-sm float-right"
                                data-placement="left">
                                Nuevo <i class="fas fa-plus"></i>
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @livewire('admin.listado-clientes')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection