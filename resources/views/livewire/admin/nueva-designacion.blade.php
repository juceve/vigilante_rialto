<div>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="form-group">
                <label>Empleado:</label>
                <div class="input-group">
                    <input type="text" class="form-control bg-white" wire:model='nombres'
                        placeholder="Seleccione un Empleado" readonly>
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalEmpleados"><i class="fas fa-search"></i> Buscar
                            <div wire:loading>
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>

                        </button>
                    </div>

                </div>
                @if ($contrato)
                    <small class="text-success">* Vigencia Contrato: [{{ $contrato->fecha_inicio }} -
                        {{ $contrato->fecha_fin ?? 'Indefinido' }}]</small>
                @endif
            </div>
        </div>

        @if ($empleado)
            {{-- Campos segun tipo de designacion --}}
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label>Clientes:</label>
                    <select name="clienteid" wire:model='clienteid' class="form-control">
                        <option value="">Seleccione un cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                    @error('clienteid')
                        <small class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label>Turno:</label>
                    <select name="turnoid" wire:model='turnoid' class="form-control">
                        <option value="">Seleccione un turno</option>
                        @if ($clienteSeleccionado)
                            @foreach ($clienteSeleccionado->turnos as $turno)
                                <option value="{{ $turno->id }}">{{ $turno->nombre }}</option>
                            @endforeach
                        @else
                            <option value="">-- Seleccione un Cliente --</option>
                        @endif
                    </select>
                    @error('turnoid')
                        <small class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label>Fecha Inicio:</label>
                    <input type="date" class="form-control" wire:model.debounce.800ms='fechaInicio'>
                    @error('fechaInicio')
                        <small class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label>Fecha Fin:</label>
                    <input type="date" class="form-control" wire:model.debounce.800ms='fechaFin'>
                    @error('fechaFin')
                        <small class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label>Intervalo Hrs:</label> <small>Alerta Hombre Vivo</small>
                    <input type="number" class="form-control" wire:model.defer='intervalo_hv'>
                    @error('intervalo_hv')
                        <small class="text-danger"><i class="fas fa-exclamation-circle"></i>
                            {{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label>Observaciones:</label>
                    <input type="text" class="form-control" wire:model.defer='observaciones'>
                    @error('observaciones')
                        <small class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <label>Días laborales:</label> <button class="btn btn-sm btn-outline-info"
                    wire:click="seleccionarTodosDias">Sel. Todo <i class="fas fa-check-double"></i></button>
                <div class="row">
                    <div class="col-6 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="domingo" wire:model="domingo">
                            <label class="form-check-label" for="domingo">Domingo</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="lunes" wire:model="lunes">
                            <label class="form-check-label" for="lunes">Lunes</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="martes" wire:model="martes">
                            <label class="form-check-label" for="martes">Martes</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="miercoles" wire:model="miercoles">
                            <label class="form-check-label" for="miercoles">Miércoles</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="jueves" wire:model="jueves">
                            <label class="form-check-label" for="jueves">Jueves</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="viernes" wire:model="viernes">
                            <label class="form-check-label" for="viernes">Viernes</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sabado" wire:model="sabado">
                            <label class="form-check-label" for="sabado">Sábado</label>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-12 col-md-6 mt-2">
                <button class="btn btn-block btn-success" wire:click='registrar'><i class="fas fa-save"></i>
                    Registrar</button>
            </div>
        @endif

    </div>


    <!-- Modal -->
    <div class="modal fade" id="modalEmpleados" tabindex="-1" aria-labelledby="modalEmpleadosLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" wire:ignore.self>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEmpleadosLabel">Seleccione un Operador</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="content">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm dataTable">
                                <thead class="table-success">
                                    <tr>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                        <th>No. Doc.</th>
                                        <th>Vigencia Contrato</th>
                                        {{-- <th>Oficina</th> --}}
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($empleados as $item)
                                        <tr>
                                            <td>{{ $item->nombres }}</td>
                                            <td>{{ $item->apellidos }}</td>
                                            <td>{{ $item->cedula }}</td>
                                            <td>{{ $item->fecha_inicio }} -
                                                {{ $item->fecha_fin ? $item->fecha_fin : 'Indefinido' }}
                                            </td>
                                            {{-- <td>{{ $item->oficina }}</td> --}}
                                            <td align="right">
                                                <button class="btn btn-sm btn-outline-success"
                                                    onclick="seleccionaEmpleado({{ $item->empleado_id }}, {{ $item->contrato_id }})"
                                                    data-dismiss="modal">
                                                    <i class="fas fa-check"></i>
                                                    Seleccionar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
    <script>
        function seleccionaEmpleado(id, contrato_id) {
            Livewire.emit('seleccionaEmpleado', id, contrato_id);
        }
    </script>
@endsection
