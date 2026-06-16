@extends('adminlte::page')

@section('title')
Tipo Contrato
@endsection
@section('content_header')
<h4>Tipo Contrato</h4>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-info">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            Listado de Tipos de Contrato
                        </span>

                        <div class="float-right">
                            @can('rrhhtipocontratos.create')
                            <a href="{{ route('rrhhtipocontratos.create') }}" class="btn btn-info btn-sm float-right"
                                data-placement="left">
                                Nuevo <i class="fas fa-plus"></i>
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dataTable">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										{{-- <th>Codigo</th> --}}
										<th>Nombre</th>
									
										<th>Activo</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rrhhtipocontratos as $rrhhtipocontrato)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											{{-- <td>{{ $rrhhtipocontrato->codigo }}</td> --}}
											<td>{{ $rrhhtipocontrato->nombre }}</td>
											
											<td>
                                                @if ($rrhhtipocontrato->activo)
                                                    <sapn class="badge badge-pill badge-primary">SI</sapn>
                                                @else
                                                    <sapn class="badge badge-pill badge-secondary">NO</sapn>
                                                @endif
                                            </td>

                                            <td class="text-right">
                                                <form action="{{ route('rrhhtipocontratos.destroy',$rrhhtipocontrato->id) }}" class="delete" onsubmit="return false" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('rrhhtipocontratos.show',$rrhhtipocontrato->id) }}" title="Ver info"><i class="fa fa-fw fa-eye"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('rrhhtipocontratos.edit',$rrhhtipocontrato->id) }}" title="Editar"><i class="fa fa-fw fa-edit"></i></a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar DB"><i class="fa fa-fw fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection
