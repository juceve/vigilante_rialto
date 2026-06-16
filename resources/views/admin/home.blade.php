@extends('adminlte::page')

@section('title')
Inicio
@endsection
@section('content_header')
<h4>CLIENTES REGISTRADOS</h4>
@endsection
@section('content')
<div class="content mb-3">
    @livewire('admin.clientestools')

</div>
<div class="content mb-3">
    @livewire('admin.listado-vencimiento-contratos')

</div>
@endsection
