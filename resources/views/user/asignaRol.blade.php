@extends('adminlte::page')

@section('template_title')
Asignar Rol
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">ROLES DE USUARIO</h4>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header bg-primary text-white">
        ASIGNAR ROL
        <div style="float: right">
            <a class="btn btn-sm btn-primary" href="{{ route('users') }}"><i class="fas fa-arrow-left"></i>
                Volver</a>
        </div>
    </div>
    <div class="card-body">
        <p class="h5">NOMBRE : </p>
        <p class="form-control">{{$user->name}}</p>
        <hr>
        <h2 class="h5 mb-3">LISTADO DE ROLES</h2>
        {!! Form::model($user, ['route' => ['users.updateRol',$user], 'method' => 'put']) !!}
        @foreach ($roles as $role)
        <div class="mb-2">
            <label for="">
                {!! Form::checkbox('roles[]', $role->id, null, ['class' => 'mr-1']) !!}
                {{$role->name}}
            </label>
        </div>
        @endforeach
        <hr>
        <button type="submit" class="btn btn-primary">Asignar Rol</button>

        {!! Form::close() !!}
    </div>

</div>
@endsection
@section('js')
<script src="{{ asset('vendor/jquery/scripts.js') }}"></script>
@include('vendor.mensajes')
@endsection