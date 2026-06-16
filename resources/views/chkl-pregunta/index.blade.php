@extends('layouts.app')

@section('template_title')
    Chkl Pregunta
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Chkl Pregunta') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('chkl-preguntas.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
										<th>Descripcion</th>
										<th>Tipoboleta Id</th>
										<th>Requerida</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($chklPreguntas as $chklPregunta)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $chklPregunta->chkl_listaschequeo_id }}</td>
											<td>{{ $chklPregunta->descripcion }}</td>
											<td>{{ $chklPregunta->tipoboleta_id }}</td>
											<td>{{ $chklPregunta->requerida }}</td>

                                            <td>
                                                <form action="{{ route('chkl-preguntas.destroy',$chklPregunta->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('chkl-preguntas.show',$chklPregunta->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('chkl-preguntas.edit',$chklPregunta->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $chklPreguntas->links() !!}
            </div>
        </div>
    </div>
@endsection
