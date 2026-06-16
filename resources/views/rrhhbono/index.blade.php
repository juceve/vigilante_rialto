@extends('layouts.app')

@section('template_title')
    Rrhhbono
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Rrhhbono') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('rrhhbonos.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Rrhhcontrato Id</th>
										<th>Fecha</th>
										<th>Rrhhtipobono Id</th>
										<th>Empleado Id</th>
										<th>Cantidad</th>
										<th>Monto</th>
										<th>Estado</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rrhhbonos as $rrhhbono)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $rrhhbono->rrhhcontrato_id }}</td>
											<td>{{ $rrhhbono->fecha }}</td>
											<td>{{ $rrhhbono->rrhhtipobono_id }}</td>
											<td>{{ $rrhhbono->empleado_id }}</td>
											<td>{{ $rrhhbono->cantidad }}</td>
											<td>{{ $rrhhbono->monto }}</td>
											<td>{{ $rrhhbono->estado }}</td>

                                            <td>
                                                <form action="{{ route('rrhhbonos.destroy',$rrhhbono->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('rrhhbonos.show',$rrhhbono->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('rrhhbonos.edit',$rrhhbono->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $rrhhbonos->links() !!}
            </div>
        </div>
    </div>
@endsection
