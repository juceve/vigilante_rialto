<div class="box box-info padding-1">
    <div class="box-body">

        <div class="form-group">
            {{ Form::label('nombre') }}
            {{ Form::text('nombre', $motivo->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre']) }}
            {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">            
            <div class="form-group{{ $errors->has('estado') ? ' has-error' : '' }}">
                {!! Form::label('estado', 'Estado') !!}
                {!! Form::select('estado', [1 => "Activo", 0=>'Inactivo'], $motivo->estado ?? 1, [
                    'id' => 'estado',
                    'class' => 'form-control']) !!}
                <small class="text-danger">{{ $errors->first('estado') }}</small>
            </div>


        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Guardar <i class="fas fa-save"></i></button>
    </div>
</div>
