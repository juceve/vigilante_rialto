<div>
    @section('title')
        Control de Asistencia
    @endsection
    @section('content_header')
        <div class="container-fluid">
            <h4>Control de Asistencia</h4>
        </div>
    @endsection
    <div class="row">
        <div class="col-12 col-md-4 col-lg-3">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Del</span>
                </div>
                <input type="date" class="form-control" wire:model.defer='fechaInicio'>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-3">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Hasta</span>
                </div>
                <input type="date" class="form-control" wire:model.defer='fechaFin'>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-3">
            <button class="btn btn-primary" wire:click='generarTabla'>Buscar <i class="fas fa-search"></i></button>
        </div>
    </div>
    <div class="card">
        <div class="card-body" wire:ignore>

            <table class="table table-bordered table-striped" id="tablaResultados">
                <thead class="bg-primary" id="cabeceras">
                    <tr>
                        <td></td>
                    </tr>
                </thead>
                <tbody id="filas">
                </tbody>
            </table>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalRegistroLabel" aria-hidden="true"
        wire:ignore>
        <div class="modal-dialog modal-dialog-centered  modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="modalRegistroLabel">REGISTRAR ASISTENCIA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Estados:</label>
                        <select class="form-control" id="selectEstados">
                            <option value="">Seleccione un estado</option>
                            @foreach ($estados as $estado)
                                <option value="{{ $estado->id }}" style="background-color: {{ $estado->color }}">
                                    {{ $estado->nombre_corto . ' - ' . $estado->nombre }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-ban"></i>
                        Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="registrarAsistencia(selectEstados.value);"
                        data-dismiss="modal">Registrar <i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
    </div>

</div>
@section('js')
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
    <script>
        $(document).ready(function() {

        });
    </script>
    <script>
        function funReg(btn_id) {
            console.log(btn_id);

        }

        function cargarDatos(empleado_id, idTD) {

            Livewire.emit('cargarDatos', empleado_id, idTD);
        }
    </script>
    <script>
        Livewire.on('dt', msg => {
            reinicializarTabla();
        });
        Livewire.on('remdt', msg => {
            borrarDt();
        });
        Livewire.on('actTd', msg => {
            const data = msg.split("~");
            const td = document.querySelector('#' + data[0]);
            td.innerHTML = "";
            td.innerHTML = data[1];
            td.style.backgroundColor = data[2];

        });
    </script>

    <script>
        window.addEventListener('cargaCabeceras', event => {

            const cabeceras = event.detail.fechas;
            let tr = "";
            tr += "<tr>"
            cabeceras.forEach(item => {
                tr += '<th>' + item + '</th>';
            });
            tr += "</tr>";
            const thead = document.querySelector('#cabeceras');
            thead.innerHTML = '';
            thead.innerHTML = tr;
        });

        window.addEventListener('cargaBody', event => {
            const resultados = event.detail.resultados;
            const arrayResultados = Object.values(resultados);


            let html = "";
            arrayResultados.forEach(resultado => {
                const arrayResultado = Object.values(resultado);
                html += "<tr>";
                arrayResultado.forEach(fila => {
                    html += fila;
                });
                html += "</tr>";
            });
            const tbody = document.querySelector('#filas');
            tbody.innerHTML = html;
            Livewire.emit('exeDt');
        });

        function registrarAsistencia(estado_id) {
            Livewire.emit('registrarAsistencia', estado_id);
        }
    </script>
    <script>
        function borrarDt() {
            const tablaId = '#tablaResultados';

            if ($.fn.DataTable.isDataTable(tablaId)) {
                $(tablaId).DataTable().destroy();
            }
        }

        function reinicializarTabla() {
            const tablaId = '#tablaResultados';


            $(tablaId).DataTable({
                scrollX: true,
                scrollCollapse: true,
                paging: true, // o false según lo necesites
                responsive: false,
                language: {
                    url: "{{ asset('plugins/es-ES.json') }}"
                },
                pageLength: 10
            });

        }
    </script>
    
@endsection

@section('css')
    <style>
        .table thead tr th:not(:first-child),
        .table tbody tr td:not(:first-child) {
            text-align: center;
            font-size: 12px;
            vertical-align: middle;
        }

        .table thead tr th:first-child,
        .table tbody tr td:first-child {
            white-space: nowrap;
            /* Que no se parta el texto */
            font-size: 13px;
            /* Tamaño un poco mayor si querés destacar */
            text-align: left;
            /* O `center`, según tu gusto */
            min-width: 200px;
            /* Dale un ancho mínimo decente */
            max-width: none;
            vertical-align: middle;
        }

        table.dataTable {
            width: 100% !important;
        }

        .dataTables_wrapper {
            overflow-x: hidden !important;
        }
    </style>
@endsection
