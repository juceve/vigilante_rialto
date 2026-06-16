<div class="container-fluid py-3">

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header text-white rounded-top-4"
                    style="background: linear-gradient(90deg, #0d6efd, #0dcaf0);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Registro de Propietario - {{ $cliente->nombre }}</h5>
                        <div wire:loading>
                            <div class="spinner-border spinner-border-sm text-light" role="status"></div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Cédula *</label>

                            <div class="input-group mb-3">
                                <input type="text" class="form-control @error('cedula') is-invalid @enderror"
                                    placeholder="Cédula" aria-label="Cédula" aria-describedby="button-addon2"
                                    wire:model.lazy="cedula">
                                <button class="btn btn-outline-success" type="button" title="Buscar"
                                    wire:click='buscarPropietario'>
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            @error('cedula')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre *</label>
                            <input type="text" @if ($existePropietario) disabled @endif
                                class="form-control text-uppercase @error('nombre') is-invalid @enderror"
                                style="text-transform: uppercase;" wire:model.lazy="nombre"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" @if ($existePropietario) disabled @endif
                                class="form-control @error('telefono') is-invalid @enderror" wire:model.lazy="telefono">
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" @if ($existePropietario) disabled @endif
                                class="form-control @error('email') is-invalid @enderror" wire:model.lazy="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Dirección</label>
                            <input type="text" @if ($existePropietario) disabled @endif
                                class="form-control text-uppercase @error('direccion') is-invalid @enderror"
                                style="text-transform: uppercase;" wire:model.lazy="direccion"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ciudad</label>
                            <input type="text" @if ($existePropietario) disabled @endif
                                class="form-control text-uppercase @error('ciudad') is-invalid @enderror"
                                style="text-transform: uppercase;" wire:model.lazy="ciudad"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('ciudad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- <br>
                    <span class="mt-4 text-primary"><strong>DATOS DE LA RESIDENCIA</strong></span>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <small>Nro. Puerta</small>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="numeropuerta">
                        </div>
                        <div class="col-12 col-md-3">
                            <small>Piso</small>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="piso">
                        </div>
                        <div class="col-12 col-md-3">
                            <small>Calle</small>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="calle">
                        </div>
                        <div class="col-12 col-md-3">
                            <small>Nro. Lote</small>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="nrolote">
                        </div>
                        <div class="col-12 col-md-3">
                            <small>Manzano</small>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="manzano">
                        </div>
                        <div class="col-12 col-md-6">
                            <small>Notas</small>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="notas">
                        </div>
                        <div class="col-12 col-md-3 d-grid">
                            <small>&nbsp;</small>
                            <button class="btn btn-primary btn-sm" wire:click="addResidencia">Agregar <i
                                    class="bi bi-plus-lg"></i></button>
                        </div>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table table-striped table-sm" style="font-size: 12px;">
                            <thead class="table-primary">
                                <tr>
                                    <th>Nro Puerta</th>
                                    <th>Piso</th>
                                    <th>Calle</th>
                                    <th>Nro Lote</th>
                                    <th>Manzano</th>
                                    <th>Notas</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @forelse ($residencias as $item)
                                    <tr>
                                        <td style="vertical-align: middle">
                                            {{ !empty($item['numeropuerta']) ? $item['numeropuerta'] : '-' }}</td>
                                        <td style="vertical-align: middle">
                                            {{ !empty($item['piso']) ? $item['piso'] : '-' }}</td>
                                        <td style="vertical-align: middle">
                                            {{ !empty($item['calle']) ? $item['calle'] : '-' }}</td>
                                        <td style="vertical-align: middle">
                                            {{ !empty($item['nrolote']) ? $item['nrolote'] : '-' }}</td>
                                        <td style="vertical-align: middle">
                                            {{ !empty($item['manzano']) ? $item['manzano'] : '-' }}</td>
                                        <td style="vertical-align: middle">
                                            {{ !empty($item['notas']) ? $item['notas'] : '-' }}</td>
                                        <td style="vertical-align: middle" class="text-end">
                                            <button class="btn btn-danger btn-sm"
                                                wire:click="delResidencia({{ $i }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No hay residencias agregadas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <hr class="my-4"> --}}
                    @if (!$existePropietario)
                        <div class="text-end">
                            <button type="submit" class="btn btn-success px-4" wire:loading.attr="disabled"
                                wire:target='save' wire:click="save">
                                <span wire:loading.remove wire:target='save'>Registrar Solicitud <i
                                        class="bi bi-floppy"></i></span>
                                <span wire:loading wire:target='save'>
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                    Registrando...
                                </span>
                            </button>
                        </div>
                    @endif


                </div>
            </div>

            {{-- Overlay global mientras Livewire procesa --}}
            <div wire:loading.flex wire:target='save'
                style="position: fixed; inset: 0; background: rgba(255,255,255,.6); z-index: 1050;
                        align-items:center; justify-content:center;">
                <div class="spinner-border" role="status"></div>
            </div>
        </div>
    </div>
</div>
