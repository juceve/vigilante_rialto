<div class="box box-info padding-1">
    <div class="box-body">
        <style>
            #color-selector {
                display: flex;
                flex-direction: column;
                align-items: start;
                gap: 10px;
            }

            canvas {
                cursor: crosshair;
                border-radius: 5px;
                border: 1px solid #ccc;
            }

            #preview {
                width: 50px;
                height: 50px;
                border-radius: 5px;
                border: 2px solid #333;
            }
        </style>
        <div class="form-group">
            {{ Form::label('nombre') }}
            {{ Form::text('nombre', $rrhhtipopermiso->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre']) }}
            {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('nombre_corto') }}
            {{ Form::text('nombre_corto', $rrhhtipopermiso->nombre_corto, ['class' => 'form-control' . ($errors->has('nombre_corto') ? ' is-invalid' : ''), 'placeholder' => 'Nombre Corto']) }}
            {!! $errors->first('nombre_corto', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('factor') }}
            {{ Form::number('factor', $rrhhtipopermiso->factor, ['class' => 'form-control' . ($errors->has('factor') ? ' is-invalid' : ''), 'placeholder' => '0.00', 'step' => 'any']) }}
            {!! $errors->first('factor', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('color') }}
            {{ Form::hidden('color', $rrhhtipopermiso->color, ['class' => 'form-control' . ($errors->has('color') ? ' is-invalid' : ''), 'placeholder' => 'Color', 'id' => 'colorInput']) }}
            <div id="color-selector">
                <canvas id="palette" width="250" height="150"></canvas>
                <div>
                    <span>Color seleccionado:</span>
                    <div id="preview"></div>
                </div>

            </div>
            {!! $errors->first('color', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary btn-block col-12 col-lg-4">Guardar <i class="fas fa-save"></i></button>
    </div>

    <script>
        const canvas = document.getElementById('palette');
        const ctx = canvas.getContext('2d');
        const preview = document.getElementById('preview');
        const input = document.getElementById('colorInput');
        preview.style.backgroundColor = "{{ $rrhhtipopermiso->color ? $rrhhtipopermiso->color : '#FFF' }}";

        function drawPalette() {
            const gradient = ctx.createLinearGradient(0, 0, canvas.width, 0);
            gradient.addColorStop(0, "red");
            gradient.addColorStop(0.17, "orange");
            gradient.addColorStop(0.34, "yellow");
            gradient.addColorStop(0.51, "green");
            gradient.addColorStop(0.68, "cyan");
            gradient.addColorStop(0.85, "blue");
            gradient.addColorStop(1, "magenta");
            ctx.fillStyle = gradient;
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            const whiteGradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
            whiteGradient.addColorStop(0, "rgba(255,255,255,1)");
            whiteGradient.addColorStop(0.5, "rgba(255,255,255,0)");
            whiteGradient.addColorStop(0.5, "rgba(0,0,0,0)");
            whiteGradient.addColorStop(1, "rgba(0,0,0,1)");
            ctx.fillStyle = whiteGradient;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
        }

        canvas.addEventListener('click', function(e) {
            const x = e.offsetX;
            const y = e.offsetY;
            const pixel = ctx.getImageData(x, y, 1, 1).data;
            const hex = rgbToHex(pixel[0], pixel[1], pixel[2]);
            preview.style.backgroundColor = hex;
            input.value = hex;
        });

        function rgbToHex(r, g, b) {
            return "#" + [r, g, b].map(x =>
                x.toString(16).padStart(2, '0')
            ).join('');
        }

        drawPalette();
    </script>
</div>
