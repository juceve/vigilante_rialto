<div class="row mb-2">
    <div class="col-12 col-md-9 mb-2">
        <strong>LISTADO PERMISOS - Contrato ID:
            {{ $contratoActivo ? cerosIzq($contratoActivo->id) : 'Sin definir' }}</strong>
    </div>
    <div class="col-12 col-md-3 mb-2">
        @if ($contratoActivo)
            <button class="btn btn-info btn-sm btn-block" data-toggle="modal" data-target="#modalPermisos">
                Nuevo <i class="fas fa-plus"></i>
            </button>
        @endif

    </div>
</div>
<div class="row mb-2">
    <div class="col-12 col-md-3">

        <small class="text-muted">Mes:</small>
        <select class="form-control form-control-sm" id="filtroMesPermisos">
        </select>
    </div>
    <div class="col-12 col-md-3">
        <small class="text-muted">Gestión:</small>
        <select class="form-control form-control-sm" id="filtroAnioPermisos">
        </select>
    </div>
    <div class="col-12 col-md-3">
        <small class="text-white">Filtrar</small>
        <button class="btn btn-sm btn-primary btn-block" id="btnFiltrar3" onclick="cargarTablaPermisos();">Filtrar <i
                class="fas fa-filter"></i></button>
    </div>

</div>
<hr>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-sm" id="tabla-permisos" style="width: 100%">
        <thead class="table-info">
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
<!-- Modal -->
<div class="modal fade" id="modalPermisos" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modalPermisosLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPermisosLabel">Formulario de Licencias y Permiso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="limpiar()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="spinner-border text-primary d-none" id="spinner" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="row" id="data">
                    <input type="hidden" id='rrhhpermiso_id'>
                    <div class="col-12 col-lg-4">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Tipo</span>
                            </div>
                            <select name="" id="rrhhtipopermiso_id" class="form-control">
                                <option value="">Seleccione un tipo</option>
                                @foreach ($tipopermisos as $tipopermiso)
                                    <option value="{{ $tipopermiso->id }}">{{ $tipopermiso->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Inicio</span>
                            </div>
                            <input type="date" class="form-control" id="fecha_inicio">
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Fin</span>
                            </div>
                            <input type="date" class="form-control" id="fecha_fin">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Motivo</span>
                            </div>
                            <input type="text" class="form-control" id="motivo">
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="far fa-file-alt"></i>
                                        &nbsp;Adjuntar</span>
                                </div>
                                <div class="custom-file custom-file-sm">
                                    <input type="file" class="custom-file-input" id="documento_adjunto">
                                    <label class="custom-file-label" id="labelInput">
                                        Seleccione un archivo
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="statusPermiso">Estado</label>
                            </div>
                            <select class="custom-select" id="statusPermiso">
                                <option value="SOLICITADO">Solicitado</option>
                                <option value="APROBADO">Aprobado</option>
                                <option value="RECHAZADO">Rechazado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 d-none" id="swActivo">
                        <label><small>Activo</small></label>
                        <input type="checkbox" id="activo" name="my-checkbox" checked data-bootstrap-switch>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="limpiar()"><i
                        class="fas fa-ban"></i>
                    Cerrar</button>
                <button type="button" class="btn btn-warning d-none" id="btnEdit"
                    onclick="updatePermiso()">Actualizar
                    permiso <i class="fas fa-save"></i></button>
                <button type="button" class="btn btn-info" id="btnRegist" onclick="registrarPermiso()">Registrar
                    permiso <i class="fas fa-save"></i></button>

            </div>
        </div>
    </div>
</div>
@section('js3')
    <script src="{{ asset('vendor/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script>
        function bloquearBoton(btn) {
            btn.disabled = true;
            btn.dataset.originalText = btn.innerHTML; // Guarda el texto original
            btn.innerHTML = 'Procesando... <i class="fas fa-spinner fa-spin"></i>';
        }

        function desbloquearBoton(btn) {
            btn.disabled = false;
            if (btn.dataset.originalText) btn.innerHTML = btn.dataset.originalText;
        }
    </script>

    <script>
        $('.nav-permisos').click(function() {

            const meses3 = {
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

            const selectMes3 = document.getElementById('filtroMesPermisos');

            // 👇 obtener mes actual (1–12)
            const mesActual3 = new Date().getMonth() + 1;
            selectMes3.innerHTML = '';
            selectMes3.innerHTML = '<option value="">Todos</option>';

            for (const num in meses3) {
                const option = document.createElement('option');
                option.value = num;
                option.textContent = meses3[num];

                // 👇 marcar automáticamente el mes actual
                if (parseInt(num) === mesActual3) {
                    option.selected = true;
                }

                selectMes3.appendChild(option);
            }

            const url = "{{ route('permisos.data', $contratoActivo ? $contratoActivo->id : 0) }}";

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {

                    const selectAnio3 = document.getElementById('filtroAnioPermisos');
                    const selectMes3 = document.getElementById('filtroMesPermisos');
                    const anioActual3 = new Date().getFullYear();

                    selectAnio3.innerHTML = '';

                    const data = response.data || response;

                    const anios = [...new Set(data.map(item => {
                        if (!item.fecha_inicio) return null;
                        return parseInt(item.fecha_inicio.substring(0, 4));
                    }).filter(a => a !== null))];

                    anios.sort((a, b) => b - a);

                    anios.forEach(anio => {
                        const option = document.createElement('option');
                        option.value = anio;
                        option.textContent = anio;

                        if (anio === anioActual3) {
                            option.selected = true;
                        }

                        selectAnio3.appendChild(option);
                    });

                    // 👇 AQUÍ ya tienes todo listo
                    const mes = selectMes3.value;
                    const anio = selectAnio3.value;

                    cargarTablaPermisos(); // ✅ ahora sí con datos reales
                },
                error: function(error) {
                    console.error('ERROR AJAX:', error);
                }
            });

            cargarTablaPermisos();
        });

        $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch({
                onText: 'SI',
                offText: 'NO',
                onColor: 'success',
                offColor: 'secondary',
            });
        })
    </script>

    <script>
        let tablaPermisos;


        function limpiar() {
            document.getElementById('rrhhtipopermiso_id').value = '';
            document.getElementById('fecha_inicio').value = '';
            document.getElementById('fecha_fin').value = '';
            document.getElementById('motivo').value = '';
            document.getElementById('statusPermiso').value = 'SOLICITADO';
            const input = document.getElementById('documento_adjunto');
            const btnEdit = document.getElementById('btnEdit');
            const btnRegist = document.getElementById('btnRegist');
            const swActivo = document.getElementById('swActivo');
            btnEdit.classList.add('d-none');
            btnRegist.classList.remove('d-none');
            // swActivo.classList.add('d-none');
            // Limpia el archivo
            input.value = '';

            // Resetea el label de Bootstrap
            const label = input.nextElementSibling;
            label.innerHTML = 'Seleccione un archivo';

        }

        function editar(rrhhpermiso_id) {
            const body = document.getElementById('data');
            const spinner = document.getElementById('spinner');
            const btnEdit = document.getElementById('btnEdit');
            const btnRegist = document.getElementById('btnRegist');
            const swActivo = document.getElementById('swActivo');
            const activo = $('#activo');

            body.classList.add('d-none');
            spinner.classList.remove('d-none');
            btnEdit.classList.remove('d-none');
            // swActivo.classList.remove('d-none');
            btnRegist.classList.add('d-none');

            const formData = new FormData();
            formData.append('rrhhpermiso_id', rrhhpermiso_id);

            fetch('{{ route('permisos.edit') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {

                    document.getElementById('rrhhpermiso_id').value = data.message.id;
                    document.getElementById('rrhhtipopermiso_id').value = data.message.rrhhtipopermiso_id;
                    document.getElementById('fecha_inicio').value = data.message.fecha_inicio;
                    document.getElementById('fecha_fin').value = data.message.fecha_fin;
                    document.getElementById('motivo').value = data.message.motivo;
                    document.getElementById('statusPermiso').value = data.message.status;
                    activo.bootstrapSwitch('state', data.message.activo ? true : false);

                    spinner.classList.add('d-none');
                    body.classList.remove('d-none');

                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al enviar la solicitud');
                });
        }

        function updatePermiso() {

            const btnEdit = document.getElementById('btnEdit');
            bloquearBoton(btnEdit);
            const input = document.getElementById('documento_adjunto');
            const formDataU = new FormData();
            const file = input.files[0];
            const activo = $('#activo');

            // Añadir campos
            formDataU.append('rrhhpermiso_id', $('#rrhhpermiso_id').val());
            formDataU.append('rrhhtipopermiso_id', $('#rrhhtipopermiso_id').val());
            formDataU.append('fecha_inicio', $('#fecha_inicio').val());
            formDataU.append('fecha_fin', $('#fecha_fin').val());
            formDataU.append('motivo', $('#motivo').val());
            formDataU.append('activo', activo.bootstrapSwitch('state') ? 1 : 0);
            formDataU.append('status', $('#statusPermiso').val());
            formDataU.append('documento_adjunto', file);

            fetch('{{ route('permisos.update') }}', {
                    method: 'POST',
                    body: formDataU,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data.message);
                    cargarTablaPermisos();
                    $('#modalPermisos').modal('hide')
                    limpiar();
                    desbloquearBoton(btnEdit);

                    if (data.success) {
                        actualizarContadorNotificaciones();
                        Swal.fire({
                            title: 'Excelente',
                            text: 'Permiso actualizado correctamente.',
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
                    desbloquearBoton(btnEdit);
                    alert('Ocurrió un error al enviar el permiso');
                });
        }
    </script>
    <script>
        function registrarPermiso() {
            const btnRegist = document.getElementById('btnRegist');
            bloquearBoton(btnRegist);

            const input = document.getElementById('documento_adjunto');
            const formData = new FormData();
            const file = input.files[0];

            // Añadir campos
            formData.append('rrhhtipopermiso_id', $('#rrhhtipopermiso_id').val());
            formData.append('fecha_inicio', $('#fecha_inicio').val());
            formData.append('fecha_fin', $('#fecha_fin').val());
            formData.append('motivo', $('#motivo').val());
            formData.append('rrhhcontrato_id', {{ $contratoActivo?->id }});
            formData.append('empleado_id', {{ $contratoActivo?->empleado->id }});
            formData.append('status', $('#statusPermiso').val());
            formData.append('documento_adjunto', file);

            if (!formData.get('motivo') || formData.get('motivo').length < 1) {
                desbloquearBoton(btnRegist);
                alert('El motivo debe tener al menos 1 caracteres');
                return;
            }

            fetch('{{ route('permisos.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    cargarTablaPermisos();
                    $('#modalPermisos').modal('hide')
                    limpiar();
                    desbloquearBoton(btnRegist);

                    if (data.success) {
                        actualizarContadorNotificaciones();
                        Swal.fire({
                            title: 'Excelente',
                            text: 'Permiso registrado correctamente.',
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
                    desbloquearBoton(btnRegist);
                    alert('Ocurrió un error al enviar el permiso');
                });
        }

        // Función para actualizar contador de notificaciones






        function cargarTablaPermisos() {
            if (tablaPermisos) {
                tablaPermisos.ajax.reload(null, false); // recarga sin resetear paginación
                return;
            }


            tablaPermisos = $('#tabla-permisos').DataTable({
                ajax: {
                    url: '{{ route('permisos.data', $contratoActivo ? $contratoActivo->id : 0) }}',
                    data: function(d) {
                        d.mes = $('#filtroMesPermisos').val();
                        d.anio = $('#filtroAnioPermisos').val();
                    }
                },

                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'tipopermiso'
                    },
                    {
                        data: 'fecha_inicio'
                    },
                    {
                        data: 'fecha_fin'
                    },
                    {
                        data: 'estado'
                    },
                    {
                        data: 'boton',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [{
                    targets: [0, 2, 3, 4, 5],
                    className: 'text-center'
                }],
                responsive: true,
                order: [
                    [4, 'desc']
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
                tablaPermisos.columns.adjust().draw();
            }, 300);
        }

        // Llama esto después de agregar un permiso exitosamente
        function permisoAgregado() {
            cargarTablaPermisos();
        }

        // document.addEventListener('DOMContentLoaded', cargarTablaPermisos);
    </script>
@endsection
