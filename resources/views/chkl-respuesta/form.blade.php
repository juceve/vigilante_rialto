<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('chkl_ejecucione_id') }}
            {{ Form::text('chkl_ejecucione_id', $chklRespuesta->chkl_ejecucione_id, ['class' => 'form-control' . ($errors->has('chkl_ejecucione_id') ? ' is-invalid' : ''), 'placeholder' => 'Chkl Ejecucione Id']) }}
            {!! $errors->first('chkl_ejecucione_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('chkl_pregunta_id') }}
            {{ Form::text('chkl_pregunta_id', $chklRespuesta->chkl_pregunta_id, ['class' => 'form-control' . ($errors->has('chkl_pregunta_id') ? ' is-invalid' : ''), 'placeholder' => 'Chkl Pregunta Id']) }}
            {!! $errors->first('chkl_pregunta_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('ok') }}
            {{ Form::text('ok', $chklRespuesta->ok, ['class' => 'form-control' . ($errors->has('ok') ? ' is-invalid' : ''), 'placeholder' => 'Ok']) }}
            {!! $errors->first('ok', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('observacion') }}
            {{ Form::text('observacion', $chklRespuesta->observacion, ['class' => 'form-control' . ($errors->has('observacion') ? ' is-invalid' : ''), 'placeholder' => 'Observacion']) }}
            {!! $errors->first('observacion', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>