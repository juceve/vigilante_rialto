@extends('adminlte::page')

@section('title')
Tipo de Boletas
@endsection
@section('content_header')
<h4>Tipo de Boletas</h4>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Tipo de boletas
                            </span>

                             <div class="float-right">
                                <a href="{{ route('tipoboletas.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  Nuevo <i class="fas fa-plus"></i>
                                </a>
                              </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Nombre</th>
										<th>Descripcion</th>
										<th>Tipo Descuento</th>
										<th>Monto</th>
										<th>Estado</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tipoboletas as $tipoboleta)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $tipoboleta->nombre }}</td>
											<td>{{ $tipoboleta->descripcion }}</td>
											<td>{{ $tipoboleta->rrhhtipodescuento_id ?$tipoboleta->rrhhtipodescuento->nombre :'Sin descuento' }}</td>
											<td>{{ $tipoboleta->monto_descuento?? '0.00' }}</td>
											<td>
                                                @if ($tipoboleta->status)
                                                     <span class="badge bg-success">Activo</span>
                                                @else
                                                    <span class="badge bg-danger">Inactivo</span>
                                                @endif
                                            </td>

                                            <td class="text-right">
                                                <form action="{{ route('tipoboletas.destroy',$tipoboleta->id) }}" class="delete" onsubmit="return false;" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('tipoboletas.show',$tipoboleta->id) }}" title="Ver info"><i class="fa fa-fw fa-eye"></i></a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('tipoboletas.edit',$tipoboleta->id) }}" title="Editar"><i class="fa fa-fw fa-edit"></i></a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-fw fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $tipoboletas->links() !!}
            </div>
        </div>
    </div>
@endsection
