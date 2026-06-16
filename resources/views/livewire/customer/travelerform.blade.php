<div>

    <h3 class="mb-2 text-center  text-white" style="background-color: #1987547a">Titular</h3>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="arrival_date" class="form-label">Fecha-Hora Ingreso</label>
            <div class="d-flex">
                <input type="date" id="arrival_date" wire:model.lazy="arrival_date"
                    class="form-control  @error('arrival_date')
                    is-invalid
                @enderror"
                    required>
                <input type="time" class="form-control @error('arrival_hour')
                    is-invalid
                @enderror"  wire:model='arrival_hour'>
            </div>

            @error('arrival_date')
                <small class="text-danger">El campo es obligatorio</small>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="departure_date" class="form-label">Fecha-Hora Salida</label>
            <div class="d-flex">
                <input type="date" id="departure_date" wire:model.lazy="departure_date"
                    class="form-control  @error('departure_date')
                        is-invalid
                    @enderror"
                    required>
                <input type="time" class="form-control  @error('departure_hour')
                        is-invalid
                    @enderror" wire:model='departure_hour'>
            </div>
            @error('departure_date')
                <small class="text-danger">El campo es obligatorio</small>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <label for="department_info" class="form-label">Datos del Departamento</label>
            <input type="text" oninput="this.value = this.value.toUpperCase()" id="department_info" wire:model.lazy="department_info"
                class="form-control   @error('department_info')
                    is-invalid
                @enderror">
            @error('department_info')
                <small class="text-danger">El campo es obligatorio</small>
            @enderror
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Nombre del Titular</label>
            <input type="text" oninput="this.value = this.value.toUpperCase()" id="name" wire:model.lazy="name"
                class="form-control  @error('name')
                    is-invalid
                @enderror">
            @error('name')
                <small class="text-danger">El campo es obligatorio</small>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="birth_date" class="form-label">Fecha de Nacimiento</label>
            <input type="date" id="birth_date" wire:model.lazy="birth_date"
                class="form-control  @error('birth_date')
                    is-invalid
                @enderror">
            @error('birth_date')
                <small class="text-danger">El campo es obligatorio</small>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="document_type" class="form-label">Tipo de Documento</label>
            <select name="document_type" id="document_type"
                class="form-select @error('document_type')
                    is-invalid
                @enderror"
                wire:model.lazy="document_type">
                <option value="">Seleccione un tipo</option>
                <option value="ciudadania">Cedula Ciudadania</option>
                <option value="extranjero">Cedula Extranjero</option>
                <option value="pasaporte">Pasaporte</option>
            </select>
            @error('document_type')
                <small class="text-danger">El campo es obligatorio</small>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="document_number" class="form-label">Número de Documento</label>
            <input type="text" oninput="this.value = this.value.toUpperCase()" id="document_number" wire:model.lazy="document_number"
                class="form-control  @error('document_number')
                is-invalid
            @enderror">
            @error('document_number')
                <small class="text-danger">El campo es obligatorio</small>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="city_of_origin" class="form-label">Ciudad de Procedencia</label>
            <input type="text" oninput="this.value = this.value.toUpperCase()" id="city_of_origin" wire:model.lazy="city_of_origin" class="form-control ">
            @error('city_of_origin')
                <small class="text-danger">El campo es obligatorio</small>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="marital_status" class="form-label">Estado Civil</label>
            <select name="travel_purpose" id="travel_purpose" class="form-select" wire:model.lazy="marital_status">
                <option value="">Seleccione un estado</option>
                <option value="soltero">Soltero(a)</option>
                <option value="casado">Casado(a)</option>
                <option value="nodeclarado">No declarado</option>
            </select>
            @error('marital_status')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Dirección de Residencia</label>
        <input type="text" oninput="this.value = this.value.toUpperCase()" id="address" wire:model.lazy="address" class="form-control ">
        @error('address')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="row">

        <div class="col-md-6 mb-3">
            <label for="pais" class="form-label">País</label>
            <input type="text" oninput="this.value = this.value.toUpperCase()" id="pais" wire:model.lazy="country" class="form-control ">
            @error('country')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="city" class="form-label">Ciudad</label>
            <input type="text" oninput="this.value = this.value.toUpperCase()" id="city" wire:model.lazy="city" class="form-control ">
            @error('city')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" id="email" wire:model.lazy="email" class="form-control text-lowercase">
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="phone" class="form-label">Teléfono</label>
            <input type="text" oninput="this.value = this.value.toUpperCase()" id="phone" wire:model.lazy="phone" class="form-control ">
            @error('phone')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="occupation" class="form-label">Ocupación</label>
            <input type="text" oninput="this.value = this.value.toUpperCase()" id="occupation" wire:model.lazy="occupation" class="form-control ">
            @error('occupation')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="luggage_count" class="form-label">Cantidad de Equipajes</label>
            <input type="number" id="luggage_count" wire:model.lazy="luggage_count" class="form-control">
            @error('luggage_count')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="company" class="form-label">Empresa (opcional)</label>
        <input type="text" oninput="this.value = this.value.toUpperCase()" id="company" wire:model.defer="company" class="form-control ">
        @error('company')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="travel_purpose" class="form-label">Propósito de Viaje</label>
        <select name="travel_purpose" id="travel_purpose" class="form-select" wire:model.defer="travel_purpose">
            <option value="TURISMO">TURISMO</option>
            <option value="NEGOCIOS">NEGOCIOS</option>
            <option value="SALUD">SALUD</option>
            <option value="OTROS">OTROS</option>
        </select>
        @error('travel_purpose')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <h4 class="mt-5 text-center text-white" style="background-color: #1987547a">Acompañantes</h4>

    <div class="row">
        <div class="col-12 col-md-6 mb-3">
            <label class="form-label" for="name">Nombre</label>
            <input type="text" oninput="this.value = this.value.toUpperCase()" placeholder="Nombre" wire:model.defer="cname" class="form-control" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-12 col-md-6 mb-3">
            <label class="form-label" for="f">Fecha de Nacimiento</label>
            <input type="date" placeholder="Fecha de Nacimiento" wire:model.defer="cbirth_date"
                class="form-control">
        </div>
        <div class="col-12 col-md-6 mb-3">
            <label class="form-label" for="f">Tipo Documento</label>
            <select name="cdocument_type" id="cdocument_type"
                class="form-select @error('cdocument_type')
                    is-invalid
                @enderror"
                wire:model.lazy="cdocument_type">
                <option value="">Seleccione un tipo</option>
                <option value="ciudadania">Cedula Ciudadania</option>
                <option value="extranjero">Cedula Extranjero</option>
                <option value="pasaporte">Pasaporte</option>
            </select>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <label class="form-label" for="f">Nro. Documento</label>
            <input type="text" oninput="this.value = this.value.toUpperCase()" placeholder="Número Documento" wire:model.defer="cdocument_number"
                class="form-control" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-12 col-md-6 mb-3">
            <label class="form-label" for="f">Nacionalidad</label>
            <input type="text" oninput="this.value = this.value.toUpperCase()" placeholder="Nacionalidad" wire:model.defer="cnationality" class="form-control" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-12 col-md-6 mb-3">
            <label class="form-label" for="f">Cantidad Equipaje</label>
            <input type="number" placeholder="Cantidad" wire:model.defer="cluggage_count" class="form-control">
        </div>
        <div class="col-12 col-md-6 mb-1 d-grid">
            <button type="button" class="btn btn-secondary btn-sm mb-3" wire:click.prevent="addCompanion">Añadir
                Acompañante <i class="fas fa-plus"></i></button>
        </div>
    </div>

    @if (count($companions))
        <div class="table-responsive">
            <h5 class="text-primary">Acompañantes agregados</h5>
            <table class="table table-striped" style="font-size: 12px">
                <thead class="table-info">
                    <tr>
                        <th>NOMBRE</th>
                        <th>NRO. DOC.</th>
                        <th>NACIONALIDAD</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $c = 0;
                    @endphp
                    @forelse ($companions as $item)
                        <tr>
                            <td class="align-middle">{{ $item[0] }}</td>
                            <td class="align-middle">{{ $item[3] }}</td>
                            <td class="align-middle">{{ $item[5] }}</td>
                            <td class="align-middle text-end">
                                <button class="btn btn-sm btn-outline-danger" style="font-size: 10px"
                                    title="Eliminar" wire:click='delCompanion({{ $c }})'><i
                                        class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @php
                            $c++;
                        @endphp
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No se econtraron resultados</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    @endif

    <hr>
    <div class="row">
        <div class="col-12 d-grid">
            <button type="button" class="btn btn-primary" wire:click='registrar' wire:loading.attr="disabled"
                wire:loading.class="opacity-50">
                <span wire:loading.remove> REGISTRAR FORMULARIO <i class="fas fa-save"></i></span>
                <span wire:loading>
                    Procesando...
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </span>

            </button>
        </div>
    </div>




</div>
