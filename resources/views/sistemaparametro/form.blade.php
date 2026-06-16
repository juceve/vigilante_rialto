<div class="box box-info padding-1">
    <div class="box-body">

        <div class="form-group">
            {{ Form::label('tolerancia_ingreso') }}
            {{ Form::number('tolerancia_ingreso', $sistemaparametro->tolerancia_ingreso, ['class' => 'form-control' . ($errors->has('tolerancia_ingreso') ? ' is-invalid' : ''), 'placeholder' => 'Tolerancia Ingreso']) }}
            {!! $errors->first('tolerancia_ingreso', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('telefono_panico') }}
            {{ Form::text('telefono_panico', $sistemaparametro->telefono_panico, ['class' => 'form-control' . ($errors->has('telefono_panico') ? ' is-invalid' : ''), 'placeholder' => 'Nro. Telefono con Whatsapp']) }}
            {!! $errors->first('telefono_panico', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Descuento por No Marcar Salida') }}
            {{ Form::text('asistencia_sin_salida', $sistemaparametro->asistencia_sin_salida, ['class' => 'form-control' . ($errors->has('asistencia_sin_salida') ? ' is-invalid' : ''), 'placeholder' => 'Descuento por No Marcar Salida']) }}
            {!! $errors->first('asistencia_sin_salida', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Descuento por Inasistencia') }}
            {{ Form::text('falta_dia_completo', $sistemaparametro->falta_dia_completo, ['class' => 'form-control' . ($errors->has('falta_dia_completo') ? ' is-invalid' : ''), 'placeholder' => 'Descuento por Inasistencia']) }}
            {!! $errors->first('falta_dia_completo', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Guardar <i class="fas fa-save"></i></button>
    </div>
</div>
