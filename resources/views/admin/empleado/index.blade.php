@extends(Auth::user()->template === 'RRHH' ? 'layouts.rrhh' : 'adminlte::page')
@section('title')
    Empleados
@endsection
@section('content_header')
    <h4>Empleados</h4>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Listado de Empleados
                            </span>

                            <div class="float-right">
                                @can('empleados.create')
                                    <a href="{{ route('empleados.create') }}" class="btn btn-info btn-sm float-right"
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
                        @livewire('admin.listado-empleados')
                    </div>
                </div>
                {{-- {!! $empleados->links() !!} --}}
            </div>
        </div>
    </div>
@endsection
