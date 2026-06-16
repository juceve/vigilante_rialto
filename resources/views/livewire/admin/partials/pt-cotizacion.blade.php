<div>
    <div class="row">

        <div class="col-12 col-md-6 mb-2">
            <label>Destinatario:</label>
            <input type="text" class="form-control" wire:model.defer='destinatario'>
        </div>
        <div class="col-12 col-md-6 mb-2">
            <label>Cargo:</label>
            <input type="text" class="form-control" wire:model.defer='cargo'>
        </div>
        <div class="col-12 col-md-6 mb-2">
            <label>Fecha Carta:</label>
            <input type="date" class="form-control" wire:model.defer='fecha'>
        </div>
        <div class="col-12 col-md-6 mb-2">
            <label>Monto:</label>
            <input type="number" class="form-control" wire:model.defer='monto'>
            @error('monto')
                <small class="text-danger">Campo Monto es requerido</small>
            @enderror
        </div>
        <div class="col-12 col-md-6 mt-3">
            <button class="btn btn-block btn-primary" wire:click='generar'>Generar Cotización <i
                class="fas fa-file-import"></i></button>
        </div>
    </div>
</div>
