<div class="box box-info padding-1">
    <div class="box-body mb-3">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('empleado') }}
                    <span class="form-control">{{ $designacione->empleado->nombres . ' ' .
                        $designacione->empleado->apellidos }}</span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('Turno') }}
                    <span class="form-control">{{ $designacione->turno->nombre }}</span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('fechaInicio') }}
                    {{ Form::date('fechaInicio', $designacione->fechaInicio, ['class' => 'form-control' .
                    ($errors->has('fechaInicio') ? ' is-invalid' : ''), 'placeholder' => 'Fechainicio']) }}
                    {!! $errors->first('fechaInicio', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('fechaFin') }}
                    {{ Form::date('fechaFin', $designacione->fechaFin, ['class' => 'form-control' .
                    ($errors->has('fechaFin') ? ' is-invalid' : ''), 'placeholder' => 'Fechafin']) }}
                    {!! $errors->first('fechaFin', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label>Intervalo Hrs:</label> <small>Alerta Hombre Vivo</small>
                    {{ Form::number('intervalo_hv', $designacione->intervalo_hv, ['class' => 'form-control' .
                    ($errors->has('intervalo_hv') ? ' is-invalid' : ''), 'placeholder' => '']) }}
                    {!! $errors->first('intervalo_hv', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label>Observaciones:</label>
                    {{ Form::text('observaciones', $designacione->observaciones, ['class' => 'form-control' .
                    ($errors->has('observaciones') ? ' is-invalid' : ''), 'placeholder' => 'Observaciones']) }}
                    {!! $errors->first('observaciones', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12">
                <label>Días laborales:</label>
                {{-- @dump($designaciondia) --}}
                <div class="row">
                    <div class="col-6 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="domingo" name="domingo"
                                @if($designaciondia->domingo)
                            checked
                            @endif>
                            <label class="form-check-label" for="domingo">Domingo</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="lunes" name="lunes"
                                @if($designaciondia->lunes)
                            checked
                            @endif>
                            <label class="form-check-label" for="lunes">Lunes</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="martes" name="martes"
                                @if($designaciondia->martes)
                            checked
                            @endif>
                            <label class="form-check-label" for="martes">Martes</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="miercoles" name="miercoles"
                                @if($designaciondia->miercoles)
                            checked
                            @endif>
                            <label class="form-check-label" for="miercoles">Miércoles</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="jueves" name="jueves"
                                @if($designaciondia->jueves)
                            checked
                            @endif>
                            <label class="form-check-label" for="jueves">Jueves</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="viernes" name="viernes"
                                @if($designaciondia->viernes)
                            checked
                            @endif>
                            <label class="form-check-label" for="viernes">Viernes</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sabado" name="sabado"
                                @if($designaciondia->sabado)
                            checked
                            @endif>
                            <label class="form-check-label" for="sabado">Sábado</label>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar cambios</button>
    </div>
</div>