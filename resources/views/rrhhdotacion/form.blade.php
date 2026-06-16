<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('rrhhcontrato_id') }}
            {{ Form::text('rrhhcontrato_id', $rrhhdotacion->rrhhcontrato_id, ['class' => 'form-control' . ($errors->has('rrhhcontrato_id') ? ' is-invalid' : ''), 'placeholder' => 'Rrhhcontrato Id']) }}
            {!! $errors->first('rrhhcontrato_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('empleado_id') }}
            {{ Form::text('empleado_id', $rrhhdotacion->empleado_id, ['class' => 'form-control' . ($errors->has('empleado_id') ? ' is-invalid' : ''), 'placeholder' => 'Empleado Id']) }}
            {!! $errors->first('empleado_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('fecha') }}
            {{ Form::text('fecha', $rrhhdotacion->fecha, ['class' => 'form-control' . ($errors->has('fecha') ? ' is-invalid' : ''), 'placeholder' => 'Fecha']) }}
            {!! $errors->first('fecha', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('responsable_entrega') }}
            {{ Form::text('responsable_entrega', $rrhhdotacion->responsable_entrega, ['class' => 'form-control' . ($errors->has('responsable_entrega') ? ' is-invalid' : ''), 'placeholder' => 'Responsable Entrega']) }}
            {!! $errors->first('responsable_entrega', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('estado') }}
            {{ Form::text('estado', $rrhhdotacion->estado, ['class' => 'form-control' . ($errors->has('estado') ? ' is-invalid' : ''), 'placeholder' => 'Estado']) }}
            {!! $errors->first('estado', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>