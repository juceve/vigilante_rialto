<div>
    <table class="table table-bordered table-striped dataTableA">

        <thead class="table-info">
            <tr align="center" style="vertical-align: middle">
                <td><strong>FECHAS</strong></td>
                @foreach ($designacione->turno->ctrlpuntos as $punto)
                    <td><strong>{{ $punto->nombre }} <br> {{ $punto->hora }}</strong></td>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if (count($rondas) > 0)
                @foreach ($rondas as $ronda)
                    <tr align="center">
                        @foreach ($ronda as $item)
                            @if (strlen($item[0]) > 5)
                                <td>{{ $item[0] }}</td>
                            @else
                                @switch($item[1])
                                    @case(1)
                                        <td><a class="text-danger" href="javascript:void(0);" title="Sin Marcado">
                                                &#10060;
                                            </a></td>
                                    @break

                                    @case(2)
                                        <td><a class="text-warning" href="javascript:void(0);" title="Con Retraso"
                                                data-toggle="modal" data-target="#modalPunto"
                                                wire:click="cargaPunto({{ $item[2] }})">
                                                {{ $item[0] }}

                                            </a></td>
                                    @break

                                    @case(0)
                                        <td><a class="text-success" href="javascript:void(0);" title="Ver Info"
                                                data-toggle="modal" data-target="#modalPunto"
                                                wire:click="cargaPunto({{ $item[2] }})">
                                                {{ $item[0] }}
                                            </a></td>
                                    @break
                                @endswitch
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            @endif

        </tbody>

    </table>


    <!-- Modal -->
    <div class="modal fade" id="modalPunto" tabindex="-1" aria-labelledby="modalPuntoLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPuntoLabel">Marcado de Ronda</h5>
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
                            <div class="col-12 mb-3">
                                <label>Anotaciones:</label>
                                <textarea rows="2" class="form-control bg-white" wire:model='anotaciones' readonly></textarea>
                            </div>
                        </div>
                    </div>
                    @if ($this->imgrondas)
                        <label for="">Capturas:</label>
                        <div class="row">
                            @foreach ($imgrondas as $img)
                                <div class="col-3">
                                    <a href="#{{ $img->id }}">
                                        <img class="img-thumbnail img-fluid" src="{{ asset('storage/' . $img->url) }}"
                                            alt="">
                                    </a>
                                    <article class="light-box" id="{{ $img->id }}">                                        
                                        <img src="{{ asset('storage/' . $img->url) }}" class="img-fluid">                                        
                                        <a href="#" class="light-box-close">X</a>
                                    </article>
                                </div>
                            @endforeach
                        </div>

                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
