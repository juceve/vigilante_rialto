@extends('layouts.app')

@section('template_title')
    Chkl Respuesta
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Chkl Respuesta') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('chkl-respuestas.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Chkl Ejecucione Id</th>
										<th>Chkl Pregunta Id</th>
										<th>Ok</th>
										<th>Observacion</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($chklRespuestas as $chklRespuesta)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $chklRespuesta->chkl_ejecucione_id }}</td>
											<td>{{ $chklRespuesta->chkl_pregunta_id }}</td>
											<td>{{ $chklRespuesta->ok }}</td>
											<td>{{ $chklRespuesta->observacion }}</td>

                                            <td>
                                                <form action="{{ route('chkl-respuestas.destroy',$chklRespuesta->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('chkl-respuestas.show',$chklRespuesta->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('chkl-respuestas.edit',$chklRespuesta->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $chklRespuestas->links() !!}
            </div>
        </div>
    </div>
@endsection
