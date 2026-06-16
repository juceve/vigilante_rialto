<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('fechahora') }}
            {{ Form::text('fechahora', $supBoleta->fechahora, ['class' => 'form-control' . ($errors->has('fechahora') ? ' is-invalid' : ''), 'placeholder' => 'Fechahora']) }}
            {!! $errors->first('fechahora', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('cliente_id') }}
            {{ Form::text('cliente_id', $supBoleta->cliente_id, ['class' => 'form-control' . ($errors->has('cliente_id') ? ' is-invalid' : ''), 'placeholder' => 'Cliente Id']) }}
            {!! $errors->first('cliente_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('empleado_id') }}
            {{ Form::text('empleado_id', $supBoleta->empleado_id, ['class' => 'form-control' . ($errors->has('empleado_id') ? ' is-invalid' : ''), 'placeholder' => 'Empleado Id']) }}
            {!! $errors->first('empleado_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('tipoboleta_id') }}
            {{ Form::text('tipoboleta_id', $supBoleta->tipoboleta_id, ['class' => 'form-control' . ($errors->has('tipoboleta_id') ? ' is-invalid' : ''), 'placeholder' => 'Tipoboleta Id']) }}
            {!! $errors->first('tipoboleta_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('supervisor_id') }}
            {{ Form::text('supervisor_id', $supBoleta->supervisor_id, ['class' => 'form-control' . ($errors->has('supervisor_id') ? ' is-invalid' : ''), 'placeholder' => 'Supervisor Id']) }}
            {!! $errors->first('supervisor_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('detalles') }}
            {{ Form::text('detalles', $supBoleta->detalles, ['class' => 'form-control' . ($errors->has('detalles') ? ' is-invalid' : ''), 'placeholder' => 'Detalles']) }}
            {!! $errors->first('detalles', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('descuento') }}
            {{ Form::text('descuento', $supBoleta->descuento, ['class' => 'form-control' . ($errors->has('descuento') ? ' is-invalid' : ''), 'placeholder' => 'Descuento']) }}
            {!! $errors->first('descuento', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>