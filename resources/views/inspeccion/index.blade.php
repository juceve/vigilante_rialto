@extends('layouts.app')

@section('template_title')
    Inspeccion
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Inspeccion') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('inspeccions.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Designacionsupervisor Id</th>
										<th>Cliente Id</th>
										<th>Inicio</th>
										<th>Fin</th>
										<th>Status</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inspeccions as $inspeccion)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $inspeccion->designacionsupervisor_id }}</td>
											<td>{{ $inspeccion->cliente_id }}</td>
											<td>{{ $inspeccion->inicio }}</td>
											<td>{{ $inspeccion->fin }}</td>
											<td>{{ $inspeccion->status }}</td>

                                            <td>
                                                <form action="{{ route('inspeccions.destroy',$inspeccion->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('inspeccions.show',$inspeccion->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('inspeccions.edit',$inspeccion->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $inspeccions->links() !!}
            </div>
        </div>
    </div>
@endsection
