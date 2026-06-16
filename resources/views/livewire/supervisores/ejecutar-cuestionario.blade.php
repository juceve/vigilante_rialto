<div style="margin-top: 95px">
    @section('title')
        {{ $cuestionario->titulo }}
    @endsection
    <div class="alert alert-secondary" role="alert">
        <span class="text-secondary" style="margin-left: 1rem">
            <i class="fas fa-building"></i> <strong>{{ $inspeccionActiva->cliente->nombre }}</strong>
        </span> <br>
        <span class="badge text-bg-primary " style="margin-left: 1rem; font-size: 11px;">Iniciado:
            {{ date('d-m-Y H:i:s') }}</span>
    </div>

    <div class="card shadow mb-5 bg-body-tertiary rounded" style="width: 92%; margin-left: 1rem">
        <div class="card-header text-center text-white bg-secondary">
            <strong>{{ $cuestionario->titulo }}</strong>
        </div>

        <div class="card-body">
            @php $nro = 0; @endphp
            @forelse ($cuestionario->chklPreguntas as $item)
                @php $nro++; @endphp
                <div class="pregunta-item mb-4">
                    <strong>{{ $nro . '.- ' . $item->descripcion }}</strong>
                    <br>

                    <!-- Radios de cumplimiento -->
                    <div class="d-flex gap-3 mb-2 flex-wrap">
                        <div class="form-check">
                            <input class="form-check-input cumplimiento-radio" type="radio"
                                name="cumplimiento{{ $item->id }}" id="si{{ $nro }}" value="SI"
                                wire:click="marcarCumplimiento({{ $item->id }}, 'SI')">
                            <label class="form-check-label" for="si{{ $nro }}">SI</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input cumplimiento-radio" type="radio"
                                name="cumplimiento{{ $item->id }}" id="no{{ $nro }}" value="NO"
                                wire:click="marcarCumplimiento({{ $item->id }}, 'NO')">
                            <label class="form-check-label" for="no{{ $nro }}">NO</label>
                        </div>
                    </div>

                    <!-- Bloque oculto de empleados y notas -->
                    <div class="incumplimiento-block mt-2" style="display: none;" wire:ignore>
                        <label class="form-label"><strong>Seleccione empleados incumplidos:</strong></label>

                        <!-- Dropdown responsive multi-select -->
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button"
                                id="dropdownEmpleados{{ $item->id }}" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Seleccionar empleados
                            </button>
                            <ul class="dropdown-menu p-2" aria-labelledby="dropdownEmpleados{{ $item->id }}"
                                style="max-height: 200px; overflow-y: auto; width: 100%;">

                                <!-- Seleccionar todos -->
                                <li>
                                    <div class="form-check" onclick="event.stopPropagation();">
                                        <input class="form-check-input" type="checkbox"
                                            id="empleados_todos_{{ $item->id }}"
                                            wire:click="toggleTodosEmpleados({{ $item->id }})">
                                        <label class="form-check-label" for="empleados_todos_{{ $item->id }}">
                                            SELECCIONAR TODOS
                                        </label>
                                    </div>
                                </li>
                                <hr class="my-1">

                                @forelse ($empleados as $empleado)
                                    <li>
                                        <div class="form-check" onclick="event.stopPropagation();" wire:ignore.self>
                                            <input class="form-check-input" type="checkbox"
                                                id="empleado{{ $item->id . $empleado['id'] }}"
                                                wire:click="agregarEmpleado({{ $item->id }}, {{ $empleado['id'] }})"
                                                wire:loading.attr="disabled"
                                                onclick="unselectTodos('empleados_todos_{{ $item->id }}')"
                                                @if (isset($respuestas[$item->id]['empleados']) && in_array($empleado['id'], $respuestas[$item->id]['empleados'])) checked @endif>
                                            <label class="form-check-label"
                                                for="empleado{{ $item->id . $empleado['id'] }}">
                                                {{ $empleado['nombre'] }}
                                            </label>
                                        </div>
                                    </li>
                                @empty
                                    <li>No hay empleados</li>
                                @endforelse
                            </ul>
                        </div>

                        <textarea class="form-control mt-2" name="notas_{{ $item->id }}" rows="2"
                            placeholder="Notas sobre el incumplimiento"
                            wire:input.debounce.2000ms="actualizarObservacion({{ $item->id }}, $event.target.value)"></textarea>
                    </div>

                </div>
            @empty
                <p>No hay preguntas.</p>
            @endforelse
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Por favor corrija los siguientes errores:</strong>
                    <ol class="mb-0 mt-2">
                        @foreach (array_unique($errors->all()) as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ol>
                </div>
            @endif
            <hr>
            <div class="d-grid">
                <button class="btn btn-primary btn-lg mb-2" wire:click="validar" wire:loading.attr="disabled">
                    <i class="fas fa-save"></i> Guardar Respuestas
                </button>
                <button onclick="salida()" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left"></i> Salir sin guardar
                </button>
            </div>
            {{-- @dump($respuestas) --}}
        </div>
    </div>

</div>
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const preguntas = document.querySelectorAll('.pregunta-item');

            preguntas.forEach(pregunta => {
                const radios = pregunta.querySelectorAll('.cumplimiento-radio');
                const bloque = pregunta.querySelector('.incumplimiento-block');

                radios.forEach(radio => {
                    radio.addEventListener('change', () => {
                        if (radio.value === 'NO' && radio.checked) {
                            bloque.style.display = 'block';
                        } else if (radio.value === 'SI' && radio.checked) {
                            bloque.style.display = 'none';
                            // Limpiar checkboxes y textarea
                            bloque.querySelectorAll('input[type="checkbox"]').forEach(cb =>
                                cb.checked = false);
                            bloque.querySelector('textarea').value = '';
                        }
                    });
                });
            });
        });
    </script>

    <script>
        Livewire.on('openConfirmacion', () => {
            Swal.fire({
                title: '¿Desea registrar el cuestionario?',
                input: 'textarea',
                inputLabel: 'Notas adicionales',
                inputPlaceholder: 'Escriba aquí sus notas...',
                inputAttributes: {
                    'aria-label': 'Escriba sus notas'
                },
                showCancelButton: true,
                confirmButtonText: 'Sí, registrar',
                cancelButtonText: 'Cancelar',
                preConfirm: (notas) => {
                    if (!notas) return ''; // No obligatorio
                    return notas;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar a Livewire
                    Livewire.emit('registrarRespuestas', result.value);
                }
            });
        });
    </script>

    <script>
        function salida() {
            Swal.fire({
                title: "¿Seguro desea salir?",
                text: "Se perderán todos los cambios",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#1abc9c",
                cancelButtonColor: "#2c3e50",
                confirmButtonText: "Si, deseo salir!",
                cancelButtonText: "No, continuaré"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('supervisores.listadosupervisiones', $inspeccionActiva->id) }}";
                }
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Para cada pregunta con dropdown de empleados
            document.querySelectorAll('.incumplimiento-block').forEach(block => {
                const selectAll = block.querySelector('input[id^="empleados_todos_"]');
                if (!selectAll) return;

                const checkboxes = block.querySelectorAll(
                    'input[type="checkbox"]:not([id^="empleados_todos_"])');

                // Evento al hacer click en "Seleccionar todos"
                selectAll.addEventListener('change', function() {
                    const checked = this.checked;
                    checkboxes.forEach(cb => {
                        cb.checked = checked;

                        // Opcional: disparar el evento click de Livewire si quieres que se actualice inmediatamente
                        cb.dispatchEvent(new Event('click', {
                            bubbles: true
                        }));
                    });
                });
            });
        });
    </script>
    <script>
        function unselectTodos(idCheckbox) {
            const selectAllCheckbox = document.getElementById(idCheckbox);
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = false;
            }
        }
    </script>
@endsection
