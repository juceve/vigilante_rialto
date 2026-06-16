<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('chkl_listaschequeo_id') }}
            {{ Form::text('chkl_listaschequeo_id', $chklPregunta->chkl_listaschequeo_id, ['class' => 'form-control' . ($errors->has('chkl_listaschequeo_id') ? ' is-invalid' : ''), 'placeholder' => 'Chkl Listaschequeo Id']) }}
            {!! $errors->first('chkl_listaschequeo_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('descripcion') }}
            {{ Form::text('descripcion', $chklPregunta->descripcion, ['class' => 'form-control' . ($errors->has('descripcion') ? ' is-invalid' : ''), 'placeholder' => 'Descripcion']) }}
            {!! $errors->first('descripcion', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('tipoboleta_id') }}
            {{ Form::text('tipoboleta_id', $chklPregunta->tipoboleta_id, ['class' => 'form-control' . ($errors->has('tipoboleta_id') ? ' is-invalid' : ''), 'placeholder' => 'Tipoboleta Id']) }}
            {!! $errors->first('tipoboleta_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('requerida') }}
            {{ Form::text('requerida', $chklPregunta->requerida, ['class' => 'form-control' . ($errors->has('requerida') ? ' is-invalid' : ''), 'placeholder' => 'Requerida']) }}
            {!! $errors->first('requerida', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>