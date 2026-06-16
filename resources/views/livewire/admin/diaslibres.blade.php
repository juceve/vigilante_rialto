<div>


    @section('title')
    Dias Libres
    @endsection
    @section('content_header')
    <h4>Dias Libres</h4>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header bg-info">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                OPERADOR:
                                {{ $designacione->empleado->nombres . ' ' . $designacione->empleado->apellidos }} ||
                                TURNO: {{ $designacione->turno->nombre }} || EMPRESA:
                                {{ $designacione->turno->cliente->nombre }}
                            </span>

                            <div class="float-right">
                                <a href="javascript:history.back()" class="btn btn-info btn-sm float-right"
                                    data-placement="left">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <label for="">Nuevo día libre:</label>
                        <div class="row">
                            <div class="col-12 col-md-3">
                                <input type="date" class="form-control" wire:model='fecha'>
                                @error('fecha')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-12 col-md-3">
                                <input type="text" class="form-control" placeholder="Observaciones"
                                    wire:model='observaciones'>
                                @error('observaciones')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-12 col-md-3"><button class="btn btn-primary btn-block"
                                    wire:click='agregar'>Agregar <i class="fas fa-plus"></i></button></div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-info">
                                    <th>Nro</th>
                                    <th>FECHA</th>
                                    <th>OBSERVACIONES</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach ($dias as $dia)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $dia->fecha }}</td>
                                        <td>{{ $dia->observaciones }}</td>
                                        <td><button class="btn btn-outline-danger"
                                                wire:click='eliminar({{$dia->id}})'>Eliminar <i
                                                    class="fa fa-trash"></i></button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>