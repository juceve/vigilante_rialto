<div class="box box-info padding-1">
    <div class="box-body">

        {{-- <div class="form-group">
            {{ Form::label('codigo') }}
            {{ Form::text('codigo', $rrhhtipocontrato->codigo, ['class' => 'form-control' . ($errors->has('codigo') ? ' is-invalid' : ''), 'placeholder' => 'Codigo']) }}
            {!! $errors->first('codigo', '<div class="invalid-feedback">:message</div>') !!}
        </div> --}}
        <div class="form-group">
            {{ Form::label('nombre') }}
            {{ Form::text('nombre', $rrhhtipocontrato->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre']) }}
            {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('descripcion') }}
            {{ Form::text('descripcion', $rrhhtipocontrato->descripcion, ['class' => 'form-control' . ($errors->has('descripcion') ? ' is-invalid' : ''), 'placeholder' => 'Descripcion']) }}
            {!! $errors->first('descripcion', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group form-check">
            <input type="checkbox" name="mensualizado" id="mensualizadoCheckbox" class="form-check-input" value="1"
                {{ isset($rrhhtipocontrato->mensualizado) ? ($rrhhtipocontrato->mensualizado ? 'checked' : '') : 'checked' }}>
            <label class="form-check-label" for="mensualizadoCheckbox">mensualizado</label>
        </div>
        <div class="form-group" id="cantidad_dias_div">
            <label for="cantidad_dias">Cantidad días al Mes <small>(Cantidad maxima 30)</small></label>
            {{ Form::number('cantidad_dias', $rrhhtipocontrato->cantidad_dias, ['class' => 'form-control' . ($errors->has('cantidad_dias') ? ' is-invalid' : ''), 'placeholder' => '0', 'id' => 'cantidad_dias', 'min' => '1', 'max' => '30']) }}
            {!! $errors->first('cantidad_dias', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        {{-- <div class="form-group">
            {{ Form::label('horas_dia') }}
            {{ Form::number('horas_dia', $rrhhtipocontrato->horas_dia, ['class' => 'form-control' . ($errors->has('horas_dia') ? ' is-invalid' : ''), 'placeholder' => '0']) }}
            {!! $errors->first('horas_dia', '<div class="invalid-feedback">:message</div>') !!}
        </div> --}}
        <div class="form-group">
            {{ Form::label('sueldo_referencial') }}
            {{ Form::number('sueldo_referencial', $rrhhtipocontrato->sueldo_referencial, ['class' => 'form-control' . ($errors->has('sueldo_referencial') ? ' is-invalid' : ''), 'placeholder' => '0.00', 'step' => 'any']) }}
            {!! $errors->first('sueldo_referencial', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('activo') }}
            {!! Form::select('activo', ['1' => 'SI', '0' => 'NO'], $rrhhtipocontrato->activo, [
                'class' => 'form-control' . ($errors->has('sueldo_referencial') ? ' is-invalid' : ''),
            ]) !!}
            {!! $errors->first('activo', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary btn-block col-12 col-md-4">Guardar <i
                class="fas fa-save"></i></button>
    </div>


</div>
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('mensualizadoCheckbox');
            const cantidadDiasDiv = document.getElementById('cantidad_dias_div');
            const cantidadDiasInput = document.getElementById('cantidad_dias');

            function toggleCantidadDias() {
                if (checkbox.checked) {
                    cantidadDiasDiv.style.display = 'none';
                    cantidadDiasInput.value = 30;
                } else {
                    cantidadDiasDiv.style.display = 'block';
                    cantidadDiasInput.value = '';
                }
            }

            // Inicializar al cargar la página
            toggleCantidadDias();

            // Escuchar cambios en el checkbox
            checkbox.addEventListener('change', toggleCantidadDias);
        });
    </script>
    <script>
        document.getElementById('cantidad_dias').addEventListener('input', function() {
            if (this.value > 30) {
                this.value = 30; // fuerza el máximo
            }
            if (this.value <=0) {
                this.value = 1; // fuerza el máximo
            }
        });
    </script>
@endsection
