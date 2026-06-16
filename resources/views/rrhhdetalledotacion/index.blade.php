@extends('layouts.app')

@section('template_title')
    Rrhhdetalledotacion
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Rrhhdetalledotacion') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('rrhhdetalledotacions.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Rrhhdotacion Id</th>
										<th>Detalle</th>
										<th>Cantidad</th>
										<th>Rrhhestadodotacion Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rrhhdetalledotacions as $rrhhdetalledotacion)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $rrhhdetalledotacion->rrhhdotacion_id }}</td>
											<td>{{ $rrhhdetalledotacion->detalle }}</td>
											<td>{{ $rrhhdetalledotacion->cantidad }}</td>
											<td>{{ $rrhhdetalledotacion->rrhhestadodotacion_id }}</td>

                                            <td>
                                                <form action="{{ route('rrhhdetalledotacions.destroy',$rrhhdetalledotacion->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('rrhhdetalledotacions.show',$rrhhdetalledotacion->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('rrhhdetalledotacions.edit',$rrhhdetalledotacion->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $rrhhdetalledotacions->links() !!}
            </div>
        </div>
    </div>
@endsection
