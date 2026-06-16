@extends('adminlte::page')

@section('title')
Areas
@endsection
@section('content_header')
<h4>Areas</h4>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-info">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            Listado de Areas
                        </span>

                        <div class="float-right">
                            @can('areas.create')
                            <a href="{{ route('areas.create') }}" class="btn btn-info btn-sm float-right"
                                data-placement="left">
                                Nuevo <i class="fas fa-plus"></i>
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                {{-- @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @endif --}}

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTable">
                            <thead class="thead table-info">
                                <tr>
                                    <th>No</th>

                                    <th>Nombre</th>
                                    <th>Plantilla</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($areas as $area)
                                <tr>
                                    <td>{{ ++$i }}</td>

                                    <td>{{ $area->nombre }}</td>
                                    <td>{{ $area->template }}</td>
                                    <td align="right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                                data-toggle="dropdown">Opciones</button>
                                            {{-- <button type="button" class="btn btn-success dropdown-toggle"
                                                data-toggle="dropdown" aria-expanded="false"> --}}
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu" style="">
                                                <form action="{{ route('areas.destroy', $area->id) }}" method="POST"
                                                    class="delete" onsubmit="return false">
                                                    <a class="dropdown-item"
                                                        href="{{ route('areas.show', $area->id) }}"><i
                                                            class="fa fa-fw fa-eye text-secondary"></i> Info</a>
                                                    @can('areas.edit')
                                                    <a class="dropdown-item"
                                                        href="{{ route('areas.edit', $area->id) }}"><i
                                                            class="fa fa-fw fa-edit text-secondary"></i> Editar</a>
                                                    @endcan

                                                    @csrf
                                                    @method('DELETE')
                                                    @can('areas.destroy')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-fw fa-trash text-secondary"></i>
                                                        Eliminar de la DB
                                                    </button>
                                                    @endcan
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- {!! $areas->links() !!} --}}
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('vendor/jquery/scripts.js') }}"></script>
@include('vendor.mensajes')
@endsection