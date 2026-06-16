@extends('layouts.app')

@section('template_title')
    Chkl Ejecucione
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Chkl Ejecucione') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('chkl-ejecuciones.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Chkl Listaschequeo Id</th>
										<th>Fecha</th>
										<th>Inspector Id</th>
										<th>Notas</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($chklEjecuciones as $chklEjecucione)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $chklEjecucione->chkl_listaschequeo_id }}</td>
											<td>{{ $chklEjecucione->fecha }}</td>
											<td>{{ $chklEjecucione->inspector_id }}</td>
											<td>{{ $chklEjecucione->notas }}</td>

                                            <td>
                                                <form action="{{ route('chkl-ejecuciones.destroy',$chklEjecucione->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('chkl-ejecuciones.show',$chklEjecucione->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('chkl-ejecuciones.edit',$chklEjecucione->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $chklEjecuciones->links() !!}
            </div>
        </div>
    </div>
@endsection
