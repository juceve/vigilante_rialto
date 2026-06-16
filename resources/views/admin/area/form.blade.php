<div class="box box-info padding-1">
    <div class="box-body">

        <div class="form-group">
            {{ Form::label('nombre') }}
            {{ Form::text('nombre', $area->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre']) }}
            {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group{{ $errors->has('template') ? ' has-error' : '' }}">
            {!! Form::label('template', 'Plantilla') !!}
            {!! Form::select('template', $templateOptions, $area->template, [
                'id' => 'template',
                'class' => 'form-control',
                'required' => 'required',
            ]) !!}
            <small class="text-danger">{{ $errors->first('template') }}</small>
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
    </div>
</div>
