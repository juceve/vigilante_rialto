<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            <div class="col-12 col-md-6 mb-3 form-group">
                {{ Form::label('fecha') }}
                {{ Form::date('fecha', $tarea->fecha, ['class' => 'form-control' . ($errors->has('fecha') ? '
                is-invalid' :
                ''), 'placeholder' => 'Fecha']) }}
                {!! $errors->first('fecha', '<div class="invalid-feedback">:message</div>') !!}
            </div>
            <div class="col-12">
                @livewire('selectcliente')
            </div>
            {{-- <div class="col-12 col-md-6 mb-3 form-group">
                {{ Form::label('cliente_id') }}
                {{ Form::text('cliente_id', $tarea->cliente_id, ['class' => 'form-control' . ($errors->has('cliente_id')
                ? '
                is-invalid' : ''), 'placeholder' => 'Cliente Id']) }}
                {!! $errors->first('cliente_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
            <div class="col-12 col-md-6 mb-3 form-group">
                {{ Form::label('empleado_id') }}
                {{ Form::text('empleado_id', $tarea->empleado_id, ['class' => 'form-control' .
                ($errors->has('empleado_id')
                ? ' is-invalid' : ''), 'placeholder' => 'Empleado Id']) }}
                {!! $errors->first('empleado_id', '<div class="invalid-feedback">:message</div>') !!}
            </div> --}}
            <div class="col-12 form-group">
                {{ Form::label('contenido') }}
                {{ Form::textarea('contenido', $tarea->contenido, ['class' => 'form-control' .
                ($errors->has('contenido') ?
                '
                is-invalid' : ''), 'placeholder' => 'Contenido', 'rows' => '2']) }}
                {!! $errors->first('contenido', '<div class="invalid-feedback">:message</div>') !!}
            </div>
            <div class="col-12 col-md-6 mb-3 d-none">
                {{ Form::label('estado') }}
                {{ Form::text('estado', $tarea->estado, ['class' => 'form-control' . ($errors->has('estado') ? '
                is-invalid'
                : ''), 'placeholder' => 'Estado']) }}
                {!! $errors->first('estado', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>


    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">REGISTRAR TAREA <i class="fas fa-save"></i></button>
    </div>
</div>