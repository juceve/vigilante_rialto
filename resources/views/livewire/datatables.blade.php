<div>
    <div class="form-group">

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><small>Busqueda: </small></span>
            </div>
            <input type="search" class="form-control" placeholder="Ingrese los datos a buscar">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr class="text-center">
                    <th style="width: 50px;">Nro</th>

                    @foreach ($titulos as $titulo)
                        <th>{{ Str::upper($titulo) }}</th>
                    @endforeach
                    <th style="width: 150px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr class="text-center">
                        <td>{{ $i++ }}</td>
                        @for ($x = 1; $x < count($item); $x++)
                            @if ($x == 4)
                                @if ($item[$x])
                                    <td><span class="badge badge-pill badge-success">Activo</span></td>
                                @else
                                    <td><span class="badge badge-pill badge-secondary">Anulado</span></td>
                                @endif
                            @else
                                <td>{{ $item[$x] }}</td>
                            @endif
                        @endfor

                        <td class="text-left">
                            <a class="btn btn-sm btn-info " href="{{ route('pdf.memorandum', $item[0]) }}"
                                title="Reimprimir" target="_blank"><i class="fa fa-fw fa-print"></i></a>
                            @if ($item[4])
                                <button class="btn btn-sm btn-warning" title="Editar"
                                    wire:click='editar({{ $item[0] }})' data-placement="left" data-toggle="modal"
                                    data-target="#modalMemo" onclick="boton('update')"><i
                                        class="fa fa-fw fa-edit"></i></button>

                                <button class="btn btn-sm btn-danger" title="Anular"
                                    onclick="anular({{ $item[0] }})"><i class="fa fa-fw fa-ban"></i></button>
                            @endif
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
