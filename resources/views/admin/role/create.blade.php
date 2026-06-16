@extends('adminlte::page')

@section('title')
NUEVO ROL
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">NUEVO ROL</h4>
        </div>
    </div>
</div>
<div class="card card-primary">
    <div class="card-header  text-white">
        Datos del Rol
        <div style="float: right">
            <a href="{{route('admin.roles.index')}}" class="btn btn-sm btn-primary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>

        </div>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => 'admin.roles.store']) !!}
        @include('admin.role.form')
        {!! Form::submit('Crear Rol', ['class' => 'btn btn-primary','style'=>'width: 250px']) !!}
        {!! Form::close() !!}
    </div>
</div>
@endsection
@section('js')
<script>
    function selectAll(){
        $('.mr-1').prop('checked', true);
    }

    function unSelectAll(){
        $('.mr-1').prop('checked', false);
    }
</script>
@endsection