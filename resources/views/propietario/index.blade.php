@extends('layouts.app')

@section('template_title')
    Propietario
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Propietario') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('propietarios.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Nombre</th>
										<th>Cedula</th>
										<th>Telefono</th>
										<th>Email</th>
										<th>Direccion</th>
										<th>Ciudad</th>
										<th>Activo</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($propietarios as $propietario)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $propietario->nombre }}</td>
											<td>{{ $propietario->cedula }}</td>
											<td>{{ $propietario->telefono }}</td>
											<td>{{ $propietario->email }}</td>
											<td>{{ $propietario->direccion }}</td>
											<td>{{ $propietario->ciudad }}</td>
											<td>{{ $propietario->activo }}</td>

                                            <td>
                                                <form action="{{ route('propietarios.destroy',$propietario->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('propietarios.show',$propietario->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('propietarios.edit',$propietario->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $propietarios->links() !!}
            </div>
        </div>
    </div>
@endsection
