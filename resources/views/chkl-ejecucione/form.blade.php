<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('chkl_listaschequeo_id') }}
            {{ Form::text('chkl_listaschequeo_id', $chklEjecucione->chkl_listaschequeo_id, ['class' => 'form-control' . ($errors->has('chkl_listaschequeo_id') ? ' is-invalid' : ''), 'placeholder' => 'Chkl Listaschequeo Id']) }}
            {!! $errors->first('chkl_listaschequeo_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('fecha') }}
            {{ Form::text('fecha', $chklEjecucione->fecha, ['class' => 'form-control' . ($errors->has('fecha') ? ' is-invalid' : ''), 'placeholder' => 'Fecha']) }}
            {!! $errors->first('fecha', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('inspector_id') }}
            {{ Form::text('inspector_id', $chklEjecucione->inspector_id, ['class' => 'form-control' . ($errors->has('inspector_id') ? ' is-invalid' : ''), 'placeholder' => 'Inspector Id']) }}
            {!! $errors->first('inspector_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('notas') }}
            {{ Form::text('notas', $chklEjecucione->notas, ['class' => 'form-control' . ($errors->has('notas') ? ' is-invalid' : ''), 'placeholder' => 'Notas']) }}
            {!! $errors->first('notas', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>