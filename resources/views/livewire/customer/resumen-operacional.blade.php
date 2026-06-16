<div>
    <div class="card">
        <div class="card-header bg-success text-white">Resumen Operacional</div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-5">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Desde</span>
                        </div>
                        <input type="date" class="form-control" wire:model='fechaInicio' aria-label="fechaInicio">
                    </div>
                </div>
                <div class="col-12 col-md-5">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Hasta</span>
                        </div>
                        <input type="date" class="form-control" wire:model='fechaFin' aria-label="fechaFin">
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped" style="font-size: 11px;">
                <thead class="table-success">
                    <tr class="text-center">
                        <th class="align-middle">OPERACIÓN REALIZADA</th>
                        <th class="align-middle text-center">CANTIDAD</th>

                    </tr>
                </thead>
                <tbody>
                    <tr></tr>
                    @forelse ($resultados as $item)
                    <tr>
                        <td class="align-middle">RONDAS EJECUTADAS</td>
                        <td class="text-center">{{$item['rondas']}}</td>
                    </tr>
                    <tr>
                        <td class="align-middle">VISITAS REGISTRADAS</td>
                        <td class="text-center">{{$item['visitas']}}</td>
                    </tr>
                    <tr>
                        <td class="align-middle">PASES QR REGISTRADOOS</td>
                        <td class="text-center">{{$item['flujopases']}}</td>
                    </tr>
                    <tr>
                        <td class="align-middle">REGISTRO DE PANICOS</td>
                        <td class="text-center">{{$item['panicos']}}</td>
                    </tr>
                    <tr>
                        <td class="align-middle">TAREAS PROCESADAS</td>
                        <td class="text-center">{{$item['tareas']}}</td>
                    </tr>
                    <tr>
                        <td class="align-middle">REGISTRO DE HOMBRES VIVOS</td>
                        <td class="text-center">{{$item['hombrevivos']}}</td>
                    </tr>
                    <tr>
                        <td class="align-middle">NOVEDADES RECIBIDAS</td>
                        <td class="text-center">{{$item['novedades']}}</td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="2" class="text-center">No existen datos disponibles</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>