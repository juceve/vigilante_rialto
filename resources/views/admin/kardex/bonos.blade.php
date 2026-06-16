<div class="row mb-2">
    <div class="col-12 col-md-9 mb-2">
        <strong>LISTADO BONOS - Contrato ID:
            {{ $contratoActivo ? cerosIzq($contratoActivo->id) : 'Sin definir' }}</strong>
    </div>
    <div class="col-12 col-md-3 mb-2">
        @if ($contratoActivo)
            <button class="btn btn-info btn-sm btn-block" data-toggle="modal" data-target="#modalBonos">
                Nuevo <i class="fas fa-plus"></i>
            </button>
        @endif

    </div>
</div>
<div class="row mb-2">
    <div class="col-12 col-md-3">

        <small class="text-muted">Mes:</small>
        <select class="form-control form-control-sm" id="filtroMesBonos">
        </select>
    </div>
    <div class="col-12 col-md-3">
        <small class="text-muted">Gestión:</small>
        <select class="form-control form-control-sm" id="filtroAnioBonos">
        </select>
    </div>
    <div class="col-12 col-md-3">
        <small class="text-white">Filtrar</small>
        <button class="btn btn-sm btn-primary btn-block" id="btnFiltrar4" onclick="cargarTablaBonos();">Filtrar <i
                class="fas fa-filter"></i></button>
    </div>

</div>
<hr>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-sm" id="tabla-bonos" style="width: 100%">
        <thead class="table-info">
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Tipo Bono</th>
                <th>Total Bs. &nbsp;</th>
                <th>Pagado</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

</div>
<!-- Modal -->
<div class="modal fade" id="modalBonos" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modalBonosLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBonosLabel">Formulario de Bonos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="limpiar2()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="spinner-border text-primary d-none" id="spinner2" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div id="data2">
                    <div class="row">
                        <input type="hidden" id="rrhhbono_id">
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Fecha Solicitud</span>
                                </div>
                                <input type="date" class="form-control" id="fecha2" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Tipo Bono</span>
                                </div>
                                <select class="form-control" id="rrhhtipobono_id">
                                    <option value="">Seleccione un tipo</option>
                                    @foreach ($tipobonos as $tipobono)
                                        <option value="{{ $tipobono->id }}">{{ $tipobono->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Cantidad</span>
                                </div>
                                <input type="number" id="cantidad2" class="form-control" value="1"
                                    placeholder="1">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Monto</span>
                                </div>
                                <input type="number" step="any" class="form-control" id="monto2"
                                    placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Pagado</span>
                                </div>
                                <select class="form-control" id="bono_pagado">
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 d-none" id="divEstado2">
                            <label><small>Activo</small></label>
                            <input type="checkbox" id="estado2" name="my-checkbox" checked data-bootstrap-switch>
                        </div>

                    </div>
                    <hr>
                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            onclick="limpiar2()"><i class="fas fa-ban"></i>
                            Cancelar</button>
                        <button type="button" id="btnEdit2" class="btn btn-warning d-none"
                            onclick="updateBono()">Actualizar
                            Bono
                            <i class="fas fa-save"></i></button>
                        <button type="button" id="btnRegist2" class="btn btn-info"
                            onclick="registrarBono()">Registrar
                            Bono <i class="fas fa-save"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js5')
    <script>
        $('.nav-bonos').click(function() {
                        const meses4 = {
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

                        const selectMes4 = document.getElementById('filtroMesBonos');

                        // 👇 obtener mes actual (1–12)
                        const mesActual4 = new Date().getMonth() + 1;

                        selectMes4.innerHTML = '';
                        selectMes4.innerHTML = '<option value="">Todos</option>';

                        for (const num in meses4) {
                            const option = document.createElement('option');
                            option.value = num;
                            option.textContent = meses4[num];

                            // 👇 marcar automáticamente el mes actual
                            if (parseInt(num) === mesActual4) {
                                option.selected = true;
                            }

                            selectMes4.appendChild(option);
                        }

                        const url = "{{ route('bonos.data', $contratoActivo ? $contratoActivo->id : 0) }}";

                        $.ajax({
                            url: url,
                            type: 'GET',
                            success: function(response) {

                                const selectAnio4 = document.getElementById('filtroAnioBonos');
                                const selectMes4 = document.getElementById('filtroMesBonos');

                                const anioActual4 = new Date().getFullYear();

                                selectAnio4.innerHTML = '';

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

                                    if (anio === anioActual4) {
                                        option.selected = true;
                                    }

                                    selectAnio4.appendChild(option);
                                });

                                // 👇 AQUÍ ya tienes todo listo
                                const mes = selectMes4.value;
                                const anio = selectAnio4.value;

                                cargarTablaBonos(); // ✅ ahora sí con datos reales
                            },
                            error: function(error) {
                                console.error('ERROR AJAX:', error);
                            }
                        });

                        cargarTablaBonos();
                    });
    </script>

    <script>
        const tiposBonos = @json($tipobonos);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('rrhhtipobono_id');
            const inputMonto = document.getElementById('monto2');

            select.addEventListener('change', function() {
                const selectedId = parseInt(this.value);
                const bonoSeleccionado = tiposBonos.find(bono => bono.id === selectedId);

                if (bonoSeleccionado) {
                    inputMonto.value = bonoSeleccionado.monto;
                } else {
                    inputMonto.value = '';
                }
            });
        });
    </script>

    <script>
        function limpiar2() {
            const hoy = new Date();

            // Formatea la fecha como YYYY-MM-DD
            const fechaFormateada = hoy.toISOString().split('T')[0];
            // document.getElementById('rrhhadelanto_id').value = '';
            document.getElementById('fecha2').value = fechaFormateada;
            document.getElementById('rrhhtipobono_id').value = '';
            document.getElementById('monto2').value = '';
            document.getElementById('cantidad2').value = '1';
            document.getElementById('divEstado2').classList.add('d-none');

            const btnEdit2 = document.getElementById('btnEdit2');
            const btnRegist2 = document.getElementById('btnRegist2');


            btnEdit2.classList.add('d-none');
            estado2.classList.add('d-none');
            btnRegist2.classList.remove('d-none');
        }

        function editar2(rrhhbono_id) {
            const body2 = document.getElementById('data2');
            const spinner2 = document.getElementById('spinner2');
            const btnEdit2 = document.getElementById('btnEdit2');
            const btnRegist2 = document.getElementById('btnRegist2');
            const divEstado = document.getElementById('divEstado2');

            body2.classList.add('d-none');
            spinner2.classList.remove('d-none');
            btnEdit2.classList.remove('d-none');
            // docad.classList.remove('d-none');
            divEstado.classList.remove('d-none');
            btnRegist2.classList.add('d-none');

            const formData = new FormData();
            formData.append('rrhhbono_id', rrhhbono_id);

            fetch('{{ route('bonos.edit') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {

                    document.getElementById('rrhhbono_id').value = data.message.id;

                    document.getElementById('fecha2').value = data.message.fecha;
                    document.getElementById('monto2').value = data.message.monto;
                    document.getElementById('cantidad2').value = data.message.cantidad;
                    const activo = $('#estado2');
                    document.getElementById('rrhhtipobono_id').value = data.message.rrhhtipobono_id;

                    activo.bootstrapSwitch('state', data.message.estado ? true : false);

                    spinner2.classList.add('d-none');
                    body2.classList.remove('d-none');

                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al enviar la solicitud');
                });
        }
    </script>

    <script>
        let tablaBonos;

        function cargarTablaBonos() {
            if (tablaBonos) {
                tablaBonos.ajax.reload(null, false); // recarga sin resetear paginación
                return;
            }


            tablaBonos = $('#tabla-bonos').DataTable({
               ajax: {
                    url: '{{ route('bonos.data', $contratoActivo ? $contratoActivo->id : 0) }}',
                    data: function(d) {
                        d.mes = $('#filtroMesBonos').val();
                        d.anio = $('#filtroAnioBonos').val();
                    }
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'fecha'
                    },
                    {
                        data: 'rrhhtipobono'
                    },
                    {
                        data: 'subtotal'
                    },
                    {
                        data: 'pagado'
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
                tablaBonos.columns.adjust().draw();
            }, 300);
        }



        // document.addEventListener('DOMContentLoaded', cargarTablaAdelantos);
    </script>
    <script>
        function updateBono() {
            const formDataU = new FormData();
            const activo = $('#estado2');
            // Añadir campos
            formDataU.append('rrhhbono_id', $('#rrhhbono_id').val());
            formDataU.append('fecha', $('#fecha2').val());
            formDataU.append('monto', $('#monto2').val());
            formDataU.append('cantidad', $('#cantidad2').val());
            formDataU.append('pagado', $('#bono_pagado').val());
            formDataU.append('estado', activo.bootstrapSwitch('state') ? 1 : 0);
            formDataU.append('rrhhtipobono_id', $('#rrhhtipobono_id').val());


            fetch('{{ route('bonos.update') }}', {
                    method: 'POST',
                    body: formDataU,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {


                    cargarTablaBonos();
                    $('#modalBonos').modal('hide')
                    limpiar2();
                    if (data.success) {
                        Swal.fire({
                            title: 'Excelente',
                            text: 'Bono actualizado correctamente.',
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
                    alert('Ocurrió un error al enviar el bono');
                });
        }

        function registrarBono() {

            const formData = new FormData();


            // Añadir campos

            formData.append('fecha', $('#fecha2').val());
            formData.append('monto', $('#monto2').val());
            formData.append('cantidad', $('#cantidad2').val());
            formData.append('rrhhcontrato_id', {{ $contratoActivo?->id }});
            formData.append('empleado_id', {{ $contratoActivo?->empleado->id }});
            formData.append('rrhhtipobono_id', $('#rrhhtipobono_id').val());


            fetch('{{ route('bonos.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    cargarTablaBonos();
                    $('#modalBonos').modal('hide')
                    limpiar2();
                    if (data.success) {
                        Swal.fire({
                            title: 'Excelente',
                            text: 'Bono registrado correctamente.',
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
                    alert('Ocurrió un error al enviar el bono');
                });
        }
    </script>
@endsection
