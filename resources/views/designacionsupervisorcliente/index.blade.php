@extends('layouts.app')

@section('template_title')
    Designacionsupervisorcliente
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Designacionsupervisorcliente') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('designacionsupervisorclientes.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($designacionsupervisorclientes as $designacionsupervisorcliente)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $designacionsupervisorcliente->designacionsupervisor_id }}</td>
											<td>{{ $designacionsupervisorcliente->cliente_id }}</td>

                                            <td>
                                                <form action="{{ route('designacionsupervisorclientes.destroy',$designacionsupervisorcliente->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('designacionsupervisorclientes.show',$designacionsupervisorcliente->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('designacionsupervisorclientes.edit',$designacionsupervisorcliente->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $designacionsupervisorclientes->links() !!}
            </div>
        </div>
    </div>
@endsection
