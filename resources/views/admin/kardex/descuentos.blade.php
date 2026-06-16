<div class="row mb-2">
    <div class="col-12 col-md-9 mb-2">
        <strong>LISTADO DESCUENTOS - Contrato ID:
            {{ $contratoActivo ? cerosIzq($contratoActivo->id) : 'Sin definir' }}</strong>
    </div>
    <div class="col-12 col-md-3 mb-2">
        @if ($contratoActivo)
            <button class="btn btn-info btn-sm btn-block" data-toggle="modal" data-target="#modalDescuento">
                Nuevo <i class="fas fa-plus"></i>
            </button>
        @endif

    </div>
</div>
<div class="row mb-2">
    <div class="col-12 col-md-3">

        <small class="text-muted">Mes:</small>
        <select class="form-control form-control-sm" id="filtroMesDescuentos">
        </select>
    </div>
    <div class="col-12 col-md-3">
        <small class="text-muted">Gestión:</small>
        <select class="form-control form-control-sm" id="filtroAnioDescuentos">
        </select>
    </div>
    <div class="col-12 col-md-3">
        <small class="text-white">Filtrar</small>
        <button class="btn btn-sm btn-primary btn-block" id="btnFiltrar" onclick="cargarTablaDescuentos();">Filtrar <i
                class="fas fa-filter"></i></button>
    </div>

</div>
<hr>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-sm" id="tabla-descuentos" style="width: 100%">
        <thead class="table-info">
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Tipo Desc.</th>
                <th>Total Bs. &nbsp;</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

</div>
<!-- Modal -->
<div class="modal fade" id="modalDescuento" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modalDescuentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDescuentoLabel">Formulario de Descuentos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="limpiarDesc()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="spinner-border text-primary d-none" id="spinnerDesc" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div id="dataDesc">
                    <div class="row">
                        <input type="hidden" id="rrhhdescuento_id">
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Fecha Solicitud</span>
                                </div>
                                <input type="date" class="form-control" id="fechaDesc" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Tipo Descuento</span>
                                </div>
                                <select class="form-control" id="rrhhtipodescuento_id">
                                    <option value="">Seleccione un tipo</option>
                                    @foreach ($tipodescuentos as $tipodescuento)
                                        <option value="{{ $tipodescuento->id }}">{{ $tipodescuento->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Cantidad</span>
                                </div>
                                <input type="number" id="cantidadDesc" class="form-control" value="1"
                                    placeholder="1">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Monto</span>
                                </div>
                                <input type="number" step="any" class="form-control" id="montoDesc"
                                    placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 d-none" id="divEstadoDesc">
                            <label><small>Activo</small></label>
                            <input type="checkbox" id="estadoDesc" name="my-checkboxDesc" checked data-bootstrap-switch>
                        </div>

                    </div>
                    <hr>
                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="limpiarDesc()"><i
                                class="fas fa-ban"></i>
                            Cancelar</button>
                        <button type="button" id="btnEditDesc" class="btn btn-warning d-none"
                            onclick="updateDescuento()">Actualizar
                            Descuento
                            <i class="fas fa-save"></i></button>
                        <button type="button" id="btnRegistDesc" class="btn btn-info"
                            onclick="registrarDescuento()">Registrar
                            Descuento <i class="fas fa-save"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js8')
    <script>
        $('.nav-descuentos').click(function() {
            const meses2 = {
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

            const selectMes2 = document.getElementById('filtroMesDescuentos');

            // 👇 obtener mes actual (1–12)
            const mesActual2 = new Date().getMonth() + 1;

            selectMes2.innerHTML = '';
            selectMes2.innerHTML = '<option value="">Todos</option>';

            for (const num in meses2) {
                const option = document.createElement('option');
                option.value = num;
                option.textContent = meses2[num];

                // 👇 marcar automáticamente el mes actual
                if (parseInt(num) === mesActual2) {
                    option.selected = true;
                }

                selectMes2.appendChild(option);
            }

            const url = "{{ route('descuentos.data', $contratoActivo ? $contratoActivo->id : 0) }}";

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {

                    const selectAnio2 = document.getElementById('filtroAnioDescuentos');
                    const selectMes2 = document.getElementById('filtroMesDescuentos');

                    const anioActual2 = new Date().getFullYear();

                    selectAnio2.innerHTML = '';

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

                        if (anio === anioActual2) {
                            option.selected = true;
                        }

                        selectAnio2.appendChild(option);
                    });

                    // 👇 AQUÍ ya tienes todo listo
                    const mes = selectMes2.value;
                    const anio = selectAnio2.value;

                    cargarTablaDescuentos(); // ✅ ahora sí con datos reales
                },
                error: function(error) {
                    console.error('ERROR AJAX:', error);
                }
            });
            cargarTablaDescuentos();
        });
    </script>

    <script>
        const tiposDescuentos = @json($tipodescuentos);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('rrhhtipodescuento_id');
            const inputMonto = document.getElementById('monto2');

            select.addEventListener('change', function() {
                const selectedId = parseInt(this.value);
                const descuentoSeleccionado = tiposDescuentos.find(descuento => descuento.id ===
                    selectedId);

                if (descuentoSeleccionado) {
                    inputMonto.value = descuentoSeleccionado.monto;
                } else {
                    inputMonto.value = '';
                }
            });
        });
    </script>

    <script>
        function limpiarDesc() {
            const hoy = new Date();

            // Formatea la fecha como YYYY-MM-DD
            const fechaFormateada = hoy.toISOString().split('T')[0];
            // document.getElementById('rrhhadelanto_id').value = '';
            document.getElementById('fechaDesc').value = fechaFormateada;
            document.getElementById('rrhhtipodescuento_id').value = '';
            document.getElementById('montoDesc').value = '';
            document.getElementById('cantidadDesc').value = '1';
            document.getElementById('divEstadoDesc').classList.add('d-none');

            const btnEditDesc = document.getElementById('btnEditDesc');
            const btnRegistDesc = document.getElementById('btnRegistDesc');


            btnEditDesc.classList.add('d-none');
            estadoDesc.classList.add('d-none');
            btnRegistDesc.classList.remove('d-none');
        }

        function editarDesc(rrhhdescuento_id) {
            const bodyDesc = document.getElementById('dataDesc');
            const spinnerDesc = document.getElementById('spinnerDesc');
            const btnEditDesc = document.getElementById('btnEditDesc');
            const btnRegistDesc = document.getElementById('btnRegistDesc');
            const divEstado = document.getElementById('divEstadoDesc');

            bodyDesc.classList.add('d-none');
            spinnerDesc.classList.remove('d-none');
            btnEditDesc.classList.remove('d-none');
            // docad.classList.remove('d-none');
            divEstado.classList.remove('d-none');
            btnRegistDesc.classList.add('d-none');

            const formData = new FormData();
            formData.append('rrhhdescuento_id', rrhhdescuento_id);

            fetch('{{ route('descuentos.edit') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {

                    document.getElementById('rrhhdescuento_id').value = data.message.id;

                    document.getElementById('fechaDesc').value = data.message.fecha;
                    document.getElementById('montoDesc').value = data.message.monto;
                    document.getElementById('cantidadDesc').value = data.message.cantidad;
                    const activo = $('#estadoDesc');
                    document.getElementById('rrhhtipodescuento_id').value = data.message.rrhhtipodescuento_id;

                    activo.bootstrapSwitch('state', data.message.estado ? true : false);

                    spinnerDesc.classList.add('d-none');
                    bodyDesc.classList.remove('d-none');

                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al enviar la solicitud');
                });
        }
    </script>

    <script>
        let tablaDescuentos;

        // function cargarTablaDescuentos() {
        //     if (tablaDescuentos) {
        //         tablaDescuentos.ajax.reload(null, false); // recarga sin resetear paginación
        //         return;
        //     }


        //     tablaDescuentos = $('#tabla-descuentos').DataTable({
        //         ajax: '{{ route('descuentos.data', $contratoActivo ? $contratoActivo->id : 0) }}',

        //         columns: [{
        //                 data: 'id'
        //             },
        //             {
        //                 data: 'fecha'
        //             },
        //             {
        //                 data: 'rrhhtipodescuento'
        //             },
        //             {
        //                 data: 'subtotal'
        //             },
        //             {
        //                 data: 'estado'
        //             },
        //             {
        //                 data: 'boton',
        //                 orderable: false,
        //                 searchable: false
        //             },
        //         ],
        //         columnDefs: [{
        //                 targets: [0, 1, 2, 4, 5],
        //                 className: 'text-center'
        //             },
        //             {
        //                 targets: [3],
        //                 className: 'text-right'
        //             },
        //         ],
        //         responsive: true,
        //         order: [
        //             [0, 'desc']
        //         ],
        //         pageLength: 5,
        //         lengthMenu: [
        //             [5, 10, 25, 50, -1],
        //             [5, 10, 25, 50, "Todos"]
        //         ],
        //         language: {
        //             url: '{{ asset('plugins/es-ES.json') }}'
        //         }
        //     });

        //     setTimeout(() => {
        //         tablaDescuentos.columns.adjust().draw();
        //     }, 300);
        // }
        function cargarTablaDescuentos() {

            if (tablaDescuentos) {
                tablaDescuentos.ajax.reload(null, false);
                return;
            }

            tablaDescuentos = $('#tabla-descuentos').DataTable({
                ajax: {
                    url: '{{ route('descuentos.data', $contratoActivo ? $contratoActivo->id : 0) }}',
                    data: function(d) {
                        d.mes = $('#filtroMesDescuentos').val();
                        d.anio = $('#filtroAnioDescuentos').val();
                    }
                },

                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'fecha'
                    },
                    {
                        data: 'rrhhtipodescuento'
                    },
                    {
                        data: 'subtotal'
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
                        targets: [0, 1, 2, 4, 5],
                        className: 'text-center'
                    },
                    {
                        targets: [3],
                        className: 'text-right'
                    },
                ],

                responsive: true,
                order: [
                    [0, 'desc']
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
                tablaDescuentos.columns.adjust().draw();
            }, 300);
        }
        // document.addEventListener('DOMContentLoaded', cargarTablaAdelantos);
    </script>
    <script>
        function updateDescuento() {
            const formDataU = new FormData();
            const activo = $('#estadoDesc');
            // Añadir campos
            formDataU.append('rrhhdescuento_id', $('#rrhhdescuento_id').val());
            formDataU.append('fecha', $('#fechaDesc').val());
            formDataU.append('monto', $('#montoDesc').val());
            formDataU.append('cantidad', $('#cantidadDesc').val());
            formDataU.append('estado', activo.bootstrapSwitch('state') ? 1 : 0);
            formDataU.append('rrhhtipodescuento_id', $('#rrhhtipodescuento_id').val());

            // for (var pair of formDataU.entries()) {
            //     console.log(pair[0] + ': ' + pair[1]);
            // }
            fetch('{{ route('descuentos.update') }}', {
                    method: 'POST',
                    body: formDataU,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {


                    cargarTablaDescuentos();
                    $('#modalDescuento').modal('hide')
                    limpiarDesc();
                    if (data.success) {
                        Swal.fire({
                            title: 'Excelente',
                            text: 'Descuento actualizado correctamente.',
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
                    alert('Ocurrió un error al enviar el descuento');
                });
        }

        function registrarDescuento() {

            const formData = new FormData();


            // Añadir campos

            formData.append('fecha', $('#fechaDesc').val());
            formData.append('monto', $('#montoDesc').val());
            formData.append('cantidad', $('#cantidadDesc').val());
            formData.append('rrhhcontrato_id', {{ $contratoActivo?->id }});
            formData.append('empleado_id', {{ $contratoActivo?->empleado->id }});
            formData.append('rrhhtipodescuento_id', $('#rrhhtipodescuento_id').val());


            fetch('{{ route('descuentos.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    cargarTablaDescuentos();
                    $('#modalDescuento').modal('hide')
                    limpiarDesc();
                    if (data.success) {
                        Swal.fire({
                            title: 'Excelente',
                            text: 'Descuento registrado correctamente.',
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
                    alert('Ocurrió un error al enviar el descuento');
                });
        }
    </script>
@endsection
