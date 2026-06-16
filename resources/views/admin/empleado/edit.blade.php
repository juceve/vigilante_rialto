@extends('adminlte::page')

@section('title')
    Editar Empleado
@endsection
@section('content_header')
    <h4>Editar Empleado</h4>
@endsection
@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header bg-info">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Formulario de Edición
                            </span>

                             <div class="float-right">
                                <a href="{{route('rrhh.kardex',$empleado->id)}}" class="btn btn-info btn-sm float-right"  data-placement="left">
                                  <i class="fas fa-arrow-left"></i> Volver
                                </a>
                              </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('empleados.update', $empleado->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('admin.empleado.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
