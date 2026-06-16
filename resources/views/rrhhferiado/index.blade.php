@extends('layouts.app')

@section('template_title')
    Rrhhferiado
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Rrhhferiado') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('rrhhferiados.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
										<th>Fecha</th>
										<th>Fecha Inicio</th>
										<th>Fecha Fin</th>
										<th>Recurrente</th>
										<th>Factor</th>
										<th>Activo</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rrhhferiados as $rrhhferiado)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $rrhhferiado->nombre }}</td>
											<td>{{ $rrhhferiado->fecha }}</td>
											<td>{{ $rrhhferiado->fecha_inicio }}</td>
											<td>{{ $rrhhferiado->fecha_fin }}</td>
											<td>{{ $rrhhferiado->recurrente }}</td>
											<td>{{ $rrhhferiado->factor }}</td>
											<td>{{ $rrhhferiado->activo }}</td>

                                            <td>
                                                <form action="{{ route('rrhhferiados.destroy',$rrhhferiado->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('rrhhferiados.show',$rrhhferiado->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('rrhhferiados.edit',$rrhhferiado->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $rrhhferiados->links() !!}
            </div>
        </div>
    </div>
@endsection
