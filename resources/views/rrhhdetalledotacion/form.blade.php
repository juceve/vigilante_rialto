<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('rrhhdotacion_id') }}
            {{ Form::text('rrhhdotacion_id', $rrhhdetalledotacion->rrhhdotacion_id, ['class' => 'form-control' . ($errors->has('rrhhdotacion_id') ? ' is-invalid' : ''), 'placeholder' => 'Rrhhdotacion Id']) }}
            {!! $errors->first('rrhhdotacion_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('detalle') }}
            {{ Form::text('detalle', $rrhhdetalledotacion->detalle, ['class' => 'form-control' . ($errors->has('detalle') ? ' is-invalid' : ''), 'placeholder' => 'Detalle']) }}
            {!! $errors->first('detalle', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('cantidad') }}
            {{ Form::text('cantidad', $rrhhdetalledotacion->cantidad, ['class' => 'form-control' . ($errors->has('cantidad') ? ' is-invalid' : ''), 'placeholder' => 'Cantidad']) }}
            {!! $errors->first('cantidad', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('rrhhestadodotacion_id') }}
            {{ Form::text('rrhhestadodotacion_id', $rrhhdetalledotacion->rrhhestadodotacion_id, ['class' => 'form-control' . ($errors->has('rrhhestadodotacion_id') ? ' is-invalid' : ''), 'placeholder' => 'Rrhhestadodotacion Id']) }}
            {!! $errors->first('rrhhestadodotacion_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>