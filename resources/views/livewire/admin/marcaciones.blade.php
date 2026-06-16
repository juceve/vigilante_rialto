<div>
    <div class="table-responsive" wire:ignore>
        <table class="table table-bordered table-striped dataTableA">

            <thead class="table-info">
                <tr align="center" style="vertical-align: middle">
                    <td><strong>FECHAS</strong></td>

                    <td><strong>INGRESO <br> {{ $designacione->turno->horainicio }}</strong></td>
                    <td><strong>SALIDA <br> {{ $designacione->turno->horafin }}</strong></td>

                </tr>
            </thead>
            <tbody>
                @if (count($marcaciones) > 0)
                @foreach ($marcaciones as $marcado)
                <tr align="center">
                    <td>{{ $marcado->fecha }}</td>
                    <td>
                        @if ($marcado->ingreso)
                        <a href="javascript:void(0);" class="text-dark" data-toggle="modal" data-target="#modalMarca"
                            wire:click='cargar({{ $marcado->id }},1)'>{{ substr($marcado->ingreso,11,5) }}</a>
                        @else
                        &#10060;
                        @endif

                    </td>

                    <td>
                        @if ($marcado->salida)
                        <a href="javascript:void(0);" class="text-dark" data-toggle="modal" data-target="#modalMarca"
                            wire:click='cargar({{ $marcado->id }},2)'>{{ substr($marcado->salida,11,5) }}</a>
                        @else
                        &#10060;
                        @endif
                    </td>
                </tr>
                @endforeach
                @endif

            </tbody>

        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalMarca" tabindex="-1" aria-labelledby="modalMarcaLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMarcaLabel">Marcado de Asistencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label>Fecha:</label>
                                <input type="text" class="form-control bg-white" wire:model='fecha' readonly>
                            </div>
                            <div class="col-6 mb-3">
                                <label>Hora:</label>
                                <input type="text" class="form-control bg-white" wire:model='hora' readonly>
                            </div>
                            {{-- <div class="col-6 mb-3">
                                <label>Latitud:</label>
                                <input type="text" class="form-control bg-white" wire:model='lat' readonly>
                            </div>
                            <div class="col-6 mb-3">
                                <label>Longitud:</label>
                                <input type="text" class="form-control bg-white" wire:model='lng' readonly>
                            </div> --}}
                            {{-- <div class="col-6 mb-3 mt-2">
                                <a href="../ubicacion/{{ $lat }}/{{ $lng }}" target="__blank"
                                    class="btn btn-success btn-block">Ver Ubicación en el Mapa <i
                                        class="fas fa-map-marked-alt"></i></a>
                            </div> --}}

                        </div>

                    </div>
                    <label>Ubicación:</label>
                    <div id="mapa1" style="width: 100%;height: 350px;">
                        @if ($lat && $lng)
                        <iframe src="../ubicacion/{{ $lat }}/{{ $lng }}" style="width: 100%; height: 100%"
                            name="ubicacion"></iframe>
                        @endif

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>