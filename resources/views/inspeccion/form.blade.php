<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('designacionsupervisor_id') }}
            {{ Form::text('designacionsupervisor_id', $inspeccion->designacionsupervisor_id, ['class' => 'form-control' . ($errors->has('designacionsupervisor_id') ? ' is-invalid' : ''), 'placeholder' => 'Designacionsupervisor Id']) }}
            {!! $errors->first('designacionsupervisor_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('cliente_id') }}
            {{ Form::text('cliente_id', $inspeccion->cliente_id, ['class' => 'form-control' . ($errors->has('cliente_id') ? ' is-invalid' : ''), 'placeholder' => 'Cliente Id']) }}
            {!! $errors->first('cliente_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('inicio') }}
            {{ Form::text('inicio', $inspeccion->inicio, ['class' => 'form-control' . ($errors->has('inicio') ? ' is-invalid' : ''), 'placeholder' => 'Inicio']) }}
            {!! $errors->first('inicio', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('fin') }}
            {{ Form::text('fin', $inspeccion->fin, ['class' => 'form-control' . ($errors->has('fin') ? ' is-invalid' : ''), 'placeholder' => 'Fin']) }}
            {!! $errors->first('fin', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('status') }}
            {{ Form::text('status', $inspeccion->status, ['class' => 'form-control' . ($errors->has('status') ? ' is-invalid' : ''), 'placeholder' => 'Status']) }}
            {!! $errors->first('status', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>