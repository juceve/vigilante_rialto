<div>
    <div class="row">
        <div class="col-12 col-md-6 mb-2">
            <div class="form-group{{ $errors->has('selID') ? ' has-error' : '' }}">
                {!! Form::label('selID', 'Cliente:') !!}
                {!! Form::select('selID', $clientes, null, [
                    'id' => 'selID',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Seleccione un Cliente',
                    'wire:model' => 'selID',
                ]) !!}
                @error('selID')
                    <small class="text-danger">Debe seleccionar un cliente</small>
                @enderror
            </div>
        </div>


        <div class="col-12 col-md-6 mb-2">
            <label>Fecha Carta:</label>
            <input type="date" class="form-control" wire:model.defer='fecha'>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <label>Mes Pagado:</label>
            <select name="mescobro" id="mes" class="form-control" wire:model='mes'>
                <option value="">Seleccione un Mes</option>
                <option value="Enero">Enero</option>
                <option value="Febrero">Febrero</option>
                <option value="Marzo">Marzo</option>
                <option value="Abril">Abril</option>
                <option value="Mayo">Mayo</option>
                <option value="Junio">Junio</option>
                <option value="Julio">Julio</option>
                <option value="Agosto">Agosto</option>
                <option value="Septiembre">Septiembre</option>
                <option value="Octubre">Octubre</option>
                <option value="Noviembre">Noviembre</option>
                <option value="Diciembre">Diciembre</option>
            </select>
            @error('mes')
                <small class="text-danger">Debe seleccionar un mes</small>
            @enderror
        </div>
        <div class="col-12 col-md-6 mb-2">
            <label>Monto:</label>
            <input type="number" class="form-control" wire:model.defer='monto'>
            @error('monto')
                <small class="text-danger">Campo Monto requerido</small>
            @enderror
        </div>
        <div class="col-12 col-md-6 mt-3">
            <button class="btn btn-block btn-primary" wire:click='generar'>Generar Recibo <i
                    class="fas fa-file-import"></i></button>
        </div>
    </div>
</div>
