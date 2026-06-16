<div class="row mb-2">
    <div class="col-12 col-md-9 mb-2">
        <strong>LISTADO ADELANTOS - Contrato ID:
            {{ $contratoActivo ? cerosIzq($contratoActivo->id) : 'Sin definir' }}</strong>
    </div>
    <div class="col-12 col-md-3 mb-2">
        @if ($contratoActivo)
            <button class="btn btn-info btn-sm btn-block" data-toggle="modal" data-target="#modalAdelantos">
                Nuevo <i class="fas fa-plus"></i>
            </button>
        @endif

    </div>
</div>
<div class="row mb-2">
    <div class="col-12 col-md-3">

        <small class="text-muted">Mes:</small>
        <select class="form-control form-control-sm" id="filtroMesAdelantos">
        </select>
    </div>
    <div class="col-12 col-md-3">
        <small class="text-muted">Gestión:</small>
        <select class="form-control form-control-sm" id="filtroAnioAdelantos">
        </select>
    </div>
    <div class="col-12 col-md-3">
        <small class="text-white">Filtrar</small>
        <button class="btn btn-sm btn-primary btn-block" id="btnFiltrar"
            onclick="cargarTablaAdelantos();">Filtrar <i class="fas fa-filter"></i></button>
    </div>

</div>
<hr>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-sm" id="tabla-adelantos" style="width: 100%">
        <thead class="table-info">
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Monto Bs. &nbsp;</th>
                <th>Estado</th>
                <th style="width: 100px"></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<!-- Modal -->
<div class="modal fade" id="modalAdelantos" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modalAdelantosLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdelantosLabel">Formulario de Adelantos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="limpiar1()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="spinner-border text-primary d-none" id="spinner1" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="row" id="data1">
                    <input type="hidden" id="rrhhadelanto_id">
                    <div class="col-12 col-md-6">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Fecha Solicitud</span>
                            </div>
                            <input type="date" class="form-control" id="fecha" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Monto</span>
                            </div>
                            <input type="number" step="any" class="form-control" id="monto" placeholder="0.00">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Motivo</span>
                            </div>
                            <input type="text" id="motivo_" class="form-control"
                                placeholder="Detalle corto del motivo">
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mb-3" id="divDocadjunto">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-file-alt"></i>
                                        &nbsp;Adjuntar</span>
                                </div>
                                <div class="custom-file custom-file-sm">
                                    <input type="file" class="custom-file-input" id="documento_adjunto1">
                                    <label class="custom-file-label" id="label1">
                                        Seleccione un archivo
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3 d-none" id="divEstado">
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Estado</span>
                            </div>
                            <select id="estado1" class="form-control">
                                @foreach ($optionsAdelantos as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="limpiar1()"><i
                        class="fas fa-ban"></i>
                    Cancelar</button>
                <button type="button" id="btnEdit1" class="btn btn-warning d-none"
                    onclick="updateAdelanto()">Actualizar Adelanto
                    <i class="fas fa-save"></i></button>
                <button type="button" id="btnRegist1" class="btn btn-info" onclick="registrarAdelanto()">Registrar
                    Adelanto <i class="fas fa-save"></i></button>
            </div>
        </div>
    </div>
</div>
@section('js4')
    <script>
        $('.nav-adelantos').click(function() {
            const meses = {
                1: 'Enero',
                2: 'Febrero',
                3: 'Marzo',
                4: 'Abril',
                5: 'Mayo',
                6: 'Junio',
                7: 'Julio',
                8: 'Agosto',
                9: 'Septiembre',
                10: 'Octubre',
                11: 'Noviembre',
                12: 'Diciembre'
            };

            const selectMes = document.getElementById('filtroMesAdelantos');

            // 👇 obtener mes actual (1–12)
            const mesActual = new Date().getMonth() + 1;

            selectMes.innerHTML = '<option value="">Todos</option>';

            for (const num in meses) {
                const option = document.createElement('option');
                option.value = num;
                option.textContent = meses[num];

                // 👇 marcar automáticamente el mes actual
                if (parseInt(num) === mesActual) {
                    option.selected = true;
                }

                selectMes.appendChild(option);
            }

            const url = "{{ route('adelantos.data', $contratoActivo ? $contratoActivo->id : 0) }}";

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {

                    const selectAnio = document.getElementById('filtroAnioAdelantos');
                    const selectMes = document.getElementById('filtroMesAdelantos');

                    const anioActual = new Date().getFullYear();

                    selectAnio.innerHTML = '';

                    const data = response.data || response;

                    const anios = [...new Set(data.map(item => {
                        if (!item.fecha) return null;
                        return parseInt(item.fecha.substring(0, 4));
                    }).filter(a => a !== null))];

                    anios.sort((a, b) => b - a);

                    anios.forEach(anio => {
                        const option = document.createElement('option');
                        option.value = anio;
                        option.textContent = anio;

                        if (anio === anioActual) {
                            option.selected = true;
                        }

                        selectAnio.appendChild(option);
                    });

                    // 👇 AQUÍ ya tienes todo listo
                    const mes = selectMes.value;
                    const anio = selectAnio.value;

                    cargarTablaAdelantos(); // ✅ ahora sí con datos reales
                },
                error: function(error) {
                    console.error('ERROR AJAX:', error);
                }
            });
        });
    </script>

    <script>
        function limpiar1() {
            document.getElementById('rrhhadelanto_id').value = '';
            document.getElementById('fecha').value = '';
            document.getElementById('monto').value = '';
            document.getElementById('motivo_').value = '';
            document.getElementById('documento_adjunto1').value = '';
            document.getElementById('estado1').value = '';
            const input1 = document.getElementById('documento_adjunto1');
            const btnEdit1 = document.getElementById('btnEdit1');
            const btnRegist1 = document.getElementById('btnRegist1');
            const docad = document.getElementById('divDocadjunto');
            const estado = document.getElementById('divEstado');
            const label1 = document.getElementById('label1');

            btnEdit1.classList.add('d-none');
            // docad.classList.add('d-none');
            estado.classList.add('d-none');
            btnRegist1.classList.remove('d-none');

            // Limpia el archivo
            input1.value = '';

            // Resetea el label de Bootstrap
            const label = input1.nextElementSibling;
            label1.innerHTML = 'Seleccione un archivo';

        }

        function editar1(rrhhadelanto_id) {
            const body = document.getElementById('data1');
            const spinner = document.getElementById('spinner1');
            const btnEdit = document.getElementById('btnEdit1');
            const btnRegist = document.getElementById('btnRegist1');
            const docad = document.getElementById('divDocadjunto');
            const estado = document.getElementById('divEstado');

            body.classList.add('d-none');
            spinner.classList.remove('d-none');
            btnEdit.classList.remove('d-none');
            // docad.classList.remove('d-none');
            estado.classList.remove('d-none');
            btnRegist.classList.add('d-none');

            const formData = new FormData();
            formData.append('rrhhadelanto_id', rrhhadelanto_id);

            fetch('{{ route('adelantos.edit') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {

                    document.getElementById('rrhhadelanto_id').value = data.message.id;

                    document.getElementById('fecha').value = data.message.fecha;
                    document.getElementById('monto').value = data.message.monto;
                    document.getElementById('motivo_').value = data.message.motivo;
                    document.getElementById('estado1').value = data.message.estado;

                    // activo.bootstrapSwitch('state', data.message.activo ? true : false);

                    spinner.classList.add('d-none');
                    body.classList.remove('d-none');

                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al enviar la solicitud');
                });
        }
    </script>

    <script>
        let tablaAdelantos;

        function cargarTablaAdelantos() {

            if (tablaAdelantos) {
                tablaAdelantos.ajax.reload(null, false);
                return;
            }

            tablaAdelantos = $('#tabla-adelantos').DataTable({
                ajax: {
                    url: '{{ route('adelantos.data', $contratoActivo ? $contratoActivo->id : 0) }}',
                    data: function(d) {
                        d.mes = $('#filtroMesAdelantos').val();
                        d.anio = $('#filtroAnioAdelantos').val();
                    }
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'fecha'
                    },
                    {
                        data: 'monto'
                    },
                    {
                        data: 'estado'
                    },
                    {
                        data: 'boton',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                        targets: [0, 1, 3, 4],
                        className: 'text-center'
                    },
                    {
                        targets: [2],
                        className: 'text-right'
                    }
                ],
                responsive: true,
                order: [
                    [3, 'desc']
                ],
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "Todos"]
                ],
                language: {
                    url: '{{ asset('plugins/es-ES.json') }}'
                }
            });

            setTimeout(() => {
                tablaAdelantos.columns.adjust().draw();
            }, 300);
        }

        // document.addEventListener('DOMContentLoaded', cargarTablaAdelantos);
    </script>
    <script>
        function updateAdelanto() {
            const input = document.getElementById('documento_adjunto1');
            const formDataU = new FormData();
            const file = input.files[0];


            // Añadir campos
            formDataU.append('rrhhadelanto_id', $('#rrhhadelanto_id').val());
            formDataU.append('fecha', $('#fecha').val());
            formDataU.append('monto', $('#monto').val());
            formDataU.append('motivo', $('#motivo_').val());
            formDataU.append('estado', $('#estado1').val());
            formDataU.append('documento_adjunto', file);

            fetch('{{ route('adelantos.update') }}', {
                    method: 'POST',
                    body: formDataU,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {


                    cargarTablaAdelantos();
                    $('#modalAdelantos').modal('hide')
                    limpiar1();
                    actualizarContadorNotificaciones();
                    if (data.success) {
                        Swal.fire({
                            title: 'Excelente',
                            text: 'Adelanto actualizado correctamente.',
                            icon: 'success'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error.',
                            icon: 'error'
                        });
                    }

                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al enviar el adelanto');
                });
        }

        function registrarAdelanto() {
            const input = document.getElementById('documento_adjunto1');
            const formData = new FormData();
            const file = input.files[0];

            // Añadir campos

            formData.append('fecha', $('#fecha').val());
            formData.append('monto', $('#monto').val());
            formData.append('motivo', $('#motivo_').val());
            formData.append('rrhhcontrato_id', {{ $contratoActivo?->id }});
            formData.append('empleado_id', {{ $contratoActivo?->empleado->id }});
            formData.append('documento_adjunto', file);

            // if (!formData.get('motivo') || formData.get('motivo').length < 1) {
            //     alert('El motivo debe tener al menos 1 caracteres');
            //     return;
            // }

            fetch('{{ route('adelantos.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    cargarTablaAdelantos();
                    $('#modalAdelantos').modal('hide')
                    limpiar1();
                    actualizarContadorNotificaciones();
                    if (data.success) {
                        Swal.fire({
                            title: 'Excelente',
                            text: 'Adelanto registrado correctamente.',
                            icon: 'success'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error.',
                            icon: 'error'
                        });
                    }

                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al enviar el adelanto');
                });
        }
    </script>
@endsection
