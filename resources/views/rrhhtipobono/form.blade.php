<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('nombre') }}
            {{ Form::text('nombre', $rrhhtipobono->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre']) }}
            {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('nombre_corto') }}
            {{ Form::text('nombre_corto', $rrhhtipobono->nombre_corto, ['class' => 'form-control' . ($errors->has('nombre_corto') ? ' is-invalid' : ''), 'placeholder' => 'Nombre Corto']) }}
            {!! $errors->first('nombre_corto', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('monto') }}
            {{ Form::number('monto', $rrhhtipobono->monto, ['class' => 'form-control' . ($errors->has('monto') ? ' is-invalid' : ''), 'placeholder' => '0.00', 'step' => 'any']) }}
            {!! $errors->first('monto', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary col-12 col-md-3">Guardar <i class="fas fa-save"></i></button>
    </div>
</div>