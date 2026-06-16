<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('designacionsupervisor_id') }}
            {{ Form::text('designacionsupervisor_id', $designacionsupervisorcliente->designacionsupervisor_id, ['class' => 'form-control' . ($errors->has('designacionsupervisor_id') ? ' is-invalid' : ''), 'placeholder' => 'Designacionsupervisor Id']) }}
            {!! $errors->first('designacionsupervisor_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('cliente_id') }}
            {{ Form::text('cliente_id', $designacionsupervisorcliente->cliente_id, ['class' => 'form-control' . ($errors->has('cliente_id') ? ' is-invalid' : ''), 'placeholder' => 'Cliente Id']) }}
            {!! $errors->first('cliente_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>