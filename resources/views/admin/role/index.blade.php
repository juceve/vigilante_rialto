@extends('adminlte::page')

@section('title')
ROLES |
@endsection

@section('content')
<br>
<div style="display: flex; justify-content: space-between; align-items: center;" class="mb-3">

    <h4>Roles de Usuario</h4>

    <div class="float-right">
        @can('admin.roles.create')
        <a href="{{ route('admin.roles.create') }}" class="btn btn-info btn-sm float-right" data-placement="left">
            <i class="fas fa-plus"></i> Nuevo
        </a>
        @endcan
    </div>
</div>
<div class="card">

    <div class="card-body">
        <table class="table table-bordered dataTableA">
            <thead class="bg-info">
                <tr class="text-center">
                    <th>ID</th>
                    <th>NOMBRE</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                <tr>
                    <td>{{$role->id}}</td>
                    <td>{{$role->name}}</td>
                    <td align="right">
                        <form action="{{route('admin.roles.destroy',$role)}}" onsubmit="return false" method="POST"
                            class="delete">
                            @csrf
                            @method('DELETE')
                            @can('admin.roles.edit')
                            <a href="{{route('admin.roles.edit',$role->id)}}" class="btn btn-sm btn-primary"><i
                                    class="fas fa-edit"></i> Editar</a>
                            @endcan
                            @can('admin.roles.destroy')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{
                                __('Borrar') }}</button>
                            @endcan

                        </form>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
@endsection
@section('js')
<script src="{{asset('vendor/jquery/scripts.js')}}"></script>
@include('vendor.mensajes')
@endsection