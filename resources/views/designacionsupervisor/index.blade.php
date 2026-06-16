@extends('layouts.app')

@section('template_title')
    Designacionsupervisor
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Designacionsupervisor') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('designacionsupervisors.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Empleado Id</th>
										<th>Fechainicio</th>
										<th>Fechafin</th>
										<th>Observaciones</th>
										<th>Estado</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($designacionsupervisors as $designacionsupervisor)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $designacionsupervisor->empleado_id }}</td>
											<td>{{ $designacionsupervisor->fechaInicio }}</td>
											<td>{{ $designacionsupervisor->fechaFin }}</td>
											<td>{{ $designacionsupervisor->observaciones }}</td>
											<td>{{ $designacionsupervisor->estado }}</td>

                                            <td>
                                                <form action="{{ route('designacionsupervisors.destroy',$designacionsupervisor->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('designacionsupervisors.show',$designacionsupervisor->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('designacionsupervisors.edit',$designacionsupervisor->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $designacionsupervisors->links() !!}
            </div>
        </div>
    </div>
@endsection
