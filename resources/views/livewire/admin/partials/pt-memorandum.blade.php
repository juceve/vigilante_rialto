<div>
    <div class="row">
        <div class="col-12 col-md-6 mb-2">
            <label>Cite:</label>
            <input type="text" class="form-control" wire:model.defer='m_cite'>
        </div>

        <div class="col-12 col-md-6 mb-2">

            <label for="selID">Empleado:</label>
            <select name="selID" id="selID" class="form-control" wire:model='selID'>
                <option value="">Seleccione un Empleado</option>
                @foreach ($empleados as $empleado)
                    <option value="{{ $empleado->id }}">{{ $empleado->nombres . ' ' . $empleado->apellidos }}</option>
                @endforeach
            </select>
            @error('selID')
                <small class="text-danger">Debe seleccionar un Empleado</small>
            @enderror
        </div>


        <div class="col-12 col-md-6 mb-2">
            <label>Fecha:</label>
            <input type="date" class="form-control" wire:model.defer='m_fecha'>
        </div>



        <div class="col-12 mb-2">
            <label>Punto:</label>
            <textarea class="form-control" name="motivo" id="motivo" rows="5" wire:model='m_motivo'>
                
            </textarea>
        </div>


        <div class="col-12 col-md-6 mt-3">
            <button class="btn btn-primary btn-block" wire:click='generarInforme'>Generar Memorandum <i
                    class="fas fa-file-import"></i></button>
        </div>
    </div>
</div>
