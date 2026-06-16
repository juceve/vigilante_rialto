<div class="box box-info padding-1">
    <div class="box-body">

        <div class="form-group">
            {{ Form::label('nombre') }}
            {{ Form::text('nombre', $tipoboleta->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre']) }}
            {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('descripción') }}
            {{ Form::text('descripcion', $tipoboleta->descripcion, ['class' => 'form-control' . ($errors->has('descripcion') ? ' is-invalid' : ''), 'placeholder' => 'Descripción']) }}
            {!! $errors->first('descripcion', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="cb_descuento">
                <label for="cb_descuento" class="custom-control-label">Genera Descuento</label>
            </div>
        </div>

        <div class="form-group d-none" id="div_rrhhtipodescuento_id">
            {{ Form::label('rrhhtipodescuento_id', 'Tipo de Descuento') }}

            {{ Form::select('rrhhtipodescuento_id', $tipodescuentos, $tipoboleta->rrhhtipodescuento_id, ['class' => 'form-control' . ($errors->has('rrhhtipodescuento_id') ? ' is-invalid' : ''), 'placeholder' => 'Seleccione un descuento']) }}
            {!! $errors->first('rrhhtipodescuento_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group d-none" id="div_monto_descuento">
            {{ Form::label('monto_descuento') }}
            {{ Form::number('monto_descuento', $tipoboleta->monto_descuento, ['class' => 'form-control' . ($errors->has('monto_descuento') ? ' is-invalid' : ''), 'placeholder' => '0.00', 'readonly']) }}
            {!! $errors->first('monto_descuento', '<div class="invalid-feedback">:message</div>') !!}
        </div>

        <div class="form-group">
            {{ Form::label('Estado') }}
            {{ Form::select('status', ['1' => 'Activo', '0' => 'Inactivo'], $tipoboleta->status, ['class' => 'form-control' . ($errors->has('status') ? ' is-invalid' : '')]) }}
            {!! $errors->first('status', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Guardar <i class="fas fa-save"></i></button>
    </div>
</div>
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cbDescuento = document.getElementById('cb_descuento');
            
            const div_rrhhtipodescuento_id = document.getElementById('div_rrhhtipodescuento_id');
            const div_monto_descuento = document.getElementById('div_monto_descuento');

            // Inicializar el estado basado en el valor actual
            if ({{ $tipoboleta->rrhhtipodescuento_id ? 'true' : 'false' }}) {
                cbDescuento.checked = true;
                div_monto_descuento.classList.remove('d-none');
                div_rrhhtipodescuento_id.classList.remove('d-none');
            }

            cbDescuento.addEventListener('change', function() {
                if (this.checked) {
                    div_rrhhtipodescuento_id.classList.remove('d-none');
                    div_monto_descuento.classList.remove('d-none');
                } else {
                    div_rrhhtipodescuento_id.classList.add('d-none');
                    div_monto_descuento.classList.add('d-none');
                    // Limpiar los campos relacionados al descuento
                    document.getElementById('rrhhtipodescuento_id').value = '';
                    document.getElementById('monto_descuento').value = '';
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectDescuento = document.getElementById('rrhhtipodescuento_id');

            selectDescuento.addEventListener('change', function() {
                const selectedId = this.value;

                if (selectedId) {
                    fetch('{{ route('traetipodescuento') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                rrhhtipodescuento_id: selectedId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Descuento details:', data.message.monto);
                                document.getElementById('monto_descuento').value = data.message.monto;
                            } else {
                                console.error('Error fetching descuento details');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            });
        });
    </script>
@endsection
