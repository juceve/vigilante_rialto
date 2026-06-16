<div>
    @if (!$contratos->isEmpty())
        <div class="bg-danger disabled color-palette p-3">
            <span>
                Contratos próximos a vencer (en los próximos 30 días)
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="table-danger">
                    <tr>
                        <th>#</th>
                        <th>Empleado</th>
                        <th>Cargo</th>
                        <th>Tipo de Contrato</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Salario</th>
                        <th>Moneda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contratos as $index => $contrato)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $contrato->empleado->nombres . ' ' . $contrato->empleado->apellidos ?? 'Sin asignar' }}
                            </td>
                            <td>{{ $contrato->rrhhcargo->nombre ?? '---' }}</td>
                            <td>{{ $contrato->rrhhtipocontrato->nombre ?? '---' }}</td>
                            <td>{{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge badge-warning">
                                    {{ \Carbon\Carbon::parse($contrato->fecha_fin)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>{{ number_format($contrato->salario_basico, 2) }}</td>
                            <td>{{ $contrato->moneda }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                No hay contratos próximos a vencer.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


    @endif



</div>
